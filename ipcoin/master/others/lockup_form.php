<?php
// 1) config.php 등 공통 설정
require_once dirname(__DIR__) . '/../config.php';
// 2) header.php가 CSS/JS 로드만 하고 DOCTYPE을 포함하지 않는다면 그대로 사용
require_once __DIR__ . '/frames/header.php';
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>코인 락업</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- AdminLTE & Bootstrap & FontAwesome CSS -->
  <link rel="stylesheet" href="/path/to/adminlte.min.css">
  <link rel="stylesheet" href="/path/to/bootstrap.min.css">
  <link rel="stylesheet" href="/path/to/fontawesome.min.css">
  
  <!-- 프로젝트 내 스타일 (예: style.css) -->
  <link rel="stylesheet" href="/master/assets/css/style.css">
  
  <!-- 추가 스타일 (코인 락업용) -->
  <style>
    /* 전체 바디 (AdminLTE layout에서는 기본적으로 .sidebar-mini .layout-fixed 등을 body에 사용) */
    body {
      font-family: Arial, sans-serif;
      background-color: #f9f9f9;
      margin: 0; /* AdminLTE가 있으므로 margin:0 보통 사용 */
      padding: 0; 
    }
    /* 카드 스타일 */
    .card {
      border: 1px solid #ddd;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      max-width: 500px;
      margin: 40px auto;
      background-color: #fff;
    }
    .card-header {
      background-color: #f7f7f7;
      padding: 1rem;
      font-weight: bold;
      text-align: center;
      border-bottom: 1px solid #ddd;
    }
    .card-body {
      padding: 1rem;
    }
    form label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
      color: #555;
    }
    form input, form select, form button {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    form button {
      background-color: #4CAF50;
      color: white;
      font-weight: bold;
      cursor: pointer;
      margin-top: 20px;
      border: none;
    }
    form button:hover {
      background-color: #45a049;
    }
    form input:focus, form select:focus {
      outline: none;
      border-color: #4CAF50;
      box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
    }
    .error-message {
      color: red;
      font-size: 12px;
      margin-top: 5px;
    }
  </style>
</head>

<!-- AdminLTE 바디 클래스 -->
<body class="hold-transition sidebar-mini layout-fixed">

<div class="wrapper"><!-- AdminLTE 전체 래퍼 -->

  <!-- (A) 상단 네비게이션바 -->
  <?php require_once __DIR__ . '/frames/top_nav.php'; ?>

  <!-- (B) 왼쪽 사이드바 (nav.php) -->
  <?php require_once __DIR__ . '/frames/nav.php'; ?>

  <!-- (C) 메인 컨텐츠: .content-wrapper -->
  <div class="content-wrapper">
    <!-- AdminLTE에서 보통 .content 안에 .container-fluid -->
    <section class="content">
      <div class="container-fluid">

        <h2>코인 락업</h2>
        <div class="card">
          <div class="card-header">코인 락업</div>
          <div class="card-body">
            <form id="lockupForm">
              <label for="lockupUserId">유저 (ID):</label>
              <select id="lockupUserId" name="lockupUserId" required>
                <option value="">유저를 선택하세요</option>
                <?php
                // DB에서 유저 목록 가져오기
                $result = $conn->query("SELECT id, mb_id FROM users");
                while ($user = $result->fetch_assoc()) {
                    echo "<option value='{$user['id']}'>{$user['mb_id']}</option>";
                }
                ?>
              </select>

              <label for="lockupAmount">락업할 코인 양:</label>
              <input type="number" step="0.00000001" id="lockupAmount" name="lockupAmount" required>

              <label for="lockupEndDate">락업 종료일:</label>
              <input type="date" id="lockupEndDate" name="lockupEndDate" required>

              <button type="submit">락업하기</button>
            </form>
          </div><!-- card-body -->
        </div><!-- card -->

      </div><!-- container-fluid -->
    </section>
  </div><!-- content-wrapper -->

  <!-- (D) 푸터 (footer.php) -->
  <?php require_once __DIR__ . '/frames/footer.php'; ?>

</div><!-- wrapper 끝 -->

<!-- JS 라이브러리 (AdminLTE, jQuery 등) -->
<script src="/path/to/jquery.min.js"></script>
<script src="/path/to/bootstrap.bundle.min.js"></script>
<script src="/path/to/adminlte.min.js"></script>

<script>
  // (1) 락업 폼 전송 처리
  document.getElementById("lockupForm").addEventListener("submit", (e) => {
      e.preventDefault();

      const formData = {
          user_id: document.getElementById("lockupUserId").value,
          amount: document.getElementById("lockupAmount").value,
          end_date: document.getElementById("lockupEndDate").value
      };

      fetch("/service/lockup_coin.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(formData)
      })
      .then((response) => response.json())
      .then((data) => {
          if (data.success) {
              alert("코인이 성공적으로 락업되었습니다.");
              window.opener.location.reload(); // 부모 창 새로고침
              window.close(); // 현재 창 닫기
          } else {
              alert("락업 실패: " + data.message);
          }
      })
      .catch((error) => console.error("Error:", error));
  });
</script>
</body>
</html>