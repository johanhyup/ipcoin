<?php
session_start();
require_once dirname(__DIR__) . '/frames/header.php';  // (필요하다면)
require_once dirname(__DIR__) . '/../config.php';       // DB 연결
require_once dirname(__DIR__) . '/frames/top_nav.php';  // 상단 네비게이션
require_once dirname(__DIR__) . '/frames/nav.php';      // 좌측 사이드바 등

// DB 연결 (이미 config에서 했으면 중복 안 해도 되지만, 필요하다면 아래 유지)
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("DB 연결 실패: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>회원 관리</title>
  <!-- AdminLTE / Bootstrap / jQuery 등 (예시) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" />
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>

  <style>
    /* 검색바 스타일 */
    .search-bar {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 20px;
      align-items: center;
    }
    .search-bar select,
    .search-bar input {
      height: 38px;
      padding: 5px 10px;
    }
    .search-bar button {
      height: 38px;
    }
    .table thead th {
      background-color: orange;
      color: #fff;
      white-space: nowrap;
    }
    .btn-approve {
      background-color: #007bff;
      color: #fff;
      border: none;
      padding: 5px 10px;
      cursor: pointer;
      border-radius: 4px;
    }
    .btn-detail {
      background-color: #ffc107;
      color: #212529;
      border: none;
      padding: 5px 10px;
      cursor: pointer;
      border-radius: 4px;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- 이미 include 한 top_nav, nav 내용이 적용되었다고 가정 -->

  <div class="content-wrapper">
    <section class="content pt-3">
      <div class="container-fluid">
        
        <!-- [1] 검색바 -->
        <div class="search-bar">
          <select id="sortType" class="form-control" style="width:auto;">
            <option value="recent">최근 가입순</option>
            <option value="coin">코인 보유순</option>
            <option value="name">가나다순</option>
          </select>
          <select id="rowsPerPage" class="form-control" style="width:auto;">
            <option value="10">10개씩 정렬</option>
            <option value="20">20개씩 정렬</option>
            <option value="30">30개씩 정렬</option>
          </select>
          <input type="text" id="searchInput" class="form-control" placeholder="이름 / 아이디" style="width:200px;">
          <button id="searchBtn" class="btn btn-primary">검색</button>
        </div>

        <!-- [2] 회원 목록 테이블 -->
        <div class="table-responsive">
          <table class="table table-bordered table-hover" id="userTable">
            <thead>
              <tr>
                <th style="width:50px;">No.</th>
                <th>가입일</th>
                <th>아이디</th>
                <th>이름</th>
                <th>전화번호</th>
                <th>메일</th>
                <th>코인</th>
                <th>상태</th>
                <th>상세정보</th>
              </tr>
            </thead>
            <tbody><!-- AJAX로 불러온 내용이 들어갈 예정 --></tbody>
          </table>
        </div>

        <div id="pagination" class="mt-2"></div>
      </div><!-- /.container-fluid -->
    </section>
  </div><!-- /.content-wrapper -->

  <!-- ============ [3] 회원 상세정보 모달 ============ -->
  <div class="modal fade" id="userDetailModal" tabindex="-1" role="dialog"
       aria-labelledby="userDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <!-- 모달 내용은 AJAX로 로드하여 #userDetailContent에 삽입 -->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">회원 상세정보</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body" id="userDetailContent">
          <!-- user_detail.php 내용이 여기로 삽입됨 (회원 상세 + 코인입금 버튼/팝업) -->
        </div>
      </div>
    </div>
  </div>

  <!-- (선택) footer -->
  <?php require_once dirname(__DIR__) . '/../master/frames/footer.php'; ?>
</div><!-- /.wrapper -->


<script>
$(document).ready(function() {
  // 검색 버튼 클릭
  $('#searchBtn').on('click', function() {
    loadUserList(1);
  });
  // 셀렉트박스 변경 시에도 재로딩
  $('#sortType, #rowsPerPage').on('change', function() {
    loadUserList(1);
  });
  // 엔터키 검색
  $('#searchInput').on('keyup', function(e) {
    if(e.key === 'Enter') loadUserList(1);
  });

  // 페이지 최초 로드
  loadUserList(1);
});

// [A] 회원 목록 불러오기
function loadUserList(page) {
  let sortType = $('#sortType').val();
  let rowsPerPage = $('#rowsPerPage').val();
  let searchText = $('#searchInput').val().trim();

  $.ajax({
    url: '/master/manage_user/user_list_ajax.php', // 예시
    method: 'GET',
    data: {
      page: page,
      sort: sortType,
      limit: rowsPerPage,
      search: searchText
    },
    dataType: 'json',
    success: function(res) {
      if (res.success) {
        renderUserTable(res.data.users, res.data.startIndex);
        renderPagination(res.data.totalPages, page);
      } else {
        alert('목록 로딩 실패: ' + res.message);
      }
    },
    error: function(err) {
      console.error(err);
      alert('목록 로딩 중 오류가 발생했습니다.');
    }
  });
}

// [B] 테이블 렌더링
function renderUserTable(users, startIndex) {
  let tbody = $('#userTable tbody');
  tbody.empty();

  if(!users || users.length === 0) {
    tbody.append('<tr><td colspan="9" style="text-align:center;">데이터가 없습니다.</td></tr>');
    return;
  }

  users.forEach(function(user, i) {
    let no = startIndex + i;
    let approved = parseInt(user.approved) === 1;
    let statusHtml = approved ? '<span style="color:black;">승인</span>'
                              : `<button class="btn-approve">승인</button>`;
    let createdDate = (user.created_at || '').substring(0,10);

    let rowHtml = `
      <tr>
        <td>${no}</td>
        <td>${createdDate}</td>
        <td>${user.mb_id}</td>
        <td>${user.mb_name}</td>
        <td>${user.mb_tel || ''}</td>
        <td>${user.mb_email || ''}</td>
        <td>${user.coin_balance || 0}</td>
        <td>${statusHtml}</td>
        <td>
          <button class="btn-detail" onclick="openDetailModal(${user.mb_id})">상세</button>
        </td>
      </tr>
    `;
    tbody.append(rowHtml);
  });
}

// [C] 페이지네이션
function renderPagination(totalPages, currentPage) {
  let pagDiv = $('#pagination');
  pagDiv.empty();
  if(totalPages <= 1) return;

  for(let i=1; i<=totalPages; i++){
    let cls = (i===currentPage) ? 'btn-primary' : 'btn-outline-primary';
    pagDiv.append(`
      <button class="btn ${cls} btn-sm mr-1" onclick="loadUserList(${i})">
        ${i}
      </button>
    `);
  }
}

// ----------------------------------------------
// [D] 상세 모달 열기 -> user_detail.php 로 AJAX
// ----------------------------------------------
function openDetailModal(userId) {
  $('#userDetailContent').html('로딩중...');
  $.ajax({
    url: 'user_detail.php', // 같은 디렉토리라 가정
    method: 'GET',
    data: { user_id: userId },
    dataType: 'html',
    success: function(html) {
      $('#userDetailContent').html(html);
      $('#userDetailModal').modal('show');
    },
    error: function(err) {
      console.error(err);
      alert('상세정보 로딩 중 오류');
    }
  });
}
</script>

</body>
</html>
<?php $conn->close(); ?>