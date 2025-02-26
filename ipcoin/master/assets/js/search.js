const rowsPerPageInput = document.getElementById("rows-per-page");
const searchInput = document.getElementById("search-input");
const searchButton = document.getElementById("search-button");
const table = document.getElementById("user-table");
const tbody = table.querySelector("tbody");
const rows = Array.from(tbody.querySelectorAll("tr")); // Array로 변환
const pagination = document.querySelector(".pagination");

let rowsPerPage = parseInt(rowsPerPageInput.value, 10) || 10;
let currentPage = 1;
let filteredRows = rows; // 검색 결과를 저장

function displayRows() {
    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;

    // 모든 행 숨기기
    rows.forEach(row => (row.style.display = "none"));

    // 현재 페이지의 행만 표시
    filteredRows.slice(start, end).forEach(row => (row.style.display = ""));
    updatePagination();
}

function updatePagination() {
    const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
    pagination.innerHTML = "";

    for (let i = 1; i <= totalPages; i++) {
        const button = document.createElement("button");
        button.textContent = i;
        button.addEventListener("click", () => {
            currentPage = i;
            displayRows();
        });

        if (i === currentPage) {
            button.style.fontWeight = "bold";
            button.style.backgroundColor = "#007bff";
            button.style.color = "#fff";
        }

        pagination.appendChild(button);
    }

    // 총 페이지가 0일 경우 안내 메시지 추가
    if (totalPages === 0) {
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

    // 검색 후 첫 번째 페이지로 이동
    currentPage = 1;
    displayRows();
}

rowsPerPageInput.addEventListener("change", () => {
    rowsPerPage = parseInt(rowsPerPageInput.value, 10) || 10;
    currentPage = 1;
    displayRows();
});

searchButton.addEventListener("click", searchRows);

// 초기 표시
displayRows();
