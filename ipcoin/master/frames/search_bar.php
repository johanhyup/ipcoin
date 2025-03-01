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
        /* Global Styles */
        body {
            margin: 0;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f9;
        }
        /* Search Bar */
        .search-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            background-color: #fff;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .search-bar div {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .search-bar label {
            font-size: 14px;
            color: #333;
        }
        .input-box {
            padding: 8px 12px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            outline: none;
            transition: border-color 0.3s;
        }
        .input-box:focus {
            border-color: #4CAF50;
        }
        .button {
            padding: 8px 16px;
            font-size: 14px;
            border: none;
            border-radius: 4px;
            background-color: #4CAF50;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #45a049;
        }
        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        thead {
            background-color: #4CAF50;
            color: #fff;
        }
        th, td {
            padding: 12px 15px;
            text-align: center;
        }
        tbody tr {
            border-bottom: 1px solid #f0f0f0;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tbody tr:hover {
            background-color: #f1f1f1;
        }
        /* Pagination Styles */
        .pagination {
            margin-top: 10px;
            display: flex;
            justify-content: center;
            gap: 8px;
        }
        .pagination button {
            padding: 6px 12px;
            border: none;
            background-color: #4CAF50;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .pagination button:hover {
            background-color: #45a049;
        }
        .pagination button.active {
            font-weight: bold;
            background-color: #007bff;
        }
        .pagination span {
            font-size: 14px;
            color: red;
            align-self: center;
        }
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
            <button id="search-button" class="button">검색</button>
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
        const rows = Array.from(tbody.querySelectorAll("tr"));
        const pagination = document.querySelector(".pagination");

        let rowsPerPage = parseInt(rowsPerPageInput.value, 10) || 10;
        let currentPage = 1;
        let filteredRows = rows;

        function displayRows() {
            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            rows.forEach(row => row.style.display = "none");
            filteredRows.slice(start, end).forEach(row => row.style.display = "");
            updatePagination();
        }

        function updatePagination() {
            const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
            pagination.innerHTML = "";
            for (let i = 1; i <= totalPages; i++) {
                const button = document.createElement("button");
                button.textContent = i;
                if (i === currentPage) {
                    button.classList.add("active");
                }
                button.addEventListener("click", () => {
                    currentPage = i;
                    displayRows();
                });
                pagination.appendChild(button);
            }
            if (filteredRows.length === 0) {
                const noResults = document.createElement("span");
                noResults.textContent = "검색 결과가 없습니다.";
                pagination.appendChild(noResults);
            }
        }

        function searchRows() {
            const searchTerm = searchInput.value.toLowerCase();
            filteredRows = rows.filter(row => {
                const columns = row.querySelectorAll("td");
                return Array.from(columns).some(column =>
                    column.textContent.toLowerCase().includes(searchTerm)
                );
            });
            currentPage = 1;
            displayRows();
        }

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
