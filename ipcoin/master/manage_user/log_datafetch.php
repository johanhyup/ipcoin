<?php
require_once dirname(__DIR__) . '/../config.php';

header('Content-Type: application/json');

try {
    // 데이터 가져오는 SQL 쿼리
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

    // SQL 실행
    $result = $conn->query($query);

    if (!$result) {
        throw new Exception("데이터베이스 쿼리 오류: " . $conn->error);
    }

    // 결과를 배열로 변환
    $logs = [];
    while ($row = $result->fetch_assoc()) {
        $logs[] = $row;
    }

    // JSON 응답 반환
    echo json_encode(['success' => true, 'logs' => $logs]);

} catch (Exception $e) {
    // 오류 발생 시 응답 반환
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    // 연결 종료
    $conn->close();
}
