@extends('include.app')
@section('title')
    طباعة فاتورة ضريبية
@endsection

@section('page')
    طباعة فاتورة ضريبية
@endsection

@section('main')

    <style>
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
            text-align: right;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .invoice {
            width: 100%;
            margin: 0;
            padding: 20px;
            background-color: #fff;
            box-sizing: border-box;
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

        .details {
            border: 1px solid #000;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            font-size: 1.3em;
            /* تكبير الخط */
        }

        .details div {
            display: block;
            justify-content: space-between;
            align-items: center;
        }

        .items {
            margin-bottom: 20px;
            font-size: 1.1em;
            /* تكبير الخط */
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
        }

        th {
            font-size: 1.2em;
            /* تكبير خط العناوين */
        }

        td {
            font-size: 1.1em;
            /* تكبير خط البيانات */
        }

        .footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            font-size: 1.1em;
            /* تكبير الخط */
            border-top: 1px solid #000;
            padding-top: 10px;
        }

        .footer p {
            margin: 0;
        }

        .tb_total {
            width: 50%;
            border: 1px solid #000;
            border-radius: 5px;
            margin-top: 20px;
            padding: 10px;
            font-size: 1.2em;
            /* تكبير الخط */
        }
    </style>
    <button class="print_btn" onclick="window.print()">طباعة<i class="fa-solid fa-print"></i></button>
    <button class="print_btn" onclick="window.location.href='{{ url('invoices') }}'">
        عودة <i class="fa-solid fa-arrow-right"></i>
    </button>
    <br>
    <div class="invoice">
        <!-- Header Section -->
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

        <!-- Details Section -->
        <h3 style="text-align: center">فاتورة ضريبية</h3>
        <div class="details">
            @if (isset($invoice))
                <div class="company-info-right">
                    <p> اسم العميل :{{ $invoice->customer_name }}</p>
                    <p>الرقم الضريبي: {{ $invoice->tax_id }}</p>
                    @if (!empty($invoice->address))
                        <p>العنوان: {{ $invoice->address }}</p>
                    @endif
                    @if (!empty($invoice->phone_number))
                        <p>رقم الجوال: {{ $invoice->phone_number }}</p>
                    @endif
                </div>
                <div style="text-align: center">
                    <p>رقم الفاتورة:
                    <p style="color: red">{{ $invoice->id }}</p>
                    </p>
                </div>
                <div class="company-info-left">
                    <p>تاريخ الفاتورة: {{ $invoice->invoice_date }}</p>
                    @if (!empty($invoice->supply_date))
                        <p>تاريخ التوريد: {{ $invoice->supply_date }}</p>
                    @endif

                </div>
        </div>

        @php
            $total = 0;
            $total_discount = 0;
            $total_tax = 0;
            $net_total = 0;
        @endphp

        <!-- Items Section -->
        <div class="items">
            <table>
                <thead>
                    <tr>
                        <th>الرمز </th>
                        <th>الشرح</th>
                        <th>الكمية</th>
                        <th>السعر</th>
                        <th>الخصم</th>
                        <th>الإجمالي</th>
                        <th>الضريبة %</th>
                        <th>قيمة الضريبة</th>
                        <th>الصافي</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- أضف العناصر هنا ديناميكيًا -->
                    @if (isset($invoice_details))
                        @foreach ($invoice_details as $d)
                            <tr>
                                <td>{{ $d->product_code }}</td>
                                <td>{{ $d->product_name }}</td>
                                <td>{{ $d->quantity }}</td>
                                <td>{{ $d->price }}</td>
                                <td>
                                    {{ $d->discount }}
                                </td>
                                @php $total_discount += $d->discount @endphp

                                <td>
                                    {{ $d->price * $d->quantity - $d->discount }}
                                </td>
                                @php $total += $d->price * $d->quantity - $d->discount @endphp
                                @if ($d->tax == 'نعم')
                                    <td>15</td>
                                @else
                                    <td>0</td>
                                @endif
                                <td>
                                    @if ($d->tax == 'نعم')
                                        {{ ($d->price * $d->quantity - $d->discount) * 0.15 }}
                                        @php $total_tax += ($d->price * $d->quantity - $d->discount) * 0.15 @endphp
                                    @else
                                        0
                                    @endif
                                </td>
                                <td>
                                    @if ($d->tax == 'نعم')
                                        {{ $d->price * $d->quantity - $d->discount + ($d->price * $d->quantity - $d->discount) * 0.15 }}
                                        @php $net_total += $d->price * $d->quantity - $d->discount + ($d->price * $d->quantity - $d->discount) * 0.15 @endphp
                                    @else
                                        {{ $d->price * $d->quantity - $d->discount }}
                                        @php $net_total += $d->price * $d->quantity - $d->discount @endphp
                                    @endif
                                </td>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <table class="tb_total">
            <tr>
                <td>الاجمالي:</td>
                <td>{{ $total }}</td>
            </tr>
            <tr>
                <td>الخصم:</td>
                <td>{{ $total_discount }}</td>
            </tr>
            <tr>
                <td>الضريبة:</td>
                <td>{{ $total_tax }}</td>
            </tr>
            <tr>
                <td>الصافي:</td>
                <td>{{ $net_total }}</td>
            </tr>
        </table>
        @endif
        <!-- Footer Section -->
        <div class="footer">
            <p>طُبع بواسطة: {{ auth()->user()->name }}</p>
            {{ \Carbon\Carbon::now()->locale('ar')->translatedFormat('d F Y H:i:s') }}

            <p>شكرًا لتعاملكم معنا!</p>
        </div>
    </div>

@endsection
