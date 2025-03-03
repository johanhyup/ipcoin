<!-- top_nav.php (변경 예시) -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- 왼쪽 햄버거 버튼 -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>

    <!-- (기존에 "관리자 메뉴"를 띄우던 항목이 있다면 제거/주석 처리) -->
    <!-- 
    <li class="nav-item d-none d-md-block">
      <span class="nav-link"><strong>관리자 메뉴</strong></span>
    </li>
    -->
  </ul>

  <!-- 오른쪽 영역 -->
  <ul class="navbar-nav ml-auto">

    <!-- 기존의 IP 가격 배지 -> "Story Protocol" 으로 텍스트만 변경 -->
    <li class="nav-item">
      <span class="nav-link">
        <span class="badge badge-danger" id="closing_price_display">Story Protocol</span>
      </span>
    </li>

    <!-- 총 입금액/총 출금액/총 관리자 배지 제거 -->
    <!--
    <li class="nav-item d-none d-md-block">
      <span class="nav-link">
        <span class="badge badge-danger" id="total_deposit_display">총 입금액<br>1,000,000,000</span>
      </span>
    </li>
    <li class="nav-item d-none d-md-block">
      <span class="nav-link">
        <span class="badge badge-danger" id="total_withdraw_display">총 출금액<br>0</span>
      </span>
    </li>
    <li class="nav-item d-none d-md-block">
      <span class="nav-link">
        <span class="badge badge-primary" id="total_admins">총 관리자<br>4</span>
      </span>
    </li>
    -->

    <!-- 총 회원 (모바일에서는 숨김) / 신규회원 등은 그대로 유지(원하시면 제거 가능) -->
    <li class="nav-item d-none d-md-block">
      <span class="nav-link">
        <span class="badge badge-primary" id="total_users">
          총 회원<br>72
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

    <!-- 로그아웃 버튼은 유지 -->
    <li class="nav-item">
      <a class="nav-link btn btn-danger text-white" href="/master/bbs/logout.php">
        로그아웃
      </a>
    </li>
  </ul>
</nav>