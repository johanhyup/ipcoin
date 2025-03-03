<?php
// index.php (변경 예시)

// 세션/DB 연결 등 기존 로직
session_start();
require_once dirname(__FILE__) . '/config.php';

// 예: 간단한 통계 조회
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
// 총 회원 수
$user_count_result = $conn->query("SELECT COUNT(*) AS user_count FROM users");
$user_count = $user_count_result->fetch_assoc()['user_count'] ?? 0;

// 오늘 가입한 회원 수
$today_count_result = $conn->query("SELECT COUNT(*) AS today_count FROM users WHERE DATE(created_at) = CURDATE()");
$today_count = $today_count_result->fetch_assoc()['today_count'] ?? 0;

// 코인 총 전송량 (예시: DB 기준. 없으면 원하는 로직 추가)
$coin_total_result = $conn->query("SELECT SUM(amount) AS total_sent FROM coin_transactions"); 
$coin_total = $coin_total_result->fetch_assoc()['total_sent'] ?? 0;

// 오늘 전송된 코인량
$coin_today_result = $conn->query("SELECT SUM(amount) AS today_sent FROM coin_transactions WHERE DATE(created_at) = CURDATE()");
$coin_today = $coin_today_result->fetch_assoc()['today_sent'] ?? 0;

$conn->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>IPcoin Wallet - 대시보드</title>
  <!-- 여기에 CSS/JS (AdminLTE, Bootstrap 등) -->
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- 상단바 -->
  <?php require_once __DIR__ . '/master/frames/top_nav.php'; ?>

  <!-- 사이드바 -->
  <?php require_once __DIR__ . '/master/frames/nav.php'; ?>

  <!-- 콘텐츠 WRAPPER -->
  <div class="content-wrapper">
    <!-- 메인 콘텐츠 헤더 - "관리자 대시보드" 제거 -->
    <section class="content-header">
      <div class="container-fluid">
        <!-- 굳이 h1 태그를 쓰고 싶다면 비워둬도 됩니다 -->
        <!-- <h1>관리자 대시보드</h1> --> 
      </div>
    </section>

    <!-- 메인 콘텐츠 -->
    <section class="content">
      <div class="container-fluid">
        
        <!-- 여기서부터 카드 2개 (회원, 코인) -->
        <div class="row">

          <!-- 회원 카드 -->
          <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card">
              <!-- 카드 헤더: 파랑 배경, '회원' 이라는 글씨 흰색 -->
              <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="card-title" style="margin:0;">회원</h3>
                <!-- 기록 버튼(우측 상단) -->
                <button class="btn btn-outline-light btn-sm" onclick="openLogPopup('user')">
                  기록
                </button>
              </div>
              <!-- 카드 바디: Total / Today 표시 -->
              <div class="card-body" style="text-align:center;">
                <div>
                  <strong>Total</strong><br>
                  <span style="font-size:1.2em; color:#000;">
                    <?php echo number_format($user_count); ?>
                  </span>
                </div>
                <hr>
                <div>
                  <strong>Today</strong><br>
                  <span style="font-size:1.2em; color:#000;">
                    <?php echo number_format($today_count); ?>
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- 코인 카드 -->
          <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card">
              <!-- 카드 헤더: 노랑 배경, '코인'이라는 글씨 흰색 -->
              <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
                <h3 class="card-title" style="margin:0;">코인</h3>
                <!-- 기록 버튼(우측 상단) -->
                <button class="btn btn-outline-light btn-sm" onclick="openLogPopup('coin')">
                  기록
                </button>
              </div>
              <!-- 카드 바디: Total / Today 표시 -->
              <div class="card-body" style="text-align:center;">
                <div>
                  <strong>Total</strong><br>
                  <span style="font-size:1.2em; color:#000;">
                    <?php echo number_format($coin_total, 2); ?>
                  </span>
                </div>
                <hr>
                <div>
                  <strong>Today</strong><br>
                  <span style="font-size:1.2em; color:#000;">
                    <?php echo number_format($coin_today, 2); ?>
                  </span>
                </div>
              </div>
            </div>
          </div>

        </div><!-- /.row -->

      </div><!-- /.container-fluid -->
    </section><!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Footer -->
  <?php require_once __DIR__ . '/master/frames/footer.php'; ?>
</div><!-- ./wrapper -->

<!-- AdminLTE 및 필요한 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/master/AdminLte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/master/AdminLte/dist/js/adminlte.min.js"></script>

<!-- 팝업 띄우기 예시 -->
<script>
function openLogPopup(type) {
  // type === 'user' 일 경우 회원 가입 로그
  // type === 'coin' 일 경우 코인 전송 로그
  // 팝업 또는 모달로 처리 가능

  // 예: 새 작은 창 띄우기
  let url = '/master/logs/log_view.php?type=' + type; 
  let popup = window.open(url, 'logPopup', 'width=600,height=600,scrollbars=yes');
  
  // 또는 Bootstrap Modal을 쓰려면 모달 요소를 HTML에 두고, Ajax 로드하여 표시
  // $('#myModal').modal('show'); ...
}
</script>

</body>
</html>