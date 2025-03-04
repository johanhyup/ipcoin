<?php
// /master/manage_user/user_list_ajax.php
require_once dirname(__DIR__) . '/../../config.php';
header('Content-Type: application/json');

// 파라미터
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// 간단히: users + wallet_coin(코인 잔액) LEFT JOIN
$sql = "
  SELECT u.id,
         u.mb_id,
         u.mb_name,
         u.mb_tel,
         u.mb_email,
         u.created_at,
         IFNULL(wc.total_balance, 0) AS coin_balance
    FROM users u
    LEFT JOIN wallet_coin wc
           ON u.id = wc.user_id
          AND wc.coin_name='IP'
   WHERE 1
";

// 검색 조건
if($search !== '') {
  $like = "%{$search}%";
  $sql .= " AND (u.mb_name LIKE ? OR u.mb_id LIKE ?)";
}
$sql .= " ORDER BY u.id DESC LIMIT 100";  // 필요 시 페이지네이션 로직

$stmt = $conn->prepare($sql);
if($search !== '') {
  $stmt->bind_param("ss", $like, $like);
}
$stmt->execute();
$res = $stmt->get_result();

$users = [];
while($row=$res->fetch_assoc()){
  $users[] = $row;
}
$stmt->close();
$conn->close();

echo json_encode(['success'=>true,'users'=>$users]);