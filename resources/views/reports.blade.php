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
        <h2>تقرير شهر: {{ $month }}/{{ $year }}</h2>
        <table class="printable-content">
            <thead>
                <tr>
                    <th>الصنف</th>
                    <th>الاجمالي</th>
                    @if (isset($branches))
                        @foreach ($branches as $b)
                            <th>{{ $b->branch }}</th>
                        @endforeach
                    @endif

                </tr>
            </thead>
            <tbody>
                @if (isset($items))
                    @foreach ($items as $i)
                        @php
                            $total = 0;
                        @endphp
                        <tr>
                            <td>{{ $i->item }}</td>
                            @foreach ($details as $d)
                                @if ($d->item_id == $i->id)
                                    @php
                                        $total += $d->total;
                                    @endphp
                                @endif
                            @endforeach
                            <td>{{ $total }}</td>
                            @if (isset($branches))
                                @foreach ($branches as $b)
                                    @php
                                        $total = 0;
                                    @endphp
                                    @foreach ($details as $d)
                                        @if ($d->item_id == $i->id && $d->branch_id == $b->id)
                                            @php
                                                $total += $d->total;
                                            @endphp
                                        @endif
                                    @endforeach
                                    <td>{{ $total }}</td>
                                @endforeach
                            @endif
                        </tr>
                    @endforeach
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <th>الاجمالي: </th>
                    @php
                        $total = 0;
                    @endphp
                    @if (isset($details))
                        @foreach ($details as $det)
                            @php
                                $total += $det->total;
                            @endphp
                        @endforeach
                    @endif
                    <th>{{ $total }}</th>
                    @if (isset($branches))
                        @foreach ($branches as $b)
                            @php
                                $total = 0;
                            @endphp
                            @if (isset($details))
                                @foreach ($details as $det)
                                    @if ($det->branch_id == $b->id)
                                        @php
                                            $total += $det->total;
                                        @endphp
                                    @endif
                                @endforeach
                            @endif
                            <th>{{ $total }}</th>
                        @endforeach
                    @endif
                </tr>
            </tfoot>

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
