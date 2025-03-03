<?php
// nav.php (변경 예시)

// DB나 세션, config 연결 등 기존 로직이 있다면 필요한 부분만 유지하세요.
require_once dirname(__DIR__) . '/../config.php';
?>

<!-- 메인 사이드바 -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">

  <!-- 사이드바 상단 로고/브랜드 영역 -->
  <a href="/master/index.php" class="brand-link">
    <span class="brand-text font-weight-light">IPcoin Admin</span>
  </a>

  <div class="sidebar">
    <!-- (관리자 정보 표시 구간 - 필요 시 유지) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="info">
        <a href="#" class="d-block">
          <?php echo htmlspecialchars($_SESSION['master_name'] ?? '관리자'); ?>
        </a>
      </div>
    </div>

    <!-- 실제 메뉴 - 요구사항에 맞게 3개만 남김 -->
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

        <!-- 
        // 나머지 메뉴는 모두 주석 처리하거나 제거
        <li class="nav-item has-treeview">
          ...
        </li>
        -->
      </ul>
    </nav>
  </div>
</aside>