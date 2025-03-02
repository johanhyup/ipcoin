<?php
// 세션 시작
session_start();


// config.php나 DB 연결 필요 시 불러오기
require_once __DIR__ . '/../../config.php';

// AdminLTE에서 권장하는 HTML 구조 시작
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>관리자 페이지</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- AdminLTE CSS -->
  <link rel="stylesheet" href="/master/AdminLte/dist/css/adminlte.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/master/AdminLte/plugins/fontawesome-free/css/all.min.css">
  <!-- (선택) Ionicons, Google Fonts 등 필요하다면 추가 -->

  <!-- 커스텀 CSS -->
  <link rel="stylesheet" href="/master/assets/css/main.css">
</head>

<!-- body 태그에 AdminLTE 기본 클래스 지정 (sidebar-mini, layout-fixed 등) -->
<body class="hold-transition sidebar-mini layout-fixed">

<!-- 래퍼(wrapper) -->
<div class="wrapper">

  <!-- 상단 Navbar (AdminLTE 구조) -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- 왼쪽 햄버거 버튼 -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <!-- pushmenu: 사이드바 열고 닫기 -->
        <a class="nav-link" data-widget="pushmenu" href="#" role="button">
          <i class="fas fa-bars"></i>
        </a>
      </li>
      <!-- 필요하다면 상단 메뉴 추가 가능 -->
      <li class="nav-item d-none d-sm-inline-block">
        <a href="/master/index.php" class="nav-link">홈</a>
      </li>
    </ul>

    <!-- 오른쪽 메뉴 -->
    <ul class="navbar-nav ml-auto">
      <!-- 로그아웃 버튼 -->
      <li class="nav-item">
        <a class="nav-link" href="/master/bbs/logout.php">
          <i class="fas fa-sign-out-alt"></i> 로그아웃
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- 메인 사이드바 (왼쪽 메뉴)-->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- 사이트 로고(브랜드 로고) -->
    <a href="/master/index.php" class="brand-link">
      <!-- 로고 이미지가 있을 경우 
      <img src="/master/AdminLte/dist/img/AdminLTELogo.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8"/>
      -->
      <span class="brand-text font-weight-light">Raycoin Wallet</span>
    </a>

    <!-- 사이드바 영역 -->
    <div class="sidebar">
      <!-- 로그인 유저 정보(옵션) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info" style="color:#fff;">
          <?php
          echo $_SESSION['master_name'] . "님";
          ?>
        </div>
      </div>

      <!-- 실제 메뉴 부분 -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
          <!-- 대시보드 -->
          <li class="nav-item">
            <a href="/master/index.php" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p> 대시보드 </p>
            </a>
          </li>

          <!-- 회원관리 -->
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                회원관리
                <i class="right fas fa-angle-left"></i> <!-- 펼치기 아이콘 -->
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/master/manage_user/userlist_view.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>회원목록</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/master/manage_user/user_log.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>회원 로그인 내역</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/master/manage_user/user_info.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>회원가입 승인</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- 기타 메뉴들... -->
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-money-bill"></i>
              <p>
                입출금 관리
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/master/wallet/deposit_view.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>입금기록 관리</p>
                </a>
              </li>
              <!-- ... -->
            </ul>
          </li>
        </ul>
      </nav>
    </div> 
    <!-- /.sidebar -->
  </aside>

  <!-- 콘텐츠 WRAPPER 시작 -->
  <div class="content-wrapper">
    <!-- 상단 콘텐츠 헤더 (옵션) -->
    <section class="content-header">
      <div class="container-fluid">
        <!-- 예: <h1>대시보드</h1> -->
      </div>
    </section>

    <!-- 실제 페이지 내용 -->
    <section class="content">
      <!-- 여기서부터 footer.php에서 </section>을 닫을 때까지, 각 페이지별 콘텐츠가 들어가게 됨 -->