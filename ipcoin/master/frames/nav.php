<?php
/**
 * nav.php
 *  - 사이드바를 "대시보드", "회원", "코인" 3가지 메뉴만 남기기
 *  - 하위 메뉴 없애기
 *  - “관리자” 표시 제거
 */
require_once dirname(__DIR__) . '/../config.php'; 
?>

<!-- 메인 사이드바(AdminLTE 구조) -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- 로고 영역 -->
  <a href="/master/index.php" class="brand-link">
    <span class="brand-text font-weight-light">IPcoin Admin</span>
  </a>

  <!-- 사이드바 내용 -->
  <div class="sidebar">
    <!-- 관리자(사용자) 이름 표시 부분 삭제
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="info">
        <a href="#" class="d-block">
          <?php // echo htmlspecialchars($_SESSION['master_name'] ?? '관리자'); ?>
        </a>
      </div>
    </div>
    -->

    <!-- 실제 메뉴 -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" 
          data-widget="treeview" role="menu" data-accordion="false">
        
        <!-- 대시보드 -->
        <li class="nav-item">
          <a href="/master/index.php" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
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
          <a href="/master/wallet/deposit_page.php" class="nav-link">
            <i class="nav-icon fas fa-coins"></i>
            <p>코인</p>
          </a>
        </li>

      </ul>
    </nav>
  </div><!-- /.sidebar -->
</aside>