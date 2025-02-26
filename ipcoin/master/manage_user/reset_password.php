<?php
require_once dirname(__DIR__) . '/../config.php'; // 데이터베이스 연결

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mb_id = isset($_POST['mb_id']) ? trim($_POST['mb_id']) : '';

    if (empty($mb_id)) {
        echo json_encode(['success' => false, 'message' => '아이디를 입력해주세요.']);
        exit;
    }

    // 입력된 아이디가 존재하는지 확인
    $stmt = $conn->prepare("SELECT id FROM users WHERE mb_id = ?");
    $stmt->bind_param("s", $mb_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => '해당 아이디의 사용자를 찾을 수 없습니다.']);
        $stmt->close();
        exit;
    }
    $stmt->close();

    // 비밀번호 초기화
    $hashed_password = password_hash('1111', PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET mb_password = ? WHERE mb_id = ?");
    $stmt->bind_param("ss", $hashed_password, $mb_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => '비밀번호 초기화에 실패했습니다.']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => '잘못된 요청 방식입니다.']);
}

$conn->close();
