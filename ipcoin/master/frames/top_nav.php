<?php
// top_nav.php
require_once dirname(__DIR__) . '/../config.php';
?>

<!-- 상단 네비게이션바 -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- 왼쪽 영역: 햄버거 + Story Protocol -->
  <ul class="navbar-nav">
    <!-- 햄버거 버튼 -->
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>
    <!-- Story Protocol 배지: 왼쪽으로 이동 -->
    <li class="nav-item">
      <span class="nav-link">
        <span class="badge badge-danger" id="closing_price_display">
          Story Protocol
        </span>
      </span>
    </li>
  </ul>

  <!-- 오른쪽 영역: 로그아웃 버튼 -->
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <a class="nav-link btn btn-danger text-white" href="/master/bbs/logout.php">
        로그아웃
      </a>
    </li>
  </ul>
</nav>