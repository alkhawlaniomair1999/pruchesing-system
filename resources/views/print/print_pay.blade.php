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

            font-size: 1.3em; /* تكبير الخط */
        }
        .details div {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .items {
            margin-bottom: 20px;
            font-size: 1.1em; /* تكبير الخط */
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            font-size: 1.2em; /* تكبير خط العناوين */
        }
        td {
            font-size: 1.1em; /* تكبير خط البيانات */
        }
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            font-size: 1.1em; /* تكبير الخط */
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
                <p>اسم الشركة: شركتك</p>
                <p>العنوان: 123 شارع رئيسي، المدينة، الدولة</p>
                <p>الهاتف: +123456789</p>
               
            </div>
            <div class="logo">
                <img src="path-to-your-logo.png" alt="شعار الشركة">
                <h1> سداد </h1>
            </div>
            <div class="company-info company-info-left">
                <p>Company Name: Your Company</p>
                <p>Address: 123 Main St, City, Country</p>
                <p>Phone: +123456789</p>
                
            </div>
        </div>

        <!-- Details Section -->
        <div class="details">
            <div>
                
                <p>رقم السند: {{ $pay->id }}</p>
                <h3>   
                    سند سداد مورد 
    </h3>
                <p>تاريخ الإصدار: {{ $pay->date }}</p>
            </div>
        </div>

        <!-- Items Section -->
        <div class="items">
            <table>
                <thead>
                    <tr>
                    <th>المورد</th>

                        <th>التفاصيل</th>
                        <th>المبلغ</th>
                        <th>الحساب</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- أضف العناصر هنا ديناميكيًا -->
                    <tr>
                    <td>
                    @if (isset($suppliers))
                    @foreach ($suppliers as $sp )
                    @if ($sp->id ==$pay->supplier )
                    {{ $sp->supplier }}
                    @else
                    
                    @endif
                    @endforeach
                    @endif
                    </td>
                        <td>{{ $pay->details }} </td>
                        <td>{{ $pay->amount }}</td>
                        
                        <td>
                        @if (isset($accounts))
                    @foreach ($accounts as $ac )
                    @if ($ac->id ==$pay->account )
                    {{ $ac->account }}
                    @else
                    
                    @endif
                    @endforeach
                    @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer Section -->
        <div class="footer">
            <p>طُبع بواسطة: {{ auth()->user()->name }}</p>
{{ \Carbon\Carbon::now()->locale('ar')->translatedFormat('d F Y H:i:s') }}

            <p>شكرًا لتعاملكم معنا!</p>
        </div>
    </div>

@endsection
