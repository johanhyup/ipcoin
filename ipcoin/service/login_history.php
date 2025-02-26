<?php
require_once('../config.php');
session_start();

$user_id = $_SESSION['user_id'] ?? 0;

if (!$user_id) {
    echo "<p>로그인이 필요합니다.</p>";
    exit;
}

$stmt = $conn->prepare("SELECT log_id, last_login, user_ip, created_at FROM user_access_logs WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<table border='1'>";
echo "<tr><th>로그 ID</th><th>마지막 로그인</th><th>IP 주소</th><th>생성 날짜</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
        <td>{$row['log_id']}</td>
        <td>{$row['last_login']}</td>
        <td>{$row['user_ip']}</td>
        <td>{$row['created_at']}</td>
    </tr>";
}
echo "</table>";
$stmt->close();
?>
