<?php
// 페이지 시작: header.php 불러오기
require_once __DIR__ . '/frame/header.php';
?>

<div class="container-fluid">
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
</div>

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

<?php
// 페이지 끝: footer.php 불러오기
require_once __DIR__ . '/frame/footer.php';
?>
