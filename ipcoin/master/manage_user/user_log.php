<?php
// (1) layout의 상단 부분 (doctype ~ head ~ body 열기) 불러오기
require_once dirname(__DIR__) . '/frames/header.php';

// (2) 상단메뉴(사이드바/탑바) 삽입
require_once dirname(__DIR__) . '/frames/nav.php';
require_once dirname(__DIR__) . '/frames/top_nav.php';
?>

<!-- (3) 실제 컨텐츠 영역 -->
<div class="content-wrapper" style="margin-top: 20px;">
    <section class="content">
        <div class="container-fluid">

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

            <table class="table table-bordered table-hover">
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

        </div><!-- /.container-fluid -->
    </section>
</div><!-- /.content-wrapper -->

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

        // 로그 데이터 fetch (GET)
        fetch('/master/manage_user/log_datafetch.php') // GET 요청
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    allLogs = data.logs;  // 백엔드에서 logs 배열로 내려줌
                    filteredLogs = allLogs;
                    renderTable(1);
                    setupPagination();
                } else {
                    console.error('Error fetching logs:', data.message);
                }
            })
            .catch(error => console.error('Error fetching logs:', error));

        // 테이블 렌더링
        function renderTable(page) {
            const start = (page - 1) * rowsPerPage;
            const end = start + rowsPerPage;

            tableBody.innerHTML = filteredLogs.slice(start, end).map(log => `
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

        // 페이지네이션
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

        // 검색 기능
        document.getElementById("search-button").addEventListener("click", () => {
            const searchTerm = searchInput.value.toLowerCase();
            filteredLogs = allLogs.filter(log =>
                log.user_mb_id.toLowerCase().includes(searchTerm) ||
                log.user_mb_name.toLowerCase().includes(searchTerm)
            );
            renderTable(1);
            setupPagination();
        });

        // 페이지당 줄 수
        rowsPerPageInput.addEventListener("change", () => {
            rowsPerPage = parseInt(rowsPerPageInput.value, 10) || 10;
            renderTable(1);
            setupPagination();
        });
    });
</script>

<?php
// (4) layout의 하단 부분 (footer 등) 불러오기
require_once dirname(__DIR__) . '/frames/footer.php';
?>