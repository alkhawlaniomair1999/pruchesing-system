@extends('include.app')
@section('title')
    التقارير
@endsection

@section('page')
    تقارير
@endsection
@section('main')
    <style>
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .company-info {
            width: 45%;
        }

        .company-info-right {
            text-align: right;
            direction: rtl;
        }

        .company-info-left {
            text-align: left;
            direction: ltr;
        }

        .logo {
            text-align: center;
            margin-bottom: 10px;
        }

        .logo img {
            max-width: 120px;
            height: auto;
        }
    </style>
    <button class="print_btn" onclick="generatePDF()">طباعة<i class="fa-solid fa-print"></i></button>
    <button class="print_btn" onclick="window.location.href='{{ url('emps') }}'">
        عودة <i class="fa-solid fa-arrow-right"></i>
    </button>
    <div class="printable-content">
        <div class="header">
            <div class="company-info company-info-right">
                <h2> مطعم الجمار الرابع لتقديم الوجبات </h2>
                <p>ابو عريش
                    حي الروضة
                    شارع الامير محمد بن ناصر </p>

            </div>
            <div class="logo">

                <img src="{{ asset('assets/img/jammar2.png') }}" alt="Logo">
            </div>
            <div class="company-info company-info-left">
                <p>سجل تجاري رقم 5901719945</p>
                <p>الرقم الضريبي 310767346400003</p>

            </div>
        </div>
        <h3>تقرير بيانات الموظفين لشهر:
            <?php
            // الحصول على التاريخ قبل أسبوع
            $dateBeforeWeek = strtotime('-2 week');
            // مصفوة بأسماء الأشهر باللغة العربية
            $months = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
            // الحصول على اسم الشهر
            $currentMonth = $months[date('n', $dateBeforeWeek) - 1];
            echo $currentMonth;
            ?>
        </h3>
        <table>
            <thead>
                <tr>
                    <th>رقم الموظف</th>
                    <th>اسم الموظف</th>
                    <th>الفرع</th>
                    <th>الجنسية</th>
                    <th>الراتب</th>
                    <th>السلفة</th>
                    <th>الغياب</th>
                    <th>الخصم</th>
                    <th>الراتب بعد السلف والخصم والغياب</th>
                    <th>مسلم صرافة</th>
                    <th>المتبقي من الراتب</th>
                </tr>

            </thead>
            <tbody>
                @if (isset($emps) && isset($dis))
                    @foreach ($emps as $e)
                        @foreach ($dis as $d)
                            @if ($e->id == $d->emp_id)
                                <tr>
                                    <td>{{ $e->id }}</td>
                                    <td>{{ $e->name_emp }}</td>
                                    <td>{{ $e->branch }}</td>
                                    <td>{{ $e->country }}</td>
                                    <td>{{ $e->salary }}</td>
                                    <td>{{ $d->slf }}</td>
                                    <td>{{ $d->absence }}</td>
                                    <td>{{ $d->discount }}</td>
                                    <td>{{ $e->salary - ($d->slf + $d->absence * ($e->salary / 30) + $d->discount) }}</td>
                                    <td>{{ $d->bank }}</td>
                                    <td>{{ $e->salary - ($d->slf + $d->absence * ($e->salary / 30) + $d->discount + $d->bank) }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script>
        function generatePDF() {
            const element = document.querySelector('.printable-content');
            html2pdf(element);
        }
    </script>
@endsection
