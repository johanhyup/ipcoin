<?php
session_start(); 
require_once dirname(__DIR__) . '/frames/header.php';
?>

<?php
require_once dirname(__DIR__) . '/../config.php'; // 데이터베이스 연결
// 승인/거절 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $approved = isset($_POST['approved']) ? intval($_POST['approved']) : 0;

    if ($user_id > 0) {
        $stmt = $conn->prepare("UPDATE users SET approved = ? WHERE id = ?");
        $stmt->bind_param("ii", $approved, $user_id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: deposit_manage.php");
    exit;
}
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

?>



<style>
    /* 검색바 스타일 (예시) */
    .search-bar {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 20px;
      align-items: center;
    }
    .search-bar select,
    .search-bar input {
      height: 38px;
      padding: 5px 10px;
    }
    .search-bar button {
      height: 38px;
    }
    .search-bar .form-control::placeholder {
      color: #aaa;
    }

    /* 테이블 헤더 주황색, 글자 흰색 */
    .table thead th {
      background-color: orange;
      color: #fff;
      white-space: nowrap;
    }
    /* 상태 버튼(미승인 시 파랑), 승인 텍스트(검정) */
    .btn-approve {
      background-color: #007bff; /* 파랑 */
      color: #fff;
      border: none;
      padding: 5px 10px;
      cursor: pointer;
      border-radius: 4px;
    }

    /* 상세정보 버튼(노랑) */
    .btn-detail {
      background-color: #ffc107; /* 노랑 */
      color: #212529; /* 검정 */
      border: none;
      padding: 5px 10px;
      cursor: pointer;
      border-radius: 4px;
    }

    /* 모달 내부 스타일 살짝 */
    .modal-header {
      background-color: #f8f9fa;
    }
    .modal-body label {
      font-weight: bold;
    }
</style>

<body class="hold-transition sidebar-mini layout-fixed">  
     <!-- Top Navigation -->
    <?php require_once dirname(__DIR__) . '/frames/top_nav.php'; ?>
    <!-- Sidebar Navigation -->
    <?php require_once dirname(__DIR__) . '/frames/nav.php'; ?>

    <script src="/master/assets/js/deposit.js" defer></script>
<!-- 코인 전체 락업기간 변경경 -->
<div class="card-body">
    <div class="actions">
        <label for="update-id">락업 </label>
            <input class="input-box" id="lockupPeriodInput" type="number" placeholder="기간을 입력 (일 단위)">
            <button class="rocktButton" type="button" id="updateLockupButton">전체 락업기간 변경</button>
    </div>
</div>

<!-- 아이디 블락기능 -->
<div class="card-body">
    <div class="actions">
        <div>
            <label for="update-id">블락 </label>
            <input type="text" id="update-id" class="input-box" placeholder="아이디 입력">
            <button class="approve-button" id="approve-button">승인</button>       
            <button class="block-button" id="block-button">블락</button>
        </div>
    </div>
</div>

<!-- 아이디 비밀번호 초기화화 -->
<div class="card-body">
    <div class="actions">
        <div>
            <label for="reset-id">비번 </label>
            <input type="text" id="reset-id" class="input-box" placeholder="아이디 입력">
            <button class="edit-button2" id="reset-button">비밀번호 초기화</button>
            <span id="reset-error" class="error"></span>
        </div>
    </div>
</div>

<div class="wrapper">
<!-- 메인 콘텐츠 WRAPPER -->
    <section class="content pt-3">
    <div class="container-fluid">
        <!-- 1) 검색바 -->
        <div class="search-bar">
            <!-- 첫 번째 셀렉트박스: 정렬 기준 -->
            <select id="sortType" class="form-control" style="width:auto;">
                <option value="recent">최근 가입순</option>
                <option value="coin">코인 보유순</option>
                <option value="name">가나다순</option>
            </select>

            <!-- 두 번째 셀렉트박스: 페이지당 표시 개수 -->
            <select id="rowsPerPage" class="form-control" style="width:auto;">
                <option value="10">10개씩 정렬</option>
                <option value="20">20개씩 정렬</option>
                <option value="30">30개씩 정렬</option>
            </select>

            <!-- 검색어 텍스트박스 (placeholder: "이름 / 아이디") -->
            <input type="text" id="searchInput" class="form-control" placeholder="이름 / 아이디" style="width:200px;">
            <!-- 검색 버튼 (파랑색) -->
            <button id="searchBtn" class="btn btn-primary">
                검색
            </button>

            <!-- [1] 코인 수동입금 버튼 -->
            <div>
                <button id="depositButton" class="btn btn-primary" onclick="openLockupForm()">
                  코인 수동입금
                </button>
            </div>
        </div>

        <!-- 2) 회원 목록 테이블 -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="userTable">
                <thead>
                <tr>
                    <th style="width:50px;">No.</th>
                    <th>가입일</th>
                    <th>아이디</th>
                    <th>이름</th>
                    <th>전화번호</th>
                    <th>메일</th>
                    <th>코인</th>
                    <th>상태</th>
                    <th>입금</th>
                </tr>
                </thead>
                <tbody>
                <!-- AJAX로 불러온 내용이 들어갈 예정 -->
                </tbody>
            </table>
        </div>

        <!-- 페이지네이션 영역(필요 시) -->
        <div id="pagination" class="mt-2"></div>

    </div><!-- /.container-fluid -->
    </section>
