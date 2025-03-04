@extends('include.app')
@section('title')
    عمليات الكاشيرات
@endsection

@section('page')
    عمليات الكاشيرات
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

    #myForm {
        display: block;
    }

    #toggleButton {
        font-size: 2em;
        /* حجم الخط كبير */
        font-weight: bold;
        /* خط عريض */
        background: none;
        /* بدون خلفية */
        border: none;
        /* بدون حدود */
        cursor: pointer;
        /* مؤشر الفأرة */
        outline: none;
        /* إزالة التحديد */
        width: 50px;
        /* عرض الزر */
        height: 50px;
        /* ارتفاع الزر */
        display: flex;
    }

    #toggleButton:focus {
        outline: none;
        /* إزالة التحديد عند التركيز */
    }

    .custom-form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0px;
        height: 30px;
    }
</style>

@section('main')
    <div class="custom-form-container">
        <div class="custom-form-header">
            <button type="button" id="toggleButton" onclick="toggleForm()">-</button>
            <h2 class="custom-form-title">نموذج إضافة عمليات كاشير</h2>
            <p></p>
        </div>
        <form id="detailsForm" action="{{ route('casher_proc.store') }}" method="POST">
            @csrf
            <div class="custom-form-fields">
                <div class="custom-form-group half-width">
                    <label for="casher">الكاشير:</label>
                    <select id="casher" name="casher" required>
                        @if (isset($casher))
                            @foreach ($casher as $ca)
                                <option value="{{ $ca->id }}">{{ $ca->casher }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="custom-form-group half-width">
                    <label for="total">الاجمالي:</label>
                    <input type="number" id="total" name="total" required>
                </div>

                <div class="custom-form-group half-width">
                    <label for="cash">الكاش:</label>
                    <input type="number" id="cash" name="cash" required>
                </div>
                <div class="custom-form-group half-width">
                    <label for="bank">البنك:</label>
                    <input type="number" id="bank" name="bank" required>
                </div>
                <div class="custom-form-group half-width">
                    <label for="out">المصروفات :</label>
                    <input type="number" id="out" name="out" required>
                </div>
                <div class="custom-form-group third-width">
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
    <input type="text" class="search-input" placeholder="بحث في بيانات الكواشير..."
        onkeyup="searchTable(this, 'categories-table')">
    <table id="categories-table">
        <thead>
            <tr>
                <th onclick="sortTable(0, 'categories-table')">الرقم</th>
                <th onclick="sortTable(1, 'categories-table')">الكاشير</th>
                <th onclick="sortTable(2, 'categories-table')">الفرع</th>
                <th onclick="sortTable(3, 'categories-table')">الإجمالي</th>
                <th onclick="sortTable(4, 'categories-table')">البنك</th>
                <th onclick="sortTable(5, 'categories-table')">الكاش</th>
                <th onclick="sortTable(6, 'categories-table')">المصروفات</th>
                <th onclick="sortTable(7, 'categories-table')">عجز/زياده</th>
                <th onclick="sortTable(8, 'categories-table')">التاريخ</th>
                <th>العمليات</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($casher_proc))
                @foreach ($casher_proc as $d)
                    <tr>
                        <td>{{ $d->id }}</td>
                        @foreach ($casher as $i)
                            @if ($i->id == $d->casher_id)
                                <td>{{ $i->casher }}</td>
                                @foreach ($branch as $b)
                                    @if ($b->id == $i->branch_id)
                                        <td>{{ $b->branch }}</td>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                        <td>{{ $d->total }}</td>

                        <td>{{ $d->bank }}</td>
                        <td>{{ $d->cash }}</td>
                        <td>{{ $d->out }}</td>
                        <td>{{ $d->plus }}</td>
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
    <div class="pagination" id="categories-pagination"></div>

    <!-- HTML للنموذج المنبثق -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>تعديل البيانات</h2>
            <form id="editForm" action="{{ route('casher_proc.update') }}" method="POST">
                @csrf
                <div class="custom-form-fields">
                    <input type="text" id="id" name="id" style="display: none;">
                    <div class="custom-form-group third-width">
                        <label for="editCasher">الكاشير:</label>
                        <select id="editCasher" name="casher_id" required>
                            @if (isset($casher))
                                @foreach ($casher as $ca)
                                    <option value="{{ $ca->id }}">{{ $ca->casher }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="custom-form-group full-width">
                        <label for="editTotal">الاجمالي:</label>
                        <input type="text" id="editTotal" name="total" required>
                    </div>

                    <div class="custom-form-group half-width">
                        <label for="editCash"> الكاش:</label>
                        <input type="number" id="editCash" name="cash" required>
                    </div>
                    <div class="custom-form-group half-width">
                        <label for="editBank"> البنك:</label>
                        <input type="number" id="editBank" name="bank" required>
                    </div>
                    <div class="custom-form-group half-width">
                        <label for="editOut"> المصروفات:</label>
                        <input type="number" id="editOut" name="out" required>
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
            const itemText = cells[1].innerText;
            const itemSelect = document.getElementById('editCasher');
            for (let i = 0; i < itemSelect.options.length; i++) {
                if (itemSelect.options[i].text === itemText) {
                    itemSelect.selectedIndex = i;
                    break;
                }
            }
            document.getElementById('editTotal').value = cells[3].innerText;
            document.getElementById('editCash').value = cells[4].innerText;
            document.getElementById('editBank').value = cells[5].innerText;
            document.getElementById('editOut').value = cells[6].innerText;

            document.getElementById('editModal').style.display = 'block';
        }

        // إغلاق النموذج المنبثق
        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        function confirmDelete(id) {
            if (confirm('هل أنت متأكد أنك تريد حذف هذا السجل؟')) {
                // إذا تم التأكيد، قم بتوجيه المستخدم إلى الراوت الخاص بالحذف
                window.location.href = '/casher_proc/destroy/' + id;
            }
        }
    </script>
@endsection
