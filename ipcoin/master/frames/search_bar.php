<?php
require_once dirname(__DIR__) . '/../config.php'; // 데이터베이스 연결

// 사용자 정보 가져오기
$query = "
    SELECT 
        u.id, u.mb_id, u.mb_name, u.mb_email, u.mb_tel, u.grade, u.approved, 
        DATE(u.created_at) AS signup_date, 
        w.wallet_address, w.total_balance, w.available_balance, w.locked_balance, 
        u.last_login, u.status, u.mb_password, u.mb_tel
    FROM users u
    LEFT JOIN wallet w ON u.id = w.user_id
    ORDER BY u.id
";
$result = $conn->query($query);
$users = $result->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>

<!-- AdminLTE Card 스타일 적용 -->
<div class="card">
  <div class="card-header">
    <h3 class="card-title">사용자 관리</h3>
  </div>
  <div class="card-body">

    <div class="row mb-3">
      <div class="col-md-6 form-inline">
        <label for="rows-per-page" class="mr-2">페이지당 줄 수:</label>
        <input type="number" id="rows-per-page" class="form-control" min="1" value="10">
      </div>
      <div class="col-md-6 form-inline">
        <label for="search-input" class="mr-2">검색:</label>
        <input type="text" id="search-input" class="form-control" placeholder="검색어 입력">
        <button id="search-button" class="btn btn-primary ml-2">검색</button>
      </div>
    </div>

    <table id="user-table" class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>번호</th>
          <th>아이디</th>
          <th>이름</th>
          <th>이메일</th>
          <th>연락처</th>
          <th>등급</th>
          <th>가입일</th>
          <th>지갑주소</th>
          <th>총 잔액</th>
          <th>사용 가능 잔액</th>
          <th>잠금 잔액</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $index => $user): ?>
        <tr>
          <td><?= htmlspecialchars($index + 1) ?></td>
          <td><?= htmlspecialchars($user['mb_id']) ?></td>
          <td><?= htmlspecialchars($user['mb_name']) ?></td>
          <td><?= htmlspecialchars($user['mb_email']) ?></td>
          <td><?= htmlspecialchars($user['mb_tel']) ?></td>
          <td><?= htmlspecialchars($user['grade']) ?></td>
          <td><?= htmlspecialchars($user['signup_date']) ?></td>
          <td><?= htmlspecialchars($user['wallet_address'] ?? '없음') ?></td>
          <td><?= number_format($user['total_balance'] ?? 0, 8) ?></td>
          <td><?= number_format($user['available_balance'] ?? 0, 8) ?></td>
          <td><?= number_format($user['locked_balance'] ?? 0, 8) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="pagination"></div>
  </div>
</div>

<script>
const rowsPerPageInput = document.getElementById("rows-per-page");
const searchInput = document.getElementById("search-input");
const searchButton = document.getElementById("search-button");
const table = document.getElementById("user-table");
const tbody = table.querySelector("tbody");
const rows = Array.from(tbody.querySelectorAll("tr")); 
const pagination = document.querySelector(".pagination");

let rowsPerPage = parseInt(rowsPerPageInput.value, 10) || 10;
let currentPage = 1;
let filteredRows = rows;

// 행 표시 함수
function displayRows() {
    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;

    rows.forEach(row => (row.style.display = "none"));
    filteredRows.slice(start, end).forEach(row => (row.style.display = ""));
    updatePagination();
}

function updatePagination() {
    const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
    pagination.innerHTML = "";

    for (let i = 1; i <= totalPages; i++) {
        const button = document.createElement("button");
        button.textContent = i;
        button.className = "btn btn-sm btn-outline-primary mr-1";
        if (i === currentPage) {
            button.classList.add("active");
        }
        button.addEventListener("click", () => {
            currentPage = i;
            displayRows();
        });
        pagination.appendChild(button);
    }

    if (filteredRows.length === 0) {
        const noResults = document.createElement("span");
        noResults.textContent = "검색 결과가 없습니다.";
        noResults.style.color = "red";
        pagination.appendChild(noResults);
    }
}

function searchRows() {
    const searchTerm = searchInput.value.toLowerCase();
    filteredRows = rows.filter(row => {
        const columns = row.querySelectorAll("td");
        return Array.from(columns).some(column =>
            column.textContent.toLowerCase().includes(searchTerm)
        );
    });
    currentPage = 1;
    displayRows();
}

rowsPerPageInput.addEventListener("change", () => {
    rowsPerPage = parseInt(rowsPerPageInput.value, 10) || 10;
    currentPage = 1;
    displayRows();
});
searchButton.addEventListener("click", searchRows);

// 초기화
displayRows();
</script>