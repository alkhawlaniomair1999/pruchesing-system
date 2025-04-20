<style>
    .sidebar {
        overflow-y: auto;
        /* تمكين شريط التمرير العمودي */
        border: 1px solid #ccc;
        /* إضافة حدود اختيارية */
        padding: 10px;
        /* مساحة داخلية */
        width: 200px;
        background: #ffffff;
        padding: 0;
        box-shadow: -2px 0 3px rgba(0, 0, 0, 0.1);
        transition: width 0.3s, opacity 0.3s, transform 0.3s;
        order: 1;
        /* Sidebar should be first in the row */
        height: 100%;
    }
</style>
<div class="sidebar" id="sidebar">
    <h3>القائمة الجانبية</h3>

    <p class="welcome-message"> مرحبا: {{ Auth::user()->name }}</p>
    <ul>
        <li></li>
        <li><a href="{{ url('init') }}"> <i class="fa-solid fa-gear"></i>التهيئة </a></li>
        <li><a href="{{ url('supplier') }}"><i class="fa-solid fa-truck"></i>التوريد </a></li>
        <li><a href="{{ url('pay') }}"><i class="fa-solid fa-hand-holding-dollar"></i> قيد تسديد </a></li>
        <li><a href="{{ url('receipt') }}"><i class="fas fa-file-invoice-dollar"></i> سند قبض </a></li>
        <li><a href="{{ url('details') }}"><i class="fa-solid fa-calendar-check"></i>التسجيل اليومي </a></li>
        <li><a href="{{ url('casher_proc') }}"><i class="fa-solid fa-cash-register"></i>عمليات الكاشير </a></li>
        <li><a href="{{ url('invoices') }}"><i class="fa-solid fa-file-invoice-dollar"></i>فاتورة ضريبية </a></li>
        <li><a href="{{ url('inventory') }}"><i class="fas fa-box"></i> المخزون </a></li>
        <li><a href="{{ url('reports') }}"><i class="fa fa-chart-bar"></i>التقارير </a></li>
        <li><a href="{{ url('emps') }}"><i class="fas fa-users"></i>الموظفين </a></li>
        <li><a href="{{ url('users') }}"><i class="fa-solid fa-circle-user"></i>المستخدمين </a></li>
    </ul>
</div>
