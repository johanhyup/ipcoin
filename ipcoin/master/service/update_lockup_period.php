<?php
session_start();
require_once dirname(__DIR__) . '/../config.php';

header('Content-Type: application/json');

try {
    // 세션 검사 (관리자 여부 확인)
    if (!isset($_SESSION['master_id'])) {
        throw new Exception("관리자 권한이 필요합니다.");
    }

    // 입력 데이터 확인
    $data = json_decode(file_get_contents("php://input"), true);
    $newPeriod = intval($data['new_period']);

    if ($newPeriod <= 0) {
        throw new Exception("유효한 락업 기간을 입력해주세요.");
    }

    // 현재 시간으로부터 새로운 종료 날짜 계산
    $newEndDate = date('Y-m-d H:i:s', strtotime("+$newPeriod days"));

    // `coin_lockup` 테이블의 `end_date` 업데이트
    $updateQuery = $conn->prepare("
        UPDATE coin_lockup 
        SET end_date = ?
        WHERE status = 'active'
    ");
    $updateQuery->bind_param("s", $newEndDate);
    $updateQuery->execute();

    if ($updateQuery->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "락업 기간이 성공적으로 변경되었습니다."]);
    } else {
        echo json_encode(["success" => false, "message" => "변경된 항목이 없거나 이미 완료된 상태입니다."]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
} finally {
    if (isset($conn)) $conn->close();
}
?>
