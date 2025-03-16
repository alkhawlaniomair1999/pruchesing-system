@extends('include.app')
@section('title')
    التوريد
@endsection

@section('page')
    التوريد
@endsection
@section('main')
    <div class="custom-form-container">
        <div class="custom-form-header">
            <button type="button" id="toggleButton" onclick="toggleForm()">-</button>
            <h2 class="custom-form-title">نموذج إضافة قيد تسديد المورد</h2>
            <p></p>
        </div>
        <form id="detailsForm" action="{{ route('pay.storepay') }}" method="POST">
            @csrf
            <div class="custom-form-fields">
                <div class="custom-form-group third-width">
                    <label for="description">التفصيل:</label>
                    <input type="text" id="description" name="details" required>
                </div>
                <div class="custom-form-group third-width">
                    <label for="price">المبلغ:</label>
                    <input type="float" id="price" name="amount" required>
                </div>
                <div class="custom-form-group third-width">
                    <label for="supplier"> حساب المورد:</label>
                    <select id="supplier" name="supplier" required>
                        @if (isset($suppliers))
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->supplier }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="custom-form-group fourth-width">
                    <label for="account"> حساب:</label>
                    <select id="account" name="account">
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

    <h2>جدول عرض البيانات</h2>
    <input type="text" class="search-input" placeholder="بحث في السندات..." onkeyup="searchTable(this, 'pro-table')">
    <table id="pro-table">
        <thead>
            <tr>
                <th onclick="sortTable(0, 'pro-table')">الرقم</th>
                <th onclick="sortTable(1, 'pro-table')">التفصيل</th>
                <th onclick="sortTable(4, 'pro-table')">المبلغ</th>
                <th onclick="sortTable(5, 'pro-table')"> المورد</th>
                <th onclick="sortTable(6, 'pro-table')">الحساب</th>
                <th onclick="sortTable(8, 'pro-table')">التاريخ</th>
                <th onclick="sortTable(9, 'pro-table')">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($proc))
                @foreach ($proc as $p)
                    <tr>
                        <td>{{ $p->id }}</td>
                        <td>{{ $p->details }}</td>
                        <td>{{ number_format($p->amount, 2) }}</td>
                        @foreach ($suppliers as $s)
                                @if ($s->id == $p->supplier)
                                    <td>{{ $s->supplier }}</td>
                                    @endif
                                    @endforeach
                                    @foreach ($accounts as $a)
                                @if ($a->id == $p->account)
                                    <td>{{ $a->account }}</td>
                                    @endif

                                    @endforeach
                      
                        <td>{{ $p->date }}</td>
                        <td class="action-buttons">
                            <button class="edit-btn" onclick="openModal(this)">تعديل<i
                                    class="fa-solid fa-pen-to-square"></i></button>
                            <button class="delete-btn" onclick="confirmDelete({{ $p->id }})">حذف<i
                                    class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    <div class="pagination" id="pro-pagination"></div>
@endsection
