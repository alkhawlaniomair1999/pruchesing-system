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
            font-size: 1.1em; /* تكبير الخط */
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

    <div class="invoice">
        <!-- Header Section -->
        <div class="header">
            <div class="company-info company-info-right">
                <p>اسم الشركة: شركتك</p>
                <p>العنوان: 123 شارع رئيسي، المدينة، الدولة</p>
                <p>الهاتف: +123456789</p>
                <p>البريد الإلكتروني: info@yourcompany.com</p>
            </div>
            <div class="logo">
                <img src="path-to-your-logo.png" alt="شعار الشركة">
                <h1>فاتورة</h1>
            </div>
            <div class="company-info company-info-left">
                <p>Company Name: Your Company</p>
                <p>Address: 123 Main St, City, Country</p>
                <p>Phone: +123456789</p>
                <p>Email: info@yourcompany.com</p>
            </div>
        </div>

        <!-- Details Section -->
        <div class="details">
            <div>
                <p>اسم العميل: [اسم العميل]</p>
                <p>رقم الفاتورة: [رقم الفاتورة]</p>
                <p>تاريخ الإصدار: {{ date('Y-m-d') }}</p>
            </div>
        </div>

        <!-- Items Section -->
        <div class="items">
            <table>
                <thead>
                    <tr>
                        <th>الوصف</th>
                        <th>الكمية</th>
                        <th>السعر</th>
                        <th>الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- أضف العناصر هنا ديناميكيًا -->
                    <tr>
                        <td>مثال عنصر</td>
                        <td>1</td>
                        <td>100</td>
                        <td>100</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer Section -->
        <div class="footer">
            <p>طُبع بواسطة: {{ auth()->user()->name }}</p>
            <p>تاريخ الطباعة: {{ date('Y-m-d H:i:s') }}</p>
            <p>شكرًا لتعاملكم معنا!</p>
        </div>
    </div>

@endsection
