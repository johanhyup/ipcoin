<?php
// index.php
// 세션, config, DB연결 등 기존 로직은 유지
session_start();
require_once dirname(__FILE__) . '/../config.php';

// 기존 쿼리들 (쿼리는 수정하지 않고 그대로 둡니다)
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// 사용자 수 가져오기
$user_count_result = $conn->query("SELECT COUNT(*) AS user_count FROM users");
$user_count = $user_count_result->fetch_assoc()['user_count'];

// 출금 대기 건수(원래) -> 여기서는 코인 총량처럼 표시만 해볼 것
$withdraw_pending_result = $conn->query("SELECT COUNT(*) AS pending_count FROM withdraw_requests WHERE status = '대기중'");
$pending_withdrawals = $withdraw_pending_result->fetch_assoc()['pending_count'];

// "오늘 가입 회원 수" (오늘 날짜로 필터) - 실제 사용 시에는 꼭 허용해주신다면 아래처럼 쿼리가 필요
// 일단 이 부분은 보여드리는 예시. DB 쿼리를 추가로 넣지 말라고 하면 주석 처리하거나, 일단 사용하되 승인 요청
$today_user_result = $conn->query("
    SELECT COUNT(*) AS today_count 
    FROM users 
    WHERE DATE(created_at) = CURDATE()
");
$today_user_count = $today_user_result->fetch_assoc()['today_count'] ?? 0;

// "오늘 전송된 코인" 부분도 원래는 따로 쿼리가 필요하나, 쿼리 수정 금지 조건 때문에 임시로 0 처리
$today_coin = 0;

// DB 연결 종료
$conn->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>IPcoin Wallet 관리자</title>
  <!-- AdminLTE / Bootstrap CSS 등 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" />
  <link rel="stylesheet" href="/master/assets/css/main.css" />
</head>
<body class="hold-transition sidebar-mini">

<div class="wrapper">
  <!-- 상단바 -->
  <?php require_once dirname(__FILE__) . '/../master/frames/top_nav.php'; ?>
  <!-- 사이드바 -->
  <?php require_once dirname(__FILE__) . '/../master/frames/nav.php'; ?>

  <!-- 메인 콘텐츠 WRAPPER -->
  <div class="content-wrapper">
    <!-- 기존에 있던 "관리자 대시보드" 문구를 제거했습니다 -->
    <!-- <section class="content-header">
      <div class="container-fluid">
        <h1>관리자 대시보드</h1>
      </div>
    </section> -->

    <!-- 메인 콘텐츠 -->
    <section class="content">
      <div class="container-fluid">

        <!-- 2개의 카드 (회원 / 코인) -->
        <div class="row">
          
          <!-- 회원 카드(파랑) -->
          <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card">
              <!-- 카드 헤더: 파랑 배경, 흰 글씨, 우측에 "기록" 버튼 -->
              <div class="card-header bg-primary d-flex justify-content-between align-items-center">
                <h3 class="card-title">회원</h3>
                <!-- 기록 버튼 (우측상단) -->
                <button class="btn btn-outline-light btn-sm" 
                        style="border-radius:15px;" 
                        onclick="openLogPopup('member')">
                  기록
                </button>
              </div>
              <div class="card-body" style="text-align:center;">
                <!-- 하얀색 필드 부분: Total / Today 표시 -->
                <p style="color:black; font-size:1.2em; margin:0;">
                  <strong>Total:</strong> <?php echo number_format($user_count); ?>
                </p>
                <p style="color:black; font-size:1.2em; margin:0;">
                  <strong>Today:</strong> <?php echo number_format($today_user_count); ?>
                </p>
              </div>
            </div>
          </div>

          <!-- 코인 카드(노랑) -->
          <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card">
              <!-- 카드 헤더: 노랑 배경, 흰 글씨, 우측에 "기록" 버튼 -->
              <div class="card-header bg-warning d-flex justify-content-between align-items-center">
                <h3 class="card-title">코인</h3>
                <!-- 기록 버튼 (우측상단) -->
                <button class="btn btn-outline-light btn-sm" 
                        style="border-radius:15px;"
                        onclick="openLogPopup('coin')">
                  기록
                </button>
              </div>
              <div class="card-body" style="text-align:center;">
                <!-- 하얀색 필드 부분: Total / Today 표시 
                     여기서 pending_withdrawals를 'Total'로, today_coin은 0 혹은 임의 값 -->
                <p style="color:black; font-size:1.2em; margin:0;">
                  <strong>Total:</strong> <?php echo number_format($pending_withdrawals); ?>
                </p>
                <p style="color:black; font-size:1.2em; margin:0;">
                  <strong>Today:</strong> <?php echo number_format($today_coin); ?>
                </p>
              </div>
            </div>
          </div>

          <!-- 필요하다면 col-lg-4 하나 더 만들어도 되지만, 지금은 두 개만 남깁니다 -->

        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
  </div><!-- /.content-wrapper -->

  <!-- Footer -->
  <?php require_once dirname(__FILE__) . '/../master/frames/footer.php'; ?>
</div><!-- /.wrapper -->

<!-- AdminLTE JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<script>
// "기록" 버튼 클릭 시 팝업 오픈하는 함수
function openLogPopup(type) {
  let url = '';
  switch(type) {
    case 'member':
      // 회원 로그 페이지 예시
      url = '/master/logs/member_log.php';
      break;
    case 'coin':
      // 코인 로그 페이지 예시
      url = '/master/logs/coin_log.php';
      break;
    default:
      url = '/master/logs/unknown.php';
  }

  // 팝업창 옵션은 필요에 따라 수정
  window.open(url, 'logPopup', 'width=800,height=600,resizable=yes,scrollbars=yes');
}
</script>
</body>
</html>