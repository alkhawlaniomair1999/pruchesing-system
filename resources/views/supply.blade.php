@extends('include.app')
@section('title')
    التوريد
@endsection

@section('page')
    التوريد
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
            <h2 class="custom-form-title">نموذج إضافة قيد</h2>
            <p></p>
        </div>
        <form id="detailsForm" action="{{ route('supplier.storeSupply') }}" method="POST">
            @csrf
            <div class="custom-form-fields">
                <div class="custom-form-group third-width">
                    <label for="description">التفصيل:</label>
                    <input type="text" id="description" name="details" required>
                </div>

                <div class="custom-form-group fourth-width">
                    <label for="price">المبلغ:</label>
                    <input type="float" id="price" name="amount" required>
                </div>

                <div class="custom-form-group third-width">
                    <label for="paymentType">نوع السند:</label>
                    <select id="paymentType" name="payment_type" onchange="toggleAccountField()" required>
                        <option value="cash">نقد</option>
                        <option value="credit">آجل</option>
                    </select>
                </div>
                <div class="custom-form-group third-width">
                    <label for="supplier"> حساب المورد:</label>
                    <select id="supplier" name="supplier_id" required>
                        @if (isset($suppliers))
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->supplier }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="custom-form-group third-width" id="accountField">
                    <label for="account"> حساب:</label>
                    <select id="account" name="account_name">
                        @if (isset($accounts))
                            @foreach ($accounts as $d1)
                                @foreach ($Branch as $b1)
                                    @if ($b1->id == $d1->branch_id)
                                        <option value="{{ $d1->id }}">{{ $d1->account }} ({{ $b1->branch }})
                                        </option>
                                    @endif
                                @endforeach
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="custom-form-group fourth-width">
                    <label for="date">التاريخ:</label>
                    <input type="date" id="date" name="date" required>
                </div>
            </div>

            <div class="custom-form-group">
                <button type="submit">إضافة</button>
            </div>
        </form>
    </div>
    <br><br>

    <h2>جدول عرض البيانات</h2>
    <input type="text" class="search-input" placeholder="بحث في السندات..." onkeyup="searchTable(this, 'pro-table')">
    <table id="pro-table">
        <thead>
            <tr>
                <th onclick="sortTable(0, 'pro-table')">الرقم</th>
                <th onclick="sortTable(1, 'pro-table')">التفصيل</th>
                <th onclick="sortTable(2, 'pro-table')">المورد</th>
                <th onclick="sortTable(3, 'pro-table')"> المبلغ</th>
                <th onclick="sortTable(4, 'pro-table')">الدفع</th>
                <th onclick="sortTable(5, 'pro-table')">الحساب</th>

                <th onclick="sortTable(6, 'pro-table')"> التاريخ </th>
                <th onclick="sortTable(7, 'pro-table')">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($proc))
                @foreach ($proc as $p)
                    <tr>
                        <td>{{ $p->id }}</td>
                        <td>{{ $p->details }}</td>

                        @foreach ($suppliers as $a)
                            @if ($a->id == $p->supplier_id)
                                <td>{{ $a->supplier }}</td>
                            @endif
                        @endforeach

                        <td>{{ number_format($p->amount, 2) }}</td>
                        <td>
                            @if ($p->payment_type === 'cash')
                                نقداً
                            @elseif($p->payment_type === 'credit')
                                آجل
                            @else
                                غير محدد
                            @endif
                        </td>

                        <td>
                            @if (isset($accounts))
                                @foreach ($accounts as $ac)
                                    @if ($p->account_name == $ac->id)
                                        {{ $ac->account }}
                                    @endif
                                @endforeach
                            @endif
                        </td>
                        <td>{{ $p->date }}</td>
                        <td class="action-buttons">
                            <button class="edit-btn" onclick="openModal(this)">تعديل<i
                                    class="fa-solid fa-pen-to-square"></i></button>
                            <button class="delete-btn"
                                onclick="confirmDelete({{ $p->id }},'/supplier/deleteSupply/')">حذف<i
                                    class="fa-solid fa-trash"></i></button>
                            <button class="btn btn-success"
                                onclick="window.location.href='/supplier/printSupply/{{ $p->id }}'">
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
            <form id="editForm" action="{{ route('supplier.updateSupply') }}" method="POST">
                @csrf
                <div class="custom-form-fields">
                    <input type="text" id="id" name="id" style="display: none;">
                    <div class="custom-form-group full-width">
                        <label for="editDescription">التفصيل:</label>
                        <input type="text" id="editDescription" name="details" required>
                    </div>

                    <div class="custom-form-group third-width">
                        <label for="editSupplier">المورد :</label>
                        <select id="editSupplier" name="supplier_id" required>
                            @if (isset($suppliers))
                                @foreach ($suppliers as $s)
                                    <option value="{{ $s->id }}">{{ $s->supplier }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="custom-form-group third-width">
                        <label for="editAmount">المبلغ:</label>
                        <input type="text" id="editAmount" name="amount" required>
                    </div>
                    <div class="custom-form-group third-width">
                        <label for="payType">نوع السند:</label>
                        <select id="payType" name="payment_type" required>
                            <option value="cash">نقد</option>
                            <option value="credit">آجل</option>
                        </select>
                    </div>

                    <div class="custom-form-group third-width">
                        <label for="editAccount">الحساب:</label>
                        <select id="editAccount" name="account_name" required>
                            @if (isset($accounts))
                                @foreach ($accounts as $d1)
                                    @foreach ($Branch as $b1)
                                        @if ($b1->id == $d1->branch_id)
                                            <option value="{{ $d1->id }}">{{ $d1->account }}({{ $b1->branch }})
                                            </option>
                                        @endif
                                    @endforeach
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="custom-form-group third-width">
                        <label for="date">التاريخ:</label>
                        <input type="date" id="date" name="date" required>
                    </div>
                </div>

                <div class="custom-form-group">
                    <button type="submit">حفظ التعديلات</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentRow; // متغير لتخزين الصف الحالي الذي يتم تعديله

        // فتح النموذج المنبثق وتعبئة البيانات الحالية
        function openModal(button) {
            currentRow = button.parentElement.parentElement; // الحصول على الصف الحالي
            const cells = currentRow.getElementsByTagName('td');

            // تعبئة النموذج بالبيانات الحالية
            document.getElementById('id').value = cells[0].innerText;
            document.getElementById('editDescription').value = cells[1].innerText;

            // تعيين القيمة الافتراضية لعنصر "select" الخاص بالصنف
            const itemText = cells[2].innerText;
            const itemSelect = document.getElementById('editSupplier');
            for (let i = 0; i < itemSelect.options.length; i++) {
                if (itemSelect.options[i].text === itemText) {
                    itemSelect.selectedIndex = i;
                    break;
                }
            }
            document.getElementById('editAmount').value = cells[3].innerText;
            const payTypeText = cells[4].innerText;
            const payTypeSelect = document.getElementById('payType');
            for (let i = 0; i < payTypeSelect.options.length; i++) {
                if (payTypeSelect.options[i].text === payTypeText) {
                    payTypeSelect.selectedIndex = i;
                    break;
                }
            }
            // تعيين القيمة الافتراضية لعنصر "select" الخاص بالحساب
            const accountText = cells[5].innerText;
            const accountSelect = document.getElementById('editAccount');
            for (let i = 0; i < accountSelect.options.length; i++) {
                if (accountSelect.options[i].text === accountText) {
                    accountSelect.selectedIndex = i;
                    break;
                }
            }
            // تعيين التاريخ كقيمة افتراضية
            const dateText = cells[6].innerText; // افترض أن التاريخ في العمود التاسع
            document.getElementById('date').value = dateText;

            document.getElementById('editModal').style.display = 'block';
        }
        // إغلاق النموذج المنبثق
        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        function confirmDelete(id, pa) {
            if (confirm('هل أنت متأكد أنك تريد حذف هذا السجل؟')) {
                // إذا تم التأكيد، قم بتوجيه المستخدم إلى الراوت الخاص بالحذف
                window.location.href = pa + id;
            }
        }
    </script>



@endsection
