<?php
// 데이터베이스 연결
require_once dirname(__DIR__) . '/../config.php';

header('Content-Type: application/json');

try {
    // 검색어를 가져오기 (기본은 빈 값)
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

    // SQL 쿼리 기본 값
    $sql = "
        SELECT 
            u.id AS user_id,
            m.master_name AS manager_name,    
            u.mb_id AS user_id_name,
            u.mb_name AS nickname,
            u.mb_email AS email,
            u.mb_tel AS phone,
            w.wallet_address AS wallet_address, 
            w.total_balance AS total_balance,  
            w.locked_balance AS locked_balance, 
            w.available_balance AS withdrawable_balance, 
            u.created_at AS created_at 
        FROM users u
        LEFT JOIN master m ON u.managed_by = m.id
        LEFT JOIN wallet w ON u.id = w.user_id
    ";

    // 검색 조건 추가
    if ($search !== '') {
        $sql .= " WHERE u.mb_id LIKE ? OR u.mb_name LIKE ?";
    }

    $sql .= " ORDER BY u.created_at DESC";

    // SQL 실행
    $stmt = $conn->prepare($sql);
    if ($search !== '') {
        $likeSearch = '%' . $search . '%';
        $stmt->bind_param("ss", $likeSearch, $likeSearch);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    // 결과 데이터 처리
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode(['success' => true, 'data' => $data]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    $stmt->close();
    $conn->close();
}
?>
