<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>입금신청</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        .pagination { margin-top: 10px; text-align: center; }
        .pagination button { padding: 5px 10px; margin: 2px; }
        .search-bar { margin: 10px 0; display: flex; justify-content: space-between; }
        .input-box { padding: 5px; font-size: 14px; }
    </style>
</head>
<body>
<?php
require_once dirname(__DIR__) . '/frames/nav.php';
require_once dirname(__DIR__) . '/frames/top_nav.php';
?>

<div class="main2" style="    transform: translate(0%, 10%)">
    <h1>입금신청</h1>
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


?>
    <div class="search-bar">
        <div>
            <label for="rows-per-page">페이지당 줄 수:</label>
            <input type="number" id="rows-per-page" class="input-box" min="1" value="10">
        </div>
        <div>
        <label for="update-id">아이디:</label>
        <input type="text" id="update-id" class="input-box" placeholder="아이디 입력">
        <button class="approve-button" id="approve-button">승인</button>       
        <button class="block-button" id="block-button">블락</button>

    </div>
    <div>
    <label for="reset-id">아이디:</label>
    <input type="text" id="reset-id" class="input-box" placeholder="아이디 입력">
    <button class="edit-button2" id="reset-button">비밀번호 초기화</button>
    <span id="reset-error" class="error"></span>
</div>

        <div>
            <label for="search-input">아이디/닉네임 검색:</label>
            <input type="text" id="search-input" class="input-box" placeholder="검색어 입력">
            <button id="search-button">검색</button>
        </div>
    </div>
    <table>

    <table id="user-table">
        <thead>
            <tr>
                <th>번호</th>
                <th>아이디</th>
                <th>관리자</th>
                <th>이름</th>
                <th>이메일</th>
                <th>연락처</th>
                <th>등급</th>
                <th>가입일</th>
                <th>지갑주소</th>
                <th>총 잔액</th>
                <th>사용 가능 잔액</th>
                <th>잠금 잔액</th>
                <th>승인 여부</th> <!-- 승인 여부 추가 -->
                <th>입금</th>

            </tr>
        </thead>
        <tbody></tbody>
    </table>
    <div id="pagination-container" class="pagination"></div>

    <script type="module">
        
        import Pagination from '/master/assets/js/pagination.js';

// Fetch data from PHP
fetch('/master/manage_user/user_datafetch.php')
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        const tbody = document.querySelector("#user-table tbody");
        tbody.innerHTML = data.map((user, index) => `
            <tr>
                <td>${index + 1}</td>
                <td>${user.mb_id}</td>
                <td>${user.managed_by}</td>
                <td>${user.mb_name}</td>
                <td>${user.mb_email}</td>
                <td>${user.mb_tel}</td>
                <td>${user.grade}</td>
                <td>${user.signup_date}</td>
                <td>${user.wallet_address || '없음'}</td>
                <td>${parseFloat(user.total_balance || 0).toFixed(8)}</td>
                <td>${parseFloat(user.available_balance || 0).toFixed(8)}</td>
                <td>${parseFloat(user.locked_balance || 0).toFixed(8)}</td>
        <td>${parseInt(user.approved, 10) === 1 ? '승인됨' : '블락'}</td>  <!--문자열을 순자로 바꿔줌-->
            <td>
                <button class="edit-button" data-user-id="${user.user_id}">입금</button>
            </td>
            
            </tr>
        `).join('');

        // Initialize pagination
        new Pagination('user-table', 'rows-per-page', 'search-input', 'search-button', 'pagination-container');
    })
    .catch(error => console.error('Error fetching data:', error));
    </script>

    <script>

        
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

    // 입금 버튼 클릭 이벤트
    document.addEventListener('click', (event) => {
        if (event.target.classList.contains('edit-button')) {
            const userId = event.target.dataset.userId; // 버튼의 data-user-id 속성에서 idx 가져오기
            if (userId) {
                // deposit_form 페이지로 이동
                const url = `/master/wallet/deposit_form.php?user_id=${userId}`;
                window.open(url, '_blank', 'width=600,height=700');
            } else {
                alert('유효하지 않은 사용자입니다.');
            }
        }
    });




    </script>
<script> //비밀번호 초기화화
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
</script>
</body>
</html>

