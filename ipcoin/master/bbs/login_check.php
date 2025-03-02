<?php
session_start(); // 세션 시작

require_once dirname(__DIR__) . '/../config.php';
require_once dirname(__DIR__) . '/../frames/asset.php';

// 폼에서 받은 아이디/비번
$master_id = trim($_POST['master_id'] ?? '');
$master_password = trim($_POST['master_password'] ?? '');

// 유효성 체크
if (empty($master_id) || empty($master_password)) {
    echo "<script>alert('아이디와 비밀번호를 모두 입력해주세요.'); history.back();</script>";
    exit;
}

// DB 연결
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo "<script>alert('DB 연결 오류: " . $conn->connect_error . "'); history.back();</script>";
    exit;
}

try {
    // 아이디로 조회
    $sql = "SELECT * FROM master WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $master_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows < 1) {
        throw new Exception('존재하지 않는 아이디입니다.');
    }
    $master = $result->fetch_assoc();

    // 비밀번호 검증
    if (!password_verify($master_password, $master['password'])) {
        throw new Exception('비밀번호가 일치하지 않습니다.');
    }

    // 여기까지 왔으면 로그인 성공
    $_SESSION['master_id'] = $master['id'];
    $_SESSION['master_name'] = $master['master_name'];
    $_SESSION['rank'] = $master['rank'];

    // 최근 로그인 시간 업데이트
    $current_time = date('Y-m-d H:i:s');
    $update_sql = "UPDATE master SET recent_login = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $current_time, $master['id']);
    $update_stmt->execute();

    echo "<script>alert('로그인 성공!'); window.location.href = '/master/index.php';</script>";
    exit;

} catch (Exception $e) {
    echo "<script>alert('로그인 실패: " . $e->getMessage() . "'); history.back();</script>";
    exit;

} finally {
    $stmt->close();
    $conn->close();
}