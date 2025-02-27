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
    <h2>جدول عرض البيانات</h2>
    <table>
        <thead>
            <tr>
                <th>التفصيل</th>
                <th>الصنف</th>
                <th>الفرع</th>
                <th>الحساب</th>
                <th>السعر الإجمالي</th>
                <th>الضريبة</th>
                <th>صافي السعر</th>
                <th>التاريخ</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($details))
                @foreach ($details as $d)
                    <tr>
                        <td>{{ $d->detail }}</td>
                        @foreach ($items as $i)
                            @if ($i->id == $d->item_id)
                                <td>{{ $i->item }}</td>
                            @endif
                        @endforeach
                        @foreach ($Branch as $b)
                            @if ($b->id == $d->branch_id)
                                <td>{{ $b->branch }}</td>
                            @endif
                        @endforeach
                        @foreach ($accounts as $a)
                            @if ($a->id == $d->account_id)
                                <td>{{ $a->account }}</td>
                            @endif
                        @endforeach
                        <td>{{ $d->total }}</td>
                        <td>{{ $d->tax }}</td>
                        <td>{{ $d->price }}</td>
                        <td>{{ $d->created_at }}</td>
                        <td class="action-buttons">
                            <button class="edit-btn">تعديل<i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="delete-btn">حذف<i class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
@endsection
