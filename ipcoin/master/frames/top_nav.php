<?php
// 필요하다면 DB 연결, 세션 코드 유지
// require_once dirname(__DIR__) . '/../config.php';
// ...
?>

<!-- 상단 네비게이션바 -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- 좌측 햄버거 버튼 (모바일에서 사이드바 열기) -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>
    <!-- 불필요한 관리자 메뉴 문구 제거 -->
  </ul>

  <!-- 오른쪽 영역 -->
  <ul class="navbar-nav ml-auto">
    
    <!-- 기존에 '현재 IP 가격: ~' 이 있었다면 -> Story Protocol 로 변경 -->
    <li class="nav-item">
      <span class="nav-link">
        <span class="badge badge-danger" id="closing_price_display">Story Protocol</span>
      </span>
    </li>

    <!-- '총 입금액', '총 출금액', '총 관리자' 등은 제거 -->
    <!-- '신규회원' 등도 사용하지 않으시면 제거 가능합니다 -->

    <!-- 로그아웃 버튼 -->
    <li class="nav-item">
      <a class="nav-link btn btn-danger text-white" href="/master/bbs/logout.php">
        로그아웃
      </a>
    </li>
  </ul>
</nav>