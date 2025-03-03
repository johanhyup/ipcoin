<?php
// top_nav.php
require_once dirname(__DIR__) . '/../config.php';
?>

<!-- 상단 네비게이션바 -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- 왼쪽 햄버거 버튼 -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <!-- data-widget="pushmenu"가 사이드바 열고닫기를 담당 -->
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>

    <!-- (원래 '관리자 메뉴' 문구 or 버튼이 있었다면 제거) -->
    <!-- <li class="nav-item d-none d-md-block">
      <span class="nav-link"><strong>관리자 메뉴</strong></span>
    </li> -->
  </ul>

  <!-- 오른쪽 영역 -->
  <ul class="navbar-nav ml-auto">
    <!-- 기존 'IP 가격' 표시 부분 -> Story Protocol로 변경 -->
    <li class="nav-item">
      <span class="nav-link">
        <span class="badge badge-danger" id="closing_price_display">
          Story Protocol
        </span>
      </span>
    </li>

    <!-- 총 입금액, 총 출금액, 총 관리자, 신규회원 등 모두 제거 -->
    <!--
    <li class="nav-item d-none d-md-block">
      <span class="nav-link">
        <span class="badge badge-danger" id="total_deposit_display">
          총 입금액<br>1,000,000,000
        </span>
      </span>
    </li>
    <li class="nav-item d-none d-md-block">
      <span class="nav-link">
        <span class="badge badge-danger" id="total_withdraw_display">
          총 출금액<br>0
        </span>
      </span>
    </li>
    <li class="nav-item d-none d-md-block">
      <span class="nav-link">
        <span class="badge badge-primary" id="total_admins">
          총 관리자<br>4
        </span>
      </span>
    </li>
    <li class="nav-item d-none d-md-block">
      <span class="nav-link">
        <span class="badge badge-primary" id="new_users">
          신규회원<br>4
        </span>
      </span>
    </li>
    -->

    <!-- 로그아웃 버튼 (모바일/PC 모두 표시) -->
    <li class="nav-item">
      <a class="nav-link btn btn-danger text-white" href="/master/bbs/logout.php">
        로그아웃
      </a>
    </li>
  </ul>
</nav>

<!-- JS 라이브러리 (원래 있던 스크립트들) -->
<!-- 
<script src="..."></script>
<script>
  // 예: IP가격 대신 textContent = 'Story Protocol';
  // document.getElementById('closing_price_display').textContent = 'Story Protocol';
</script> 
-->