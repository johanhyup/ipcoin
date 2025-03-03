<?php
// 세션 시작
session_start();

// config.php나 DB 연결 필요 시 불러오기
require_once __DIR__ . '/../../config.php';
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

<div class="wrapper"><!-- AdminLTE 래퍼 시작 -->

  <!-- 상단 Navbar와 왼쪽 사이드바를 제거한 상태입니다.
       필요하면 이 자리에 다른 요소를 넣거나 그냥 비워둡니다. -->

  <!-- 메인 콘텐츠 WRAPPER -->
  <div class="content-wrapper">
    <!-- 상단 콘텐츠 헤더 (옵션) -->
    <section class="content-header">
      <div class="container-fluid">
        <!-- 예: <h1>페이지 제목</h1> -->
      </div>
    </section>

    <!-- 실제 페이지 내용 -->
    <section class="content">
      <!-- 이 내부에서 각 페이지별 컨텐츠를 출력 -->
      <div class="container-fluid">
        <h1>여기에 실제 페이지 컨텐츠</h1>
        <p>상단바와 사이드바가 제거된 레이아웃 예시입니다.</p>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- 푸터(footer) 필요 시 -->
  <footer class="main-footer">
    <strong>&copy; 2025 IPcoin Wallet</strong>
  </footer>

</div><!-- /.wrapper 끝 -->

<!-- AdminLTE 및 관련 스크립트 -->
<script src="/master/AdminLte/plugins/jquery/jquery.min.js"></script>
<script src="/master/AdminLte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/master/AdminLte/dist/js/adminlte.min.js"></script>
</body>
</html>