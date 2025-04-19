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

    <!-- التسجيل اليومي -->
    <h2>تقرير التسجيل اليومي الشهري</h2>
    <form action="{{ route('reports.monthly') }}" method="post">
        @csrf
        <label for="month">اختر الشهر:</label>
        <select name="month" id="month">
            @for ($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>{{ $m }}</option>
            @endfor
        </select>

        <label for="year">اختر السنة:</label>
        <select name="year" id="year">
            @for ($y = 2023; $y <= now()->year; $y++)
                <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
        <button type="submit" class="print_btn"> عرض التقرير<i class=""></i></button>
    </form>
    <!-- المخزون  -->
    <h2>تقرير المخزون الشهري</h2>
    <form action="{{ route('reports.inventory') }}" method="post">
        @csrf
        <label for="month">اختر الشهر:</label>
        <select name="month" id="month">
            @for ($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>{{ $m }}</option>
            @endfor
        </select>

        <label for="year">اختر السنة:</label>
        <select name="year" id="year">
            @for ($y = 2023; $y <= now()->year; $y++)
                <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}
                </option>
            @endfor
        </select>
        <button type="submit" class="print_btn"> عرض التقرير<i class=""></i></button>
    </form>
    <!-- تقرير التسجيل الشهري حسب الكاشير -->

    <h2>تقرير الكاشير اليومي الشهري</h2>
    <form action="{{ route('reports.casher') }}" method="post">
        @csrf
        <label for="casher"> الكاشير:</label>
        <select name="casher" id="casher">
            @foreach ($cashers as $casher)
                <option value="{{ $casher->id }}">{{ $casher->casher }}</option>
            @endforeach
        </select>

        <label for="month"> الشهر:</label>
        <select name="month" id="month">
            @for ($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>{{ $m }}
                </option>
            @endfor
        </select>

        <label for="year"> السنة:</label>
        <select name="year" id="year">
            @for ($y = 2023; $y <= now()->year; $y++)
                <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}
                </option>
            @endfor
        </select>

        <button type="submit" class="print_btn">عرض التقرير<i class=""></i></button>
    </form>

    <!-- تقارير الفروع -->


    <h2>تقرير عمليات الكواشير لفرع معين خلال شهر</h2>
    <form action="{{ route('reports.branch') }}" method="post">
        @csrf
        <label for="branch"> الفروع:</label>
        <select name="branch" id="branch">
            @foreach ($branches as $branch)
                <option value="{{ $branch->id }}">{{ $branch->branch }}</option>
            @endforeach
        </select>

        <label for="month"> الشهر:</label>
        <select name="month" id="month">
            @for ($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>{{ $m }}
                </option>
            @endfor
        </select>

        <label for="year"> السنة:</label>
        <select name="year" id="year">
            @for ($y = 2023; $y <= now()->year; $y++)
                <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}
                </option>
            @endfor
        </select>

        <button type="submit" class="print_btn">عرض التقرير<i class=""></i></button>
    </form>
    <h2>التقرير الاجمالي لعمليات الكاشير خلال الشهر </h2>
    <form action="{{ route('reports.total') }}" method="post">
        @csrf
        <label for="month">اختر الشهر:</label>
        <select name="month" id="month">
            @for ($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>{{ $m }}
                </option>
            @endfor
        </select>

        <label for="year">اختر السنة:</label>
        <select name="year" id="year">
            @for ($y = 2023; $y <= now()->year; $y++)
                <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}
                </option>
            @endfor
        </select>
        <button type="submit" class="print_btn"> عرض التقرير<i class=""></i></button>
    </form>
@endsection
