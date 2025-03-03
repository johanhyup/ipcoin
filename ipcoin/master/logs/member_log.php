<?php
/**
 * 파일 위치 예: /master/logs/member_log.php
 * 회원 로그 팝업창
 */

// 세션이나 권한 체크 등 필요하다면 추가
session_start();
require_once dirname(__DIR__, 2) . '/config.php'; // config.php 연결 (경로 주의)

// DB 연결
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// 검색어 (action 또는 description에 대해 검색)
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// 페이지네이션
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1); // 1보다 작으면 1로
$limit = 10; // 페이지당 표시할 로그 수
$offset = ($page - 1) * $limit;

// 검색 조건
$whereSql = '';
if (!empty($search)) {
    // action 또는 description에 검색어가 포함
    $whereSql = "WHERE (action LIKE '%$search%' OR description LIKE '%$search%')";
}

// 전체 로그 개수
$countSql = "SELECT COUNT(*) as cnt FROM member_logs $whereSql";
$countResult = $conn->query($countSql);
$totalCount = ($countResult && $countResult->num_rows > 0) 
    ? $countResult->fetch_assoc()['cnt'] 
    : 0;

// 실제 로그 조회
$sql = "
    SELECT ml.*, u.mb_id AS user_mb_id
    FROM member_logs ml
    LEFT JOIN users u ON ml.user_id = u.id
    $whereSql
    ORDER BY ml.created_at DESC
    LIMIT $offset, $limit
";
$result = $conn->query($sql);

// 총 페이지
$totalPages = ceil($totalCount / $limit);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>회원 로그</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" />
  <style>
    body {
      padding: 10px;
    }
    .table-responsive { margin-top: 20px; }
    .pagination { margin-top: 10px; }
  </style>
</head>
<body class="bg-light">

<h2>회원 로그</h2>
<form method="GET" action="member_log.php">
  <input type="text" name="search" placeholder="검색어 입력" value="<?php echo htmlspecialchars($search); ?>">
  <button type="submit" class="btn btn-sm btn-primary">검색</button>
</form>

<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead class="thead-light">
      <tr>
        <th>ID</th>
        <th>유저 ID</th>
        <th>액션(Action)</th>
        <th>설명(Description)</th>
        <th>로그 시각</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['user_mb_id'] ?: $row['user_id']); ?></td>
            <td><?php echo htmlspecialchars($row['action']); ?></td>
            <td><?php echo htmlspecialchars($row['description']); ?></td>
            <td><?php echo $row['created_at']; ?></td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="5" style="text-align:center;">로그가 없습니다.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div><!-- /.table-responsive -->

<!-- 페이지네이션 -->
<div class="pagination">
  <?php if ($totalPages > 1): ?>
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>" 
         class="btn btn-sm btn-<?php echo ($i == $page) ? 'primary' : 'outline-primary'; ?>">
         <?php echo $i; ?>
      </a>
    <?php endfor; ?>
  <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>