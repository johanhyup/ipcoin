<?php
// 데이터베이스 연결
require_once dirname(__DIR__) . '/config.php';

// JSON 반환 설정
header('Content-Type: application/json');

try {
    // 1. 총 회원 수
    $sql_total_users = "SELECT COUNT(*) AS total_users FROM users";
    $result_total_users = $conn->query($sql_total_users);
    $total_users = $result_total_users->fetch_assoc()['total_users'];

    // 2. 총 관리자 수 (master 테이블의 모든 레코드 수)
    $sql_total_admins = "SELECT COUNT(*) AS total_admins FROM master";
    $result_total_admins = $conn->query($sql_total_admins);
    $total_admins = $result_total_admins->fetch_assoc()['total_admins'];

    // 3. 오늘 가입한 회원 수
    $sql_new_users = "SELECT COUNT(*) AS new_users FROM users WHERE DATE(created_at) = CURDATE()";
    $result_new_users = $conn->query($sql_new_users);
    $new_users = $result_new_users->fetch_assoc()['new_users'];

    // 결과 JSON 반환
    echo json_encode([
        'success' => true,
        'total_users' => $total_users,
        'total_admins' => $total_admins,
        'new_users' => $new_users
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

// 연결 종료
$conn->close();
?>
