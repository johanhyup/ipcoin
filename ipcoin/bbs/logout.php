<?php
session_start(); // 세션 시작

// 세션에서 사용자 정보 제거
unset($_SESSION['user_id']);
unset($_SESSION['user_name']);
unset($_SESSION['last_login']);

// 세션 파괴
session_destroy();

?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>로그아웃</title>
    <script>
        // 로그아웃 완료 메시지 후 로그인 페이지로 이동
        alert("로그아웃이 완료되었습니다.");
        window.location.href = "./login.php"; // 로그인 페이지로 리디렉션
    </script>
</head>
<body>
</body>
</html>

