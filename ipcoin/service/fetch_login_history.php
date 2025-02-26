<?php
session_start();
require_once dirname(__DIR__) . '/config.php';

// 로그인 확인
if (!isset($_SESSION['user_idx'])) {
    echo json_encode([]);
    exit;
}

$user_id = $_SESSION['user_idx'];

// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("데이터베이스 연결 실패: " . $conn->connect_error);
}

// 로그인 내역 가져오기
$sql = "SELECT log_id, last_login, user_ip, created_at FROM user_access_logs WHERE user_id = ? ORDER BY created_at ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$logs = [];
while ($row = $result->fetch_assoc()) {
    $logs[] = $row;
}

echo json_encode($logs);

// 연결 종료
$stmt->close();
$conn->close();
?>
