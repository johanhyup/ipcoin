<?php
// 현재 파일의 상위 디렉토리의 config.php를 불러옴
require_once dirname(__DIR__) . '/../config.php';
require_once dirname(__DIR__) . '/../frames/asset.php';
?>
<!-- 상단바: AdminLTE 구조 -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- 왼쪽 햄버거 버튼 -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <!-- data-widget="pushmenu"가 사이드바 열고닫기를 담당 -->
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>
    <!-- 관리자 메뉴 텍스트 (모바일에서는 숨기고 md 이상에서만 보이도록) -->
    <li class="nav-item d-none d-md-block">
      <span class="nav-link"><strong>관리자 메뉴</strong></span>
    </li>
  </ul>

  <!-- 오른쪽 영역 -->
  <ul class="navbar-nav ml-auto">
    <!-- IP 가격 (모바일/PC 모두에서 표시) -->
    <li class="nav-item">
      <span class="nav-link">
        <span class="badge badge-danger" id="closing_price_display">IP가격: Loading...</span>
      </span>
    </li>

    <!-- 총 입금액 (모바일에서는 숨기고 md 이상에서만 보임) -->
    <li class="nav-item d-none d-md-block">
      <span class="nav-link">
        <span class="badge badge-danger" id="total_deposit_display">총 입금액<br>1,000,000,000</span>
      </span>
    </li>
    <!-- 총 출금액 (모바일에서는 숨기고 md 이상에서만 보임) -->
    <li class="nav-item d-none d-md-block">
      <span class="nav-link">
        <span class="badge badge-danger" id="total_withdraw_display">총 출금액<br>0</span>
      </span>
    </li>
    
    <!-- 총 회원 (모바일에서는 숨기고 md 이상에서만 보임) -->
    <li class="nav-item d-none d-md-block">
      <span class="nav-link">
        <span class="badge badge-primary" id="total_users">총 회원<br>72</span>
      </span>
    </li>
    <!-- 총 관리자 (모바일에서는 숨기고 md 이상에서만 보임) -->
    <li class="nav-item d-none d-md-block">
      <span class="nav-link">
        <span class="badge badge-primary" id="total_admins">총 관리자<br>4</span>
      </span>
    </li>
    <!-- 신규회원 (모바일에서는 숨기고 md 이상에서만 보임) -->
    <li class="nav-item d-none d-md-block">
      <span class="nav-link">
        <span class="badge badge-primary" id="new_users">신규회원<br>4</span>
      </span>
    </li>

    <!-- 로그아웃 버튼 (모바일/PC 모두에서 표시) -->
    <li class="nav-item">
      <a class="nav-link btn btn-danger text-white" href="/master/bbs/logout.php">
        로그아웃
      </a>
    </li>
  </ul>
</nav>