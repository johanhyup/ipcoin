<?php
require_once('../config.php'); // DB 연결
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = trim($_POST['current_password'] ?? '');
    $new_password = trim($_POST['new_password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    $user_id = $_SESSION['user_idx'] ?? null; // Use consistent session variable

    if (!$user_id) {
        echo json_encode(["success" => false, "message" => "로그인이 필요합니다."]);
        exit;
    }

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        echo json_encode(["success" => false, "message" => "모든 필드를 입력해주세요."]);
        exit;
    }

    if ($new_password !== $confirm_password) {
        echo json_encode(["success" => false, "message" => "새 비밀번호가 일치하지 않습니다."]);
        exit;
    }

    if (strlen($new_password) < 3) {
        echo json_encode(["success" => false, "message" => "새 비밀번호는 최소 3자 이상이어야 합니다."]);
        exit;
    }

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        echo json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]);
        exit;
    }

    try {
        $stmt = $conn->prepare("SELECT mb_password FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            echo json_encode(["success" => false, "message" => "사용자를 찾을 수 없습니다."]);
            exit;
        }

        $user = $result->fetch_assoc();
        if (!password_verify($current_password, $user['mb_password'])) {
            echo json_encode(["success" => false, "message" => "현재 비밀번호가 일치하지 않습니다."]);
            exit;
        }

        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $update_stmt = $conn->prepare("UPDATE users SET mb_password = ? WHERE id = ?");
        $update_stmt->bind_param("si", $hashed_password, $user_id);
        $update_stmt->execute();

        if ($update_stmt->affected_rows > 0) {
            echo json_encode(["success" => true, "message" => "비밀번호가 성공적으로 변경되었습니다."]);
        } else {
            echo json_encode(["success" => false, "message" => "비밀번호 변경에 실패했습니다. 다시 시도해주세요."]);
        }

        $update_stmt->close();
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "오류가 발생했습니다: " . $e->getMessage()]);
    } finally {
        $stmt->close();
        $conn->close();
    }
}
?>
