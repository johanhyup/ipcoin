<?php
// 현재 파일의 상위 디렉토리의 config.php를 불러옴
require_once dirname(__DIR__) . '/../config.php';
require_once dirname(__DIR__) . '/../frames/asset.php';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>관리자 페이지</title>
    <link rel="stylesheet" href="/master/assets/css/main.css">
</head>
<body>

<!-- Top Navigation Bar -->
<div class="top-nav">
    <!-- 햄버거 버튼 -->
    <div class="hamburger" onclick="toggleNav()">
        <span></span>
        <span></span>
        <span></span>
    </div>
    <h2 style="left: 30px; position: relative;">관리자 메뉴</h2>
    <div class="info">
        <div class="red" id="closing_price_display">현재 IP가격: Loading...</div>
        <div class="red" id="total_deposit_display">총 입금액<br>1,000,000,000</div>
        <div class="red" id="total_withdraw_display">총 출금액<br>0</div>
        <div class="blank"></div>
        <div class="blue" id="total_users">총 회원<br>72</div>
        <div class="blue" id="total_admins">총 관리자<br>4</div>
        <div class="blue" id="new_users">신규회원<br>4</div>
    </div>
    <div class="logout" style="margin-right:30px">
        <a href="/master/bbs/logout.php">로그아웃</a>
    </div>
</div>

<script>
    // 햄버거 버튼 클릭 시 사이드바 열고 닫기
    function toggleNav() {
        const navBar = document.getElementById('nav-bar');
        navBar.classList.toggle('open');
    }
</script>

</body>
</html>