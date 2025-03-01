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
    <link rel="stylesheet" href="/master/assets/css/main.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f9;
        }
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
            color: #333;
            margin: 0;
        }
        .info {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        .info div {
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 14px;
        }
        .red {
            background-color: #ffe6e6;
            color: #d9534f;
            font-weight: bold;
        }
        .blue {
            background-color: #e6f2ff;
            color: #337ab7;
            font-weight: bold;
        }
        .blank {
            flex-grow: 1;
        }
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

<!-- Top Navigation Bar -->
<div class="top-nav">
<h2 style="left :30px; position: relative;">관리자 메뉴</h2>
    <div class="info">
    <div class="red" id="closing_price_display">현재 IP가격: Loading...</div>

        <div class="red" id="total_deposit_display">총 입금액<br>1,000,000,000</div>
        <div class="red" id="total_withdraw_display">총 출금액<br>0</div>
        <div class="blank"></div>
        <div class="blue" id="total_users">총 회원<br>72</div>
        <div class="blue" id="total_admins">총 관리자<br>4</div>
        <div class="blue" id="new_users">신규회원<br>4</div>
    </div>
    <div class="logout" style="margin-right:30px" > <a href="/master/bbs/logout.php">로그아웃</a></div>
</div>


    </div>

</body>
</html>

