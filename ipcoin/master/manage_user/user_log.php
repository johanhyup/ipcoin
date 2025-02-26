<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>사용자 로그</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .pagination {
            margin-top: 10px;
            text-align: center;
        }
        .pagination button {
            padding: 5px 10px;
            margin: 2px;
        }
        .search-bar {
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
        }
        .input-box {
            padding: 5px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <?php
    require_once dirname(__DIR__) . '/frames/nav.php';
    require_once dirname(__DIR__) . '/frames/top_nav.php';
    ?>
    <div class="main2" style="transform: translate(0%, 10%)">
        <h1>사용자 로그</h1>
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
        <table>
            <thead>
                <tr>
                    <th>로그 ID</th>
                    <th>사용자 ID</th>
                    <th>사용자 닉네임</th>
                    <th>이메일</th>
                    <th>회원 등급</th>
                    <th>지갑 주소</th>
                    <th>총 잔액</th>
                    <th>사용 가능 잔액</th>
                    <th>잠금 잔액</th>
                    <th>코인 이름</th>
                    <th>코인 총량</th>
                    <th>코인 잠금량</th>
                    <th>접속 IP</th>
                    <th>접속 시간</th>
                </tr>
            </thead>
            <tbody id="logTableBody"></tbody>
        </table>
        <div id="pagination-container" class="pagination"></div>
    </div>
    <script type="module">
        import Pagination from '/master/assets/js/pagination.js';

        document.addEventListener("DOMContentLoaded", () => {
            let allLogs = [];
            let filteredLogs = [];
            let rowsPerPage = 10;

            const tableBody = document.getElementById("logTableBody");
            const searchInput = document.getElementById("search-input");
            const rowsPerPageInput = document.getElementById("rows-per-page");
            const paginationContainer = document.getElementById("pagination-container");

            // Fetch logs data
            fetch('/master/manage_user/log_datafetch.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        allLogs = data.logs;
                        filteredLogs = allLogs;
                        renderTable(1);
                        setupPagination();
                    } else {
                        console.error('Error fetching logs:', data.message);
                    }
                })
                .catch(error => console.error('Error fetching logs:', error));

            // Render table data
            function renderTable(page) {
                const start = (page - 1) * rowsPerPage;
                const end = start + rowsPerPage;

                tableBody.innerHTML = filteredLogs.slice(start, end).map((log, index) => `
                    <tr>
                        <td>${log.log_id}</td>
                        <td>${log.user_mb_id}</td>
                        <td>${log.user_mb_name}</td>
                        <td>${log.user_mb_email}</td>
                        <td>${log.user_grade}</td>
                        <td>${log.wallet_address || '없음'}</td>
                        <td>${parseFloat(log.total_balance || 0).toFixed(8)}</td>
                        <td>${parseFloat(log.available_balance || 0).toFixed(8)}</td>
                        <td>${parseFloat(log.locked_balance || 0).toFixed(8)}</td>
                        <td>${log.coin_name || '없음'}</td>
                        <td>${parseFloat(log.coin_total_amount || 0).toFixed(8)}</td>
                        <td>${parseFloat(log.coin_locked_amount || 0).toFixed(8)}</td>
                        <td>${log.user_ip || '알 수 없음'}</td>
                        <td>${log.last_login}</td>
                    </tr>
                `).join('');
            }

            // Setup pagination
            function setupPagination() {
                const totalPages = Math.ceil(filteredLogs.length / rowsPerPage);
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
                filteredLogs = allLogs.filter(log =>
                    log.user_mb_id.toLowerCase().includes(searchTerm) ||
                    log.user_mb_name.toLowerCase().includes(searchTerm)
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
        });
    </script>
</body>
</html>
