<?php
session_start([
    'cookie_lifetime' => 3000, // 쿠키 유효 시간
]);

// 세션, DB 등 필요한 PHP 처리
require_once dirname(__DIR__) . '/../config.php';
require_once dirname(__DIR__) . '/../frames/asset.php';

// 세션 체크
if (!isset($_SESSION['master_id']) || !isset($_SESSION['master_name'])) {
    echo "<script>
        alert('관리자 로그인이 필요한 페이지입니다.');
        window.location.href = '/master/bbs/login.php';
    </script>";
    exit;
}
?>

<!-- 여기부터는 div만 -->
<div class="nav-bar">
    <ul>
        <li><a href="/master/index.php" class="active">대시보드</a></li>
        <li>
            <a href="#" onclick="toggleSubMenu(event)">회원관리</a>
            <ul>
                <li><a href="/master/manage_user/userlist_view.php">회원목록</a></li>
                <!-- ... -->
            </ul>
        </li>
        <!-- ... 기타 메뉴 -->
    </ul>
</div>

<script>
function toggleSubMenu(event) {
    const subMenu = event.target.nextElementSibling;
    if (subMenu && subMenu.tagName === "UL") {
        if (subMenu.style.display === "block") {
            subMenu.style.display = "none";
        } else {
            // 다른 열린 하위 메뉴 모두 닫기
            document.querySelectorAll(".nav-bar ul li ul").forEach(function(otherSubMenu) {
                otherSubMenu.style.display = "none";
            });
            subMenu.style.display = "block";
        }
        event.preventDefault();
    }
}
</script>