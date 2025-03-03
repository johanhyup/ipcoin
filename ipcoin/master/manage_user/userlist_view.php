<?php
// /master/manage_user/userlist_view.php

// (1) header.php 불러오기 (AdminLTE <html> 시작, <head>, <body>, .wrapper 시작 등)
require_once __DIR__ . '/../frames/header.php';

// (2) 승인/거절 처리 로직 (DB 연결은 header.php -> config.php에서 이미 로드됨)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $approved = isset($_POST['approved']) ? intval($_POST['approved']) : 0;

    if ($user_id > 0) {
        $stmt = $conn->prepare("UPDATE users SET approved = ? WHERE id = ?");
        $stmt->bind_param("ii", $approved, $user_id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: userlist_view.php");
    exit;
}
?>

<!-- (4) 메인 컨텐츠 -->
<div class="content-wrapper">
    <div class="container-fluid" style="margin-top: 20px;">
        <h1>사용자 관리</h1>
        
        <div class="search-bar" style="display:flex; gap:1rem; flex-wrap: wrap;">
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

        <table id="user-table" style="width:100%; border-collapse:collapse; margin-top: 1rem;">
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
                    <th>승인 여부</th>
                    <th>정보수정</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <!-- 페이지네이션 -->
        <div id="pagination-container" class="pagination" style="margin-top: 1rem;"></div>
    </div>
</div>

<!-- (5) 스크립트 로직 -->
<script type="module">
import Pagination from '/master/assets/js/pagination.js';

fetch('/master/manage_user/user_datafetch.php')
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
                <td>${parseInt(user.approved, 10) === 1 ? '승인됨' : '블락'}</td>
                <td>
                    <button class="edit-button" data-user-id="${user.user_id}">정보수정</button>
                </td>
            </tr>
        `).join('');
        
        new Pagination('user-table', 'rows-per-page', 'search-input', 'search-button', 'pagination-container');
    });
</script>

<!-- footer.php 불러오기 -->
<?php require_once __DIR__ . '/../frames/footer.php'; ?>