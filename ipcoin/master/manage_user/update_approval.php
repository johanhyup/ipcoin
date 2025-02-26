<?php
require_once dirname(__DIR__) . '/../config.php'; // 데이터베이스 연결

header('Content-Type: application/json; charset=utf-8'); // JSON 응답 설정

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mb_id = isset($_POST['mb_id']) ? trim($_POST['mb_id']) : '';
    $approved = isset($_POST['approved']) ? intval($_POST['approved']) : 0;

    if (!empty($mb_id)) {
        // 사용자 존재 여부 확인
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE mb_id = ?");
        $check_stmt->bind_param("s", $mb_id);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $check_stmt->close();

            // 승인 상태 업데이트
            $stmt = $conn->prepare("UPDATE users SET approved = ? WHERE mb_id = ?");
            $stmt->bind_param("is", $approved, $mb_id);

            if ($stmt->execute()) {
                echo json_encode([
                    'success' => true,
                    'message' => '사용자 상태가 성공적으로 업데이트되었습니다.'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => '사용자 상태 업데이트에 실패했습니다.',
                    'error' => $stmt->error
                ]);
            }

            $stmt->close();
        } else {
            echo json_encode([
                'success' => false,
                'message' => '해당 아이디의 사용자를 찾을 수 없습니다.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => '유효하지 않은 아이디입니다.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => '잘못된 요청 방식입니다.'
    ]);
}

$conn->close();
