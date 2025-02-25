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
        <table>
            <thead>
                <tr>
                    <th>الفروع</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>فرع 1</td>
                    <td class="action-buttons">
                        <button class="edit-btn">تعديل</button>
                        <button class="delete-btn">حذف</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <table>
            <thead>
                <tr>
                    <th>الحسابات</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>حساب 1</td>
                    <td class="action-buttons">
                        <button class="edit-btn">تعديل</button>
                        <button class="delete-btn">حذف</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <table>
            <thead>
                <tr>
                    <th>الأصناف</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>صنف 1</td>
                    <td class="action-buttons">
                        <button class="edit-btn">تعديل</button>
                        <button class="delete-btn">حذف</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>






@endsection

