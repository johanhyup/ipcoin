<?php
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
    header("Location: user_info.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>회원관리 승인</title>
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

<div class="main2" style="    transform: translate(0%, 10%)">
<h1>승인 관리</h1>
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
            <th>승인 상태</th>
            <th>작업</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
<div id="pagination-container" class="pagination"></div>
</div>
<script type="module">
    import Pagination from '/master/assets/js/pagination.js';

    let allData = [];
    let filteredData = [];
    let rowsPerPage = 10;

    const tableBody = document.querySelector("#user-table tbody");
    const searchInput = document.getElementById("search-input");
    const rowsPerPageInput = document.getElementById("rows-per-page");
    const paginationContainer = document.getElementById("pagination-container");

    // Fetch user data
    fetch('/master/manage_user/user_datafetch.php')
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            allData = data;
            filteredData = data;
            renderTable(1);
            setupPagination();
        })
        .catch(error => console.error('Error fetching data:', error));

    // Render table data
    function renderTable(page) {
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        tableBody.innerHTML = filteredData.slice(start, end).map((user, index) => `
            <tr>
                <td>${start + index + 1}</td>
                <td>${user.mb_id}</td>
                <td>${user.mb_name}</td>
                <td>${user.mb_email}</td>
                <td>${user.mb_tel}</td>
                <td>${user.grade}</td>
                <td>${user.signup_date}</td>
                <td>${user.approved ? '승인됨' : '거절됨'}</td>
                <td>
                    <button class="approve-button">승인</button>
                    <button class="reject-button">거절</button>
                </td>
            </tr>
        `).join('');
    }

    // Setup pagination
    function setupPagination() {
        const totalPages = Math.ceil(filteredData.length / rowsPerPage);
        paginationContainer.innerHTML = '';
        for (let i = 1; i <= totalPages; i++) {
            const button = document.createElement('button');
            button.textContent = i;
            button.addEventListener('click', () => renderTable(i));
            paginationContainer.appendChild(button);
        }
    }

    // Search functionality
    document.getElementById("search-button").addEventListener("click", () => {
        const searchTerm = searchInput.value.toLowerCase();
        filteredData = allData.filter(user =>
            user.mb_id.toLowerCase().includes(searchTerm) ||
            user.mb_name.toLowerCase().includes(searchTerm)
        );
        renderTable(1);
        setupPagination();
    });

    // Rows per page functionality
    rowsPerPageInput.addEventListener("change", () => {
        rowsPerPage = parseInt(rowsPerPageInput.value, 10) || 10;
        renderTable(1);
        setupPagination();
    });
</script>
</body>
</html>
