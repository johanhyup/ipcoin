<?php
// 현재 파일의 상위 디렉토리의 config.php를 불러옴
require_once dirname(__DIR__) . '/../config.php';
require_once dirname(__DIR__) . '/../frames/asset.php';
?>

<!-- AdminLTE 상단 네비게이션 바 -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- 왼쪽에 햄버거 버튼 -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <!-- data-widget="pushmenu" 는 AdminLTE에서 사이드바를 접고 펼치는 역할 -->
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="#" class="nav-link">관리자 메뉴</a>
    </li>
  </ul>

  <!-- 우측 정보들 -->
  <ul class="navbar-nav ml-auto">
    <!-- 여기에는 기존 top_nav에 있던 '현재 IP가격' 등등을 간단히 카드/버튼 형태로 표현할 수도 있습니다. -->
    <li class="nav-item">
      <div class="nav-link">
        <span id="closing_price_display">현재 IP가격: Loading...</span>
      </div>
    </li>
    <li class="nav-item">
      <div class="nav-link text-danger" id="total_deposit_display">
        총 입금액: <strong>1,000,000,000</strong>
      </div>
    </li>
    <li class="nav-item">
      <div class="nav-link text-danger" id="total_withdraw_display">
        총 출금액: <strong>0</strong>
      </div>
    </li>
    <li class="nav-item">
      <div class="nav-link text-primary" id="total_users">
        총 회원: <strong>72</strong>
      </div>
    </li>
    <li class="nav-item">
      <div class="nav-link text-primary" id="total_admins">
        총 관리자: <strong>4</strong>
      </div>
    </li>
    <li class="nav-item">
      <div class="nav-link text-primary" id="new_users">
        신규회원: <strong>4</strong>
      </div>
    </li>

    <!-- 로그아웃 -->
    <li class="nav-item">
      <a href="/master/bbs/logout.php" class="nav-link text-danger">
        로그아웃
      </a>
    </li>
  </ul>
</nav>
<!-- /.navbar -->