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
            <h2 class="custom-form-title">نموذج إضافة قيد</h2>
            <p></p>
        </div>
        <form id="detailsForm" action="{{ route('supplier.det') }}" method="POST">
            @csrf
            <div class="custom-form-fields">
                <div class="custom-form-group third-width">
                    <label for="description">التفصيل:</label>
                    <input type="text" id="description" name="detail" required>
                </div>

                <div class="custom-form-group fourth-width">
                    <label for="price">المبلغ:</label>
                    <input type="float" id="price" name="price" required>
                </div>

                <div class="custom-form-group third-width">
                    <label for="paymentType">نوع السند:</label>
                    <select id="paymentType" name="paymentType" onchange="toggleAccountField()" required>
                        <option value="cash">نقد</option>
                        <option value="credit">آجل</option>
                    </select>
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

                <div class="custom-form-group third-width" id="accountField">
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
    <br><br>
   @endsection