<?php
// /var/www/ip-wallet.com/master/frame/header.php

// 세션 사용
session_start([
    'cookie_lifetime' => 3000, // 필요에 따라 조정
]);

// 세션 만료 처리 예시
if (isset($_SESSION['last_activity'])) {
    if ((time() - $_SESSION['last_activity']) > 600) { // 10분
        session_unset();
        session_destroy();
        echo "<script>
            alert('세션이 만료되었습니다. 다시 로그인 해주세요.');
            window.location.href = '/master/bbs/login.php';
        </script>";
        exit;
    }
}
$_SESSION['last_activity'] = time(); // 마지막 활동 시간 갱신

// 로그인 여부 체크
if (!isset($_SESSION['master_id']) || !isset($_SESSION['master_name'])) {
    echo "<script>
        alert('관리자 로그인이 필요한 페이지입니다.');
        window.location.href = '/master/bbs/login.php';
    </script>";
    exit;
}

// 필요한 config.php 등을 불러오기
require_once dirname(__DIR__) . '/../config.php';

// AdminLTE 공통 리소스 불러오기
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8"/>
  <title>관리자 페이지 | Raycoin Wallet</title>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/master/AdminLte/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <!-- (필요하다면) <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> -->
  <!-- AdminLTE css -->
  <link rel="stylesheet" href="/master/AdminLte/dist/css/adminlte.min.css">
  <!-- Google Font (옵션) -->
  <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700"> -->
  
  <!-- 커스텀 CSS -->
  <link rel="stylesheet" href="/master/assets/css/main.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- 상단 Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- 왼쪽 햄버거 버튼 -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <!-- 사이드바 열고닫기 -->
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="/master/index.php" class="nav-link">홈</a>
      </li>
    </ul>

    <!-- 오른쪽 메뉴 -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="/master/bbs/logout.php">로그아웃</a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- 메인 사이드바 -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- 브랜드 로고 -->
    <a href="/master/index.php" class="brand-link">
      <!-- <img src="/master/AdminLte/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
      <span class="brand-text font-weight-light">Raycoin Wallet</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- 유저 정보(옵션) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info" style="color:#fff;">
          <span><?php echo $_SESSION['master_name']; ?> 님</span>
        </div>
      </div>

      <!-- 사이드바 메뉴 -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
          <!-- 대시보드 -->
          <li class="nav-item">
            <a href="/master/index.php" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>대시보드</p>
            </a>
          </li>
          
          <!-- 회원관리 메뉴 -->
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                회원관리
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="display: none;">
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
                  <p>회원가입승인</p>
                </a>
              </li>
              <!-- 필요한 메뉴 추가 -->
            </ul>
          </li>

          <!-- 상위관리자 메뉴 -->
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-user-shield"></i>
              <p>
                상위관리자
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="display: none;">
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>관리자목록</p>
                </a>
              </li>
              <!-- 필요 메뉴 추가 -->
            </ul>
          </li>

          <!-- 입출금 관리 -->
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-money-bill"></i>
              <p>
                입출금 관리
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="display:none;">
              <li class="nav-item">
                <a href="/master/wallet/deposit_view.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>입금기록 관리</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/master/wallet/deposit_manage.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>임시 입금신청</p>
                </a>
              </li>
              <!-- 필요 메뉴 추가 -->
            </ul>
          </li>

          <!-- 기타 설정 -->
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cogs"></i>
              <p>
                기타 설정
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="display:none;">
              <li class="nav-item">
                <a href="/master/others/lockup_manage.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>락업시간 설정</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/master/others/lockup_view.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>락업기록 조회</p>
                </a>
              </li>
              <!-- 필요 메뉴 추가 -->
            </ul>
          </li>
          
          <!-- 필요시 더 추가 -->
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- 콘텐츠 영역 시작 -->
  <div class="content-wrapper">
    <!-- 상단 콘텐츠 헤더 (옵션) -->
    <section class="content-header">
      <div class="container-fluid">
        <h1>관리자 페이지</h1>
      </div>
    </section>

    <!-- 메인 콘텐츠 -->
    <section class="content">
    <!-- 여기서부터 각 페이지가 들어갈 영역 -->
