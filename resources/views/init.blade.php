@extends('include.app')
@section('title')
التهيئة
@endsection

@section('page')
التهيئة
@endsection

@section('main')
<div class="container">
    <div class="form-container">
        <h3>إضافة فرع</h3>
        <form>
            <div class="form-group">
                <label for="branch-name">اسم الفرع</label>
                <input type="text" id="branch-name" name="branch-name" required>
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
        <form>
            <div class="form-group">
                <label for="category-name">اسم الصنف</label>
                <input type="text" id="category-name" name="category-name" required>
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
            <tr>
                <td>1</td>
                <td>فرع 1</td>
                <td class="action-buttons">
                    <button class="edit-btn">تعديل<i class="fa-solid fa-pen-to-square"></i></button>
                    <button class="delete-btn">حذف<i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td>فرع 2</td>
                <td class="action-buttons">
                    <button class="edit-btn">تعديل</button>
                    <button class="delete-btn">حذف</button>
                </td>
            </tr>
            <tr>
                <td>3</td>
                <td>فرع 3</td>
                <td class="action-buttons">
                    <button class="edit-btn">تعديل</button>
                    <button class="delete-btn">حذف</button>
                </td>
            </tr>
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
            <tr>
                <td>1</td>
                <td>صنف 1</td>
                <td class="action-buttons">
                    <button class="edit-btn">تعديل</button>
                    <button class="delete-btn">حذف</button>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="pagination" id="categories-pagination"></div>
</div>
</div>
@endsection

