<?php
session_start([
    'cookie_lifetime' => 3000, // 쿠키 유효 시간 (3000초)
]);

// 세션 만료 체크 (10분)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 600)) {
    session_unset();
    session_destroy();
    echo "<script>
            alert('세션이 만료되었습니다. 다시 로그인 해주세요.');
            window.location.href = '/master/bbs/login.php';
          </script>";
    exit;
}
$_SESSION['last_activity'] = time();

// 관리자 로그인 확인
if (!isset($_SESSION['master_id']) || !isset($_SESSION['master_name'])) {
    echo "<script>
            alert('관리자 로그인이 필요한 페이지입니다.');
            window.location.href = '/master/bbs/login.php';
          </script>";
    exit;
}

// 필수 파일 포함
require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/frames/asset.php';

// 예시: 동적 데이터 - 총 회원 수 가져오기
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
$userCountResult = $conn->query("SELECT COUNT(*) AS user_count FROM users");
$userCountData = $userCountResult->fetch_assoc();
$userCount = $userCountData['user_count'];
$conn->close();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>관리자 대시보드 | Story(IP) Wallet</title>
  <!-- AdminLTE CSS -->
  <link rel="stylesheet" href="/master/AdminLte/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="/master/AdminLte/dist/css/adminlte.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="/master/assets/css/main.css">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4f7f9;
      margin: 0;
      padding: 0;
    }
    .info-box {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      margin-bottom: 20px;
    }
    .info-box-icon {
      border-radius: 8px 0 0 8px;
    }
    .info-box-content {
      padding: 10px 15px;
    }
    .content-header h1 {
      font-size: 28px;
      color: #333;
      margin: 0;
      font-weight: 600;
    }
    .breadcrumb a {
      color: #4CAF50;
      text-decoration: none;
    }
    .breadcrumb a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- 상단 네비게이션 (top_nav.php) -->
  <?php include_once dirname(__DIR__) . '/frames/top_nav.php'; ?>
  
  <!-- 좌측 사이드바 (nav.php) -->
  <?php include_once dirname(__DIR__) . '/frames/nav.php'; ?>

  <!-- Content Wrapper: 페이지 콘텐츠 영역 -->
  <div class="content-wrapper">
    <!-- Content Header (페이지 헤더) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2 align-items-center">
          <div class="col-sm-6">
            <h1 class="m-0">대시보드</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/master/index.php">홈</a></li>
              <li class="breadcrumb-item active">대시보드</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <!-- Main Content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Info Box: 총 회원 수 -->
        <div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1">
                <i class="fas fa-users"></i>
              </span>
              <div class="info-box-content">
                <span class="info-box-text">총 회원 수</span>
                <span class="info-box-number"><?= htmlspecialchars($userCount) ?></span>
              </div>
            </div>
          </div>
          <!-- 추가 정보 박스들을 여기에 추가할 수 있습니다. -->
        </div>
      </div>
    </section>
  </div>
  
  <!-- Footer -->
  <footer class="main-footer" style="background-color: #fff; box-shadow: 0 -2px 8px rgba(0,0,0,0.1); border-top: 1px solid #e0e0e0; padding: 15px 20px;">
    <div class="float-right d-none d-sm-inline">
      Story(IP) Wallet Admin
    </div>
    <strong>&copy; <?= date('Y') ?> Story(IP) Wallet.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- AdminLTE & 필수 JS -->
<script src="/master/AdminLte/plugins/jquery/jquery.min.js"></script>
<script src="/master/AdminLte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/master/AdminLte/dist/js/adminlte.min.js"></script>
<!-- Custom JS -->
<script src="/master/assets/js/main.js"></script>
</body>
</html>