</div><!-- /.content-wrapper -->

<!-- 회원 상세정보 모달 -->
<div class="modal fade" id="userDetailModal" tabindex="-1" role="dialog" aria-labelledby="userDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document"><!-- 좀 넓게 -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userDetailModalLabel">회원 상세정보</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- 내용은 AJAX 로드 -->
                <div id="userDetailContent">로딩중...</div>
            </div>
            <div class="modal-footer">
                <!-- 닫기 버튼 -->
                <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<?php require_once dirname(__DIR__) . '/../master/frames/footer.php'; ?>

<script type="module">
    import Pagination from '/master/assets/js/pagination.js';

    // ================================
    // 1. 페이지 로드시 user_datafetch.php로부터 전체 회원 목록 불러오기 (예: 구버전 코드)
    // ================================
    /*
       만약 아래 로직 대신, 
       바로 아래 'loadUserList()'가 user_list_ajax.php 호출하는 로직을 쓰신다면 
       이 부분은 주석처리하거나 삭제하셔도 됩니다.
    */
    fetch('/master/manage_user/user_datafetch.php')
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        const tbody = document.querySelector("#userTable tbody");
        // 예시 렌더링 코드 (컬럼 개수가 맞지 않을 수 있으니 실제 구조에 맞춰 조정)
        tbody.innerHTML = data.map((user, index) => `
            <tr>
                <td>${index + 1}</td>
                <td>${user.signup_date?.substring(0,10) || ''}</td>
                <td>${user.mb_id}</td>
                <td>${user.mb_name}</td>
                <td>${user.mb_tel || ''}</td>
                <td>${user.mb_email || ''}</td>
                <td>${parseFloat(user.total_balance || 0).toFixed(8)}</td>
                <td>${parseInt(user.approved, 10) === 1 ? '승인됨' : '블락'}</td>
                <td>
                    <button class="edit-button" data-user-id="${user.user_id}">
                        입금
                    </button>
                </td>
            </tr>
        `).join('');

        // pagination 등 초기화
        new Pagination('user-table', 'rows-per-page', 'search-input', 'search-button', 'pagination-container');
    })
    .catch(error => console.error('Error fetching data:', error));

    // ================================
    // 2. "코인 수동입금" 버튼을 눌렀을 때 수동으로 유저 ID 입력받아 처리
    // ================================
    function openLockupForm() {
        // [2] user_id를 직접 입력받기 (prompt) → deposit_page.php 로 이동
        const inputUserId = prompt("수동입금할 회원의 user_id를 입력해주세요.\n(숫자로 된 ID)");

        // prompt가 취소되었거나 빈 값이면 중단
        if (!inputUserId) {
            return;
        }

        // 숫자인지 간단한 체크 (문자열인 경우도 있을 수 있으니)
        // 필요없다면 제거 가능
        if (isNaN(parseInt(inputUserId))) {
            alert("user_id는 숫자만 입력 가능합니다.");
            return;
        }

        // 이제 deposit_page.php(또는 deposit_form.php)에 user_id를 쿼리로 넘겨 새 창 열기
        const url = `/master/wallet/deposit_page.php?user_id=${encodeURIComponent(inputUserId)}`;
        window.open(url, 'lockupForm', 'width=600,height=600,resizable=yes,scrollbars=yes');
    }

    // ================================
    // 3. 승인 / 블락 / 비밀번호 초기화 / 검색 등 기존 로직들
    // ================================

    // 블락 기능
    document.getElementById('block-button').addEventListener('click', () => {
        const mbId = document.getElementById('update-id').value.trim();
        if (!mbId) {
            alert('아이디를 입력해주세요.');
            return;
        }
        updateApprovalStatus(mbId, 0);
    });

    // 승인 기능
    document.getElementById('approve-button').addEventListener('click', () => {
        const mbId = document.getElementById('update-id').value.trim();
        if (!mbId) {
            alert('아이디를 입력해주세요.');
            return;
        }
        updateApprovalStatus(mbId, 1);
    });

    // 개별 [입금] 버튼 클릭 이벤트 (각 행에 있는 버튼)
    document.addEventListener('click', (event) => {
        if (event.target.classList.contains('edit-button')) {
            const userId = event.target.dataset.userId; 
            if (userId) {
                // 개별 회원에 대한 deposit_page 이동 (새 창)
                const url = `/master/wallet/deposit_page.php?user_id=${userId}`;
                window.open(url, '_blank', 'width=600,height=700');
            } else {
                alert('유효하지 않은 사용자입니다.');
            }
        }
    });

    // 비밀번호 초기화
    document.getElementById('reset-button').addEventListener('click', () => {
        const mbId = document.getElementById('reset-id').value.trim();
        const errorSpan = document.getElementById('reset-error');

        if (!mbId) {
            errorSpan.textContent = '아이디를 입력해주세요.';
            return;
        }

        errorSpan.textContent = ''; // 초기화
        fetch('/master/manage_user/reset_password.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `mb_id=${mbId}`,
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert('1111로 비밀번호가 초기화되었습니다.');
            } else {
                alert(result.message || '비밀번호 초기화에 실패했습니다.');
            }
        })
        .catch(error => {
            console.error('Error resetting password:', error);
            alert('비밀번호 초기화 중 오류가 발생했습니다.');
        });
    });

    // ================================
    // 4. 나머지 검색/정렬/페이지네이션 로직
    // ================================
    $(document).ready(function() {
        // 검색 버튼 클릭 이벤트
        $('#searchBtn').on('click', function() {
            loadUserList(1); // 첫 페이지부터 다시 로드
        });

        // 셀렉트박스 변경 시에도 자동으로 로드하도록
        $('#sortType, #rowsPerPage').on('change', function() {
            loadUserList(1);
        });

        // 엔터키로 검색
        $('#searchInput').on('keyup', function(e) {
            if(e.key === 'Enter') {
                loadUserList(1);
            }
        });

        // 초기 로드
        loadUserList(1);
    });

    // 유저 목록 불러오기 (page 파라미터)
    function loadUserList(page) {
        let sortType = $('#sortType').val();
        let rowsPerPage = $('#rowsPerPage').val();
        let searchText = $('#searchInput').val().trim();

        $.ajax({
            url: '/master/manage_user/user_list_ajax.php',
            method: 'GET',
            data: {
                page: page,
                sort: sortType,
                limit: rowsPerPage,
                search: searchText
            },
            dataType: 'json',
            success: function(res) {
                if(res.success) {
                    renderUserTable(res.data.users, res.data.startIndex);
                    renderPagination(res.data.totalPages, page);
                } else {
                    alert('회원 목록을 불러오지 못했습니다: ' + res.message);
                }
            },
            error: function(err) {
                console.error(err);
                alert('회원 목록 로드 중 오류가 발생했습니다.');
            }
        });
    }

    // 테이블 렌더링
    function renderUserTable(users, startIndex) {
        let tbody = $('#userTable tbody');
        tbody.empty();

        if(!users || users.length === 0) {
            let emptyRow = `
            <tr>
                <td colspan="9" style="text-align:center;">데이터가 없습니다.</td>
            </tr>
            `;
            tbody.append(emptyRow);
            return;
        }

        users.forEach(function(user, i) {
            let no = startIndex + i; // No. (시작 인덱스+i)
            let approved = parseInt(user.approved) === 1; // DB에서 approved=1이면 승인됨
            let statusHtml = '';

            if(!approved) {
                // 미승인 -> 승인 버튼
                statusHtml = `<button class="btn-approve" onclick="approveUser(${user.id})">승인</button>`;
            } else {
                // 승인됨 -> 검정 텍스트
                statusHtml = `<span style="color:black;">승인</span>`;
            }

            let detailBtnHtml = `
            <button class="btn-detail" onclick="openDetailModal(${user.id})">
                상세
            </button>
            `;

            // 가입일: yyyy-mm-dd
            let createdDate = (user.created_at) ? user.created_at.substring(0, 10) : '';

            let rowHtml = `
            <tr>
                <td>${no}</td>
                <td>${createdDate}</td>
                <td>${user.mb_id}</td>
                <td>${user.mb_name}</td>
                <td>${user.mb_tel || ''}</td>
                <td>${user.mb_email || ''}</td>
                <td>${user.coin_balance || 0}</td>
                <td>${statusHtml}</td>
                <td>
                    <button class="edit-button" data-user-id="${user.user_id}">입금</button>
                </td>
            </tr>
            `;
            tbody.append(rowHtml);
        });
    }

    // 페이지네이션 렌더링
    function renderPagination(totalPages, currentPage) {
        let pagDiv = $('#pagination');
        pagDiv.empty();
        
        if(totalPages <= 1) return;

        for(let i=1; i <= totalPages; i++) {
            let btnClass = (i === currentPage) ? 'btn-primary' : 'btn-outline-primary';
            pagDiv.append(`
            <button class="btn ${btnClass} btn-sm mr-1" onclick="loadUserList(${i})">
                ${i}
            </button>
            `);
        }
    }

    // 승인 버튼
    function approveUser(userId) {
        if(!confirm('승인 처리하시겠습니까?')) return;
        
        $.ajax({
            url: '/master/manage_user/approve_user.php',
            method: 'POST',
            data: { user_id: userId },
            dataType: 'json',
            success: function(res) {
                if(res.success) {
                    alert('승인되었습니다.');
                    loadUserList(1); // 목록 새로고침
                } else {
                    alert('승인 실패: ' + res.message);
                }
            },
            error: function(err) {
                console.error(err);
                alert('승인 처리 중 오류가 발생했습니다.');
            }
        });
    }

    // 상세 모달 열기
    function openDetailModal(userId) {
        // 모달 열기 전, 내용 초기화
        $('#userDetailContent').html('로딩중...');

        // Ajax로 상세정보 불러오기
        $.ajax({
            url: '/master/manage_user/user_detail.php',
            method: 'GET',
            data: { user_id: userId },
            dataType: 'html',
            success: function(html) {
                $('#userDetailContent').html(html);
                // 모달 열기
                $('#userDetailModal').modal('show');
            },
            error: function(err) {
                console.error(err);
                alert('상세정보 불러오기 중 오류가 발생했습니다.');
            }
        });
    }
</script>

<?php $conn->close(); ?>