<?php
require_once dirname(__DIR__) . '/../config.php';

header('Content-Type: application/json');

try {
    $query = $conn->query("   SELECT 
        u.id AS user_id,
        u.mb_id,
        u.mb_name,
        u.mb_email,
        u.mb_tel,
        u.grade,
        u.approved,
        DATE(u.created_at) AS signup_date,
        w.wallet_address,
        w.total_balance,
        w.available_balance,
        w.locked_balance,
        c.name AS coin_name,
        c.total_amount AS coin_total_amount,
        c.locked_amount AS coin_locked_amount
    FROM users u
    LEFT JOIN wallet w ON u.id = w.user_id
    LEFT JOIN coin c ON u.id = c.user_id
    ORDER BY u.id");
    $users = [];

    while ($row = $query->fetch_assoc()) {
        $users[] = $row;
    }

    echo json_encode(['success' => true, 'users' => $users]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    $conn->close();
}
