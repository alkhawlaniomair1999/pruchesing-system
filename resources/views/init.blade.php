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
            <form method="POST" action="{{ route('account.store') }}">
                @csrf
                <div class="form-group">
                    <label for="account_name">اسم الحساب</label>
                    <input type="text" id="account_name" name="account_name" required>
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
                                <button class="edit-btn">تعديل<i class="fa-solid fa-pen-to-square"></i></button>
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
                @if (isset($data1))
                @foreach ($data1 as $d1)
                    <tr>
                        <td>{{ $d1->id }}</td>
                        <td>{{ $d1->account}} </td>
                        <td class="action-buttons">
                            <button class="edit-btn">تعديل<i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="delete-btn">حذف<i class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
            @endif
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
@endsection
