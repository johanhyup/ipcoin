<?php
session_start();
require_once dirname(__DIR__) . '/../config.php';
require_once dirname(__DIR__) . '/../frames/asset.php';

// POST 데이터 받기
$master_id = isset($_POST['master_id']) ? trim($_POST['master_id']) : '';
$master_password = isset($_POST['master_password']) ? trim($_POST['master_password']) : '';

// 유효성 검사
if (empty($master_id) || empty($master_password)) {
    require_once dirname(__DIR__) . '/../frames/header.php';
    ?>
    <div class="container-fluid mt-5">
      <div class="card">
        <div class="card-header">로그인 오류</div>
        <div class="card-body">
          <p>아이디와 비밀번호를 모두 입력해주세요.</p>
          <a href="javascript:history.back()" class="btn btn-secondary">뒤로가기</a>
        </div>
      </div>
    </div>
    <?php
    require_once dirname(__DIR__) . '/../frames/footer.php';
    exit;
}

// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    require_once dirname(__DIR__) . '/../frames/header.php';
    ?>
    <div class="container-fluid mt-5">
      <div class="card">
        <div class="card-header">데이터베이스 연결 오류</div>
        <div class="card-body">
          <p><?php echo "Database connection failed: " . $conn->connect_error; ?></p>
          <a href="javascript:history.back()" class="btn btn-secondary">뒤로가기</a>
        </div>
      </div>
    </div>
    <?php
    require_once dirname(__DIR__) . '/../frames/footer.php';
    exit;
}

try {
    // 아이디로 master 테이블 조회
    $sql = "SELECT * FROM master WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $master_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $master = $result->fetch_assoc();

        // 비밀번호 검증
        if (password_verify($master_password, $master['password'])) {
            // 세션에 로그인 정보 저장
            $_SESSION['master_id'] = $master['id'];
            $_SESSION['master_name'] = $master['master_name'];
            $_SESSION['rank'] = $master['rank'];

            // 최근 로그인 시간 업데이트
            $current_time = date('Y-m-d H:i:s');
            $update_sql = "UPDATE master SET recent_login = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("si", $current_time, $master['id']);
            $update_stmt->execute();

            echo "<script>alert('로그인 성공!'); window.location.href = '/master/index.php';</script>";
            exit;
        } else {
            throw new Exception('비밀번호가 일치하지 않습니다.');
        }
    } else {
        throw new Exception('아이디가 존재하지 않습니다.');
    }
} catch (Exception $e) {
    require_once dirname(__DIR__) . '/../frames/header.php';
    ?>
    <div class="container-fluid mt-5">
      <div class="card">
        <div class="card-header">로그인 실패</div>
        <div class="card-body">
          <p><?php echo "로그인 실패: " . $e->getMessage(); ?></p>
          <a href="javascript:history.back()" class="btn btn-secondary">뒤로가기</a>
        </div>
      </div>
    </div>
    <?php
} finally {
    $stmt->close();
    $conn->close();
}
?>
