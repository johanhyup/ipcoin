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
      /* 반응형에서 줄바꿈을 쉽게 하기 위해 flex-wrap 허용 */
      flex-wrap: wrap;
    }
    .top-nav h2 {
      font-size: 20px;
      margin: 0;
      /* 글씨를 검정색으로 변경 */
      color: #333;
    }

    /* 정보 영역 (현재 회원수, 신규회원수) */
    .info {
      display: flex;
      gap: 20px; /* 항목 간격 */
      align-items: center;
      flex-wrap: wrap; /* 화면이 좁아지면 줄바꿈 */
    }
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

    /* 오른쪽으로 밀어내는 빈 영역 (화면 넓을 때만) */
    .blank {
      flex-grow: 1;
    }

    /* 로그아웃 버튼 */
    .logout a {
      text-decoration: none;
      color: #fff;
      background-color: #5cb85c;
      /* 글씨를 조금 작게, 여백도 작게 */
      font-size: 13px;
      padding: 6px 10px;
      border-radius: 4px;
      transition: background-color 0.3s;
    }
    .logout a:hover {
      background-color: #4cae4c;
    }

    /* 화면이 작아졌을 때(예: 768px 이하) 반응형 스타일 */
    @media (max-width: 768px) {
      .top-nav {
        padding: 10px 15px;
        margin-bottom: 10px;
      }
      .top-nav h2 {
        font-size: 18px;
      }
      .info {
        margin-top: 10px;
      }
    }
  </style>
</head>
<body>
  <!-- 상단 네비게이션 바 -->
  <div class="top-nav">
    <!-- 좌측에 관리자 메뉴 (검정색 글씨) -->
    <h2>관리자 메뉴</h2>
    
    <!-- 가운데/오른쪽으로 회원수 정보 -->
    <div class="info">
      <div class="info-item">
        <span class="label">현재 회원수:</span>
        <span class="value" id="current_users">72</span>
      </div>
      <div class="info-item">
        <span class="label">신규회원수:</span>
        <span class="value" id="new_users">4</span>
      </div>
    </div>

    <!-- 오른쪽 로그아웃 버튼 -->
    <div class="logout">
      <a href="/master/bbs/logout.php">로그아웃</a>
    </div>
  </div>
</body>
</html>