<?php
require_once dirname(__DIR__) . '/../config.php';

header('Content-Type: application/json');

try {
    $query = "
        SELECT 
            cl.coin_id,
            cl.user_id,
            c.name AS coin_name,
            u.mb_id AS user_id_name,
            u.mb_name AS user_name,
            cl.locked_amount,
            cl.start_date,
            cl.end_date,
            cl.status
        FROM 
            coin_lockup AS cl
        INNER JOIN 
            coin AS c ON cl.coin_id = c.id
        INNER JOIN 
            users AS u ON cl.user_id = u.id
        ORDER BY 
            cl.start_date DESC
    ";

    $result = $conn->query($query);

    if (!$result) {
        throw new Exception("쿼리 실행 실패: " . $conn->error);
    }

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode(['success' => true, 'data' => $data], JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    $conn->close();
}
?>
