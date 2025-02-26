<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>입금 관리</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="/assets/js/deposit.js" defer></script>
    <style>
    .actions {
        margin-bottom: 20px;
    }

    #depositButton {
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 5px;
        font-size: 16px;
    }

    #depositButton:hover {
        background-color: #45a049;
    }
</style>

<script>
    // 새 창에서 락업 폼을 열도록 하는 함수
    function openLockupForm() {
        window.open('/master/wallet/deposit_form.php', 'lockupForm', 'width=600,height=600,resizable=yes,scrollbars=yes');
    }

</script>
</head>
<body>
<?php
// 현재 파일의 디렉토리를 기준으로 frames/nav.php 포함
require_once dirname(__DIR__) . '/frames/nav.php';
require_once dirname(__DIR__) . '/frames/top_nav.php';
// 추가 코드 작성 가능

?>
<div class="main2">
    <div class="actions">
        <h1>입금 관리</h1>
  
    </div>

    <div class="search-bar">
        <div>
            <label for="rows-per-page">페이지당 줄 수:</label>
            <input type="number" id="rows-per-page" class="input-box" min="1" value="10">
        </div>
        <div>
            <label for="search-input">아이디/닉네임/코인 검색:</label>
            <input type="text" id="search-input" class="input-box" placeholder="검색어 입력">
            <button id="search-button">검색</button>
        </div>

        <div>
        <button id="depositButton" onclick="openLockupForm()">수동입금</button>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>번호</th>
                <th>아이디</th>
                <th>닉네임</th>
                <th>코인</th>
                <th>입금주소</th>
                <th>금액</th>
                <th>상태</th>
                <th>신청일</th>
                <th>승인</th>
 <!--               <th>수동입금</th>-->
            </tr>
        </thead>
        <tbody id="depositTableBody">
            <!-- 데이터가 여기에 동적으로 들어옵니다 -->
        </tbody>
    </table>
    <div id="pagination-container" class="pagination"></div>
</div>

<script type="module">
    import Pagination from '/master/assets/js/pagination.js';

    document.addEventListener("DOMContentLoaded", () => {
    let allData = [];
    let filteredData = [];
    let rowsPerPage = 10;

    const tableBody = document.getElementById("depositTableBody");
    const searchInput = document.getElementById("search-input");
    const rowsPerPageInput = document.getElementById("rows-per-page");
    const paginationContainer = document.getElementById("pagination-container");

    // Fetch deposit data
    fetch('/master/wallet/deposit_list.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                allData = data.deposits; // deposits 배열 사용
                filteredData = allData;
                renderTable(1);
                setupPagination();
            } else {
                console.error('Error fetching deposits:', data.message);
            }
        })
        .catch(error => console.error('Error fetching deposits:', error));

    // Render table data
    function renderTable(page) {
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        tableBody.innerHTML = filteredData.slice(start, end).map((deposit, index) => `
            <tr>
                <td>${start + index + 1}</td>
                <td>${deposit.user_id_name}</td>
                <td>${deposit.nickname}</td>
                <td>${deposit.coin_name}</td>
                <td>${deposit.deposit_address || '없음'}</td>
                <td>${parseFloat(deposit.amount || 0).toFixed(8)}</td>
                <td>${deposit.status}</td>
                <td>${deposit.created_at}</td>
                <td>
                    ${deposit.status === "pending"
                        ? `
                            <button class="approve-button" data-id="${deposit.deposit_id}">승인</button>
                            <button class="reject-button" data-id="${deposit.deposit_id}">거절</button>
                          `
                        : '완료'}
                </td>
            
            </tr>
        `).join('');
    }

    // Setup pagination
    function setupPagination() {
        const totalPages = Math.ceil(filteredData.length / rowsPerPage);
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
        filteredData = allData.filter(deposit =>
            deposit.user_id_name.toLowerCase().includes(searchTerm) ||
            deposit.nickname.toLowerCase().includes(searchTerm) ||
            deposit.coin_name.toLowerCase().includes(searchTerm)
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

    // Approve or reject deposit
    tableBody.addEventListener("click", (event) => {
        const target = event.target;
        if (target.classList.contains("approve-button") || target.classList.contains("reject-button")) {
            const depositId = target.dataset.id;
            const action = target.classList.contains("approve-button") ? "approve" : "reject";

            if (confirm(`해당 입금을 ${action === "approve" ? "승인" : "거절"}하시겠습니까?`)) {
                updateDepositStatus(depositId, action);
            }
        }
    });

    function updateDepositStatus(depositId, action) {
        fetch('/master/wallet/update_deposit_status.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ deposit_id: depositId, action })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`입금이 ${action === "approve" ? "승인" : "거절"}되었습니다.`);
                    fetchDepositList(); // 테이블 새로고침
                } else {
                    alert(`입금 ${action === "approve" ? "승인" : "거절"} 실패: ${data.message}`);
                }
            })
            .catch(error => console.error('Error updating deposit status:', error));
    }
});

</script>
</body>
</html>
