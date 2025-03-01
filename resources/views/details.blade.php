@extends('include.app')
@section('title')
    التسجيل اليومي
@endsection

@section('page')
    التسجيل اليومي
@endsection
<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0, 0, 0);
        background-color: rgba(0, 0, 0, 0.4);
        padding-top: 60px;
    }

    .modal-content {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 500px;
        border-radius: 8px;
    }

    .close {
        color: #aaa;
        float: left;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
</style>

@section('main')
    <div class="custom-form-container">
        <h2 class="custom-form-title">نموذج إضافة تفاصيل</h2>
        <form id="detailsForm" action="{{ route('details.store') }}" method="POST">
            @csrf
            <div class="custom-form-fields">
                <div class="custom-form-group full-width">
                    <label for="description">التفصيل:</label>
                    <input type="text" id="description" name="detail" required>
                </div>

                <div class="custom-form-group half-width">
                    <label for="totalPrice">السعر الإجمالي:</label>
                    <input type="number" id="totalPrice" name="totalPrice" required>
                </div>

                <div class="custom-form-group half-width">
                    <label for="tax">الضريبة:</label>
                    <select id="tax" name="tax" required>
                        <option value="0">لا</option>
                        <option value="1">نعم</option>
                    </select>
                </div>

                <div class="custom-form-group third-width">
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
                                <option value="{{ $d1->id }}">{{ $d1->account }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="custom-form-group third-width">
                    <label for="date">التاريخ:</label>
                    <input type="date" id="date" name="date" required>
                </div>
                <div class="custom-form-group third-width">
                    <label for="item">الصنف:</label>
                    <select id="item" name="item" required>
                        @if (isset($items))
                            @foreach ($items as $d)
                                <option value="{{ $d->id }}">{{ $d->item }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div class="custom-form-group">
                <button type="submit">إضافة</button>
            </div>
        </form>
    </div>
    <h2>جدول عرض البيانات</h2>
    <table>
        <thead>
            <tr>
                <th>الرقم</th>
                <th>التفصيل</th>
                <th>الصنف</th>
                <th>الفرع</th>
                <th>الحساب</th>
                <th>السعر الإجمالي</th>
                <th>الضريبة</th>
                <th>صافي السعر</th>
                <th>التاريخ</th>
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
                        @foreach ($accounts as $a)
                            @if ($a->id == $d->account_id)
                                <td>{{ $a->account }}</td>
                            @endif
                        @endforeach
                        <td>{{ $d->total }}</td>

                        @if ($d->tax == 1)
                            <td>نعم</td>
                        @elseif ($d->tax == 0)
                            <td>لا</td>
                        @else
                            <td></td>
                        @endif


                        <td>{{ $d->price }}</td>
                        <td>{{ $d->date }}</td>
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
                        <input type="number" id="editTotalPrice" name="totalPrice" required>
                    </div>

                    <div class="custom-form-group half-width">
                        <label for="editTax">الضريبة:</label>
                        <select id="editTax" name="tax" required>
                            <option value="0">لا</option>
                            <option value="1">نعم</option>
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
                                    <option value="{{ $d1->id }}">{{ $d1->account }}</option>
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

    <!-- JavaScript لفتح وإغلاق النموذج المنبثق -->
    <script>
        let currentRow; // متغير لتخزين الصف الحالي الذي يتم تعديله

        // فتح النموذج المنبثق وتعبئة البيانات الحالية
        function openModal(button) {
            currentRow = button.parentElement.parentElement; // الحصول على الصف الحالي
            const cells = currentRow.getElementsByTagName('td');

            // تعبئة النموذج بالبيانات الحالية
            document.getElementById('id').value = cells[0].innerText;
            document.getElementById('editDescription').value = cells[1].innerText;
            document.getElementById('editItem').value = cells[2].innerText;
            document.getElementById('editBranch').value = cells[3].innerText;
            document.getElementById('editAccount').value = cells[4].innerText;
            document.getElementById('editTotalPrice').value = cells[5].innerText;
            document.getElementById('editTax').value = cells[6].innerText;

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
    </script>
@endsection
