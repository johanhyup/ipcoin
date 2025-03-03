<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>관리자 대시보드</title>
  <!-- 반응형 -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- AdminLTE or Bootstrap CSS -->
  <link rel="stylesheet" href="/path/to/adminlte.min.css">
  <link rel="stylesheet" href="/path/to/bootstrap.min.css">
  <link rel="stylesheet" href="/path/to/fontawesome.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">

<?php
// config.php 등 필요 파일 로드
require_once dirname(__DIR__) . '/../config.php';
require_once dirname(__DIR__) . '/../frames/asset.php';
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
    <!-- 관리자 메뉴 (모바일에서는 숨기고 md 이상에서만 보이게) -->
    <li class="nav-item d-none d-md-block">
      <span class="nav-link"><strong>관리자 메뉴</strong></span>
    </li>
  </ul>

  <!-- 오른쪽 영역 -->
  <ul class="navbar-nav ml-auto">
    <!-- IP 가격 (모바일/PC 모두 표시) -->
    <li class="nav-item">
      <span class="nav-link">
        <span class="badge badge-danger" id="closing_price_display">IP가격: Loading...</span>
      </span>
    </li>

    <!-- 총 입금액 (모바일에서는 숨김: d-none d-md-block) -->
    <li class="nav-item d-none d-md-block">
      <span class="nav-link">
        <span class="badge badge-danger" id="total_deposit_display">
          총 입금액<br>1,000,000,000
        </span>
      </span>
    </li>
    
    <!-- 총 출금액 (모바일에서는 숨김) -->
    <li class="nav-item d-none d-md-block">
      <span class="nav-link">
        <span class="badge badge-danger" id="total_withdraw_display">
          총 출금액<br>0
        </span>
      </span>
    </li>

    <!-- 총 회원 (모바일에서는 숨김) -->
    <li class="nav-item d-none d-md-block">
      <span class="nav-link">
        <span class="badge badge-primary" id="total_users">
          총 회원<br>72
        </span>
      </span>
    </li>

    <!-- 총 관리자 (모바일에서는 숨김) -->
    <li class="nav-item d-none d-md-block">
      <span class="nav-link">
        <span class="badge badge-primary" id="total_admins">
          총 관리자<br>4
        </span>
      </span>
    </li>

    <!-- 신규회원 (모바일에서는 숨김) -->
    <li class="nav-item d-none d-md-block">
      <span class="nav-link">
        <span class="badge badge-primary" id="new_users">
          신규회원<br>4
        </span>
      </span>
    </li>

    <!-- 로그아웃 버튼 (모바일/PC 모두 표시) -->
    <li class="nav-item">
      <a class="nav-link btn btn-danger text-white" href="/master/bbs/logout.php">
        로그아웃
      </a>
    </li>
  </ul>
</nav>

<!-- 메인 컨텐츠 -->
<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <h1>대시보드 내용</h1>
      <!-- 나머지 페이지 내용 -->
    </div>
  </section>
</div>

<!-- 푸터 (필요 시) -->
<footer class="main-footer">
  <strong>&copy; 
    <script>document.write(new Date().getFullYear())</script>
    IP Wallet
  </strong>
</footer>

<!-- JS 라이브러리 -->
<script src="/path/to/jquery.min.js"></script>
<script src="/path/to/bootstrap.bundle.min.js"></script>
<script src="/path/to/adminlte.min.js"></script>

<script>
  // 예시: IP 가격 업데이트, 배지 값 업데이트 등
  document.getElementById('closing_price_display').textContent = 'IP가격: 12345';
</script>

</body>
</html>