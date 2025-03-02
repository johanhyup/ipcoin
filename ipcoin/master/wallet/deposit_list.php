<?php
require_once dirname(__DIR__) . '/../config.php';

header('Content-Type: application/json');

try {
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

    // SQL 쿼리: 검색 조건 적용
    $sql = "
        SELECT 
            dr.id AS deposit_id,
            u.id AS user_id,
            u.mb_id AS user_id_name,
            u.mb_name AS nickname,
            dr.coin_name,
            dr.deposit_address,
            dr.amount,
            dr.status,
            dr.created_at
        FROM deposit_requests dr
        LEFT JOIN users u ON dr.user_id = u.id
    ";
    
    // 검색 조건 추가
    if (!empty($search)) {
        $sql .= " WHERE u.mb_id LIKE ? OR u.mb_name LIKE ? OR dr.coin_name LIKE ?";
    }

    $stmt = $conn->prepare($sql);

    if (!empty($search)) {
        $searchParam = "%{$search}%";
        $stmt->bind_param("sss", $searchParam, $searchParam, $searchParam);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $deposits = [];
    while ($row = $result->fetch_assoc()) {
        $deposits[] = $row;
    }
} catch (Exception $e) {
    $error_message = $e->getMessage();
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
}
?>

<?php // 페이지 시작: header.php 불러오기
require_once __DIR__ . '/frame/header.php'; ?>

<!-- 여기에 페이지별 내용 (본문) -->
<div class="container-fluid mt-5">
  <div class="card">
    <div class="card-header">입금 내역</div>
    <div class="card-body">
      <?php if (!empty($error_message)): ?>
        <p class="text-danger">Error: <?= htmlspecialchars($error_message) ?></p>
      <?php endif; ?>
      
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>입금 ID</th>
            <th>유저 ID</th>
            <th>유저 계정</th>
            <th>이름</th>
            <th>코인 이름</th>
            <th>입금 주소</th>
            <th>입금 금액</th>
            <th>상태</th>
            <th>생성일</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($deposits)): ?>
            <?php foreach ($deposits as $deposit): ?>
              <tr>
                <td><?= htmlspecialchars($deposit['deposit_id']) ?></td>
                <td><?= htmlspecialchars($deposit['user_id']) ?></td>
                <td><?= htmlspecialchars($deposit['user_id_name']) ?></td>
                <td><?= htmlspecialchars($deposit['nickname']) ?></td>
                <td><?= htmlspecialchars($deposit['coin_name']) ?></td>
                <td><?= htmlspecialchars($deposit['deposit_address']) ?></td>
                <td><?= htmlspecialchars($deposit['amount']) ?></td>
                <td><?= htmlspecialchars($deposit['status']) ?></td>
                <td><?= htmlspecialchars($deposit['created_at']) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="9">입금 내역이 존재하지 않습니다.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div><!-- card-body -->
  </div><!-- card -->
</div><!-- container-fluid -->

<?php // 페이지 끝: footer.php 불러오기
require_once __DIR__ . '/frame/footer.php'; ?>
