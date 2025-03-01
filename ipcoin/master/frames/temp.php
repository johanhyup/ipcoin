<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>사용자 목록</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <script type="module" src="/path/to/pagination.js"></script>
    <style>
        body {
            margin: 0;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f9;
        }
        .search-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .search-bar div {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .input-box {
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .button {
            padding: 8px 12px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        thead {
            background-color: #4CAF50;
            color: #fff;
        }
        th, td {
            padding: 12px 15px;
            text-align: center;
            border: none;
        }
        tbody tr {
            border-bottom: 1px solid #ddd;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tbody tr:hover {
            background-color: #f1f1f1;
        }
        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .pagination button {
            padding: 8px 12px;
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
        <tbody></tbody>
    </table>
    <div id="pagination-container" class="pagination"></div>

    <script type="module">
        import Pagination from '/path/to/pagination.js';

        // Fetch 데이터 가져오기
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

                // 페이지네이션 초기화
                new Pagination('user-table', 'rows-per-page', 'search-input', 'search-button', 'pagination-container');
            });
    </script>
</body>
</html>
