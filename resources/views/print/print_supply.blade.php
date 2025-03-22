@extends('include.app')
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
    <button class="print_btn" onclick="window.location.href='{{ url('supplier') }}'">
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

                <h1> توريد </h1>
            </div>
            <div class="company-info company-info-left">
                <p>سجل تجاري رقم 5901719945</p>
                <p>الرقم الضريبي 310767346400003</p>

            </div>
        </div>

        <!-- Details Section -->
        <div class="details">
            <div>
                @if (isset($supply))
                    <p> اسم المورد :{{ $supply->supplier->supplier }}</p>
                    <p>رقم الفاتورة: [{{ $supply->id }}]</p>
                    <p>تاريخ الإصدار: {{ $supply->date }}</p>
            </div>
        </div>

        <!-- Items Section -->
        <div class="items">
            <table>
                <thead>
                    <tr>
                        <th>التفاصيل</th>
                        <th>المبلغ</th>
                        <th>الدفع</th>
                        <th>الحساب</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- أضف العناصر هنا ديناميكيًا -->
                    <tr>
                        <td>{{ $supply->details }} </td>
                        <td>{{ $supply->amount }}</td>
                        <td>
                            @if ($supply->payment_type === 'cash')
                                نقداً
                            @elseif($supply->payment_type === 'credit')
                                آجل
                            @else
                                غير محدد
                            @endif

                        </td>
                        <td>
                            {{ $supply->account->account ?? '- ' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif
        <!-- Footer Section -->
        <div class="footer">
            <p>طُبع بواسطة: {{ auth()->user()->name }}</p>
            {{ \Carbon\Carbon::now()->locale('ar')->translatedFormat('d F Y H:i:s') }}

            <p>شكرًا لتعاملكم معنا!</p>
        </div>
    </div>

@endsection
