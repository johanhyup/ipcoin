<!-- index.php -->
<?php
require_once dirname(__DIR__) . '/config.php';
// 세션, DB 연결 등등...

// 세션에서 로그인한 관리자 정보 (예: $master_name, $rank)
$master_name = $_SESSION['master_name'] ?? '관리자';
$rank = $_SESSION['rank'] ?? '0';

// 기존 예시 코드에서
// $user_count = 총 회원 수
// $pending_withdrawals = 출금 대기 중 건수
// 라고 되어 있었는데, "코인" 용으로 사용하겠음.

// 오늘 가입한 회원
$today_users_result = $conn->query("
    SELECT COUNT(*) AS today_count 
    FROM users 
    WHERE DATE(created_at) = CURDATE()
");
$today_users = $today_users_result->fetch_assoc()['today_count'] ?? 0;

// 코인 전송된 총량(샘플)
$total_coin = 999999; // 임의
// 오늘 전송된 코인(샘플)
$today_coin = 123;    // 임의

// (주의) 위 코인 관련 부분은 실제 DB 구조에 맞게 수정 필요

$conn->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>대시보드</title>
  <!-- AdminLTE / Bootstrap / FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" />
  <link rel="stylesheet" href="/master/assets/css/main.css">
</head>
<body class="hold-transition sidebar-mini">

<div class="wrapper">
  <!-- 상단바 -->
  <?php require_once dirname(__DIR__) . '/master/frames/top_nav.php'; ?>
  <!-- 사이드바 -->
  <?php require_once dirname(__DIR__) . '/master/frames/nav.php'; ?>

  <!-- 메인 콘텐츠 WRAPPER -->
  <div class="content-wrapper">
    <!-- content-header 부분에서 '관리자 대시보드' 등 헤더문구 제거 -->
    <section class="content-header">
      <div class="container-fluid">
        <!-- 굳이 문구 필요 없다면 완전히 비워둠 -->
      </div>
    </section>

    <!-- 실제 대시보드 내용 -->
    <section class="content">
      <div class="container-fluid">

        <!-- 2개 카드 배치 (가로 row) -->
        <div class="row">
          <!-- ================ 첫 번째 카드: 회원(파랑) ================ -->
          <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card">
              <!-- 카드 헤더(배경 파랑, 글씨 흰색) -->
              <div class="card-header bg-primary text-white">
                <h3 class="card-title">회원</h3>
                <!-- 우측에 '기록' 버튼 배치 (float-right) -->
                <button type="button"
                        class="btn btn-outline-light btn-sm float-right"
                        style="border-radius: 15px; margin-left:10px;"
                        onclick="openMemberLog()">
                  기록
                </button>
              </div>

              <!-- 카드 내부 -->
              <div class="card-body">
                <!-- Total / Today 2개 필드 -->
                <p>
                  <strong>Total:</strong> 
                  <span><?php echo number_format($user_count); ?></span>
                </p>
                <p>
                  <strong>Today:</strong> 
                  <span><?php echo number_format($today_users); ?></span>
                </p>
              </div>
            </div>
          </div>

          <!-- ================ 두 번째 카드: 코인(노랑) ================ -->
          <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card">
              <!-- 카드 헤더(배경 노랑, 글씨 흰색) -->
              <div class="card-header bg-warning text-white">
                <h3 class="card-title">코인</h3>
                <!-- 우측에 '기록' 버튼 배치 (float-right) -->
                <button type="button"
                        class="btn btn-outline-light btn-sm float-right"
                        style="border-radius: 15px; margin-left:10px;"
                        onclick="openCoinLog()">
                  기록
                </button>
              </div>

              <!-- 카드 내부 -->
              <div class="card-body">
                <!-- Total / Today 2개 필드 -->
                <p>
                  <strong>Total:</strong> 
                  <span><?php echo number_format($total_coin); ?></span>
                </p>
                <p>
                  <strong>Today:</strong> 
                  <span><?php echo number_format($today_coin); ?></span>
                </p>
              </div>
            </div>
          </div>

        </div> <!-- .row -->

      </div><!-- /.container-fluid -->
    </section>
  </div>
  <!-- /콘텐츠 WRAPPER 끝 -->

  <!-- Footer -->
  <?php require_once dirname(__DIR__) . '/master/frames/footer.php'; ?>
</div><!-- /.wrapper -->

<!-- AdminLTE 및 jQuery, Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<script>
  // "기록" 버튼 클릭 시 팝업을 띄우는 샘플
  // 실제로 어떤 페이지를 열어서 어떻게 로그를 보여줄지는
  // 별도 구현이 필요합니다.
  function openMemberLog() {
    // 회원 관련 이력 팝업
    window.open('/master/logs/member_log.php','memberLog',
      'width=800,height=600,scrollbars=yes,resizable=yes');
  }
  function openCoinLog() {
    // 코인 관련 이력 팝업
    window.open('/master/logs/coin_log.php','coinLog',
      'width=800,height=600,scrollbars=yes,resizable=yes');
  }
</script>
</body>
</html>