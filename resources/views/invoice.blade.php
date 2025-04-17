@extends('include.app')
@section('title')
    فاتورة ضريبية
@endsection

@section('page')
    فاتورة ضريبية
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
            <h2 class="custom-form-title"> إضافة فاتورة ضريبية </h2>
            <p></p>
        </div>
        <form id="detailsForm" action="{{ route('invoices.store') }}" method="POST">
            @csrf
            <div class="custom-form-fields">
                <div class="custom-form-group third-width">
                    <label for="customer_name">اسم العميل:</label>
                    <input type="text" id="customer_name" name="customer_name" required>
                </div>
                <div class="custom-form-group fourth-width">
                    <label for="tax_id">الرقم الضريبي:</label>
                    <input type="number" id="tax_id" name="tax_id" required>
                </div>
                <div class="custom-form-group fourth-width">
                    <label for="address">العنوان:</label>
                    <input type="text" id="address" name="address">
                </div>
                <div class="custom-form-group fourth-width">
                    <label for="phone_number">رقم الجوال:</label>
                    <input type="number" id="phone_number" name="phone_number">
                </div>
                <div class="custom-form-group fourth-width">
                    <label for="invoice_date">تاريخ الفاتورة:</label>
                    <input type="date" id="invoice_date" name="invoice_date" required>
                </div>
                <div class="custom-form-group fourth-width">
                    <label for="supply_date">تاريخ التوريد:</label>
                    <input type="date" id="supply_date" name="supply_date">
                </div>
            </div>
            <div class="custom-form-group">
                <button type="submit">إضافة</button>
            </div>
        </form>
    </div>
    <br><br>
    <h2>جدول عرض البيانات</h2>
    <input type="text" class="search-input" placeholder="بحث في الفواتير الضريبية..."
        onkeyup="searchTable(this, 'invoice-table')">
    <table id="invoice-table">
        <thead>
            <tr>
                <th onclick="sortTable(0, 'invoice-table')">الرقم</th>
                <th onclick="sortTable(1, 'invoice-table')">اسم العميل</th>
                <th onclick="sortTable(2, 'invoice-table')">الرقم الضريبي</th>
                <th onclick="sortTable(3, 'invoice-table')"> العنوان</th>
                <th onclick="sortTable(4, 'invoice-table')">رقم الهاتف</th>
                <th onclick="sortTable(5, 'invoice-table')">تاريخ الفاتورة</th>
                <th onclick="sortTable(6, 'invoice-table')"> تاريخ التوريد </th>
                <th onclick="sortTable(7, 'invoice-table')">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($invoices))
                @foreach ($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->id }}</td>
                        <td>{{ $invoice->customer_name }}</td>
                        <td>{{ $invoice->tax_id }}</td>
                        <td>{{ $invoice->address }}</td>
                        <td>{{ $invoice->phone_number }}</td>
                        <td>{{ $invoice->invoice_date }}</td>
                        <td>{{ $invoice->supply_date }}</td>
                        <td class="action-buttons">
                            <button class="edit-btn" onclick="openModal(this)">تعديل<i
                                    class="fa-solid fa-pen-to-square"></i></button>
                            <button class="delete-btn"
                                onclick="confirmDelete({{ $invoice->id }},'/invoices/destroy/')">حذف<i
                                    class="fa-solid fa-trash"></i></button>
                            <button class="btn btn-success"
                                onclick="window.location.href='/invoices/print/{{ $invoice->id }}'">
                                طباعة <i class="fa-solid fa-print"></i>
                            </button>
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
            <form id="editForm" action="{{ route('invoices.update') }}" method="POST">
                @csrf
                <div class="custom-form-fields">
                    <input type="text" id="id" name="id" style="display: none;">
                        <div class="custom-form-group third-width">
                            <label for="edit_customer_name">اسم العميل:</label>
                            <input type="text" id="edit_customer_name" name="customer_name" required>
                        </div>
                        <div class="custom-form-group fourth-width">
                            <label for="edit_tax_id">الرقم الضريبي:</label>
                            <input type="number" id="edit_tax_id" name="tax_id" required>
                        </div>
                        <div class="custom-form-group fourth-width">
                            <label for="edit_address">العنوان:</label>
                            <input type="text" id="edit_address" name="address">
                        </div>
                        <div class="custom-form-group fourth-width">
                            <label for="edit_phone_number">رقم الجوال:</label>
                            <input type="number" id="edit_phone_number" name="phone_number">
                        </div>
                        <div class="custom-form-group fourth-width">
                            <label for="edit_invoice_date">تاريخ الفاتورة:</label>
                            <input type="date" id="edit_invoice_date" name="invoice_date" required>
                        </div>
                        <div class="custom-form-group fourth-width">
                            <label for="edit_supply_date">تاريخ التوريد:</label>
                            <input type="date" id="edit_supply_date" name="supply_date">
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
            currentRow = button.parentElement.parentElement;
            const cells = currentRow.getElementsByTagName('td');
            document.getElementById('id').value = cells[0].innerText;
            document.getElementById('edit_customer_name').value = cells[1].innerText;
            document.getElementById('edit_tax_id').value = cells[2].innerText;
            document.getElementById('edit_address').value = cells[3].innerText;
            document.getElementById('edit_phone_number').value = cells[4].innerText;
            const dateText = cells[5].innerText;
            document.getElementById('edit_invoice_date').value = dateText;
            const dateText2 = cells[6].innerText;
            document.getElementById('edit_supply_date').value = dateText2;
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
