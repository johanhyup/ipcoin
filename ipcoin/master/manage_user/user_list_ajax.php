<?php
/**
 * user_list_ajax.php
 * - GET params: page, sort, limit, search
 * - DB 조회 후 JSON으로 반환
 */
session_start();
require_once dirname(__DIR__, 2) . '/config.php';

$page = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'recent'; // default: 최근가입순
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$offset = ($page - 1) * $limit;

// 검색 조건
$where = "1=1";
if($search !== '') {
  // 이름(mb_name) 또는 아이디(mb_id)에 search 문자열이 포함
  $searchEsc = $conn->real_escape_string($search);
  $where .= " AND (u.mb_name LIKE '%$searchEsc%' OR u.mb_id LIKE '%$searchEsc%')";
}

// 정렬
$orderBy = "";
switch($sort) {
  case 'coin':
    // 코인 보유순 (가정) => wallet.available_balance DESC
    // 실제로 wallet.available_balance 컬럼이 있는지 확인 필요
    $orderBy = "ORDER BY w.available_balance DESC, u.id DESC";
    break;
  case 'name':
    // 가나다순 (mb_name ASC)
    $orderBy = "ORDER BY u.mb_name ASC";
    break;
  case 'recent':
  default:
    // 최근 가입순
    $orderBy = "ORDER BY u.id DESC";
    break;
}

// 전체 개수
$countSql = "
  SELECT COUNT(*) AS cnt
    FROM users u
    LEFT JOIN wallet w ON u.id = w.user_id
   WHERE $where
";
$countRes = $conn->query($countSql);
$row = $countRes->fetch_assoc();
$totalCount = (int)$row['cnt'];

// 조회
$sql = "
  SELECT
    u.id,
    u.mb_id,
    u.mb_name,
    u.mb_email,
    u.mb_tel,
    u.approved,
    u.created_at,
    IFNULL(w.available_balance, 0) AS coin_balance
  FROM users u
  LEFT JOIN wallet w ON u.id = w.user_id
  WHERE $where
  $orderBy
  LIMIT $offset, $limit
";
$result = $conn->query($sql);

$users = [];
if($result && $result->num_rows > 0) {
  while($r = $result->fetch_assoc()) {
    $users[] = $r;
  }
}

$totalPages = ceil($totalCount / $limit);
$startIndex = $offset + 1; // 페이지 첫 항목의 인덱스 (No. 표시용)

// JSON 응답
header('Content-Type: application/json');
echo json_encode([
  'success' => true,
  'data' => [
    'users' => $users,
    'totalCount' => $totalCount,
    'totalPages' => $totalPages,
    'startIndex' => $startIndex
  ]
], JSON_UNESCAPED_UNICODE);

$conn->close();