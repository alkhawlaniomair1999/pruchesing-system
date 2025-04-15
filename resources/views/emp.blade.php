@extends('include.app')
@section('title')
    الموظفين
@endsection

@section('page')
    الموظفين
@endsection
<style>
    .sBtn {
        background-color: #f44336;
        color: white;
        padding: 5px 20px;
        font-size: 1.3em;
        font-style: bold;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .sBtn:hover {
        background-color: #d32f2f;
    }
</style>

@section('main')
    @if (session('success'))
        <div class="alert alert-success" id="success-alert">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" id="error-alert">
            {{ session('error') }}
        </div>
    @endif

    <!-- Employee Form using the custom form container -->
    <div class="custom-form-container">
        <div class="custom-form-header">
            <!-- Toggle button to show/hide the form -->
            <button type="button" id="toggleButton" onclick="toggleForm()">-</button>
            <h2 class="custom-form-title">تهيئة الموظفين</h2>
            <p></p>
        </div>
        <form id="detailsForm" action="{{ route('emps.store') }}" method="POST">
            @csrf
            <div class="custom-form-fields">
                <div class="custom-form-group half-width">
                    <label for="name_emp">اسم الموظف</label>
                    <input type="text" id="name_emp" name="name_emp" placeholder="اسم الموظف" required>
                </div>
                <div class="custom-form-group third-width">
                    <label for="branch">الفرع</label>
                    <input type="text" id="branch" name="branch" placeholder="الفرع" required>
                </div>
                <div class="custom-form-group third-width">
                    <label for="country">الجنسية</label>
                    <input type="text" id="country" name="country" placeholder="الجنسية" required>
                </div>
                <div class="custom-form-group third-width">
                    <label for="salary">الراتب</label>
                    <input type="number" id="salary" name="salary" placeholder="الراتب" required>
                </div>
                <div class="custom-form-group third-width">
                    <label for="date_hirring">تاريخ التوظيف</label>
                    <input type="date" id="date_hirring" name="date_hirring" placeholder="تاريخ التوظيف" required>
                </div>
                <div class="custom-form-group">
                    <button type="submit">تأكيد</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Employee Data Table -->
    <div>
        <h2>جدول عرض بيانات الموظفين</h2>
        <button class="print_btn" onclick="window.location.href='{{ url('emps/print_report') }}'">طباعة<i
                class="fa-solid fa-print"></i></button>
        <button class="sBtn" onclick="confirmZeros('/emps/zeros')">تصفير الخصومات</button>
    </div>
    <input type="text" class="search-input" placeholder="بحث في الموظفين..."
        onkeyup="searchTable(this, 'employee-table')">
    <table id="employee-table">
        <thead>
            <tr>
                <th onclick="sortTable(0, 'employee-table')">رقم الموظف</th>
                <th onclick="sortTable(1, 'employee-table')">اسم الموظف</th>
                <th onclick="sortTable(2, 'employee-table')">الفرع</th>
                <th onclick="sortTable(3, 'employee-table')">الجنسية</th>
                <th onclick="sortTable(4, 'employee-table')">الراتب</th>
                <th onclick="sortTable(5, 'employee-table')">تاريخ التوظيف</th>
                <th>الاجراءات</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($emps) && !empty($emps))
                @foreach ($emps as $d)
                    @foreach ($disses as $dis)
                        @if ($dis->emp_id == $d->id)
                            <tr>
                                <td>{{ $d->id }}</td>
                                <td>{{ $d->name_emp }}</td>
                                <td>{{ $d->branch }}</td>
                                <td>{{ $d->country }}</td>
                                <td>{{ $d->salary }}</td>
                                <td>{{ $d->date_hirring }}</td>
                                <td style="display: none">{{ $dis->slf }}</td>
                                <td style="display: none">{{ $dis->absence }}</td>
                                <td style="display: none">{{ $dis->discount }}</td>
                                <td style="display: none">{{ $dis->bank }}</td>
                                <td class="action-buttons">
                                    <button class="edit-btn" onclick="openModal(this)">تعديل</button>
                                    <button class="delete-btn"
                                        onclick="confirmDelete({{ $d->id }},'/emps/destroy/')">حذف</button>
                                    <button class="edit-btn" onclick="openModal2(this)">خصم</button>
                                    <button class="btn btn-success"
                                        onclick="window.location.href='{{ route('emps.print', $d->id) }}'">طباعه</button>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @endforeach
            @else
                <tr>
                    <td colspan="10">لا توجد بيانات لعرضها</td>
                </tr>
            @endif
        </tbody>
    </table>
    <div class="pagination" id="employee-pagination"></div>
    <!-- Modal for Editing Employee Data -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>تعديل بيانات الموظف</h2>
            <form id="editForm" action="{{ route('emps.update') }}" method="POST">
                @csrf
                <div class="custom-form-fields">
                    <input type="hidden" id="editEmpId" name="id">
                    <div class="custom-form-group full-width">
                        <label for="editNameEmp">اسم الموظف:</label>
                        <input type="text" id="editNameEmp" name="name_emp" required>
                    </div>
                    <div class="custom-form-group half-width">
                        <label for="editBranch">الفرع:</label>
                        <input type="text" id="editBranch" name="branch" required>
                    </div>
                    <div class="custom-form-group half-width">
                        <label for="editCountry">الجنسية:</label>
                        <input type="text" id="editCountry" name="country" required>
                    </div>
                    <div class="custom-form-group third-width">
                        <label for="editSalary">الراتب:</label>
                        <input type="number" id="editSalary" name="salary" required>
                    </div>
                    <div class="custom-form-group third-width">
                        <label for="editDateHirring">تاريخ التوظيف:</label>
                        <input type="date" id="editDateHirring" name="date_hirring" required>
                    </div>
                </div>
                <div class="custom-form-group">
                    <button type="submit">حفظ التعديلات</button>
                </div>
            </form>
        </div>
    </div>
    <div id="editModal2" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal2()">&times;</span>
            <h2>تعديل بيانات الموظف</h2>
            <form id="editForm" action="{{ route('emps.dis') }}" method="POST">
                @csrf
                <div class="custom-form-fields">
                    <input type="hidden" id="id" name="id">
                    <div class="custom-form-group full-width">
                        <label for="name">اسم الموظف:</label>
                        <input type="text" id="name" name="name_emp" disabled>
                    </div>
                    <div class="custom-form-group half-width">
                        <label for="editslf">سلفه:</label>
                        <input type="float" id="editslf" name="slf" required>
                    </div>
                    <div class="custom-form-group half-width">
                        <label for="editabsence">غياب:</label>
                        <input type="float" id="editabsence" name="absence" required>
                    </div>
                    <div class="custom-form-group half-width">
                        <label for="editdiscount">خصم:</label>
                        <input type="float" id="editdiscount" name="discount" required>
                    </div>
                    <div class="custom-form-group half-width">
                        <label for="editbank">مسلم صرافة:</label>
                        <input type="float" id="editbank" name="bank" required>
                    </div>
                </div>
                <div class="custom-form-group">
                    <button type="submit">حفظ التعديلات</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        // Toggle the display of the employee form
        function toggleForm() {
            var formContainer = document.querySelector('.custom-form-container');
            var toggleButton = document.getElementById('toggleButton');
            if (formContainer.style.display === 'none' || formContainer.style.display === '') {
                formContainer.style.display = 'block';
                toggleButton.innerText = '-';
            } else {
                formContainer.style.display = 'none';
                toggleButton.innerText = '+';
            }
        }

        // Search the table based on user input
        function searchTable(input, tableId) {
            var filter = input.value.toUpperCase();
            var table = document.getElementById(tableId);
            var tr = table.getElementsByTagName("tr");
            for (var i = 1; i < tr.length; i++) {
                var visible = false;
                var tds = tr[i].getElementsByTagName("td");
                for (var j = 0; j < tds.length; j++) {
                    if (tds[j] && tds[j].innerText.toUpperCase().indexOf(filter) > -1) {
                        visible = true;
                        break;
                    }
                }
                tr[i].style.display = visible ? "" : "none";
            }
        }

        // Sort table rows based on a chosen column
        function sortTable(n, tableId) {
            var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            table = document.getElementById(tableId);
            switching = true;
            // Set initial sorting direction to ascending:
            dir = "asc";
            while (switching) {
                switching = false;
                rows = table.rows;
                // Loop through all rows (skip the header row)
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("td")[n];
                    y = rows[i + 1].getElementsByTagName("td")[n];
                    if (dir === "asc") {
                        if (x.innerText.toLowerCase() > y.innerText.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir === "desc") {
                        if (x.innerText.toLowerCase() < y.innerText.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    switchcount++;
                } else {
                    // If no switching occurred and the direction is "asc", set it to "desc" and run again.
                    if (switchcount === 0 && dir === "asc") {
                        dir = "desc";
                        switching = true;
                    }
                }
            }
        }

        // --- Modal Functions for Editing ---

        // Open the editing modal and populate fields from the selected row.
        function openModal(button) {
            var currentRow = button.parentElement.parentElement;
            var cells = currentRow.getElementsByTagName('td');
            // Assuming the order:
            // 0: id_emp, 1: name_emp, 2: branch, 3: country, 4: salary, 5: date_hirring
            document.getElementById('editEmpId').value = cells[0].innerText.trim();
            document.getElementById('editNameEmp').value = cells[1].innerText.trim();
            document.getElementById('editBranch').value = cells[2].innerText.trim();
            document.getElementById('editCountry').value = cells[3].innerText.trim();
            document.getElementById('editSalary').value = cells[4].innerText.trim();
            document.getElementById('editDateHirring').value = cells[5].innerText.trim();
            document.getElementById('editModal').style.display = 'block';
        }

        function openModal2(button) {
            var currentRow = button.parentElement.parentElement;
            var cells = currentRow.getElementsByTagName('td');
            // Assuming the order:
            document.getElementById('id').value = cells[0].innerText.trim();
            document.getElementById('name').value = cells[1].innerText.trim();
            document.getElementById('editslf').value = cells[6].innerText.trim();
            document.getElementById('editabsence').value = cells[7].innerText.trim();
            document.getElementById('editdiscount').value = cells[8].innerText.trim();
            document.getElementById('editbank').value = cells[9].innerText.trim();
            document.getElementById('editModal2').style.display = 'block';
        }

        // Close the editing modal.
        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        function closeModal2() {
            document.getElementById('editModal2').style.display = 'none';
        }

        // Confirm deletion of an employee record.
        // The 'urlBase' parameter should be a URL string ending with a slash,
        // so that appending the employee ID produces the correct route.
        function confirmDelete(id, urlBase) {
            if (confirm('هل أنت متأكد أنك تريد حذف هذا السجل؟')) {
                window.location.href = urlBase + id;
            }
        }

        function confirmZeros(urlBase) {
            if (confirm('هل أنت متأكد أنك تريد تصفير جميع الخصومات')) {
                window.location.href = urlBase;
            }
        }
    </script>
@endsection
