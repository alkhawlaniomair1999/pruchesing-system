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
        <h2 class="custom-form-title">نموذج إضافة قيد تسديد المورد</h2>
    </div>
    <form id="detailsForm" action="{{ route('supplier.det2') }}" method="POST">
        @csrf

        <div class="custom-form-group third-width">
            <label for="price">المبلغ:</label>
            <input type="float" id="price" name="price" required>
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
        <div class="custom-form-group third-width">
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
        <div class="custom-form-group third-width">
            <label for="date">التاريخ:</label>
            <input type="date" id="date" name="date" required>
        </div>

        <div class="custom-form-group">
            <button type="submit">إضافة</button>
        </div>
    </form>
</div>

@endsection
