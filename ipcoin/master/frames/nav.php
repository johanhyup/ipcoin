<!-- nav.php -->
<?php
// 필요시 DB 연결, 세션 등 (예: require_once dirname(__DIR__) . '/../config.php';)
// ... 생략 ...

/** 
 * 기존에 복잡했던 메뉴 부분을 전부 주석 처리하고,
 * "대시보드", "회원", "코인" 메뉴만 남기는 예시입니다.
 */
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- 사이드바 로고/상단 -->
  <a href="/master/index.php" class="brand-link">
    <span class="brand-text font-weight-light">Raycoin Admin</span>
  </a>

  <!-- 사이드바 스크롤 영역 -->
  <div class="sidebar">
    <!-- 관리자 이름 표시 등(원하면 유지) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="info">
        <a href="#" class="d-block">
          <?php echo htmlspecialchars($_SESSION['master_name'] ?? '관리자'); ?>
        </a>
      </div>
    </div>

    <!-- 실제 메뉴: 3개만 노출 -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" 
          data-widget="treeview" 
          role="menu"
          data-accordion="false">
        
        <!-- 1) 대시보드 -->
        <li class="nav-item">
          <a href="/master/index.php" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>대시보드</p>
          </a>
        </li>

        <!-- 2) 회원 -->
        <li class="nav-item">
          <a href="/master/manage_user/userlist_view.php" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <p>회원</p>
          </a>
        </li>

        <!-- 3) 코인 -->
        <li class="nav-item">
          <a href="/master/wallet/deposit_view.php" class="nav-link">
            <i class="nav-icon fas fa-coins"></i>
            <p>코인</p>
          </a>
        </li>

        <!-- =========================
             (이하 전부 숨김/주석 처리)
             ========================= -->
        <!--
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-user-shield"></i>
            <p>상위관리자<i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            ...
          </ul>
        </li>

        <li class="nav-item has-treeview">...</li>
        <li class="nav-item">...</li>
        etc...
        -->
      </ul>
    </nav>
  </div>
</aside>