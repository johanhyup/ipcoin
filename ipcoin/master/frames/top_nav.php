<!-- top_nav.php -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- 왼쪽 햄버거 버튼 -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>
    <!-- 관리자메뉴 (모바일에서는 숨기고...) -> 삭제/주석 처리 -->
    <!--
    <li class="nav-item d-none d-md-block">
      <span class="nav-link"><strong>관리자 메뉴</strong></span>
    </li>
    -->
  </ul>

  <!-- 오른쪽 영역 -->
  <ul class="navbar-nav ml-auto">
    <!-- 기존: "IP 가격" -> "Story Protocol" 로 변경 -->
    <li class="nav-item">
      <span class="nav-link">
        <span class="badge badge-danger" id="closing_price_display">
          Story Protocol
        </span>
      </span>
    </li>

    <!-- 총 입금액, 총 출금액, 총 관리자, 신규회원 등등 -> 전부 주석/삭제
    <li class="nav-item d-none d-md-block"> ... </li>
    <li class="nav-item"> ... </li>
    ... 
    -->

    <!-- 로그아웃 버튼 (유지) -->
    <li class="nav-item">
      <a class="nav-link btn btn-danger text-white" href="/master/bbs/logout.php">
        로그아웃
      </a>
    </li>
  </ul>
</nav>