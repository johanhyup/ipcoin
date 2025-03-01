<?php
session_start([
    'cookie_lifetime' => 3000, // 쿠키 유효 시간: 3000초
]);

// 세션 만료 확인
if (isset($_SESSION['last_activity'])) {
    if ((time() - $_SESSION['last_activity']) > 600) { // 10분 초과
        session_unset();
        session_destroy();
        echo "<script>
            alert('세션이 만료되었습니다. 다시 로그인 해주세요.');
            window.location.href = '/master/bbs/login.php';
        </script>";
        exit;
    }
}
$_SESSION['last_activity'] = time(); // 마지막 활동 시간 갱신

require_once dirname(__DIR__) . '/../config.php';
require_once dirname(__DIR__) . '/../frames/asset.php';

if (!isset($_SESSION['master_id']) || !isset($_SESSION['master_name'])) {
    echo "<script>
        alert('관리자 로그인이 필요한 페이지입니다.');
        window.location.href = '/master/bbs/login.php';
    </script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>관리자 페이지</title>
    <link rel="stylesheet" href="/master/assets/css/main.css">
    <style>
        /* Navigation Bar Global Styles */
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f9;
        }
        .nav-bar {
            width: 250px;
            background-color: #fff;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-right: 1px solid #e0e0e0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        .nav-bar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .nav-bar ul li {
            margin-bottom: 10px;
        }
        .nav-bar ul li a {
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            color: #333;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
        }
        .nav-bar ul li a.active, 
        .nav-bar ul li a:hover {
            background-color: #4CAF50;
            color: #fff;
        }
        .nav-bar ul li ul {
            margin-top: 5px;
            padding-left: 15px;
            display: none;
        }
        .nav-bar ul li ul li a {
            background-color: #f9f9f9;
            color: #333;
            padding: 8px 15px;
        }
        .nav-bar ul li ul li a:hover {
            background-color: #e0e0e0;
        }
    </style>
    <script src="/master/assets/js/main.js"></script>
    <script src="/master/assets/js/deposit.js"></script>
    <script src="/master/assets/js/lockup_manage.js"></script>
    <script src="/master/assets/js/search.js"></script>
    <script src="/master/assets/js/pagination.js"></script>
</head>
<body>
    <!-- 왼쪽 네비게이션 바 -->
    <div class="nav-bar">
        <ul>
            <li><a href="/master/index.php" class="active">대시보드</a></li>
            <li>
                <a href="#" onclick="toggleSubMenu(event)">회원관리</a>
                <ul>
                    <li><a href="/master/manage_user/userlist_view.php">회원목록</a></li>
                    <li><a href="#">회원실시간</a></li>
                    <li><a href="/master/manage_user/user_log.php">회원 로그인 내역</a></li>
                    <li><a href="#">회원 수정내역</a></li>
                    <li><a href="#">회원 복구</a></li>
                    <li><a href="/master/manage_user/user_info.php">회원가입승인</a></li>
                </ul>
            </li>
            <li>
                <a href="#" onclick="toggleSubMenu(event)">상위관리자</a>
                <ul>
                    <li><a href="#">관리자목록</a></li>
                    <li><a href="#">관리자실시간</a></li>
                    <li><a href="#">로그인 내역</a></li>
                    <li><a href="#">수정내역</a></li>
                    <li><a href="#">관리자 블락</a></li>
                    <li><a href="#">관리자 트리뷰</a></li>
                </ul>
            </li>
            <li>
                <a href="#" onclick="toggleSubMenu(event)">입출금 관리</a>
                <ul>
                    <li><a href="/master/wallet/deposit_view.php">입금기록 관리</a></li>
                    <li><a href="/master/wallet/deposit_manage.php">임시 입금신청</a></li>
                    <li><a href="#">로그인 내역</a></li>
                    <li><a href="#">수정내역</a></li>
                    <li><a href="#">관리자 블락</a></li>
                    <li><a href="#">관리자 트리뷰</a></li>
                </ul>
            </li>
            <li><a href="#">상위관리자 입금신청</a></li>
            <li><a href="#">전체 입출금 내역</a></li>
            <li>
                <a href="#" onclick="toggleSubMenu(event)">기타 설정</a>
                <ul>
                    <li><a href="/master/others/lockup_manage.php">락업시간 설정</a></li>
                    <li><a href="/master/others/lockup_view.php">락업기록 조회</a></li>
                    <li><a href="#">코인설정</a></li>
                    <li><a href="#">블랙리스트</a></li>
                    <li><a href="#">페이지 설정</a></li>
                </ul>
            </li>
        </ul>
    </div>

    <!-- JavaScript: 하위 메뉴 토글 기능 -->
    <script>
        function toggleSubMenu(event) {
            event.preventDefault();
            const subMenu = event.target.nextElementSibling;
            if (subMenu && subMenu.tagName === "UL") {
                // 다른 열린 하위 메뉴 모두 닫기
                document.querySelectorAll(".nav-bar ul li ul").forEach(function(menu) {
                    if(menu !== subMenu) {
                        menu.style.display = "none";
                    }
                });
                subMenu.style.display = (subMenu.style.display === "block") ? "none" : "block";
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            const menuItems = document.querySelectorAll(".nav-bar ul > li > a");
            menuItems.forEach(function(item) {
                item.addEventListener("click", function(e) {
                    // 하위 메뉴가 있는 경우 자동으로 toggleSubMenu가 처리하므로 여기서는 리턴
                    const nextElem = this.nextElementSibling;
                    if (nextElem && nextElem.tagName === "UL") {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</body>
</html>