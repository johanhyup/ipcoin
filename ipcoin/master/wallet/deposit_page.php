<?php
/**
 * manual_deposit.php
 *
 *  1) GET 요청  -> HTML UI (검색 & 테이블) 노출
 *  2) GET ?action=search&query=... -> JSON 형식으로 검색 결과 반환
 *  3) POST (JSON) -> 코인 입금 로직 처리 후 JSON 응답
 */

// DB 연결
session_start();
require_once dirname(__DIR__) . '/../../config.php';

// ===== [A] AJAX POST: 코인 "입금하기" 로직 처리 =====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    try {
        // JSON 데이터 파싱
        $input = json_decode(file_get_contents('php://input'), true);
        $userId = isset($input['user_id']) ? intval($input['user_id']) : 0;
        $amount = isset($input['amount']) ? floatval($input['amount']) : 0;

        if ($userId <= 0 || $amount <= 0) {
            throw new Exception("유효하지 않은 파라미터");
        }

        // 예: coin 테이블 / wallet 테이블 / deposit_requests 테이블 등 업데이트
        $conn->begin_transaction();

        // 1) coin 테이블에서 해당 유저의 'IP' 코인 찾기 (없으면 생성)
        $sqlCoin = "SELECT id, total_amount FROM coin WHERE user_id = ? AND name = 'IP'";
        $stmt = $conn->prepare($sqlCoin);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 0) {
            // 코인이 없으면 생성
            $insertCoin = $conn->prepare("
                INSERT INTO coin (user_id, name, total_amount, locked_amount)
                VALUES (?, 'IP', 0, 0)
            ");
            $insertCoin->bind_param("i", $userId);
            $insertCoin->execute();
            $coinId = $insertCoin->insert_id;
            $insertCoin->close();
        } else {
            $coinRow = $res->fetch_assoc();
            $coinId = $coinRow['id'];
        }
        $stmt->close();

        // 2) deposit_requests 테이블 기록(원한다면)
        $insertDep = $conn->prepare("
            INSERT INTO deposit_requests (user_id, coin_name, amount, deposit_address, status)
            VALUES (?, 'IP', ?, 'manual', 'approved')
        ");
        $insertDep->bind_param("id", $userId, $amount);
        $insertDep->execute();
        $insertDep->close();

        // 3) coin 테이블에 수량 추가 (total_amount += $amount)
        $updCoin = $conn->prepare("
            UPDATE coin
            SET total_amount = total_amount + ?
            WHERE id = ?
        ");
        $updCoin->bind_param("di", $amount, $coinId);
        $updCoin->execute();
        $updCoin->close();

        // 4) wallet 테이블도 있다면 업데이트 (total_balance += $amount)
        $updWallet = $conn->prepare("
            UPDATE wallet
            SET total_balance = total_balance + ?
            WHERE user_id = ?
        ");
        $updWallet->bind_param("di", $amount, $userId);
        $updWallet->execute();
        $updWallet->close();

        $conn->commit();

        echo json_encode([
            'success' => true,
            'message' => "코인 {$amount}개가 수동 입금되었습니다."
        ]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    } finally {
        $conn->close();
    }
    exit; // POST 요청 처리 끝 (아래 HTML 출력 X)
}

// ===== [B] AJAX GET ?action=search => 회원 검색 결과 JSON 반환 =====
if (isset($_GET['action']) && $_GET['action'] === 'search') {
    header('Content-Type: application/json');
    $query = isset($_GET['query']) ? trim($_GET['query']) : '';
    if (!$query) {
        echo json_encode(['success' => false, 'message' => '검색어가 없습니다.']);
        exit;
    }

    // users 테이블 + coin 잔고 조회(예: 'IP' 코인만)
    $like = "%{$query}%";
    $sql = "
        SELECT u.id AS user_id,
               u.mb_name,
               u.mb_id,
               IFNULL(c.total_amount, 0) AS coin_balance
          FROM users u
          LEFT JOIN coin c
            ON u.id = c.user_id
           AND c.name = 'IP'
         WHERE u.mb_name LIKE ? OR u.mb_id LIKE ?
         ORDER BY u.id DESC
         LIMIT 50
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();

    $users = [];
    while ($row = $result->fetch_assoc()) {
        // row: [user_id, mb_name, mb_id, coin_balance]
        $users[] = $row;
    }
    $stmt->close();
    $conn->close();

    echo json_encode(['success' => true, 'users' => $users]);
    exit;
}

// ===== [C] 그 외(기본 GET) -> HTML UI 렌더링 (처음 화면) =====
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>수동입금</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 20px;
        }
        h1 { margin-bottom: 10px; }
        .search-box {
            margin-bottom: 10px;
        }
        .search-box input {
            width: 200px;
            height: 30px;
            margin-right: 8px;
            padding: 5px;
        }
        .search-box button {
            height: 30px;
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2a55f;
            color: #fff;
        }
        input[type="number"] {
            width: 80px;
            padding: 5px;
            text-align: right;
        }
        .btn-deposit {
            background: #28a745;
            color: #fff;
            border: none;
            padding: 6px 10px;
            cursor: pointer;
            border-radius: 4px;
        }
        .btn-deposit:hover {
            background: #218838;
        }
        .notice {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
        .container {
            max-width: 600px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>수동입금</h1>

    <!-- 검색 영역 -->
    <div class="search-box">
        <input type="text" id="searchInput" placeholder="이름 또는 아이디 검색" />
        <button id="searchButton">검색</button>
        <div class="notice">
            ※ 검색 전에는 목록이 뜨지 않습니다.
        </div>
    </div>
    
    <!-- 유저 목록 테이블 -->
    <table>
        <thead>
            <tr>
                <th>이름</th>
                <th>아이디</th>
                <th>기존코인</th>
                <th>입금수량</th>
                <th>입금하기</th>
            </tr>
        </thead>
        <tbody id="userListBody">
            <!-- 검색하기 전까지는 비어 있음 -->
        </tbody>
    </table>
</div>

<script>
// ========== 1) 검색 버튼 클릭 시 => Ajax GET: ?action=search&query=... ==========
document.getElementById('searchButton').addEventListener('click', function() {
  const searchValue = document.getElementById('searchInput').value.trim();
  if (!searchValue) {
    alert('검색어를 입력하세요.');
    return;
  }

  fetch('?action=search&query=' + encodeURIComponent(searchValue))
    .then(res => res.json())
    .then(data => {
      if (!data.success) {
        alert('검색 실패: ' + (data.message || '알 수 없는 오류'));
        return;
      }
      renderUserList(data.users);
    })
    .catch(err => {
      console.error(err);
      alert('검색 중 오류가 발생했습니다.');
    });
});

// ========== 2) 검색된 유저 목록을 테이블에 표시 ==========
function renderUserList(users) {
  const tbody = document.getElementById('userListBody');
  tbody.innerHTML = ''; // 초기화

  if (!users || users.length === 0) {
    tbody.innerHTML = '<tr><td colspan="5">검색 결과가 없습니다.</td></tr>';
    return;
  }

  users.forEach(user => {
    const tr = document.createElement('tr');

    // [이름]
    const tdName = document.createElement('td');
    tdName.textContent = user.mb_name;
    tr.appendChild(tdName);

    // [아이디]
    const tdId = document.createElement('td');
    tdId.textContent = user.mb_id;
    tr.appendChild(tdId);

    // [기존코인] (예: coin_balance)
    const tdCoin = document.createElement('td');
    tdCoin.textContent = user.coin_balance || 0;
    tr.appendChild(tdCoin);

    // [입금수량] (input number)
    const tdAmount = document.createElement('td');
    const input = document.createElement('input');
    input.type = 'number';
    input.step = '0.00000001';
    input.dataset.userId = user.user_id; // 나중에 참조 용
    tdAmount.appendChild(input);
    tr.appendChild(tdAmount);

    // [입금하기] 버튼
    const tdAction = document.createElement('td');
    const btn = document.createElement('button');
    btn.textContent = '입금하기';
    btn.className = 'btn-deposit';
    btn.addEventListener('click', () => {
      const depositValue = parseFloat(input.value);
      if (isNaN(depositValue) || depositValue <= 0) {
        alert('올바른 입금 수량을 입력하세요.');
        return;
      }
      handleDeposit(user.user_id, depositValue);
    });
    tdAction.appendChild(btn);
    tr.appendChild(tdAction);

    tbody.appendChild(tr);
  });
}

// ========== 3) 실제 입금 처리 (POST) ==========
function handleDeposit(userId, amount) {
  fetch(location.href, { // 현재 페이지 URL(동일 파일)
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ user_id: userId, amount: amount })
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert('입금 완료: ' + data.message);
        // 성공 후, 테이블 갱신을 원하시면 여기서 검색 재실행:
        // document.getElementById('searchButton').click();
      } else {
        alert('입금 실패: ' + data.message);
      }
    })
    .catch(err => {
      console.error(err);
      alert('입금 처리 중 오류가 발생했습니다.');
    });
}
</script>
</body>
</html>