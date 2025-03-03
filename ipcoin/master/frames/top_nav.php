<?php
// top_nav.php
// - 모바일에서 햄버거 메뉴 아이콘이 항상 보이도록
require_once dirname(__DIR__) . '/../config.php';
?>

<!-- 상단 네비게이션바 -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- 왼쪽 햄버거 버튼 -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <!-- data-widget="pushmenu" 로 사이드바 열고닫기 -->
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <!-- FontAwesome 아이콘 fa-bars -->
        <i class="fas fa-bars"></i>
      </a>
    </li>
  </ul>

  <!-- 오른쪽 영역 -->
  <ul class="navbar-nav ml-auto">
    <!-- Story Protocol 배지 (IP 가격 대체) -->
    <li class="nav-item">
      <span class="nav-link">
        <span class="badge badge-danger" id="closing_price_display">
          Story Protocol
        </span>
      </span>
    </li>

    <!-- 로그아웃 버튼 -->
    <li class="nav-item">
      <a class="nav-link btn btn-danger text-white" href="/master/bbs/logout.php">
        로그아웃
      </a>
    </li>
  </ul>
</nav>