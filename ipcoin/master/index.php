<?php
// index.php
session_start();
require_once dirname(__FILE__) . '/../config.php';

// DB 연결 (쿼리 수정 없이 기존 로직 유지)
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// 예시: 회원 수
$user_count_result = $conn->query("SELECT COUNT(*) AS user_count FROM users");
$user_count = $user_count_result->fetch_assoc()['user_count'];

// 예시: 출금 대기 건수(코인 표기로 사용)
$withdraw_result = $conn->query("SELECT COUNT(*) AS pending_count FROM withdraw_requests WHERE status = '대기중'");
$pending_withdrawals = $withdraw_result->fetch_assoc()['pending_count'];

// 예시: 오늘 가입 회원 수(실제 쿼리)
$today_user_result = $conn->query("SELECT COUNT(*) AS today_count FROM users WHERE DATE(created_at) = CURDATE()");
$today_user_count = $today_user_result->fetch_assoc()['today_count'] ?? 0;

// 예시: 오늘 전송된 코인(임시로 0)
$today_coin = 0;

$conn->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>IPcoin Wallet 관리자</title>
  <!-- AdminLTE / Bootstrap CSS / FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" />
  <link rel="stylesheet" href="/master/assets/css/main.css" />

  <!-- 추가: 반응형 -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <style>
    /* 모바일에서 카드 크기가 너무 클 경우 조정 가능 */
    @media (max-width: 576px) {
      .card-body p {
        font-size: 0.95rem; /* 예시로 조금 작게 */
      }
      .card-title {
        font-size: 1.1rem; /* 타이틀 조금 줄이기 */
      }
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- 상단바 -->
  <?php require_once dirname(__FILE__) . '/../master/frames/top_nav.php'; ?>

  <!-- 사이드바 -->
  <?php require_once dirname(__FILE__) . '/../master/frames/nav.php'; ?>

  <!-- 메인 콘텐츠 WRAPPER -->
  <div class="content-wrapper">
    <!-- 컨텐츠 헤더(필요없어서 제거했다면 주석 처리) -->
    <!-- <section class="content-header">
      <div class="container-fluid">
        <h1>관리자 대시보드</h1>
      </div>
    </section> -->

    <section class="content">
      <div class="container-fluid">
        <!-- 2개의 카드 (회원 / 코인). 
             Bootstrap 컬럼: 
             - col-12: 모바일 전체 폭
             - col-sm-12: 스몰(576px~)에서 전체 폭
             - col-md-6: md(768px~)부터 반폭
             - col-lg-4: lg(992px~)부터는 세 등분
             => 모바일에서는 위/아래로 쌓이고, 데스크톱에서는 옆으로 나란히.
        -->
        <div class="row">
          
          <!-- 회원 카드 (파랑) -->
          <div class="col-12 col-sm-12 col-md-6 col-lg-4">
            <div class="card">
              <div class="card-header bg-primary d-flex justify-content-between align-items-center">
                <h3 class="card-title">회원</h3>
                <!-- 기록 버튼 -->
                <button class="btn btn-outline-light btn-sm" style="border-radius:15px;"
                        onclick="openLogPopup('member')">
                  기록
                </button>
              </div>
              <div class="card-body text-center">
                <p style="color:black; font-size:1.2em; margin:0;">
                  <strong>Total:</strong> <?php echo number_format($user_count); ?>
                </p>
                <p style="color:black; font-size:1.2em; margin:0;">
                  <strong>Today:</strong> <?php echo number_format($today_user_count); ?>
                </p>
              </div>
            </div>
          </div>

          <!-- 코인 카드 (노랑) -->
          <div class="col-12 col-sm-12 col-md-6 col-lg-4">
            <div class="card">
              <div class="card-header bg-warning d-flex justify-content-between align-items-center">
                <h3 class="card-title">코인</h3>
                <!-- 기록 버튼 -->
                <button class="btn btn-outline-light btn-sm" style="border-radius:15px;"
                        onclick="openLogPopup('coin')">
                  기록
                </button>
              </div>
              <div class="card-body text-center">
                <p style="color:black; font-size:1.2em; margin:0;">
                  <strong>Total:</strong> <?php echo number_format($pending_withdrawals); ?>
                </p>
                <p style="color:black; font-size:1.2em; margin:0;">
                  <strong>Today:</strong> <?php echo number_format($today_coin); ?>
                </p>
              </div>
            </div>
          </div>

        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
  </div><!-- /.content-wrapper -->

  <!-- Footer -->
  <?php require_once dirname(__FILE__) . '/../master/frames/footer.php'; ?>
</div><!-- /.wrapper -->


<script>
// 팝업 열기
function openLogPopup(type) {
  let url = '';
  if (type === 'member') {
    url = '/master/logs/member_log.php';
  } else if (type === 'coin') {
    url = '/master/logs/coin_log.php';
  }
  window.open(url, 'logPopup', 'width=900,height=700,resizable=yes,scrollbars=yes');
}
</script>
</body>
</html>