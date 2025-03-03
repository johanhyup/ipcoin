<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>관리자 대시보드 | IPcoin Wallet</title>
  <!-- AdminLTE CSS / Bootstrap / FontAwesome 예시 CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" />
  <!-- 기존 main.css -->
  <link rel="stylesheet" href="/master/assets/css/main.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- 상단바 불러오기 -->
  <?php
    require_once dirname(__DIR__) . '/master/frames/top_nav.php';
  ?>

  <!-- 사이드바 불러오기 -->
  <?php
    require_once dirname(__DIR__) . '/master/frames/nav.php';
  ?>

  <!-- 콘텐츠 WRAPPER 시작 -->
  <div class="content-wrapper">
    <!-- 메인 콘텐츠 헤더(옵션) -->
    <section class="content-header">
      <div class="container-fluid">
        <h1>관리자 대시보드</h1>
      </div>
    </section>

    <!-- 메인 콘텐츠 -->
    <section class="content">
      <div class="container-fluid">
        <?php
        // 추가 PHP 로직 (DB 연결, 세션 확인 등)
        // require_once dirname(__DIR__) . '/config.php';
        
        // 세션에서 로그인한 관리자 정보
        $master_name = $_SESSION['master_name'];
        $rank = $_SESSION['rank'];

        // 간단한 통계 데이터 조회 (예제)
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Database connection failed: " . $conn->connect_error);
        }

        // 사용자 수 가져오기
        $user_count_result = $conn->query("SELECT COUNT(*) AS user_count FROM users");
        $user_count = $user_count_result->fetch_assoc()['user_count'];

        // 출금 요청 수 가져오기
        $withdraw_pending_result = $conn->query("SELECT COUNT(*) AS pending_count FROM withdraw_requests WHERE status = '대기중'");
        $pending_withdrawals = $withdraw_pending_result->fetch_assoc()['pending_count'];

        $conn->close();
        ?>

        <!-- 카드 등으로 예시 대시보드 -->
        <div class="row">
          <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card">
              <div class="card-header bg-primary text-white">
                <h3 class="card-title">총 회원 수</h3>
              </div>
              <div class="card-body">
                <p><?php echo $user_count; ?> 명</p>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card">
              <div class="card-header bg-warning text-white">
                <h3 class="card-title">출금 대기중 건수</h3>
              </div>
              <div class="card-body">
                <p><?php echo $pending_withdrawals; ?> 건</p>
              </div>
            </div>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
  </div>
  <!-- /콘텐츠 WRAPPER 끝 -->

  <!-- Footer (선택사항) -->
  <footer class="main-footer">
    <strong>Copyright &copy; 
      <a href="#">IPoin Wallet</a>.
    </strong>
    All rights reserved.
  </footer>

</div><!-- /.wrapper -->

<!-- AdminLTE 및 jQuery, Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>