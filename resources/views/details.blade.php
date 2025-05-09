@extends('include.app')
@section('title')
    التسجيل اليومي
@endsection

@section('page')
    التسجيل اليومي
@endsection

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

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
            <h2 class="custom-form-title">نموذج إضافة تسجيل يومي</h2>
            <p></p>
        </div>
        <form id="detailsForm" action="{{ route('details.store') }}" method="POST">
            @csrf
            <div class="custom-form-fields">
                <div class="custom-form-group third-width">
                    <label for="description">التفصيل:</label>
                    <input type="text" id="description" name="detail" required>
                </div>

                <div class="custom-form-group fourth-width">
                    <label for="totalPrice">السعر الإجمالي:</label>
                    <input type="float" id="totalPrice" name="totalPrice" required>
                </div>
                <div class="custom-form-group fourth-width">
                    <label for="item">الصنف:</label>
                    <select id="item" name="item" required>
                        @if (isset($items))
                            @foreach ($items as $d)
                                <option value="{{ $d->id }}">{{ $d->item }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="custom-form-group fourth-width">
                    <label for="tax">الضريبة:</label>
                    <select id="tax" name="tax" required>
                        <option value="False">لا</option>
                        <option value="True">نعم</option>
                    </select>
                </div>
                <div class="custom-form-group fourth-width">
                    <label for="payment_method">طريقة الدفع:</label>
                    <select id="payment_method" name="payment_method" required>
                        <option value="cash">نقد</option>
                        <option value="credit">اجل</option>
                    </select>
                </div>
                <div class="custom-form-group fourth-width">
                    <label for="branch">الفرع:</label>
                    <select id="branch" name="branch" required>
                        @if (isset($Branch))
                            @foreach ($Branch as $b)
                                <option value="{{ $b->id }}">{{ $b->branch }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="custom-form-group third-width">
                    <label for="account">الحساب:</label>
                    <select id="account" name="account" required>
                        @if (isset($accounts))
                            @foreach ($accounts as $d1)
                                <option value="{{ $d1->id }}">{{ $d1->account }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="custom-form-group third-width">
                    <label for="supplier">حساب المورد:</label>
                    <select id="supplier" name="supplier" required>
                        @if (isset($suppliers))
                            @foreach ($suppliers as $s)
                                <option value="{{ $s->id }}">{{ $s->supplier }}</option>
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

    <h2>جدول عرض البيانات</h2>
    <input type="text" class="search-input" placeholder="بحث في الأصناف..."
        onkeyup="searchTable(this, 'categories-table')">
    <table id="categories-table">
        <thead>
            <tr>
                <th onclick="sortTable(0, 'categories-table')">الرقم</th>
                <th onclick="sortTable(1, 'categories-table')">التفصيل</th>
                <th onclick="sortTable(2, 'categories-table')">الصنف</th>
                <th onclick="sortTable(3, 'categories-table')">الفرع</th>
                <th onclick="sortTable(4, 'categories-table')">طريقة الدفع</th>
                <th onclick="sortTable(5, 'categories-table')">الحساب</th>
                <th onclick="sortTable(6, 'categories-table')">السعر الإجمالي</th>
                <th onclick="sortTable(7, 'categories-table')">الضريبة</th>
                <th onclick="sortTable(8, 'categories-table')">صافي السعر</th>
                <th onclick="sortTable(9, 'categories-table')">التاريخ</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($details))
                @foreach ($details as $d)
                    <tr>
                        <td>{{ $d->id }}</td>
                        <td>{{ $d->detail }}</td>
                        @foreach ($items as $i)
                            @if ($i->id == $d->item_id)
                                <td>{{ $i->item }}</td>
                            @endif
                        @endforeach
                        @foreach ($Branch as $b)
                            @if ($b->id == $d->branch_id)
                                <td>{{ $b->branch }}</td>
                            @endif
                        @endforeach
                        @if ($d->payment_method == 'cash')
                            <td>نقد</td>
                        @elseif ($d->payment_method == 'credit')
                            <td>اجل</td>
                        @else
                            <td></td>
                        @endif
                        @if ($d->payment_method == 'cash')
                            @foreach ($accounts as $a)
                                @if ($a->id == $d->account_id)
                                    <td>{{ $a->account }}</td>
                                @endif
                            @endforeach
                        @else
                            @foreach ($suppliers as $s)
                                @if ($s->id == $d->account_id)
                                    <td>{{ $s->supplier }}</td>
                                @endif
                            @endforeach
                        @endif
                        <td>{{ $d->total }}</td>

                        @if ($d->tax == 'True')
                            <td>نعم</td>
                        @elseif ($d->tax == 'False')
                            <td>لا</td>
                        @else
                            <td></td>
                        @endif


                        <td>{{ $d->price }}</td>
                        <td>
                            {{ $d->date }}
                        </td>
                        <td class="action-buttons">
                            <button class="edit-btn" onclick="openModal(this)">تعديل<i
                                    class="fa-solid fa-pen-to-square"></i></button>
                            <button class="delete-btn" onclick="confirmDelete({{ $d->id }})">حذف<i
                                    class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    <div class="pagination" id="categories-pagination"></div>

    <!-- HTML للنموذج المنبثق -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>تعديل البيانات</h2>
            <form id="editForm" action="{{ route('details.update') }}" method="POST">
                @csrf
                <div class="custom-form-fields">
                    <input type="text" id="id" name="id" style="display: none;">
                    <div class="custom-form-group full-width">
                        <label for="editDescription">التفصيل:</label>
                        <input type="text" id="editDescription" name="detail" required>
                    </div>

                    <div class="custom-form-group half-width">
                        <label for="editTotalPrice">السعر الإجمالي:</label>
                        <input type="float" id="editTotalPrice" name="totalPrice" required>
                    </div>

                    <div class="custom-form-group half-width">
                        <label for="editTax">الضريبة:</label>
                        <select id="editTax" name="tax" required>
                            <option value="False">لا</option>
                            <option value="True">نعم</option>
                        </select>
                    </div>
                    <div class="custom-form-group fourth-width">
                        <label for="edit_payment_method">طريقة الدفع:</label>
                        <select id="edit_payment_method" name="payment_method" required>
                            <option value="cash">نقد</option>
                            <option value="credit">اجل</option>
                        </select>
                    </div>
                    <div class="custom-form-group third-width">
                        <label for="editBranch">الفرع:</label>
                        <select id="editBranch" name="branch" required>
                            @if (isset($Branch))
                                @foreach ($Branch as $b)
                                    <option value="{{ $b->id }}">{{ $b->branch }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="custom-form-group third-width">
                        <label for="editAccount">الحساب:</label>
                        <select id="editAccount" name="account" required>
                            @if (isset($accounts))
                                @foreach ($accounts as $d1)
                                    <option value="{{ $d1->id }}">
                                        {{ $d1->account }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="custom-form-group third-width">
                        <label for="edit_supplier">حساب المورد:</label>
                        <select id="edit_supplier" name="supplier" required>
                            @if (isset($suppliers))
                                @foreach ($suppliers as $s)
                                    <option value="{{ $s->id }}">{{ $s->supplier }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="custom-form-group third-width">
                        <label for="date">التاريخ:</label>
                        <input type="date" id="date" name="date" required>
                    </div>
                    <div class="custom-form-group third-width">
                        <label for="editItem">الصنف:</label>
                        <select id="editItem" name="item" required>
                            @if (isset($items))
                                @foreach ($items as $d)
                                    <option value="{{ $d->id }}">{{ $d->item }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="custom-form-group">
                    <button type="submit">حفظ التعديلات</button>
                </div>
            </form>
        </div>
    </div>

    <!-- CSS للنموذج المنبثق -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <!-- تضمين jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script> <!-- JavaScript لفتح وإغلاق النموذج المنبثق -->
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
            const itemSelect = document.getElementById('editItem');
            for (let i = 0; i < itemSelect.options.length; i++) {
                if (itemSelect.options[i].text === itemText) {
                    itemSelect.selectedIndex = i;
                    break;
                }
            }

            // تعيين القيمة الافتراضية لعنصر "select" الخاص بالفرع
            const branchText = cells[3].innerText;
            const branchSelect = document.getElementById('editBranch');
            for (let i = 0; i < branchSelect.options.length; i++) {
                if (branchSelect.options[i].text === branchText) {
                    branchSelect.selectedIndex = i;
                    break;
                }
            }
            // تعيين القيمة الافتراضية لعنصر "select" الخاص بطريقة الدفع
            const paymentMethodText = cells[4].innerText;
            const paymentMethodSelect = document.getElementById('edit_payment_method');
            for (let i = 0; i < paymentMethodSelect.options.length; i++) {
                if (paymentMethodSelect.options[i].text === paymentMethodText) {
                    paymentMethodSelect.selectedIndex = i;
                    break;
                }
            }
            toggleAccountSupplier2();

            // تعيين القيمة الافتراضية لعنصر "select" الخاص بالحساب
            if (paymentMethodText == 'نقد') {
                const accountText = cells[5].innerText;
                const accountSelect = document.getElementById('editAccount');
                for (let i = 0; i < accountSelect.options.length; i++) {
                    if (accountSelect.options[i].text === accountText) {
                        accountSelect.selectedIndex = i;
                        break;
                    }
                }
            } else {
                const supplierText = cells[5].innerText;
                const supplierSelect = document.getElementById('edit_supplier');
                for (let i = 0; i < supplierSelect.options.length; i++) {
                    if (supplierSelect.options[i].text === supplierText) {
                        supplierSelect.selectedIndex = i;
                        break;
                    }
                }
            }

            document.getElementById('editTotalPrice').value = cells[6].innerText;

            // تعيين القيمة الافتراضية لعنصر "select" الخاص بالضريبة
            const taxText = cells[7].innerText.trim();
            const taxSelect = document.getElementById('editTax');
            taxSelect.value = (taxText === 'نعم') ? 'True' : 'False';

            // تعيين التاريخ كقيمة افتراضية
            const dateText = cells[9].innerText; // افترض أن التاريخ في العمود التاسع
            document.getElementById('date').value = dateText;

            document.getElementById('editModal').style.display = 'block';

        }
        // إغلاق النموذج المنبثق
        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        function confirmDelete(id) {
            if (confirm('هل أنت متأكد أنك تريد حذف هذا السجل؟')) {
                // إذا تم التأكيد، قم بتوجيه المستخدم إلى الراوت الخاص بالحذف
                window.location.href = '/details/destroy/' + id;
            }
        }
        $(document).ready(function() {
            // تهيئة Select2 على قائمة الخيارات الخاصة بالصنف
            $('#item').select2({
                placeholder: "اختر صنفًا",
                allowClear: true
            });

            // تهيئة Select2 على قائمة الخيارات الخاصة بالصنف في النموذج المنبثق
            $('#editItem').select2({
                placeholder: "اختر صنفًا",
                allowClear: true
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            // عناصر السلكت
            const paymentMethod = document.getElementById("payment_method");
            const accountGroup = document.getElementById("account").closest('.custom-form-group');
            const supplierGroup = document.getElementById("supplier").closest('.custom-form-group');

            const edit_paymentMethod = document.getElementById("edit_payment_method");
            const accountGroup2 = document.getElementById("editAccount").closest('.custom-form-group');
            const supplierGroup2 = document.getElementById("edit_supplier").closest('.custom-form-group');

            function toggleAccountSupplier() {
                if (paymentMethod.value === "cash") {
                    accountGroup.style.display = "flex";
                    supplierGroup.style.display = "none";
                    // اجعل الـ select الخاص بالمورد غير مطلوب
                    document.getElementById("supplier").required = false;
                    document.getElementById("account").required = true;
                } else if (paymentMethod.value === "credit") {
                    accountGroup.style.display = "none";
                    supplierGroup.style.display = "flex";
                    // اجعل الـ select الخاص بالحساب غير مطلوب
                    document.getElementById("supplier").required = true;
                    document.getElementById("account").required = false;
                }
            }

            window.toggleAccountSupplier2 = function() {
                if (edit_paymentMethod.value === "cash") {
                    accountGroup2.style.display = "flex";
                    supplierGroup2.style.display = "none";
                    // اجعل الـ select الخاص بالمورد غير مطلوب
                    document.getElementById("edit_supplier").required = false;
                    document.getElementById("editAccount").required = true;
                } else if (edit_paymentMethod.value === "credit") {
                    accountGroup2.style.display = "none";
                    supplierGroup2.style.display = "flex";
                    // اجعل الـ select الخاص بالحساب غير مطلوب
                    document.getElementById("edit_supplier").required = true;
                    document.getElementById("editAccount").required = false;
                }
            }

            // شغّل الدالة في البداية
            toggleAccountSupplier();
            toggleAccountSupplier2();

            // استمع لتغيير طريقة الدفع
            paymentMethod.addEventListener('change', toggleAccountSupplier);
            edit_paymentMethod.addEventListener('change', toggleAccountSupplier2);
        });
    </script>
@endsection
