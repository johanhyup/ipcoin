<?php

// 현재 파일의 상위 디렉토리의 config.php를 불러옴
require_once dirname(__DIR__) . '/config.php';



try {
    // 1. 모든 사용자 ID 가져오기
    $get_users_sql = "SELECT DISTINCT user_id FROM wallet";
    $user_result = $conn->query($get_users_sql);

    if ($user_result->num_rows > 0) {
        while ($user = $user_result->fetch_assoc()) {
            $user_id = $user['user_id'];

            // 2. 해당 사용자의 코인 total_amount 및 locked_amount 합계 가져오기
            $sum_sql = "SELECT 
                            SUM(total_amount) AS total_amount_sum, 
                            SUM(locked_amount) AS locked_amount_sum 
                        FROM coin 
                        WHERE user_id = ?";
            $sum_stmt = $conn->prepare($sum_sql);
            $sum_stmt->bind_param("i", $user_id);
            $sum_stmt->execute();
            $sum_result = $sum_stmt->get_result();

            if ($row = $sum_result->fetch_assoc()) {
                // 총 잔액 및 락업 잔액 계산
                $total_balance = (float) $row['total_amount_sum'];
                $locked_balance = (float) $row['locked_amount_sum'];
                $available_balance = $total_balance - $locked_balance;

                // 3. wallet 테이블의 total_balance, locked_balance, available_balance 업데이트
                $update_sql = "UPDATE wallet 
                               SET total_balance = ?, 
                                   locked_balance = ?, 
                                   available_balance = ? 
                               WHERE user_id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("dddi", $total_balance, $locked_balance, $available_balance, $user_id);
                $update_stmt->execute();

                // 로그 출력 (옵션)
                //echo "Updated user_id: $user_id, total_balance: $total_balance, locked_balance: $locked_balance, available_balance: $available_balance\n";

                $update_stmt->close();
            }

            $sum_stmt->close();
        }
    } else {
        echo "No users found in wallet table.\n";
    }

    // 연결 종료
    $conn->close();
} catch (Exception $e) {
    // 오류 로그 출력
    echo "Error: " . $e->getMessage();
}
?>
