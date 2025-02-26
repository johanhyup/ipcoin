<?php
session_start();
require_once('../config.php');
header('Content-Type: application/json');

// 데이터베이스에서 최신 사용자 정보를 가져옴
if (isset($_SESSION['user_idx'])) {
    // 데이터베이스 연결
    $conn = new mysqli($servername, $username, $password, $dbname);

    // 연결 오류 확인
    if ($conn->connect_error) {
        echo json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]);
        exit;
    }

    // Prepared Statement를 사용하여 데이터 가져오기
    $query = "SELECT mb_name, mb_email, mb_tel, birth_date FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $_SESSION['user_idx']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode($user);
    } else {
        echo json_encode(['error' => 'User not found.']);
    }

    // 자원 정리
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['error' => 'Unauthorized access.']);
}
?>
