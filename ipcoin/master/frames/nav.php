<?php
// 세션 만료 확인
if (isset($_SESSION['last_activity'])) {
    if ((time() - $_SESSION['last_activity']) > 600) { // 10분 초과
        session_unset();
        session_destroy();

        echo "<script>
            alert('세션이 만료되었습니다. 다시 로그인 해주세요.');
            window.location.href = '/master/bbs/login.php';
        </script>";
        exit;
    }
}
$_SESSION['last_activity'] = time(); // 마지막 활동 시간 갱신

require_once dirname(__DIR__) . '/../config.php';
require_once dirname(__DIR__) . '/../frames/asset.php';


?>

<!-- 사이드바: AdminLTE 구조 -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- 사이드바 로고/상단 -->
  <a href="/master/index.php" class="brand-link">
    <span class="brand-text font-weight-light">Raycoin Admin</span>
  </a>

  <!-- 사이드바 스크롤 영역 -->
  <div class="sidebar">
    <!-- 관리자 이름 등 간단히 표시할 수도 있음 -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="info">
        <a href="#" class="d-block">
          <?php echo htmlspecialchars($_SESSION['master_name'] ?? '관리자'); ?>
        </a>
      </div>
    </div>

    <!-- 실제 메뉴 -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

        <!-- 회원관리 -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <p>
              회원관리
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="/master/manage_user/userlist_view.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>회원목록</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>회원실시간</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/master/manage_user/user_log.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>회원 로그인 내역</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>회원 수정내역</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>회원 복구</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/master/manage_user/user_info.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>회원가입승인</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- 상위관리자 -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-user-shield"></i>
            <p>
              상위관리자
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>관리자목록</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>관리자실시간</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>로그인 내역</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>수정내역</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>관리자 블락</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>관리자 트리뷰</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- 입출금 관리 -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-wallet"></i>
            <p>
              입출금 관리
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="/master/wallet/deposit_view.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>입금기록 관리</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/master/wallet/deposit_manage.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>임시 입금신청</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>로그인 내역</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>수정내역</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>관리자 블락</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>관리자 트리뷰</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-coins"></i>
            <p>상위관리자 입금신청</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-history"></i>
            <p>전체 입출금 내역</p>
          </a>
        </li>

        <!-- 기타 설정 -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-cog"></i>
            <p>
              기타 설정
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="/master/others/lockup_manage.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>락업시간 설정</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/master/others/lockup_view.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>락업기록 조회</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>코인설정</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>블랙리스트</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>페이지 설정</p>
              </a>
            </li>
          </ul>
        </li>

      </ul>
    </nav>
  </div>
</aside>