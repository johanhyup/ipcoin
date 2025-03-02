<?php
session_start([
    'cookie_lifetime' => 3000, // 쿠키 유효 시간
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

<?php // 페이지 시작: header.php 불러오기
require_once __DIR__ . '/frame/header.php'; ?>

<!-- 여기에 페이지별 내용 (본문) -->
<div class="container-fluid mt-5">
    <h2>대시보드</h2>
    <p>메인 콘텐츠...</p>

    <!-- 왼쪽 네비게이션 바 -->
    <div class="nav-bar" id="sidebar">
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
</div>

<!-- JavaScript for toggling submenus -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const menuItems = document.querySelectorAll(".nav-bar ul > li > a");
    menuItems.forEach(function (menuItem) {
        menuItem.addEventListener("click", function (event) {
            const subMenu = this.nextElementSibling;
            if (subMenu && subMenu.tagName === "UL") {
                if (subMenu.style.display === "block") {
                    subMenu.style.display = "none";
                } else {
                    document.querySelectorAll(".nav-bar ul li ul").forEach(function (otherSubMenu) {
                        otherSubMenu.style.display = "none";
                    });
                    subMenu.style.display = "block";
                }
                event.preventDefault();
            }
        });
    });
});
</script>

<?php // 페이지 끝: footer.php 불러오기
require_once __DIR__ . '/frame/footer.php';
?>
