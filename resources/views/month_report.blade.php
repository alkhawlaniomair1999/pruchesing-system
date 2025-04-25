@extends('include.app')
@section('title')
    التقارير
@endsection

@section('page')
    تقارير
@endsection

@section('main')
    <style>
        /* تحسين تصميم الحقول والأزرار */
        select, button {
            padding: 12px 15px;
            font-size: 16px;
            border-radius: 10px;
            border: 1px solid #007BFF;
            background-color: #f9f9f9;
            transition: all 0.3s ease;
        }

        select:focus, button:hover {
            border-color: #0056b3;
            background-color: #e6f2ff;
        }

        button {
            display: flex;
            align-items: center;
            gap: 8px;
            background-color: #007BFF;
            color: #fff;
            cursor: pointer;
            border: none;
        }

        button i {
            font-size: 18px;
        }

        button:hover {
            background-color: #0056b3;
        }

        label {
            font-weight: bold;
            margin-right: 10px;
        }

        fieldset {
            border: 2px solid #ccc;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        legend {
            font-weight: bold;
            font-size: 18px;
            color: #000;
            padding: 0 10px;
        }

        .form-row {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap; /* يسمح بالتفاف العناصر عند ضيق المساحة */
        }
    </style>

    <!-- تقرير التسجيل اليومي الشهري -->
    <fieldset>
        <legend>تقرير التسجيل اليومي الشهري</legend>
        <form action="{{ route('reports.monthly') }}" method="post">
            @csrf
            <div class="form-row">
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
                <button type="submit">
                    <i class="fas fa-calendar-alt"></i> عرض التقرير
                </button>
            </div>
        </form>
    </fieldset>

    <!-- تقرير التسجيل اليومي الشهري بدون الضريبة -->
    <fieldset>
        <legend>تقرير التسجيل اليومي الشهري بدون الضريبة</legend>
        <form action="{{ route('reports.monthly_tax') }}" method="post">
            @csrf
            <div class="form-row">
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
                <button type="submit">
                    <i class="fas fa-percent"></i> عرض التقرير
                </button>
            </div>
        </form>
    </fieldset>

    <!-- تقرير المخزون الشهري -->
    <fieldset>
        <legend>تقرير المخزون الشهري</legend>
        <form action="{{ route('reports.inventory') }}" method="post">
            @csrf
            <div class="form-row">
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
                <button type="submit">
                    <i class="fas fa-box"></i> عرض التقرير
                </button>
            </div>
        </form>
    </fieldset>

    <!-- تقرير الكاشير اليومي الشهري -->
    <fieldset>
        <legend>تقرير الكاشير اليومي الشهري</legend>
        <form action="{{ route('reports.casher') }}" method="post">
            @csrf
            <div class="form-row">
                <label for="casher">الكاشير:</label>
                <select name="casher" id="casher">
                    @foreach ($cashers as $casher)
                        <option value="{{ $casher->id }}">{{ $casher->casher }}</option>
                    @endforeach
                </select>

                <label for="month">الشهر:</label>
                <select name="month" id="month">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>{{ $m }}</option>
                    @endfor
                </select>

                <label for="year">السنة:</label>
                <select name="year" id="year">
                    @for ($y = 2023; $y <= now()->year; $y++)
                        <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                <button type="submit">
                    <i class="fas fa-user"></i> عرض التقرير
                </button>
            </div>
        </form>
    </fieldset>

    <!-- تقرير عمليات الكواشير لفرع معين خلال شهر -->
    <fieldset>
        <legend>تقرير عمليات الكواشير لفرع معين خلال شهر</legend>
        <form action="{{ route('reports.branch') }}" method="post">
            @csrf
            <div class="form-row">
                <label for="branch">الفروع:</label>
                <select name="branch" id="branch">
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->branch }}</option>
                    @endforeach
                </select>

                <label for="month">الشهر:</label>
                <select name="month" id="month">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>{{ $m }}</option>
                    @endfor
                </select>

                <label for="year">السنة:</label>
                <select name="year" id="year">
                    @for ($y = 2023; $y <= now()->year; $y++)
                        <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                <button type="submit">
                    <i class="fas fa-map-marker-alt"></i> عرض التقرير
                </button>
            </div>
        </form>
    </fieldset>

    <!-- التقرير الإجمالي لعمليات الكاشير خلال الشهر -->
    <fieldset>
        <legend>التقرير الإجمالي لعمليات الكاشير خلال الشهر</legend>
        <form action="{{ route('reports.total') }}" method="post">
            @csrf
            <div class="form-row">
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
                <button type="submit">
                    <i class="fas fa-chart-bar"></i> عرض التقرير
                </button>
            </div>
        </form>
    </fieldset>
@endsection