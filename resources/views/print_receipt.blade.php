@extends('include.app')

@section('title')
    طباعة سند قبض
@endsection

@section('page')
    طباعة سند قبض
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

            font-size: 1.3em;
            /* تكبير الخط */
        }

        .details div {
            display: flex;
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
    </style>
    <button class="print_btn" onclick="window.print()">طباعة<i class="fa-solid fa-print"></i></button>
    <button class="print_btn" onclick="window.location.href='{{ url('receipt') }}'">
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
        <div class="details">
            <div>
                <div>
                    <p>رقم السند: </p>
                    <p style="color: red"> {{ $receipt->id }}</p>

                </div>
                <h3>
                    سند قبض
                </h3>
                <div>
                    <p>تاريخ الإصدار: </p>
                    <p style="font-size: 15px"> {{ $receipt->created_at }}</p>

                </div>
            </div>
        </div>

        <!-- Items Section -->
        <div class="items">
            <table>
                <thead>
                    <tr>
                        <th>استلمنا من</th>

                        <th>مبلغ وقدره</th>
                        <th>المبلغ كتابة</th>
                        <th>نوع الدفع</th>
                        <th>بتأريخ</th>
                        <th>وذلك بمقابل</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- أضف العناصر هنا ديناميكيًا -->
                    <tr>
                        <td>
                            {{ $receipt->customer_name }}
                        </td>
                        <td>SAR {{ $receipt->amount }} </td>
                        <td> {{ $amountInWords }} ريال سعودي</td>
                        <td>
                            @if ($receipt->payment_method == 'cash')
                                نقدا
                            @elseif ($receipt->payment_method == 'credit')
                                تحويل لحسابنا البنكي
                            @endif
                        </td>
                        <td>{{ $receipt->date }}</td>
                        <td>
                            {{ $receipt->detail }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div
            style="justify-content: space-between; display: flex; margin-top: 20px; margin-bottom: 100px; margin-right: 60px; margin-left: 60px;">
            <h2>الحسابات</h2>
            <h2>المدير العام</h2>
        </div>
        <!-- Footer Section -->
        <div class="footer">
            <p>طُبع بواسطة: {{ auth()->user()->name }}</p>
            {{ \Carbon\Carbon::now()->locale('ar')->translatedFormat('d F Y H:i:s') }}

            <p>شكرًا لتعاملكم معنا!</p>
        </div>
    </div>
@endsection
