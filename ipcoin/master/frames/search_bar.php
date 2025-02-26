<?php
require_once dirname(__DIR__) . '/../config.php'; // 데이터베이스 연결

// 사용자 정보 가져오기
$query = "
    SELECT 
        u.id, u.mb_id, u.mb_name, u.mb_email, u.mb_tel, u.grade, u.approved, 
        DATE(u.created_at) AS signup_date, 
        w.wallet_address, w.total_balance, w.available_balance, w.locked_balance, 
        u.last_login, u.status, u.mb_password, u.mb_tel
    FROM users u
    LEFT JOIN wallet w ON u.id = w.user_id
    ORDER BY u.id
";
$result = $conn->query($query);
$users = $result->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>사용자 관리</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        .pagination { margin-top: 10px; text-align: center; }
        .pagination button { padding: 5px 10px; margin: 2px; }
        .search-bar, .pagination-bar { margin: 10px 0; display: flex; justify-content: space-between; }
        .input-box { padding: 5px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="search-bar">
        <div>
            <label for="rows-per-page">페이지당 줄 수:</label>
            <input type="number" id="rows-per-page" class="input-box" min="1" value="10">
        </div>
        <div>
            <label for="search-input">아이디/닉네임 검색:</label>
            <input type="text" id="search-input" class="input-box" placeholder="검색어 입력">
            <button id="search-button">검색</button>
        </div>
    </div>
    <table id="user-table">
        <thead>
            <tr>
                <th>번호</th>
                <th>아이디</th>
                <th>이름</th>
                <th>이메일</th>
                <th>연락처</th>
                <th>등급</th>
                <th>가입일</th>
                <th>지갑주소</th>
                <th>총 잔액</th>
                <th>사용 가능 잔액</th>
                <th>잠금 잔액</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $index => $user): ?>
            <tr>
                <td><?= htmlspecialchars($index + 1) ?></td>
                <td><?= htmlspecialchars($user['mb_id']) ?></td>
                <td><?= htmlspecialchars($user['mb_name']) ?></td>
                <td><?= htmlspecialchars($user['mb_email']) ?></td>
                <td><?= htmlspecialchars($user['mb_tel']) ?></td>
                <td><?= htmlspecialchars($user['grade']) ?></td>
                <td><?= htmlspecialchars($user['signup_date']) ?></td>
                <td><?= htmlspecialchars($user['wallet_address'] ?? '없음') ?></td>
                <td><?= number_format($user['total_balance'] ?? 0, 8) ?></td>
                <td><?= number_format($user['available_balance'] ?? 0, 8) ?></td>
                <td><?= number_format($user['locked_balance'] ?? 0, 8) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="pagination"></div>

    <script>
const rowsPerPageInput = document.getElementById("rows-per-page");
const searchInput = document.getElementById("search-input");
const searchButton = document.getElementById("search-button");
const table = document.getElementById("user-table");
const tbody = table.querySelector("tbody");
const rows = Array.from(tbody.querySelectorAll("tr")); // 모든 행을 배열로 저장
const pagination = document.querySelector(".pagination");

let rowsPerPage = parseInt(rowsPerPageInput.value, 10) || 10;
let currentPage = 1;
let filteredRows = rows; // 필터링된 결과를 저장할 변수

// 행 표시 함수
function displayRows() {
    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;

    // 모든 행 숨기기
    rows.forEach(row => (row.style.display = "none"));

    // 현재 페이지의 행만 표시
    filteredRows.slice(start, end).forEach(row => (row.style.display = ""));
    updatePagination();
}

// 페이징 버튼 업데이트
function updatePagination() {
    const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
    pagination.innerHTML = "";

    for (let i = 1; i <= totalPages; i++) {
        const button = document.createElement("button");
        button.textContent = i;
        button.addEventListener("click", () => {
            currentPage = i;
            displayRows();
        });

        if (i === currentPage) {
            button.style.fontWeight = "bold";
            button.style.backgroundColor = "#007bff";
            button.style.color = "#fff";
        }

        pagination.appendChild(button);
    }

    // 결과가 없을 경우 안내 메시지 추가
    if (filteredRows.length === 0) {
        const noResults = document.createElement("span");
        noResults.textContent = "검색 결과가 없습니다.";
        noResults.style.color = "red";
        pagination.appendChild(noResults);
    }
}

// 검색 기능
function searchRows() {
    const searchTerm = searchInput.value.toLowerCase();

    // 검색어가 포함된 행만 필터링
    filteredRows = rows.filter(row => {
        const columns = row.querySelectorAll("td");
        return Array.from(columns).some(column =>
            column.textContent.toLowerCase().includes(searchTerm)
        );
    });

    // 검색 후 첫 페이지로 이동
    currentPage = 1;
    displayRows();
}

// 이벤트 리스너 등록
rowsPerPageInput.addEventListener("change", () => {
    rowsPerPage = parseInt(rowsPerPageInput.value, 10) || 10;
    currentPage = 1;
    displayRows();
});

searchButton.addEventListener("click", searchRows);

// 초기화
displayRows();

    </script>
</body>
</html>
