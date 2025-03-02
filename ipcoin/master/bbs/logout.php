<?php
session_start(); // 세션 시작

// 세션에서 사용자 정보 제거 및 파괴
unset($_SESSION['master_id']);
unset($_SESSION['master_name']);
unset($_SESSION['rank']);
session_destroy();

// 헤더 포함
require_once dirname(__DIR__) . '/../frames/header.php';
?>

<div class="container-fluid mt-5">
  <div class="card">
    <div class="card-header">로그아웃</div>
    <div class="card-body">
      <p>로그아웃이 완료되었습니다.</p>
      <a href="/master/bbs/login.php" class="btn btn-primary">로그인 페이지로 이동</a>
    </div>
  </div>
</div>

<?php
// 푸터 포함
require_once dirname(__DIR__) . '/../frames/footer.php';
?>
