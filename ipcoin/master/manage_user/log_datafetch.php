<?php
require_once dirname(__DIR__) . '/../config.php'; // DB 연결

header('Content-Type: application/json; charset=utf-8');

// POST/GET 분기 제거하고, 단순 GET 요청을 처리
try {
    // user_access_logs + users + wallet + coin 테이블 조인
    // (reset_password.php 파일에 있던 쿼리를 그대로 가져온 예시)
    $query = "
        SELECT 
            l.log_id,
            l.user_id,
            l.last_login,
            l.user_ip,
            l.created_at AS log_created_at,
            u.mb_id AS user_mb_id,
            u.mb_name AS user_mb_name,
            u.mb_email AS user_mb_email,
            u.grade AS user_grade,
            w.wallet_address,
            w.total_balance,
            w.available_balance,
            w.locked_balance,
            c.name AS coin_name,
            c.total_amount AS coin_total_amount,
            c.locked_amount AS coin_locked_amount
        FROM user_access_logs l
        LEFT JOIN users u ON l.user_id = u.id
        LEFT JOIN wallet w ON u.id = w.user_id
        LEFT JOIN coin c ON u.id = c.user_id
        ORDER BY l.log_id DESC
    ";

    $result = $conn->query($query);
    if (!$result) {
        throw new Exception("DB 조회 오류: " . $conn->error);
    }

    $logs = [];
    while ($row = $result->fetch_assoc()) {
        $logs[] = $row;
    }

    echo json_encode([
        'success' => true,
        'logs' => $logs
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    $conn->close();
}