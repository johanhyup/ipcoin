<?php
require_once dirname(__DIR__) . '/config.php';

header('Content-Type: application/json');

try {
    // 현재 시간
    $currentDate = date('Y-m-d H:i:s');

    // 락업이 완료된 레코드 찾기
    $sql = "
        SELECT cl.id, cl.user_id, cl.coin_id, cl.locked_amount, c.name AS coin_name
        FROM coin_lockup cl
        INNER JOIN coin c ON cl.coin_id = c.id
        WHERE cl.end_date <= ? AND cl.status = 'active'
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("SQL 준비 중 오류 발생: " . $conn->error);
    }

    $stmt->bind_param("s", $currentDate);
    $stmt->execute();
    $result = $stmt->get_result();

    // 완료된 락업을 순회하며 업데이트
    while ($row = $result->fetch_assoc()) {
        $lockupId = $row['id'];
        $userId = $row['user_id'];
        $coinId = $row['coin_id'];
        $lockedAmount = $row['locked_amount'];

        // START TRANSACTION: 모든 작업이 성공해야 커밋
        $conn->begin_transaction();

        try {
            // wallet 테이블 업데이트
            $walletSql = "
                UPDATE wallet
                SET locked_balance = locked_balance - ?, total_balance = total_balance + ?
                WHERE user_id = ?
            ";
            $walletStmt = $conn->prepare($walletSql);
            $walletStmt->bind_param("ddi", $lockedAmount, $lockedAmount, $userId);
            $walletStmt->execute();
            $walletStmt->close();

            // coin 테이블 업데이트
            $coinSql = "
                UPDATE coin
                SET locked_amount = locked_amount - ?
                WHERE id = ?
            ";
            $coinStmt = $conn->prepare($coinSql);
            $coinStmt->bind_param("di", $lockedAmount, $coinId);
            $coinStmt->execute();
            $coinStmt->close();

            // coin_lockup 상태 업데이트
            $lockupUpdateSql = "UPDATE coin_lockup SET status = 'completed' WHERE id = ?";
            $lockupUpdateStmt = $conn->prepare($lockupUpdateSql);
            $lockupUpdateStmt->bind_param("i", $lockupId);
            $lockupUpdateStmt->execute();
            $lockupUpdateStmt->close();

            // 모든 작업 성공 시 커밋
            $conn->commit();

        } catch (Exception $e) {
            // 실패 시 롤백
            $conn->rollback();
            throw new Exception("업데이트 중 오류 발생: " . $e->getMessage());
        }
    }

    echo json_encode(['success' => true, 'message' => 'Lockup updates completed successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    // 객체가 존재할 경우에만 close 호출
    if (isset($stmt) && $stmt instanceof mysqli_stmt) {
        $stmt->close();
    }
    if (isset($conn) && $conn instanceof mysqli && !$conn->connect_errno) {
        $conn->close();
    }
}
?>
