@extends('include.app')
@section('title')
    سند قبض
@endsection

@section('page')
    سند قبض
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
            <h2 class="custom-form-title"> سند قبض </h2>
            <p></p>
        </div>
        <form id="detailsForm" action="{{ route('receipt.store') }}" method="POST">
            @csrf
            <div class="custom-form-fields">
                <div class="custom-form-group third-width">
                    <label for="customer_name">استلمت من:</label>
                    <input type="text" id="customer_name" name="customer_name" required>
                </div>
                <div class="custom-form-group third-width">
                    <label for="price">مبلغ وقدره:</label>
                    <input type="float" id="price" name="amount" required>
                </div>
                <div class="custom-form-group third-width">
                    <label for="paymentType">نوع الدفع:</label>
                    <select id="paymentType" name="payment_method" onchange="toggleAccountField()" required>
                        <option value="cash">نقد</option>
                        <option value="credit">تحويل</option>
                    </select>
                </div>
                <div class="custom-form-group fourth-width">
                    <label for="date">التاريخ:</label>
                    <input type="date" id="date" name="date" required>
                </div>
                <div class="custom-form-group half-width">
                    <label for="description">وذلك مقابل:</label>
                    <input type="text" id="description" name="detail" required>
                </div>
                <div class="custom-form-group">
                    <button type="submit">إضافة</button>
                </div>
            </div>
        </form>
    </div>
    <h2>جدول عرض البيانات</h2>
    <input type="text" class="search-input" placeholder="بحث في السندات..." onkeyup="searchTable(this, 'receipt-table')">
    <table id="receipt-table">
        <thead>
            <tr>
                <th onclick="sortTable(0, 'receipt-table')">الرقم</th>
                <th onclick="sortTable(1, 'receipt-table')">اسم العميل</th>
                <th onclick="sortTable(2, 'receipt-table')"> المبلغ</th>
                <th onclick="sortTable(3, 'receipt-table')">نوع الدفع</th>
                <th onclick="sortTable(4, 'receipt-table')">التاريخ</th>
                <th onclick="sortTable(5, 'pro-table')">بمقابل</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($receipts))
                @foreach ($receipts as $r)
                    <tr>
                        <td>{{ $r->id }}</td>
                        <td>{{ $r->customer_name }}</td>
                        <td>{{ $r->amount }}</td>
                        <td>{{ $r->payment_method }}</td>
                        <td>{{ $r->date }}</td>
                        <td>{{ $r->detail }}</td>
                        <td class="action-buttons">
                            <button class="btn btn-success"
                                onclick="window.location.href='/receipt/printreceipt/{{ $r->id }}'">
                                طباعة <i class="fa-solid fa-print"></i>

                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7">لا توجد بيانات لعرضها</td>
                </tr>
            @endif
        </tbody>
    </table>
    <div class="pagination" id="receipt-pagination"></div>

@endsection
