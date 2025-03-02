<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8" />
  <title>관리자 대시보드 | Raycoin Wallet</title>
  <!-- 반응형 -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- AdminLTE(부트스트랩) CSS 불러오기 예시 -->
  <link rel="stylesheet" href="/master/assets/css/all.min.css">
  <link rel="stylesheet" href="/master/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="/master/assets/css/adminlte.min.css">

  <!-- 기존 main.css(필요 시) -->
  <link rel="stylesheet" href="/master/assets/css/main.css">

</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <!-- 상단 네비 (Top Navbar) -->
    <?php 
        // 상단바
        require_once dirname(__DIR__) . '/master/frames/top_nav.php'; 
    ?>

    <!-- 왼쪽 사이드바 (Side Navigation) -->
    <?php
        // 왼쪽 사이드바
        require_once dirname(__DIR__) . '/master/frames/nav.php'; 
    ?>

    <!-- 컨텐츠 영역 -->
    <div class="content-wrapper">
      <!-- 메인 콘텐츠 헤더 (선택사항) -->
      <section class="content-header">
        <div class="container-fluid">
          <h1>관리자 대시보드</h1>
          <hr>
        </div>
      </section>

      <!-- 메인 콘텐츠 -->
      <section class="content">
        <div class="container-fluid">
            <?php
            // ------------------------------------------
            // 원본 index.php 에 있던 PHP 로직(예: 세션, DB접속 등)
            // ------------------------------------------

            // 추가로 페이지에 필요한 코드를 배치할 수 있습니다.
            // 데이터베이스 연결
            require_once dirname(__DIR__) . '/config.php';

            // 세션에서 로그인한 관리자 정보 가져오기
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

            // DB 연결 종료
            $conn->close();
            ?>
            
            <!-- 원하는 위치에 콘텐츠 출력 -->
            <div class="card">
              <div class="card-body">
                <h3>관리자님 환영합니다, <?= htmlspecialchars($master_name) ?> 님</h3>
                <p>전체 회원 수: <?= (int)$user_count ?> 명</p>
                <p>대기중인 출금 요청: <?= (int)$pending_withdrawals ?> 건</p>
              </div>
            </div>
        </div>
      </section><!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- 페이지 하단 푸터 (원하시면 추가 가능) -->
    <footer class="main-footer">
      <strong>Copyright &copy; 2023 IPcoin.</strong>
      All rights reserved.
    </footer>
</div><!-- /.wrapper -->

<!-- AdminLTE JS -->
<script src="/master/assets/js/jquery.min.js"></script>
<script src="/master/assets/js/bootstrap.bundle.min.js"></script>
<script src="/master/assets/js/adminlte.min.js"></script>

<!-- 기존 main.js 등 필요 시 -->
<script src="/master/assets/js/main.js"></script>

</body>
</html>