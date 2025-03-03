const sidebar = document.getElementById('sidebar');
const content = document.getElementById('content');
const toggleBtn = document.getElementById('toggleBtn');

toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
    if (window.innerWidth > 768) {
        content.classList.toggle('expanded');
    }
});

function searchTable(input, tableId) {
    const filter = input.value.toLowerCase();
    const table = document.getElementById(tableId);
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        let match = false;
        for (let j = 0; j < cells.length; j++) {
            if (cells[j]) {
                if (cells[j].innerText.toLowerCase().indexOf(filter) > -1) {
                    match = true;
                    break;
                }
            }
        }
        rows[i].style.display = match ? '' : 'none';
    }
}

function sortTable(columnIndex, tableId) {
    const table = document.getElementById(tableId);
    const rows = Array.from(table.rows).slice(1);
    const isAscending = table.getAttribute('data-sort-order') === 'asc';
    table.setAttribute('data-sort-order', isAscending ? 'desc' : 'asc');

    rows.sort((a, b) => {
        const cellA = a.cells[columnIndex].innerText;
        const cellB = b.cells[columnIndex].innerText;
        return isAscending ? cellA.localeCompare(cellB) : cellB.localeCompare(cellA);
    });

    rows.forEach(row => table.appendChild(row));
}

function paginateTable(tableId, paginationId, rowsPerPage = 5) {
    const table = document.getElementById(tableId);
    const pagination = document.getElementById(paginationId);
    const rows = Array.from(table.rows).slice(1);
    const pageCount = Math.ceil(rows.length / rowsPerPage);
    let currentPage = 1;

    function renderTable() {
        rows.forEach((row, index) => {
            row.style.display = (index >= (currentPage - 1) * rowsPerPage && index < currentPage * rowsPerPage) ? '' : 'none';
        });
    }

    function renderPagination() {
        pagination.innerHTML = '';
        for (let i = 1; i <= pageCount; i++) {
            const button = document.createElement('button');
            button.innerText = i;
            button.className = i === currentPage ? 'disabled' : '';
            button.onclick = () => {
                currentPage = i;
                renderTable();
                renderPagination();
            };
            pagination.appendChild(button);
        }
    }

    renderTable();
    renderPagination();
}

document.addEventListener('DOMContentLoaded', () => {
    paginateTable('branches-table', 'branches-pagination');
    paginateTable('accounts-table', 'accounts-pagination');
    paginateTable('categories-table', 'categories-pagination');
});

document.querySelectorAll('select').forEach(select => {
    select.addEventListener('click', function () {
        if (this.classList.contains('open')) {
            this.classList.remove('open');
            this.style.backgroundImage = "url('data:image/svg+xml;charset=UTF-8,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 4 5\"><path fill=\"%23007bff\" d=\"M2 0L0 2h4z\"/></svg>')";
        } else {
            this.classList.add('open');
            this.style.backgroundImage = "url('data:image/svg+xml;charset=UTF-8,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 4 5\"><path fill=\"%23007bff\" d=\"M2 5L0 3h4z\"/></svg>')";
        }
    });
});

var today = new Date();
        var dateField = document.getElementById('date');
        dateField.value = today.toISOString().split('T')[0];
