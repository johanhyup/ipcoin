<?php
require_once dirname(__DIR__) . '/config.php';


header('Content-Type: application/json');

try {
    // 출금 테이블에서 amount 합계 계산
    $query = "SELECT SUM(amount) AS total_withdraw FROM withdraw_requests WHERE status = 'pending'";
    $result = $conn->query($query);

    if ($row = $result->fetch_assoc()) {
        $total_withdraw = $row['total_withdraw'] ? $row['total_withdraw'] : 0;
        echo json_encode(["success" => true, "total_withdraw" => $total_withdraw]);
    } else {
        echo json_encode(["success" => false, "message" => "No data found"]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
} finally {
    $conn->close();
}
?>
