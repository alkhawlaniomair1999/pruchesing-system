<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')  </title>
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>

    @include('include.navbar')

    <main>
        @include('include.main')
        @include('include.saidebar')
    </main>

    @include('include.footer')

    <script>
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
    </script>

</body>

</html>
