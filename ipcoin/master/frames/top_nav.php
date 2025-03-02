<?php
require_once dirname(__DIR__) . '/../config.php';
?>

<!-- 상단바 DIV만 -->
<div class="top-nav">
    <div class="hamburger-menu" onclick="toggleNav()">&#9776;</div>
    <h2>관리자 메뉴</h2>
    <div class="logout" style="margin-right:30px">
        <a href="/master/bbs/logout.php">로그아웃</a>
    </div>
</div>

<script>
function toggleNav() {
    const navBar = document.querySelector('.nav-bar');
    navBar.classList.toggle('open');
}
</script>