@extends('include.app')
@section('title')
    التسجيل اليومي
@endsection

@section('page')
    التسجيل اليومي
@endsection

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
                            <td>{{ $d1->account }} </td>
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
