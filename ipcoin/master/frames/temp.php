<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>사용자 목록</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <script type="module" src="/path/to/pagination.js"></script>
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
        <tbody></tbody>
    </table>
    <div id="pagination-container" class="pagination"></div>

    <script type="module">
        import Pagination from '/path/to/pagination.js';

        // Fetch data from PHP
        fetch('/path/to/data_fetch.php')
            .then(response => response.json())
            .then(data => {
                const tbody = document.querySelector("#user-table tbody");
                tbody.innerHTML = data.map((user, index) => `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${user.mb_id}</td>
                        <td>${user.mb_name}</td>
                        <td>${user.mb_email}</td>
                        <td>${user.mb_tel}</td>
                        <td>${user.grade}</td>
                        <td>${user.signup_date}</td>
                        <td>${user.wallet_address || '없음'}</td>
                        <td>${parseFloat(user.total_balance || 0).toFixed(8)}</td>
                        <td>${parseFloat(user.available_balance || 0).toFixed(8)}</td>
                        <td>${parseFloat(user.locked_balance || 0).toFixed(8)}</td>
                    </tr>
                `).join('');

                // Initialize pagination
                new Pagination('user-table', 'rows-per-page', 'search-input', 'search-button', 'pagination-container');
            });
    </script>
</body>
</html>
