@extends('include.app')
@section('title')
    طباعة فاتورة ضريبية
@endsection

@section('page')
    طباعة فاتورة ضريبية
@endsection

@section('main')
    <style>
        /* إعادة تعيين القيم الافتراضية */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        :root {
            --primary-color: #000;
            --secondary-color: #fff;
            --background-color: #f4f4f4;
            --accent-color: #e74c3c;
            --header-bg-color: #3498db;  /* لون مميز لرأس الجدول */
            --header-text-color: #fff;
            --row-even-bg: #f9f9f9;
            --row-odd-bg: #fff;
            --hover-bg: #f1f1f1;
            --border-color: #ddd;
            --border-radius: 8px;
            --box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
            text-align: right;
            background-color: var(--background-color);
            line-height: 1.6;
        }
        .container {
            max-width: 900px;
            margin: 10px auto;
            padding: 10px;
        }
        /* تحديث محاذاة الأزرار: جعلها تظهر في الجانب الأيمن */
        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 20px;
            margin-bottom: 10px;
        }
        .actions button {
            background-color: var(--primary-color);
            color: var(--secondary-color);
            border: none;
            padding: 10px 20px;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 1em;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .actions button:hover {
            background-color: var(--accent-color);
        }
        .invoice {
            background-color: var(--secondary-color);
            padding: 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }
        /* قسم رأس الفاتورة – معلومات الشركة */
        .header {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 10px;
            margin-bottom: 10px;
            border-bottom: 2px solid var(--primary-color);
        }
        .header > div {
            flex: 1;
            min-width: 200px;
        }
        .header .company-info h2 {
            font-size: 1.4em;  /* تصغير خط اسم المطعم */
        }
        .header .company-info {
            font-size: 1.2em;
        }
        .header .logo {
            text-align: center;
        }
        .header .logo img {
            max-width: 150px;
            height: auto;
        }
        /* صف عنوان الفاتورة الجديد */
        .invoice-heading {
            display: flex;
            flex-wrap: nowrap; /* عدم التفاف العناصر */
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            /* نعيين اتجاه LTR لضمان بقاء الترتيب كما في الكود */
            direction: ltr;
        }
        .invoice-heading > div {
            flex: 1;
            white-space: nowrap;  /* إبقاء المحتوى في سطر واحد */
        }
        .invoice-heading .left {
            text-align: left;
            /* إعادة اتجاه المحتوى إلى RTL داخل العنصر */
            direction: rtl;
        }
        .invoice-heading .center {
            text-align: center;
            font-size: 1.5em;
            font-weight: bold;
            direction: rtl;
        }
        .invoice-heading .right {
            text-align: right;
            direction: rtl;
        }
        /* قسم التفاصيل المُتبقية */
        .details {
            border: 1px solid var(--primary-color);
            border-radius: var(--border-radius);
            padding: 15px;
            background-color: #fcfcfc;
            margin-bottom: 10px;
        }
        .details .row {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }
        .details .row span {
            margin-right: 20px;
            font-size: 1.1em;
            white-space: nowrap;
        }
        /* تحسين تصميم الجدول */
        .items {
            overflow-x: auto;
            margin-bottom: 10px;
        }
        .items table {
            width: 100%;
            border-collapse: collapse;
            font-size: 1em;
        }
        .items th,
        .items td {
            padding: 12px 15px;
            text-align: center;
            border: 1px solid var(--border-color);
        }
        .items th {
            background-color: var(--header-bg-color);
            color: var(--header-text-color);
            font-size: 1.1em;
            font-weight: bold;
        }
        .items tbody tr:nth-child(odd) {
            background-color: var(--row-odd-bg);
        }
        .items tbody tr:nth-child(even) {
            background-color: var(--row-even-bg);
        }
        .items tbody tr:hover {
            background-color: var(--hover-bg);
        }
        /* تنسيق جدول الإجماليات */
        .tb_total {
            width: 100%;
            max-width: 400px;
            margin: 10px auto;
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: 15px;
            font-size: 1.2em;
        }
        .tb_total tr td:first-child {
            text-align: right;
            font-weight: bold;
        }
        /* التذييل */
        .footer {
            border-top: 1px solid var(--primary-color);
            padding-top: 10px;
            font-size: 1.1em;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .footer p {
            margin: 5px 0;
        }
        @media print {
            .actions {
                display: none;
            }
            body {
                background-color: var(--secondary-color);
            }
        }
    </style>

    <div class="container">
        <div class="actions">
            <button onclick="window.print()">
                <i class="fa-solid fa-print"></i> طباعة
            </button>
            <button onclick="window.location.href='{{ url('invoices') }}'">
                <i class="fa-solid fa-arrow-right"></i> عودة
            </button>
        </div>

        <div class="invoice">
            <!-- قسم الرأس: معلومات الشركة -->
            <div class="header">
                <div class="company-info company-info-right">
                    <h4>مطعم الجمار الرابع لتقديم الوجبات</h4>
                    <p>ابو عريش، حي الروضة، شارع الأمير محمد بن ناصر</p>
                </div>
                <div class="logo">
                    <img src="{{ asset('assets/img/jammar2.png') }}" alt="Logo">
                </div>
                <div class="company-info company-info-left">
                    <p>سجل تجاري رقم: 5901719945</p>
                    <p>الرقم الضريبي: 310767346400003</p>
                </div>
            </div>

            <!-- صف عنوان الفاتورة الجديد -->
            @if(isset($invoice))
            <div class="invoice-heading">
                <div class="left">
                    <span><strong>تاريخ الفاتورة:</strong> {{ $invoice->invoice_date }}</span>
                </div>
                <div class="center">
                    <span>فاتورة ضريبية</span>
                </div>
                <div class="right">
                    <span><strong>رقم الفاتورة:</strong> {{ $invoice->id }}</span>
                </div>
            </div>
            @endif

            <!-- قسم التفاصيل المُتبقية -->
            <div class="details">
                @if (isset($invoice))
                    <!-- الصف الأول: بيانات العميل الشخصية -->
                    <div class="row" style="justify-content: flex-start;">
                        <span><strong>اسم العميل:</strong> {{ $invoice->customer_name }}</span>
                        <span><strong>الرقم الضريبي:</strong> {{ $invoice->tax_id }}</span>
                    </div>
                    <!-- الصف الثاني: العنوان ورقم الجوال مع تاريخ التوريد في صف واحد -->
                    <div class="row" style="justify-content: flex-start;">
                        @if (!empty($invoice->address))
                            <span><strong>العنوان:</strong> {{ $invoice->address }}</span>
                        @endif
                        @if (!empty($invoice->phone_number))
                            <span><strong>رقم الجوال:</strong> {{ $invoice->phone_number }}</span>
                        @endif
                        @if (!empty($invoice->supply_date))
                            <span><strong>تاريخ التوريد:</strong> {{ $invoice->supply_date }}</span>
                        @endif
                    </div>
                @endif
            </div>

            @php
                $total = 0;
                $total_discount = 0;
                $total_tax = 0;
                $net_total = 0;
            @endphp

            <!-- قسم العناصر -->
            <div class="items">
                <table>
                    <thead>
                        <tr>
                            <th>الرمز</th>
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
                        @if (isset($invoice_details))
                            @foreach ($invoice_details as $d)
                                <tr>
                                    <td>{{ $d->product_code }}</td>
                                    <td>{{ $d->product_name }}</td>
                                    <td>{{ $d->quantity }}</td>
                                    <td>{{ $d->price }}</td>
                                    <td>{{ $d->discount }}</td>
                                    @php $total_discount += $d->discount @endphp
                                    <td>{{ $d->price * $d->quantity - $d->discount }}</td>
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
                                            {{ $d->price * $d->quantity - $d->discount + (($d->price * $d->quantity - $d->discount) * 0.15) }}
                                            @php $net_total += $d->price * $d->quantity - $d->discount + (($d->price * $d->quantity - $d->discount) * 0.15) @endphp
                                        @else
                                            {{ $d->price * $d->quantity - $d->discount }}
                                            @php $net_total += $d->price * $d->quantity - $d->discount @endphp
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- قسم الإجماليات -->
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

            <!-- تذييل الفاتورة -->
            <div class="footer">
                <p>طُبع بواسطة: {{ auth()->user()->name }}</p>
                <p>{{ \Carbon\Carbon::now()->locale('ar')->translatedFormat('d F Y H:i:s') }}</p>
                <p>شكرًا لتعاملكم معنا!</p>
            </div>
        </div>
    </div>
@endsection