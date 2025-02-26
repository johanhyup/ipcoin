<?php
session_start();
require_once dirname(__DIR__) . '/config.php';


// 세션에서 값 가져오기
$user_idx = isset($_SESSION['user_idx']) ? intval($_SESSION['user_idx']) : 0;
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ''; // 사용자 이름
$user_ip = $_SERVER['REMOTE_ADDR']; // 사용자 IP 주소
// 유효성 검사
if ($user_idx <= 0 || empty($user_name)) {
    echo json_encode(["success" => false, "message" => "Invalid session data"]);
    exit;
}

// 디버깅: 세션 값 확인
error_log("Session user_idx: " . $user_idx);

// 유효성 검사
if ($user_idx <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid session user ID"]);
    exit;
}

// SQL 쿼리: transactions 테이블과 coin 테이블의 user_id를 비교하여 일치하는 데이터만 조회
$sql = "SELECT DISTINCT
            t.id, 
            t.transaction_date, 
            t.coin_name, 
            t.amount, 
            t.status 
        FROM transactions t
        INNER JOIN coin c ON t.user_id = c.user_id
        WHERE t.user_id = ?
        ORDER BY t.transaction_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_idx);
$stmt->execute();
$result = $stmt->get_result();

$response = ["success" => false, "transactions" => []];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // TID를 SHA-256 암호화 (transaction_date + user_name)
        $combined_data = $row['transaction_date'] . $user_name;
        $encrypted_tid = hash('sha256', $combined_data);

        $response['transactions'][] = [
            "id" => $encrypted_tid, // 암호화된 TID
            "transaction_date" => $row['transaction_date'],
            "coin_name" => $row['coin_name'],
            "amount" => $row['amount'],
            "status" => $row['status'] == 1 ? "Completed" : "Cancelled"
        ];
    }
    $response['success'] = true;
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>