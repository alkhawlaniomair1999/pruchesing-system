@extends('include.app')
@section('title')
    المخزون
@endsection

@section('page')
    المخزون
@endsection
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
    <div class="custom-form-container">
        <div class="custom-form-header">
            <button type="button" id="toggleButton" onclick="toggleForm()">-</button>
            <h2 class="custom-form-title"> إضافة مخزون </h2>
            <p></p>
        </div>
        <form id="detailsForm" action="{{ route('inventory.store') }}" method="POST">
            @csrf
            <div class="custom-form-fields">
                <div class="custom-form-group fourth-width">
                    <label for="item">الصنف:</label>
                    <select id="item" name="item_id" required>
                        @if (isset($items))
                            @foreach ($items as $d)
                                <option value="{{ $d->id }}">{{ $d->item }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="custom-form-group fourth-width">
                    <label for="branch">الفرع:</label>
                    <select id="branch" name="branch_id" required>
                        @if (isset($branches))
                            @foreach ($branches as $b)
                                <option value="{{ $b->id }}">{{ $b->branch }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="custom-form-group fourth-width">
                    <label for="month">اختر الشهر:</label>
                    <select name="month" id="month">
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>
                                {{ $m }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="custom-form-group fourth-width">

                    <label for="year">اختر السنة:</label>
                    <select name="year" id="year">
                        @for ($y = 2023; $y <= now()->year; $y++)
                            <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="custom-form-group fourth-width">
                    <label for="first_inventory">رصيد اول المدة:</label>
                    <input type="number" id="first_inventory" name="first_inventory">
                </div>
                <div class="custom-form-group fourth-width">
                    <label for="last_inventory">رصيد اخر المدة:</label>
                    <input type="number" id="last_inventory" name="last_inventory">
                </div>
                <div class="custom-form-group fourth-width">
                    <label for="inventory_out"> المنصرف:</label>
                    <input type="number" id="inventory_out" name="inventory_out">
                </div>
            </div>
            <div class="custom-form-group">
                <button type="submit">إضافة</button>
            </div>
        </form>
    </div>
    <br><br>
    <h2>جدول عرض البيانات</h2>
    <input type="text" class="search-input" placeholder="بحث في المخزون ..."
        onkeyup="searchTable(this, 'invoice-table')">
    <table id="invoice-table">
        <thead>
            <tr>
                <th onclick="sortTable(0, 'invoice-table')">الرقم</th>
                <th onclick="sortTable(1, 'invoice-table')"> الصنف</th>
                <th onclick="sortTable(2, 'invoice-table')"> الفرع</th>
                <th onclick="sortTable(3, 'invoice-table')"> الشهر</th>
                <th onclick="sortTable(4, 'invoice-table')"> السنة</th>
                <th onclick="sortTable(5, 'invoice-table')">رصيد اول المدة</th>
                <th onclick="sortTable(6, 'invoice-table')">رصيد اخر المدة</th>
                <th onclick="sortTable(7, 'invoice-table')">المنصرف </th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($inventory))
                @foreach ($inventory as $i)
                    <tr>
                        <td>{{ $i->id }}</td>
                        @foreach ($items as $item)
                            @if ($item->id == $i->item_id)
                                <td>{{ $item->item }}</td>
                            @endif
                        @endforeach
                        @foreach ($branches as $branch)
                            @if ($branch->id == $i->branch_id)
                                <td>{{ $branch->branch }}</td>
                            @endif
                        @endforeach
                        <td>{{ $i->month }}</td>
                        <td>{{ $i->year }}</td>
                        <td>{{ $i->first_inventory }}</td>
                        <td>{{ $i->last_inventory }}</td>
                        <td>{{ $i->inventory_out }}</td>
                        <td class="action-buttons">
                            <button class="edit-btn" onclick="openModal(this)">تعديل<i
                                    class="fa-solid fa-pen-to-square"></i></button>
                            <button class="delete-btn"
                                onclick="confirmDelete({{ $i->id }},'/inventory/destroy/')">حذف<i
                                    class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <!-- HTML للنموذج المنبثق -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>تعديل البيانات</h2>
            <form id="editForm" action="{{ route('inventory.update') }}" method="POST">
                @csrf
                <div class="custom-form-fields">
                    <input type="text" id="id" name="id" style="display: none;">
                    <div class="custom-form-group fourth-width">
                        <label for="edit_item">الصنف:</label>
                        <select id="edit_item" name="item_id" required>
                            @if (isset($items))
                                @foreach ($items as $d)
                                    <option value="{{ $d->id }}">{{ $d->item }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="custom-form-group fourth-width">
                        <label for="edit_branch">الفرع:</label>
                        <select id="edit_branch" name="branch_id" required>
                            @if (isset($branches))
                                @foreach ($branches as $b)
                                    <option value="{{ $b->id }}">{{ $b->branch }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="custom-form-group fourth-width">
                        <label for="edit_month">اختر الشهر:</label>
                        <select name="month" id="edit_month">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>
                                    {{ $m }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="custom-form-group fourth-width">
                        <label for="edit_year">اختر السنة:</label>
                        <select name="year" id="edit_year">
                            @for ($y = 2023; $y <= now()->year; $y++)
                                <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="custom-form-group fourth-width">
                        <label for="edit_first_inventory">رصيد اول المدة:</label>
                        <input type="number" id="edit_first_inventory" name="first_inventory">
                    </div>
                    <div class="custom-form-group fourth-width">
                        <label for="edit_last_inventory">رصيد اخر المدة:</label>
                        <input type="number" id="edit_last_inventory" name="last_inventory">
                    </div>
                    <div class="custom-form-group fourth-width">
                        <label for="edit_inventory_out"> المنصرف:</label>
                        <input type="number" id="edit_inventory_out" name="inventory_out">
                    </div>
                </div>
                <div class="custom-form-group">
                    <button type="submit">حفظ التعديلات</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        let currentRow;

        function openModal(button) {
            currentRow = button.closest('tr');
            const cells = currentRow.getElementsByTagName('td');
            document.getElementById('id').value = cells[0].innerText;
            const itemText = cells[1].innerText;
            const itemSelect = document.getElementById('edit_item');
            for (let i = 0; i < itemSelect.options.length; i++) {
                if (itemSelect.options[i].text === itemText) {
                    itemSelect.selectedIndex = i;
                    break;
                }
            }
            const branchText = cells[2].innerText;
            const branchSelect = document.getElementById('edit_branch');
            for (let i = 0; i < branchSelect.options.length; i++) {
                if (branchSelect.options[i].text === branchText) {
                    branchSelect.selectedIndex = i;
                    break;
                }
            }
            const month = cells[3].innerText;
            const monthSelect = document.getElementById('month');
            for (let i = 0; i < monthSelect.options.length; i++) {
                if (monthSelect.options[i].value == month) {
                    monthSelect.selectedIndex = i;
                    break;
                }
            }
            const year = cells[4].innerText;
            const yearSelect = document.getElementById('year');
            for (let i = 0; i < yearSelect.options.length; i++) {
                if (yearSelect.options[i].value == year) {
                    yearSelect.selectedIndex = i;
                    break;
                }
            }
            document.getElementById('edit_first_inventory').value = cells[5].innerText;
            document.getElementById('edit_last_inventory').value = cells[6].innerText;
            document.getElementById('edit_inventory_out').value = cells[7].innerText;

            document.getElementById('editModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        function confirmDelete(id, pa) {
            if (confirm('هل أنت متأكد أنك تريد حذف هذا السجل؟')) {
                window.location.href = pa + id;
            }
        }
    </script>
@endsection
