<?php
// 현재 파일의 상위 디렉토리의 config.php를 불러옴
require_once dirname(__DIR__) . '/../config.php';
require_once dirname(__DIR__) . '/../frames/asset.php';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>관리자 페이지</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Noto Sans KR', sans-serif;
            background-color: #f7f9fc;
            color: #333;
        }
        .top-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #ffffff;
            padding: 15px 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        .top-nav h2 {
            font-size: 22px;
            font-weight: 700;
            margin: 0;
            color: #202124;
        }
        .info {
            display: flex;
            gap: 12px;
        }
        .info div {
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 13px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            font-weight: 500;
            text-align: center;
            line-height: 1.4;
        }
        .red {
            background-color: #fff1f0;
            color: #e74c3c;
        }
        .blue {
            background-color: #eaf3ff;
            color: #3498db;
        }
        .logout a {
            text-decoration: none;
            color: #ffffff;
            background-color: #5c6bc0;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        .logout a:hover {
            background-color: #3949ab;
        }
    </style>
</head>
<body>

<!-- Top Navigation Bar -->
<div class="top-nav">
    <h2>관리자 메뉴</h2>
    <div class="info">
        <div class="red" id="closing_price_display">현재 IP 가격<br>Loading...</div>
        <div class="red" id="total_deposit_display">총 입금액<br>1,000,000,000</div>
        <div class="red" id="total_withdraw_display">총 출금액<br>0</div>
        <div class="blue" id="total_users">총 회원<br>72</div>
        <div class="blue" id="total_admins">총 관리자<br>4</div>
        <div class="blue" id="new_users">신규회원<br>4</div>
    </div>
    <div class="logout">
        <a href="/master/bbs/logout.php">로그아웃</a>
    </div>
</div>

</body>
</html>
