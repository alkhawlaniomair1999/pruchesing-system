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
    <button class="print_btn" onclick="window.location.href='{{ url('reports') }}'">
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
        <h2>التقرير الاجمالي لعمليات الكاشير لشهر:
            @switch($month)
                @case(1)
                    يناير
                @break

                @case(2)
                    فبراير
                @break

                @case(3)
                    مارس
                @break

                @case(4)
                    أبريل
                @break

                @case(5)
                    مايو
                @break

                @case(6)
                    يونيو
                @break

                @case(7)
                    يوليو
                @break

                @case(8)
                    أغسطس
                @break

                @case(9)
                    سبتمبر
                @break

                @case(10)
                    أكتوبر
                @break

                @case(11)
                    نوفمبر
                @break

                @case(12)
                    ديسمبر
                @break

                @default
                    رقم الشهر غير صحيح
            @endswitch
            -
            {{ $year }}
        </h2>
        @php
            $sum_total = 0;
            $sum_bank = 0;
            $sum_cash = 0;
            $sum_out = 0;
            $sum_plus = 0;
        @endphp




        <table>
            <thead>
                <tr>
                    <th>التاريخ</th>
                    <th>المبيعات</th>
                    <th>البنك</th>
                    <th>الكاش</th>
                    <th>المصروفات</th>
                    <th>عجز-زيادة</th>
                </tr>

            </thead>
            <tbody>

                @if (isset($operations))
                    @foreach ($operations as $op)
                        <tr>

                            <td>{{ $op->operation_date }}</td>
                            <td>{{ $op->total_sum }}</td>

                            <td>{{ $op->bank_sum }}</td>
                            <td>{{ $op->cash_sum }}</td>
                            <td>{{ $op->out_sum }}</td>
                            <td>{{ $op->plus_sum }}</td>


                        </tr>
                        @php
                            $sum_total += $op->total_sum;
                            $sum_bank += $op->bank_sum;
                            $sum_cash += $op->cash_sum;
                            $sum_out += $op->out_sum;
                            $sum_plus += $op->plus_sum;
                        @endphp
                    @endforeach
                @endif
            <tfoot>
                <tr>
                    <th>الاجمالي:</th>
                    <th>{{ $sum_total }}</th>
                    <th>{{ $sum_bank }}</th>
                    <th>{{ $sum_cash }}</th>
                    <th>{{ $sum_out }}</th>
                    <th>{{ $sum_plus }}</th>


                </tr>


            </tfoot>



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
