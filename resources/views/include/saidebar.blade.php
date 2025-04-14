<div class="sidebar" id="sidebar">
    <h3>القائمة الجانبية</h3>

    <p class="welcome-message"> مرحبا: {{ Auth::user()->name }}</p>
    <ul>
        <li></li>
        <li><a href="{{ url('init') }}"> <i class="fa-solid fa-gear"></i>التهيئة </a></li>
        <li><a href="{{ url('supplier') }}"><i class="fa-solid fa-truck"></i>التوريد </a></li>
        <li><a href="{{ url('pay') }}"><i class="fa-solid fa-hand-holding-dollar"></i> قيد تسديد </a></li>
        <li><a href="{{ url('receipt') }}"><i class="fa-solid fa-hand-holding-dollar"></i> سند قبض </a></li>
        <li><a href="{{ url('details') }}"><i class="fa-solid fa-calendar-check"></i>التسجيل اليومي </a></li>
        <li><a href="{{ url('casher_proc') }}"><i class="fa-solid fa-cash-register"></i>عمليات الكاشير </a></li>
        <li><a href="{{ url('reports') }}"><i class="fa fa-chart-bar"></i>التقارير </a></li>
        <li><a href="{{ url('emps') }}"><i class="fa fa-chart-bar"></i>الموظفين </a></li>
        <li><a href="{{ url('users') }}"><i class="fa-solid fa-circle-user"></i>المستخدمين </a></li>
    </ul>
</div>
