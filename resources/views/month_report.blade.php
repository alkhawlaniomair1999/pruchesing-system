@extends('include.app')
@section('title')
    التقارير
@endsection

@section('page')
    تقارير
@endsection
@section('main')
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
            @for ($y = 2020; $y <= now()->year; $y++)
                <option value="{{ $y }}">{{ $y }}</option>
            @endfor
        </select>

        <button type="submit">عرض التقرير</button>
    </form>
@endsection
