@extends('include.app')
@section('title')
    تفاصيل فاتورة ضريبية
@endsection

@section('page')
    تفاصيل فاتورة ضريبية
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
            <h2 class="custom-form-title"> إضافة تفاصيل فاتورة ضريبية رقم: {{ $invoice->id }}</h2>
            <p>اسم العميل: {{ $invoice->customer_name }}</p>
        </div>
        <form id="detailsForm" action="{{ route('invoices.details') }}" method="POST">
            @csrf
            <div class="custom-form-fields">
                <input type="text" id="invoice_id" name="invoice_id" value="{{ $invoice->id }}" style="display: none;">
                <div class="custom-form-group third-width">
                    <label for="product_name">اسم الصنف:</label>
                    <input type="text" id="product_name" name="product_name" required>
                </div>
                <div class="custom-form-group fourth-width">
                    <label for="product_code">كود الصنف:</label>
                    <input type="number" id="product_code" name="product_code">
                </div>
                <div class="custom-form-group fourth-width">
                    <label for="price">سعر الصنف:</label>
                    <input type="float" id="price" name="price" required>
                </div>
                <div class="custom-form-group fourth-width">
                    <label for="quantity">الكمية:</label>
                    <input type="number" id="quantity" name="quantity" required>
                </div>
                <div class="custom-form-group fourth-width">
                    <label for="discount"> الخصم:</label>
                    <input type="float" id="discount" name="discount">
                </div>
                <div class="custom-form-group third-width">
                    <label for="tax"> الضريبة:</label>
                    <select id="tax" name="tax" required>
                        <option value="نعم">نعم</option>
                        <option value="لا">لا</option>
                    </select>
                </div>
            </div>
            <div class="custom-form-group">
                <button type="submit">إضافة</button>
            </div>
        </form>
    </div>
    <br><br>
    <h2>جدول عرض بيانات الفاتورة الضريبة رقم: {{ $invoice->id }}</h2>
    <button class="print_btn" onclick="window.location.href='/invoices/print/{{ $invoice->id }}'">
        طباعة <i class="fa-solid fa-print"></i>
    </button>
    <input type="text" class="search-input" placeholder="بحث في الفواتير الضريبية..."
        onkeyup="searchTable(this, 'invoice-table')">
    <table id="invoice-table">
        <thead>
            <tr>
                <th onclick="sortTable(0, 'invoice-table')">الرقم</th>
                <th onclick="sortTable(1, 'invoice-table')">رمز الصنف</th>
                <th onclick="sortTable(2, 'invoice-table')">اسم الصنف</th>
                <th onclick="sortTable(3, 'invoice-table')"> الكمية</th>
                <th onclick="sortTable(4, 'invoice-table')"> السعر</th>
                <th onclick="sortTable(5, 'invoice-table')"> الخصم</th>
                <th onclick="sortTable(6, 'invoice-table')">السعر الاجمالي </th>
                <th onclick="sortTable(7, 'invoice-table')">الضريبة %</th>
                <th onclick="sortTable(8, 'invoice-table')">قيمة الضريبة </th>
                <th onclick="sortTable(9, 'invoice-table')"> الصافي </th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($invoice_details))
                @foreach ($invoice_details as $invoice_detail)
                    <tr>
                        <td>{{ $invoice_detail->id }}</td>
                        <td>{{ $invoice_detail->product_code }}</td>
                        <td>{{ $invoice_detail->product_name }}</td>
                        <td>{{ $invoice_detail->quantity }}</td>
                        <td>{{ $invoice_detail->price }}</td>
                        <td>{{ $invoice_detail->discount }}</td>
                        <td>{{ $invoice_detail->price * $invoice_detail->quantity - $invoice_detail->discount }}</td>
                        <td>{{ $invoice_detail->tax }}</td>
                        <td>{{ $invoice_detail->tax == 'نعم' ? ($invoice_detail->price * $invoice_detail->quantity - $invoice_detail->discount) * 0.15 : 0 }}
                        <td>{{ $invoice_detail->tax == 'نعم' ? $invoice_detail->price * $invoice_detail->quantity - $invoice_detail->discount + ($invoice_detail->price * $invoice_detail->quantity - $invoice_detail->discount) * 0.15 : $invoice_detail->price * $invoice_detail->quantity - $invoice_detail->discount }}
                        </td>
                        </td>
                        <td class="action-buttons">
                            <button class="edit-btn" onclick="openModal(this)">تعديل<i
                                    class="fa-solid fa-pen-to-square"></i></button>
                            <button class="delete-btn"
                                onclick="confirmDelete({{ $invoice_detail->id }},'/invoices/destroy_detail/')">حذف<i
                                    class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="10">لا توجد بيانات لعرضها</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- HTML للنموذج المنبثق -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>تعديل البيانات</h2>
            <form id="editForm" action="{{ route('invoices.update_detail') }}" method="POST">
                @csrf
                <div class="custom-form-fields">
                    <input type="number" id="invoice_id" name="invoice_id" value="{{ $invoice->id }}"
                        style="display: none;">
                    <input type="number" id="id" name="id" style="display: none;">
                    <div class="custom-form-group third-width">
                        <label for="edit_product_name">اسم الصنف:</label>
                        <input type="text" id="edit_product_name" name="product_name" required>
                    </div>
                    <div class="custom-form-group fourth-width">
                        <label for="edit_product_code">كود الصنف:</label>
                        <input type="number" id="edit_product_code" name="product_code">
                    </div>
                    <div class="custom-form-group fourth-width">
                        <label for="edit_price">سعر الصنف:</label>
                        <input type="float" id="edit_price" name="price" required>
                    </div>
                    <div class="custom-form-group fourth-width">
                        <label for="edit_quantity">الكمية:</label>
                        <input type="number" id="edit_quantity" name="quantity" required>
                    </div>
                    <div class="custom-form-group fourth-width">
                        <label for="edit_discount"> الخصم:</label>
                        <input type="float" id="edit_discount" name="discount">
                    </div>
                    <div class="custom-form-group third-width">
                        <label for="edit_tax"> الضريبة:</label>
                        <select id="edit_tax" name="tax" required>
                            <option value="نعم">نعم</option>
                            <option value="لا">لا</option>
                        </select>
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
            document.getElementById('edit_product_name').value = cells[2].innerText;
            document.getElementById('edit_product_code').value = cells[1].innerText;
            document.getElementById('edit_price').value = cells[4].innerText;
            document.getElementById('edit_quantity').value = cells[3].innerText;
            document.getElementById('edit_discount').value = cells[5].innerText;
            document.getElementById('edit_tax').value = cells[7].innerText;
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
