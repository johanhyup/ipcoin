<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8" />
  <title>회원 목록</title>
  <!-- 필요한 CSS (Bootstrap, AdminLTE 등) 불러오기 -->
  <link rel="stylesheet" href="/assets/bootstrap.min.css" />
  <link rel="stylesheet" href="/assets/adminlte.min.css" />
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- 상단 Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- 좌측 햄버거 메뉴 버튼 -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <!-- data-widget="pushmenu"로 사이드바 토글 활성화 -->
        <a class="nav-link" data-widget="pushmenu" href="#" role="button">
          <i class="fas fa-bars"></i>
        </a>
      </li>
      <!-- (다른 Navbar 항목들) -->
    </ul>
  </nav>

  <!-- 사이드바 -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- 사이드바 내용 (로고, 메뉴 등) -->
    <!-- ... -->
  </aside>

  <!-- 콘텐츠 영역 -->
  <div class="content-wrapper">
    <div class="content-header">
      <h1 class="m-0 text-dark">회원 목록</h1>
    </div>
    <div class="content">
      <div class="table-responsive">
        <table id="userTable" class="table table-striped table-bordered">
          <thead>
            <tr>
              <!-- 모바일에서는 숨겨지도록 클래스 적용 -->
              <th class="d-none d-sm-table-cell">번호</th>
              <th>이름</th>
              <th>이메일</th>
              <th>가입일</th>
              <th>액션</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $index => $user): ?>
            <tr>
              <td class="d-none d-sm-table-cell"><?php echo $index + 1; ?></td>
              <td><?php echo htmlspecialchars($user['name']); ?></td>
              <td><?php echo htmlspecialchars($user['email']); ?></td>
              <td><?php echo htmlspecialchars($user['created_at']); ?></td>
              <td>
                <!-- 정보 버튼 (파란색 배경, 흰색 글씨) -->
                <button class="btn btn-primary btn-sm" onclick="openUserInfo(<?php echo $user['id']; ?>)">
                  정보
                </button>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div><!-- /.content-wrapper -->

  <!-- 회원정보 모달 (데스크톱용 팝업) -->
  <div class="modal fade" id="userInfoModal" tabindex="-1" role="dialog" aria-labelledby="userInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document"><!-- 큰 모달 사용으로 가독성 향상 -->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="userInfoModalLabel">회원 정보</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body"><!-- 회원 상세 정보 내용 --></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
        </div>
      </div>
    </div>
  </div>
</div><!-- /.wrapper -->

<!-- 필요한 JS 스크립트들 (jQuery -> Bootstrap -> AdminLTE 순서로) -->
<script src="/assets/jquery.min.js"></script>
<script src="/assets/bootstrap.bundle.min.js"></script>
<script src="/assets/adminlte.min.js"></script>
<script>
// 회원 정보 팝업 열기 함수
function openUserInfo(userId) {
  if (window.innerWidth < 576) {
    // 모바일: 새 탭에서 회원 정보 페이지 열기
    window.open('/user/info/' + userId, '_blank');
  } else {
    // 데스크톱: 모달에 회원 정보 내용 불러와서 표시
    $('#userInfoModal .modal-body').load('/user/info/' + userId);
    $('#userInfoModal').modal('show');
  }
}
</script>
<style>
/* 테이블 헤더 스타일 지정 */
#userTable thead tr {
  background-color: #FF0000;
  color: #FFFFFF;
}
</style>
</body>
</html>