@extends('include.app')
@section('title')
    تقرير المخزون
@endsection

@section('page')
    تقرير المخزون
@endsection
@section('main')
    <style>
        .page-border {
            border: 5px solid #000;
            /* تحديد الحدود بعرض 5 بكسل */
            padding: 15px;
            /* مسافة داخلية بين الحدود والمحتوى */
            margin: 10px auto;
            /* ضبط الهوامش */
            width: 100%;
            box-sizing: border-box;
            /* ضمان احتساب الحواف مع الحجم الكلي */
        }

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

        th,
        td {
            border: 1px solid #000;
            text-align: center;
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
        <h2>تقرير شهر: {{ $month }}/{{ $year }}</h2>
        <table class="printable-content">
            <thead>
                @php
                    $total = 1;
                @endphp
                @foreach ($branches as $b)
                    @php
                        $total += 1;
                    @endphp
                @endforeach
                <tr>
                    <th rowspan="2">الصنف</th>
                    <th colspan="{{ $total }}">رصيد اول المدة</th>
                    <th colspan="{{ $total }}">نزل خلال الشهر</th>
                    <th colspan="{{ $total }}">الاجمالي</th>
                    <th colspan="{{ $total }}">رصيد اخر المدة</th>
                    <th colspan="{{ $total }}">المنصرف</th>
                </tr>
                <tr>
                    @if (isset($branches))
                        @foreach ($branches as $b)
                            <th>{{ $b->branch }}</th>
                        @endforeach
                        <th> الاجمالي</th>
                    @endif
                    @if (isset($branches))
                        @foreach ($branches as $b)
                            <th>{{ $b->branch }}</th>
                        @endforeach
                        <th> الاجمالي</th>
                    @endif
                    @if (isset($branches))
                        @foreach ($branches as $b)
                            <th>{{ $b->branch }}</th>
                        @endforeach
                        <th> الاجمالي</th>
                    @endif
                    @if (isset($branches))
                        @foreach ($branches as $b)
                            <th>{{ $b->branch }}</th>
                        @endforeach
                        <th> الاجمالي</th>
                    @endif
                    @if (isset($branches))
                        @foreach ($branches as $b)
                            <th>{{ $b->branch }}</th>
                        @endforeach
                        <th> الاجمالي</th>
                    @endif
            </thead>
            <tbody>
                @if (isset($items))
                    @foreach ($items as $i)
                        <tr>
                            @php
                                $total = 0;
                                $count = 0;
                                $sum_total = [];
                                $total_total = 0;
                            @endphp
                            <td>{{ $i->item }}</td>
                            @if (isset($branches))
                                @foreach ($branches as $b)
                                    @php
                                        $sum_total[$b->id] = 0;
                                    @endphp
                                @endforeach
                                @foreach ($branches as $b)
                                    @if (isset($inventories))
                                        @foreach ($inventories as $inv)
                                            @if ($inv->branch_id == $b->id && $inv->item_id == $i->id)
                                                <td>{{ $inv->first_inventory }}</td>
                                                @php
                                                    $total += $inv->first_inventory;
                                                    $total_total += $inv->first_inventory;
                                                    $sum_total[$b->id] += $inv->first_inventory;
                                                @endphp
                                            @else
                                                @php
                                                    $count += 1;
                                                @endphp
                                            @endif
                                        @endforeach
                                    @endif
                                    @if ($count == count($inventories))
                                        <td></td>
                                    @endif
                                    @php
                                        $count = 0;
                                    @endphp
                                @endforeach
                                <td>{{ $total }}</td>
                                @php
                                    $total = 0;
                                @endphp
                                @foreach ($branches as $b)
                                    @if (isset($details))
                                        @foreach ($details as $d)
                                            @if ($d->branch_id == $b->id && $d->item_id == $i->id)
                                                <td>{{ $d->total_sum }}</td>
                                                @php
                                                    $total += $d->total_sum;
                                                    $total_total += $d->total_sum;
                                                    $sum_total[$b->id] += $d->total_sum;
                                                @endphp
                                            @else
                                                @php
                                                    $count += 1;
                                                @endphp
                                            @endif
                                        @endforeach
                                        @if ($count == count($details))
                                            <td></td>
                                        @endif
                                        @php
                                            $count = 0;
                                        @endphp
                                    @endif
                                @endforeach
                                <td>{{ $total }}</td>
                                @php
                                    $total = 0;
                                @endphp
                                @foreach ($branches as $b)
                                    <td>{{ $sum_total[$b->id] }}</td>
                                @endforeach
                                <td>{{ $total_total }}</td>
                                @foreach ($branches as $b)
                                    @if (isset($inventories))
                                        @foreach ($inventories as $inv)
                                            @if ($inv->branch_id == $b->id && $inv->item_id == $i->id)
                                                <td>{{ $inv->last_inventory }}</td>
                                                @php
                                                    $total += $inv->last_inventory;
                                                @endphp
                                            @else
                                                @php
                                                    $count += 1;
                                                @endphp
                                            @endif
                                        @endforeach
                                    @endif
                                    @if ($count == count($inventories))
                                        <td></td>
                                    @endif
                                    @php
                                        $count = 0;
                                    @endphp
                                @endforeach
                                <td>{{ $total }}</td>
                                @php
                                    $total = 0;
                                @endphp
                                @foreach ($branches as $b)
                                    @if (isset($inventories))
                                        @foreach ($inventories as $inv)
                                            @if ($inv->branch_id == $b->id && $inv->item_id == $i->id)
                                                <td>{{ $inv->inventory_out }}</td>
                                                @php
                                                    $total += $inv->inventory_out;
                                                @endphp
                                            @else
                                                @php
                                                    $count += 1;
                                                @endphp
                                            @endif
                                        @endforeach
                                    @endif
                                    @if ($count == count($inventories))
                                        <td></td>
                                    @endif
                                    @php
                                        $count = 0;
                                    @endphp
                                @endforeach
                                <td>{{ $total }}</td>
                                @php
                                    $total = 0;
                                @endphp
                            @endif

                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script>
        function generatePDF() {
            const element = document.querySelector('.printable-content');
            const options = {
                margin: 3, // الهوامش داخل الملف
                filename: 'report_with_border.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a4',
                    orientation: 'portrait'
                }
            };
            html2pdf().set(options).from(element).save();
        }
    </script>


@endsection
