@extends('include.app')
@section('title')
    التقارير
@endsection

@section('page')
    تقارير
@endsection
@section('main')
    <style>
        select {
            padding: 10px;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #ccc;
            background-color: #fff;
            appearance: none;
            /* إخفاء السهم */
            background-position: right 10px center;
            background-repeat: no-repeat;
        }
    </style>
    <h2>تقرير التسجيل اليومي الشهري</h2>
    <form action="{{ route('reports.monthly') }}" method="post">
        @csrf
        <label for="month">اختر الشهر:</label>
        <select name="month" id="month">
            @for ($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}">{{ $m }}</option>
            @endfor
        </select>

        <label for="year">اختر السنة:</label>
        <select name="year" id="year">
            @for ($y = 2023; $y <= now()->year; $y++)
                <option value="{{ $y }}">{{ $y }}</option>
            @endfor
        </select>
        <button type="submit" class="edit-btn"> عرض التقرير<i class=""></i></button>
    </form>
    <h2>تقرير الكاشير اليومي الشهري</h2>
    <form action="{{ route('reports.casher') }}" method="post">
        @csrf
        <label for="casher">اختر الكاشير:</label>
        <select name="casher" id="casher">
            @foreach ($cashers as $casher)
                <option value="{{ $casher->id }}">{{ $casher->casher }}</option>
            @endforeach
        </select>
        <label for="month">اختر الشهر:</label>
        <select name="month" id="month">
            @for ($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}">{{ $m }}</option>
            @endfor
        </select>

        <label for="year">اختر السنة:</label>
        <select name="year" id="year">
            @for ($y = 2023; $y <= now()->year; $y++)
                <option value="{{ $y }}">{{ $y }}</option>
            @endfor
        </select>
        <button type="submit" class="edit-btn"> عرض التقرير<i class=""></i></button>
    </form>
@endsection
