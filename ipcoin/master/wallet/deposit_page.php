<?php
// filepath: /Users/joko/ipcoin/ipcoin/master/wallet/deposit_page.php
session_start();
require_once dirname(__DIR__) . '/../config.php'; // DB 연결

// 승인/거절 처리 (POST 요청 시)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $approved = isset($_POST['approved']) ? intval($_POST['approved']) : 0;

    if ($user_id > 0) {
        $stmt = $conn->prepare("UPDATE users SET approved = ? WHERE id = ?");
        $stmt->bind_param("ii", $approved, $user_id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: deposit_manage.php");
    exit;
}
?>
<?php require_once dirname(__FILE__) . '/../frames/header.php'; ?>
<?php require_once dirname(__FILE__) . '/../frames/sidebar.php'; ?>


    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- 코인 전체 락업기간 변경 -->
        <div class="card mb-3">
          <div class="card-body">
            <div class="actions">
              <label for="lockupPeriodInput">락업</label>
              <input class="input-box" id="lockupPeriodInput" type="number" placeholder="기간을 입력 (일 단위)">
              <button class="rocktButton btn btn-primary" type="button" id="updateLockupButton">전체 락업기간 변경</button>
            </div>
          </div>
        </div>

        <!-- 아이디 블락/승인 기능 -->
        <div class="card mb-3">
          <div class="card-body">
            <div class="actions">
              <label for="update-id">블락</label>
              <input type="text" id="update-id" class="input-box" placeholder="아이디 입력">
              <button class="approve-button btn btn-success" id="approve-button">승인</button>       
              <button class="block-button btn btn-danger" id="block-button">블락</button>
            </div>
          </div>
        </div>

        <!-- 비밀번호 초기화 -->
        <div class="card mb-3">
          <div class="card-body">
            <div class="actions">
              <label for="reset-id">비번</label>
              <input type="text" id="reset-id" class="input-box" placeholder="아이디 입력">
              <button class="edit-button2 btn btn-info" id="reset-button">비밀번호 초기화</button>
              <span id="reset-error" class="error"></span>
            </div>
          </div>
        </div>

        <!-- 검색바 및 회원 목록 -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">회원 목록</h3>
          </div>
          <div class="card-body">
            <!-- 검색바 -->
            <div class="search-bar mb-3">
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
              <button id="depositButton" class="btn btn-primary" onclick="openLockupForm()">코인 수동입금</button>
            </div>
            <!-- 회원 목록 테이블 -->
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
                    <th>입금</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- AJAX로 불러온 내용 -->
                </tbody>
              </table>
            </div>
            <!-- 페이지네이션 -->
            <div id="pagination" class="mt-2"></div>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<!-- 회원 상세정보 모달 -->
<div class="modal fade" id="userDetailModal" tabindex="-1" role="dialog" aria-labelledby="userDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userDetailModalLabel">회원 상세정보</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="userDetailContent">로딩중...</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
      </div>
    </div>
  </div>
</div>

<?php require_once dirname(__FILE__) . '/../frames/footer.php'; ?>

<!-- 스크립트 -->
<script type="module">
  import Pagination from '/master/assets/js/pagination.js';

  // 블락 기능
  document.getElementById('block-button').addEventListener('click', () => {
      const mbId = document.getElementById('update-id').value.trim();
      if (!mbId) {
          alert('아이디를 입력해주세요.');
          return;
      }
      updateApprovalStatus(mbId, 0);
  });

  // 승인 기능
  document.getElementById('approve-button').addEventListener('click', () => {
      const mbId = document.getElementById('update-id').value.trim();
      if (!mbId) {
          alert('아이디를 입력해주세요.');
          return;
      }
      updateApprovalStatus(mbId, 1);
  });

  // 입금 버튼 클릭 이벤트 (입금 폼 새 창)
  document.addEventListener('click', (event) => {
      if (event.target.classList.contains('edit-button')) {
          const userId = event.target.dataset.userId;
          if (userId) {
              const url = `/master/wallet/deposit_form.php?user_id=${userId}`;
              window.open(url, '_blank', 'width=600,height=700');
          } else {
              alert('유효하지 않은 사용자입니다.');
          }
      }
  });

  // 비밀번호 초기화
  document.getElementById('reset-button').addEventListener('click', () => {
      const mbId = document.getElementById('reset-id').value.trim();
      const errorSpan = document.getElementById('reset-error');
      if (!mbId) {
          errorSpan.textContent = '아이디를 입력해주세요.';
          return;
      }
      errorSpan.textContent = '';
      fetch('/master/manage_user/reset_password.php', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `mb_id=${mbId}`,
      })
      .then(response => response.json())
      .then(result => {
          if (result.success) {
              alert('1111로 비밀번호가 초기화되었습니다.');
          } else {
              alert(result.message || '비밀번호 초기화에 실패했습니다.');
          }
      })
      .catch(error => {
          console.error('Error resetting password:', error);
          alert('비밀번호 초기화 중 오류가 발생했습니다.');
      });
  });

  // AJAX를 통한 회원 목록 로드 및 렌더링
  function loadUserList(page) {
      let sortType = $('#sortType').val();
      let rowsPerPage = $('#rowsPerPage').val();
      let searchText = $('#searchInput').val().trim();

      $.ajax({
          url: '/master/manage_user/user_list_ajax.php',
          method: 'GET',
          data: {
              page: page,
              sort: sortType,
              limit: rowsPerPage,
              search: searchText
          },
          dataType: 'json',
          success: function(res) {
              if(res.success) {
                  renderUserTable(res.data.users, res.data.startIndex);
                  renderPagination(res.data.totalPages, page);
              } else {
                  alert('회원 목록을 불러오지 못했습니다: ' + res.message);
              }
          },
          error: function(err) {
              console.error(err);
              alert('회원 목록 로드 중 오류가 발생했습니다.');
          }
      });
  }

  // 회원 테이블 렌더링
  function renderUserTable(users, startIndex) {
      let tbody = $('#userTable tbody');
      tbody.empty();

      if(!users || users.length === 0) {
          tbody.append(`
              <tr>
                  <td colspan="9" style="text-align:center;">데이터가 없습니다.</td>
              </tr>
          `);
          return;
      }

      users.forEach(function(user, i) {
          let no = startIndex + i;
          let approved = parseInt(user.approved) === 1;
          let statusHtml = approved ? `<span style="color:black;">승인</span>` :
              `<button class="btn-approve btn btn-primary" onclick="approveUser(${user.id})">승인</button>`;
          let detailBtnHtml = `<button class="btn-detail btn btn-warning" onclick="openDetailModal(${user.id})">상세</button>`;
          let createdDate = (user.created_at) ? user.created_at.substring(0, 10) : '';
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
                      <button class="edit-button btn btn-info" data-user-id="${user.user_id}">입금</button>
                      ${detailBtnHtml}
                  </td>
              </tr>
          `;
          tbody.append(rowHtml);
      });
  }

  // 페이지네이션 렌더링
  function renderPagination(totalPages, currentPage) {
      let pagDiv = $('#pagination');
      pagDiv.empty();
      
      if(totalPages <= 1) return;
      for(let i=1; i <= totalPages; i++) {
          let btnClass = (i === currentPage) ? 'btn-primary' : 'btn-outline-primary';
          pagDiv.append(`
              <button class="btn ${btnClass} btn-sm mr-1" onclick="loadUserList(${i})">
                  ${i}
              </button>
          `);
      }
  }

  // 승인 처리 함수
  function approveUser(userId) {
      if(!confirm('승인 처리하시겠습니까?')) return;
  
      $.ajax({
          url: '/master/manage_user/approve_user.php',
          method: 'POST',
          data: { user_id: userId },
          dataType: 'json',
          success: function(res) {
              if(res.success) {
                  alert('승인되었습니다.');
                  loadUserList(1);
              } else {
                  alert('승인 실패: ' + res.message);
              }
          },
          error: function(err) {
              console.error(err);
              alert('승인 처리 중 오류가 발생했습니다.');
          }
      });
  }

  // 상세 모달 열기
  function openDetailModal(userId) {
      $('#userDetailContent').html('로딩중...');
      $.ajax({
          url: '/master/manage_user/user_detail.php',
          method: 'GET',
          data: { user_id: userId },
          dataType: 'html',
          success: function(html) {
              $('#userDetailContent').html(html);
              $('#userDetailModal').modal('show');
          },
          error: function(err) {
              console.error(err);
              alert('상세정보 불러오기 중 오류가 발생했습니다.');
          }
      });
  }

  // 새 창에서 코인 수동입금 폼 열기
  function openLockupForm() {
      window.open('/master/wallet/deposit_form.php', 'lockupForm', 'width=600,height=600,resizable=yes,scrollbars=yes');
  }

  // 초기 로드
  $(document).ready(function() {
      loadUserList(1);
      
      // 검색 및 정렬 이벤트 설정
      $('#searchBtn').on('click', function() {
          loadUserList(1);
      });
      $('#sortType, #rowsPerPage').on('change', function() {
          loadUserList(1);
      });
      $('#searchInput').on('keyup', function(e) {
          if(e.key === 'Enter') {
              loadUserList(1);
          }
      });
  });
</script>
</body>
</html>
<?php $conn->close(); ?>
