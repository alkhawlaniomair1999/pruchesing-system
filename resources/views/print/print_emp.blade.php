@extends('include.app')

@section('title')
    طباعة سند راتب
@endsection

@section('page')
    طباعة سند راتب
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
    @media print {
        footer{
        display: none;
    }
    }
</style>
<button class="print_btn" onclick="window.print()">طباعة<i class="fa-solid fa-print"></i></button>
<button class="print_btn" onclick="window.location.href='{{ url('emps') }}'">
عودة <i class="fa-solid fa-arrow-right"></i>
</button>
<br>
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
    <div class="col">
        <center>
            <h1>سند استلام راتب</h1>
        </center>
        <br>
        <div class="row">
            <h3> انا الموقع ادناه: {{ $emps->name_emp }}</h3>
        </div>
        <div class="row">
            <h3>استلمت من مطعم الجمار الرابع مبلغ وقدره صرافة : {{ $dis->bank }} ونقدا :
                {{ $emps->salary - ($dis->slf + $dis->absence * ($emps->salary / 30) + $dis->discount + $dis->bank) }}
            </h3>
        </div>
        <div class="row">
            <h3>وذلك راتب وجميع المستحقات بعد خصم السلف والخصميات ل شهر : <?php
            // الحصول على التاريخ قبل أسبوع
            $dateBeforeWeek = strtotime('-2 week');
            // مصفوفة بأسماء الأشهر باللغة العربية
            $months = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
            // الحصول على اسم الشهر
            $currentMonth = $months[date('n', $dateBeforeWeek) - 1];
            echo $currentMonth;
            ?> من عام : <?php
            // الحصول على السنة الحالية
            echo date('Y');
            ?>
            </h3>
        </div>
        <h3>الاسم : ......................</h3>
        <h3>التوقيع : .....................</h3>
        <h3>التاريخ: <?php
        // الحصول على السنة الحالية
        echo date('Y-m-d');
        ?> </h3>
        </h3>
    </div>
    </div>
@endsection
