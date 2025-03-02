<?php
// 페이지 시작: header.php 불러오기
require_once __DIR__ . '/headers.php/';
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>코인 락업 관리</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        .actions {
            margin-bottom: 20px;
        }
        .rocktButton {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }
        .rocktButton:hover {
            background-color: #45a049;
        }
        .search-bar {
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        .input-box {
            padding: 5px;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .pagination {
            margin-top: 10px;
            text-align: center;
        }
        .pagination button {
            padding: 5px 10px;
            margin: 2px;
        }
        /* 카드 스타일 */
        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin: 20px auto;
            max-width: 1200px;
            background-color: #fff;
        }
        .card-header {
            background-color: #f7f7f7;
            padding: 1rem;
            font-weight: bold;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        .card-body {
            padding: 1rem;
        }
    </style>
    <script>
        // 새 창에서 락업 폼을 열도록 하는 함수
        function openLockupForm() {
            window.open('/master/others/lockup_form.php', 'lockupForm', 'width=600,height=600,resizable=yes,scrollbars=yes');
        }
        // 새 창에서 락업 해제 폼을 열도록 하는 함수
        function openUnlockForm2() {
            window.open('/master/others/unlock_form.php', 'unlockForm', 'width=600,height=600,resizable=yes,scrollbars=yes');
        }
    </script>
</head>
<body>
<?php
require_once dirname(__DIR__) . '/frames/nav.php';
require_once dirname(__DIR__) . '/frames/top_nav.php';
?>
<div class="container-fluid mt-5">
  <div class="card">
    <div class="card-header">사용자 목록</div>
    <div class="card-body">
      <div class="actions">
          <section style="margin-bottom: 10px;">
              <input class="period_box" id="lockupPeriodInput" type="number" placeholder="기간을 입력 (일 단위)">
              <button class="rocktButton" type="button" id="updateLockupButton">전체 락업기간 변경</button>
          </section>
          <section style="margin-bottom: 10px;">
              <span>유저 코인 락업 :</span>
              <button class="rocktButton" type="button" onclick="openLockupForm()">락업하기</button>
          </section>
          <section style="margin-bottom: 10px;">
              <span>유저 코인 락업 해제:</span>
              <button class="rocktButton" type="button" onclick="openUnlockForm2()">락업 해제</button>
          </section>
      </div>
      
      <div class="search-bar" style="display:flex; gap:1rem; flex-wrap: wrap;">
          <div>
              <label for="rows-per-page">페이지당 줄 수:</label>
              <input type="number" id="rows-per-page" class="input-box" min="1" value="10">
          </div>
          <div>
              <label for="search-input">아이디/닉네임 검색:</label>
              <input type="text" id="search-input" class="input-box" placeholder="검색어 입력">
              <button id="search-button" class="rocktButton">검색</button>
          </div>
      </div>
      
      <div class="lockup_user">
          <table>
              <thead>
                  <tr>
                      <th>번호</th>
                      <th>상위관리자</th>
                      <th>아이디</th>
                      <th>닉네임</th>
                      <th>이메일</th>
                      <th>연락처</th>
                      <th>지갑주소</th>
                      <th>토탈금액</th>
                      <th>락업금액</th>
                      <th>출금가능금액</th>
                      <th>생성일</th>
                  </tr>
              </thead>
              <tbody id="userTableBody">
                  <!-- 데이터가 여기에 동적으로 들어옵니다 -->
              </tbody>
          </table>
          <div id="pagination-container" class="pagination"></div>
      </div>
      
      <script>
          document.addEventListener("DOMContentLoaded", () => {
              let allUsers = [];
              let filteredUsers = [];
              let rowsPerPage = 10;

              const tableBody = document.getElementById("userTableBody");
              const searchInput = document.getElementById("search-input");
              const rowsPerPageInput = document.getElementById("rows-per-page");
              const paginationContainer = document.getElementById("pagination-container");

              // Fetch user data
              fetch('/master/manage_user/user_list.php')
                  .then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          allUsers = data.data; // 데이터 배열
                          filteredUsers = allUsers;
                          renderTable(1);
                          setupPagination();
                      } else {
                          console.error("Error fetching users:", data.message);
                      }
                  })
                  .catch(error => console.error("Error fetching users:", error));

              // Render table data
              function renderTable(page) {
                  const start = (page - 1) * rowsPerPage;
                  const end = start + rowsPerPage;
                  tableBody.innerHTML = filteredUsers.slice(start, end).map((user, index) => `
                      <tr>
                          <td>${start + index + 1}</td>
                          <td>${user.manager_name || 'N/A'}</td>
                          <td>${user.user_id_name}</td>
                          <td>${user.nickname || 'N/A'}</td>
                          <td>${user.email || 'N/A'}</td>
                          <td>${user.phone || 'N/A'}</td>
                          <td>${user.wallet_address || 'N/A'}</td>
                          <td>${Number(user.total_balance).toLocaleString()} RAY</td>
                          <td>${Number(user.locked_balance).toLocaleString()} RAY</td>
                          <td>${Number(user.withdrawable_balance).toLocaleString()} RAY</td>
                          <td>${user.created_at}</td>
                      </tr>
                  `).join('');
              }

              // Setup pagination
              function setupPagination() {
                  const totalPages = Math.ceil(filteredUsers.length / rowsPerPage);
                  paginationContainer.innerHTML = '';
                  for (let i = 1; i <= totalPages; i++) {
                      const button = document.createElement('button');
                      button.textContent = i;
                      button.addEventListener('click', () => renderTable(i));
                      paginationContainer.appendChild(button);
                  }
              }

              // Search functionality
              document.getElementById("search-button").addEventListener("click", () => {
                  const searchTerm = searchInput.value.toLowerCase();
                  filteredUsers = allUsers.filter(user =>
                      user.user_id_name.toLowerCase().includes(searchTerm) ||
                      (user.nickname && user.nickname.toLowerCase().includes(searchTerm))
                  );
                  renderTable(1);
                  setupPagination();
              });

              // Rows per page functionality
              rowsPerPageInput.addEventListener("change", () => {
                  rowsPerPage = parseInt(rowsPerPageInput.value, 10) || 10;
                  renderTable(1);
                  setupPagination();
              });
          });
      </script>
    </div><!-- card-body -->
  </div><!-- card -->
</div><!-- container-fluid -->

<?php
// 페이지 끝: footer.php 불러오기
require_once __DIR__ . '/frames/footer.php';
?>
</body>
</html>
