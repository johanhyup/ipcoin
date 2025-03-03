<?php
/**
 * approve_user.php
 * - POST: user_id
 * - 해당 user_id의 approved 필드를 1로 변경
 * - JSON 반환
 */

session_start();
require_once dirname(__DIR__, 2) . '/config.php';

$user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
if($user_id <= 0) {
  echo json_encode(['success' => false, 'message' => '잘못된 user_id']);
  exit;
}

$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error) {
  echo json_encode(['success' => false, 'message' => 'DB 연결 실패']);
  exit;
}

$sql = "UPDATE users SET approved = 1 WHERE id = $user_id";
if($conn->query($sql)) {
  if($conn->affected_rows > 0) {
    echo json_encode(['success' => true]);
  } else {
    echo json_encode(['success' => false, 'message' => '해당 회원이 존재하지 않습니다.']);
  }
} else {
  echo json_encode(['success' => false, 'message' => '쿼리 오류: '.$conn->error]);
}
$conn->close();