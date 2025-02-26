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

    echo json_encode(['success' => true, 'deposits' => $deposits]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    $stmt->close();
    $conn->close();
}
?>
