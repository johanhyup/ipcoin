<?php
// 필요한 DB 연결이나 세션 코드가 있다면 유지
// require_once dirname(__DIR__) . '/../config.php'; 
// ... etc ...
?>

<!-- 메인 사이드바 -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- 사이드바 로고 -->
  <a href="/master/index.php" class="brand-link">
    <span class="brand-text font-weight-light">Raycoin Admin</span>
  </a>

  <!-- 사이드바 스크롤 영역 -->
  <div class="sidebar">
    <!-- (관리자 이름 표시 등 필요하다면 유지) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="info">
        <a href="#" class="d-block">
          <?php echo htmlspecialchars($_SESSION['master_name'] ?? '관리자'); ?>
        </a>
      </div>
    </div>

    <!-- 실제 메뉴: 대시보드, 회원, 코인만 남김 -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
        
        <!-- 대시보드 -->
        <li class="nav-item">
          <a href="/master/index.php" class="nav-link">
            <i class="nav-icon fas fa-home"></i>
            <p>대시보드</p>
          </a>
        </li>

        <!-- 회원 -->
        <li class="nav-item">
          <a href="/master/manage_user/userlist_view.php" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <p>회원</p>
          </a>
        </li>

        <!-- 코인 -->
        <li class="nav-item">
          <a href="/master/wallet/deposit_view.php" class="nav-link">
            <i class="nav-icon fas fa-coins"></i>
            <p>코인</p>
          </a>
        </li>

      </ul>
    </nav>
  </div>
</aside>