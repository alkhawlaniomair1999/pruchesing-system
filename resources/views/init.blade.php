@extends('include.app')
@section('title')
    التهيئة
@endsection

@section('page')
    التهيئة
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
        background-color: rgba(0,0,0,0.6);
        padding-top: 60px;
    }

    .modal-content {
        background-color: #fff;
        margin: 5% auto;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        width: 80%;
        max-width: 500px;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: #000;
        text-decoration: none;
    }

    button {
        padding: 10px 20px;
        font-size: 16px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #0056b3;
    }

    input[type="text"], input[type="hidden"] {
        width: 100%;
        padding: 10px;
        margin: 8px 0;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    input[disabled] {
        background-color: #f2f2f2;
        cursor: not-allowed;
    }
</style>
@section('main')
    <div class="container">
        <div class="form-container">
            <h3>إضافة فرع</h3>
            <form method="POST" action="{{route('branch.store')}}">
                @csrf
                <div class="form-group">
                    <label for="branch_name">اسم الفرع</label>
                    <input type="text" id="branch_name" name="branch_name" required>
                    <button type="submit">إضافة</button>
                </div>
            </form>
        </div>

        <div class="form-container">
            <h3>إضافة حساب</h3>
            <form>
                
                <div class="form-group">
                    <label for="account-name">اسم الحساب</label>
                    <input type="text" id="account-name" name="account-name" required>
                    <button type="submit">إضافة</button>
                </div>
            </form>
        </div>

        <div class="form-container">
            <h3>إضافة صنف</h3>
            <form action="{{ route('items.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="category-name">اسم الصنف</label>
                    <input type="text" id="category-name" name="category_name" required>
                    <button type="submit">إضافة</button>
                </div>
            </form>
        </div>
    </div>

    <div class="tables-container">
        <input type="text" class="search-input" placeholder="بحث في الفروع..."
            onkeyup="searchTable(this, 'branches-table')">
        <table id="branches-table">
            <thead>
                <tr>
                    <th onclick="sortTable(0, 'branches-table')">الرقم</th>
                    <th onclick="sortTable(1, 'branches-table')">الفروع</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($branchs))
                    @foreach ($branchs as $b)
                        <tr>
                            <td>{{ $b->id }}</td>
                            <td>{{ $b->branch }} </td>
                            <td class="action-buttons">
                                <button class="edit-btn"onclick="openModal('{{$b->id}}', ' {{$b->branch}}')">تعديل<i class="fa-solid fa-pen-to-square"></i></button>
                                <button class="delete-btn">حذف<i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <div class="pagination" id="branches-pagination"></div>

        <input type="text" class="search-input" placeholder="بحث في الحسابات..."
            onkeyup="searchTable(this, 'accounts-table')">
        <table id="accounts-table">
            <thead>
                <tr>
                    <th onclick="sortTable(0, 'accounts-table')">الرقم</th>
                    <th onclick="sortTable(1, 'accounts-table')">الحسابات</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>حساب 1</td>
                    <td class="action-buttons">
                        <button class="edit-btn">تعديل</button>
                        <button class="delete-btn">حذف</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="pagination" id="accounts-pagination"></div>

        <input type="text" class="search-input" placeholder="بحث في الأصناف..."
            onkeyup="searchTable(this, 'categories-table')">
        <table id="categories-table">
            <thead>
                <tr>
                    <th onclick="sortTable(0, 'categories-table')">الرقم</th>
                    <th onclick="sortTable(1, 'categories-table')">الأصناف</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($data))
                    @foreach ($data as $d)
                        <tr>
                            <td>{{ $d->id }}</td>
                            <td>{{ $d->item }} </td>
                            <td class="action-buttons">
                                <button class="edit-btn">تعديل<i class="fa-solid fa-pen-to-square"></i></button>
                                <button class="delete-btn">حذف<i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                @endif

            </tbody>
        </table>
        <div class="pagination" id="categories-pagination"></div>
    </div>
    </div>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form id="editForm">
                <label for="branchName">اسم الفرع (قديم):</label>
                <input type="text" id="branchName" name="branchName" disabled>
    
                <label for="branchId">رقم الفرع:</label>
                <input type="text" id="branchId" name="branchId" disabled>
    
                <label for="name">الاسم الجديد:</label>
                <input type="text" id="name" name="name" required>
    
                <input type="hidden" id="itemId" name="itemId">
                <button type="submit">حفظ التعديلات</button>
            </form>
        </div>
    </div>
    
    <script>
        // الحصول على العناصر
        var modal = document.getElementById("myModal");
        var span = document.getElementsByClassName("close")[0];
        var form = document.getElementById("editForm");
        var itemIdInput = document.getElementById("itemId");
        var branchNameInput = document.getElementById("branchName");
        var branchIdInput = document.getElementById("branchId");
        var nameInput = document.getElementById("name");
    
        // عندما يتم الضغط على زر التعديل، تظهر النافذة المنبثقة مع ID واسم الفرع ورقم الفرع
        function openModal(id, branchName) {
            itemIdInput.value = id;
            branchNameInput.value = branchName;
            branchIdInput.value = id; // رقم الفرع هو نفسه الـ ID
            nameInput.value = ""; // يمكن تخصيص هذا لإظهار الاسم الجديد
            modal.style.display = "block";
        }
    
        // عندما يتم الضغط على زر الإغلاق، تختفي النافذة المنبثقة
        span.onclick = function() {
            modal.style.display = "none";
        }
    
        // عندما يتم النقر في أي مكان خارج النافذة المنبثقة، تختفي النافذة
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    
        // إرسال الفورم (يمكنك تعديل هذا الجزء لمعالجة البيانات كما تريد)
        form.onsubmit = function(event) {
            event.preventDefault();
            alert("ID: " + itemIdInput.value + "\nBranch Name: " + branchNameInput.value + "\nBranch ID: " + branchIdInput.value + "\nNew Name: " + nameInput.value);
            modal.style.display = "none";
        }
    </script>
    



@endsection
