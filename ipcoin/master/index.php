<?php
// 오류 메시지 표시 (개발 중 필요시)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 세션, DB 연결
session_start();
require_once dirname(__DIR__) . '/config.php';

// DB 예시 쿼리
// 기존: 총 회원 수
$user_count_result = $conn->query("SELECT COUNT(*) AS user_count FROM users");
$user_count = $user_count_result->fetch_assoc()['user_count'] ?? 0;

// 오늘 가입한 회원 수 (예시 쿼리)
$today_result = $conn->query("SELECT COUNT(*) AS today_count FROM users WHERE DATE(created_at) = CURDATE()");
$new_users_today = $today_result->fetch_assoc()['today_count'] ?? 0;

// 기존에 있던: 출금 대기중 건수 → 여기서는 코인 통계로 가정
$withdraw_pending_result = $conn->query("SELECT COUNT(*) AS pending_count FROM withdraw_requests WHERE status = '대기중'");
$coin_total = $withdraw_pending_result->fetch_assoc()['pending_count'] ?? 0;

// 오늘 전송된 코인 수 (예시)
$today_coin_result = $conn->query("SELECT SUM(amount) AS today_coin FROM coin_transactions WHERE DATE(created_at) = CURDATE()");
$coin_today = $today_coin_result->fetch_assoc()['today_coin'] ?? 0;

$conn->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>IPcoin Wallet | 대시보드</title>
  <!-- AdminLTE CSS / Bootstrap / FontAwesome CDN 예시 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" />
  <!-- 기존 main.css 등 -->
  <link rel="stylesheet" href="/master/assets/css/main.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- 상단바 -->
  <?php require_once dirname(__DIR__) . '/master/frames/top_nav.php'; ?>

  <!-- 사이드바 -->
  <?php require_once dirname(__DIR__) . '/master/frames/nav.php'; ?>

  <!-- 콘텐츠 WRAPPER -->
  <div class="content-wrapper">

    <!-- 내용 영역 -->
    <section class="content pt-3">
      <div class="container-fluid">

        <!-- 카드 2개 (회원 / 코인) -->
        <div class="row">
          <!-- 회원 카드 (파랑색) -->
          <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card">
              <!-- 카드 헤더: 파랑 배경 -->
              <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">회원</h3>
                <!-- 기록 버튼 -->
                <button class="btn btn-outline-light btn-sm" onclick="openRecordPopup('member')">기록</button>
              </div>
              <div class="card-body">
                <!-- Total / Today 표시 -->
                <p class="mb-1"><strong>Total</strong> : <?php echo number_format($user_count); ?></p>
                <p class="mb-0"><strong>Today</strong> : <?php echo number_format($new_users_today); ?></p>
              </div>
            </div>
          </div>

          <!-- 코인 카드 (노란색) -->
          <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card">
              <!-- 카드 헤더: 노랑 배경 -->
              <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">코인</h3>
                <!-- 기록 버튼 -->
                <button class="btn btn-outline-light btn-sm" onclick="openRecordPopup('coin')">기록</button>
              </div>
              <div class="card-body">
                <!-- Total / Today 표시 -->
                <p class="mb-1"><strong>Total</strong> : <?php echo number_format($coin_total); ?></p>
                <p class="mb-0"><strong>Today</strong> : <?php echo number_format($coin_today); ?></p>
              </div>
            </div>
          </div>
        </div>

      </div><!-- /.container-fluid -->
    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

  <!-- 하단 Footer -->
  <?php require_once dirname(__DIR__) . '/master/frames/footer.php'; ?>
</div><!-- /.wrapper -->

<!-- AdminLTE 및 jQuery, Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<script>
/**
 * 기록 버튼 클릭 시 팝업창 열기 (예시)
 * type: 'member' or 'coin'
 */
function openRecordPopup(type) {
  // 새 창으로 띄우거나, 모달 방식으로 구현 가능
  // 여기서는 새 창 예시
  let url = '/master/logs/' + type + '_log.php'; // 예: /master/logs/member_log.php
  let popupWidth = 800;
  let popupHeight = 600;
  let left = (screen.width - popupWidth) / 2;
  let top = (screen.height - popupHeight) / 2;

  window.open(
    url,
    'logPopup',
    `width=${popupWidth},height=${popupHeight},top=${top},left=${left},resizable=yes,scrollbars=yes`
  );
}
</script>

</body>
</html>