<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>사용자 목록</title>
  <!-- AdminLTE CSS / Bootstrap / FontAwesome 예시 CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" />
  <!-- 기존 main.css -->
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/master/assets/css/main.css">
</head>
<body class="hold-transition sidebar-mini">

<div class="card">
  <div class="card-header">
    <h3 class="card-title">사용자 목록 (temp.php 예시)</h3>
  </div>
  <div class="card-body">

    <div class="row mb-3">
      <div class="col-md-6 form-inline">
        <label for="rows-per-page" class="mr-2">페이지당 줄 수:</label>
        <input type="number" id="rows-per-page" class="form-control" min="1" value="10">
      </div>
      <div class="col-md-6 form-inline">
        <label for="search-input" class="mr-2">아이디/닉네임 검색:</label>
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
      <tbody></tbody>
    </table>
    <div id="pagination-container" class="pagination"></div>

  </div>
</div>

<!-- AdminLTE & jQuery JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<script type="module">
    // 예시 fetch -> pagination
    // fetch('/path/to/data_fetch.php')
    //     .then(response => response.json())
    //     .then(data => { ... });

    // 여기서는 가짜 데이터로 예시
    const data = [
      {mb_id:'test1', mb_name:'홍길동', mb_email:'hong@test.com', mb_tel:'010-xxxx-xxxx', grade:'1', signup_date:'2025-01-01', wallet_address:'0x1234', total_balance:100.1234, available_balance:90.12, locked_balance:10.0},
      {mb_id:'test2', mb_name:'김철수', mb_email:'kim@test.com', mb_tel:'010-xxxx-xxxx', grade:'2', signup_date:'2025-01-02', wallet_address:'0x5678', total_balance:50.1111, available_balance:45.11, locked_balance:5.0},
      // ...
    ];
    const tbody = document.querySelector("#user-table tbody");

    tbody.innerHTML = data.map((user, index) => `
      <tr>
        <td>${index + 1}</td>
        <td>${user.mb_id}</td>
        <td>${user.mb_name}</td>
        <td>${user.mb_email}</td>
        <td>${user.mb_tel}</td>
        <td>${user.grade}</td>
        <td>${user.signup_date}</td>
        <td>${user.wallet_address || '없음'}</td>
        <td>${parseFloat(user.total_balance || 0).toFixed(8)}</td>
        <td>${parseFloat(user.available_balance || 0).toFixed(8)}</td>
        <td>${parseFloat(user.locked_balance || 0).toFixed(8)}</td>
      </tr>
    `).join('');

    // 간단한 페이지네이션 구현 예시
    const rows = Array.from(document.querySelectorAll("#user-table tbody tr"));
    const rowsPerPageInput = document.getElementById("rows-per-page");
    const searchInput = document.getElementById("search-input");
    const searchButton = document.getElementById("search-button");
    const pagination = document.getElementById("pagination-container");

    let rowsPerPage = parseInt(rowsPerPageInput.value, 10) || 10;
    let currentPage = 1;
    let filteredRows = rows;

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
        const btn = document.createElement('button');
        btn.textContent = i;
        btn.className = 'btn btn-sm btn-outline-primary mr-1';
        if(i === currentPage) {
          btn.classList.add('active');
        }
        btn.addEventListener('click', () => {
          currentPage = i;
          displayRows();
        });
        pagination.appendChild(btn);
      }

      if(filteredRows.length === 0) {
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
          return Array.from(columns).some(col => col.textContent.toLowerCase().includes(searchTerm));
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

    displayRows();
</script>
</body>
</html>