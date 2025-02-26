<?php
require_once dirname(__DIR__) . '/config.php';

header('Content-Type: application/json');

try {
    // SQL 쿼리 실행
    $sql = "SELECT SUM(amount) AS total_deposit FROM deposit_requests WHERE status = 'approved'";
    $result = $conn->query($sql);

    // 결과 처리
    if ($result) {
        $row = $result->fetch_assoc();
        $totalDeposit = $row['total_deposit'] ?? 0;
        echo json_encode(['success' => true, 'total_deposit' => $totalDeposit]);
    } else {
        throw new Exception("Failed to fetch total deposit amount.");
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    $conn->close();
}
?>
