<?php
require_once dirname(__DIR__) . '/../config.php';
require_once dirname(__DIR__) . '/../frames/asset.php';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>관리자 페이지</title>
  <link rel="stylesheet" href="/master/assets/css/main.css">
  <style>
    /* 전체 바탕과 폰트 설정 */
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4f7f9;
      color: #333;
    }
    /* 상단 네비게이션 바 */
    .top-nav {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background-color: #ffffff;
      padding: 15px 30px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
    }
    .top-nav h2 {
      font-size: 24px;
      margin: 0;
    }

    /* 정보 영역 (현재 IP가격 / 총 입금액 등) */
    .info {
      display: flex;
      gap: 30px; /* 항목 간격 */
      align-items: center;
    }
    /* 각 항목의 라벨/값 형태를 구분 */
    .info-item {
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 5px; /* 라벨과 값 사이 간격 */
    }
    .info-item .label {
      font-weight: bold;
      color: #555;
    }
    .info-item .value {
      color: #333;
    }

    /* 오른쪽 여백을 자동으로 채워주는 영역 */
    .blank {
      flex-grow: 1;
    }

    /* 로그아웃 버튼 */
    .logout a {
      text-decoration: none;
      color: #fff;
      background-color: #5cb85c;
      padding: 10px 15px;
      border-radius: 4px;
      transition: background-color 0.3s;
    }
    .logout a:hover {
      background-color: #4cae4c;
    }
  </style>
</head>
<body>
  <!-- 상단 네비게이션 바 -->
  <div class="top-nav">
    <h2>관리자 메뉴</h2>
    <div class="info">
      <!-- 기존 빨간 박스 대신 간결하게 라벨+값 형태로 -->
      <div class="info-item">
        <span class="label">현재 IP가격:</span>
        <span class="value" id="closing_price_display">Loading...</span>
      </div>
      <div class="info-item">
        <span class="label">총 입금액:</span>
        <span class="value" id="total_deposit_display">1,000,000,000</span>
      </div>
      <div class="info-item">
        <span class="label">총 출금액:</span>
        <span class="value" id="total_withdraw_display">0</span>
      </div>

      <!-- 오른쪽으로 밀어내는 빈 영역 -->
      <div class="blank"></div>
      
      <div class="info-item">
        <span class="label">총 회원:</span>
        <span class="value" id="total_users">72</span>
      </div>
      <div class="info-item">
        <span class="label">총 관리자:</span>
        <span class="value" id="total_admins">4</span>
      </div>
      <div class="info-item">
        <span class="label">신규회원:</span>
        <span class="value" id="new_users">4</span>
      </div>
    </div>
    <div class="logout">
      <a href="/master/bbs/logout.php">로그아웃</a>
    </div>
  </div>
</body>
</html>