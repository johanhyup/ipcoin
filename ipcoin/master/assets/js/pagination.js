export default class Pagination {
    constructor(tableId, rowsPerPageInputId, searchInputId, searchButtonId, paginationContainerId) {
        this.table = document.getElementById(tableId);
        this.tbody = this.table.querySelector("tbody");
        this.rows = Array.from(this.tbody.querySelectorAll("tr"));
        this.rowsPerPageInput = document.getElementById(rowsPerPageInputId);
        this.searchInput = document.getElementById(searchInputId);
        this.searchButton = document.getElementById(searchButtonId);
        this.pagination = document.getElementById(paginationContainerId);

        this.rowsPerPage = parseInt(this.rowsPerPageInput.value, 10) || 10;
        this.currentPage = 1;
        this.filteredRows = this.rows; // 초기에는 전체 행 사용

        this.init();
    }

    init() {
        this.addEventListeners();
        this.displayRows();
    }

    addEventListeners() {
        this.rowsPerPageInput.addEventListener("change", () => {
            this.rowsPerPage = parseInt(this.rowsPerPageInput.value, 10) || 10;
            this.currentPage = 1;
            this.displayRows();
        });

        this.searchButton.addEventListener("click", () => this.searchRows());
    }

    displayRows() {
        const start = (this.currentPage - 1) * this.rowsPerPage;
        const end = start + this.rowsPerPage;

        this.rows.forEach(row => (row.style.display = "none"));
        this.filteredRows.slice(start, end).forEach(row => (row.style.display = ""));
        this.updatePagination();
    }

    updatePagination() {
        const totalPages = Math.ceil(this.filteredRows.length / this.rowsPerPage);
        this.pagination.innerHTML = "";

        for (let i = 1; i <= totalPages; i++) {
            const button = document.createElement("button");
            button.textContent = i;
            button.addEventListener("click", () => {
                this.currentPage = i;
                this.displayRows();
            });

            if (i === this.currentPage) {
                button.style.fontWeight = "bold";
                button.style.backgroundColor = "#007bff";
                button.style.color = "#fff";
            }

            this.pagination.appendChild(button);
        }

        if (this.filteredRows.length === 0) {
            const noResults = document.createElement("span");
            noResults.textContent = "검색 결과가 없습니다.";
            noResults.style.color = "red";
            this.pagination.appendChild(noResults);
        }
    }

    searchRows() {
        const searchTerm = this.searchInput.value.toLowerCase();
        this.filteredRows = this.rows.filter(row => {
            const columns = row.querySelectorAll("td");
            return Array.from(columns).some(column =>
                column.textContent.toLowerCase().includes(searchTerm)
            );
        });

        this.currentPage = 1;
        this.displayRows();
    }
}
