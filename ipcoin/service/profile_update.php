<?php
session_start(); // 세션 시작

// 데이터베이스 연결
require_once dirname(__DIR__) . '/config.php';

// 로그인 여부 확인
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='/login.php';</script>";
    exit;
}

// 사용자 ID
$user_id = $_SESSION['user_idx'];

// POST로 전달된 값 받기
$mb_name = isset($_POST['mb_name']) ? trim($_POST['mb_name']) : '';
$mb_tel = isset($_POST['mb_tel']) ? trim($_POST['mb_tel']) : '';
$mb_email = isset($_POST['mb_email']) ? trim($_POST['mb_email']) : '';
$birth_date = isset($_POST['birth_date']) ? trim($_POST['birth_date']) : '';

// 유효성 검사
if (empty($mb_name) || empty($mb_tel) || empty($mb_email) || empty($birth_date)) {
    echo "<script>alert('모든 항목을 입력해주세요.'); history.back();</script>";
    exit;
}

// 이메일 형식 확인
if (!filter_var($mb_email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('유효한 이메일 주소를 입력해주세요.'); history.back();</script>";
    exit;
}

// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("데이터베이스 연결 실패: " . $conn->connect_error);
}

// 업데이트 쿼리
$update_sql = "UPDATE users SET mb_name = ?, mb_tel = ?, mb_email = ?, birth_date = ? WHERE id = ?";
$stmt = $conn->prepare($update_sql);
$stmt->bind_param("ssssi", $mb_name, $mb_tel, $mb_email, $birth_date, $user_id);

// 업데이트 실행
if ($stmt->execute()) {
    echo "<script>alert('프로필이 성공적으로 업데이트되었습니다.'); location.href='/service/profile-settings.php';</script>";
} else {
    echo "<script>alert('프로필 업데이트에 실패했습니다. 다시 시도해주세요.'); history.back();</script>";
}

// 연결 종료
$stmt->close();
$conn->close();
?>
