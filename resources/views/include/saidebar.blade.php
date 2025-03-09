<div class="sidebar" id="sidebar">
    <h3>القائمة الجانبية</h3>
    <ul>
        <li> مرحبا: {{ Auth::user()->name }}<br> </li>
        <li>
            <hr>
        </li>
        <li><a href="{{ url('init') }}" class="fa-solid fa-gear">التهيئة </a></li>
        <li><a href="{{ url('supplier') }}" class="fa-solid fa-">التوريد  </a></li>

        
        <li><a href="{{ url('details') }}" class="fa-solid fa-">التسجيل اليومي </a></li>
        <li><a href="{{ url('casher_proc') }}" class="fa-solid fa-">عمليات الكاشير  </a></li>

        <li><a href="{{ url('reports') }}" class="fa fa-info-circle">التقارير </a></li>
        <li><a href="#" class="fa-solid fa-circle-user">المستخدمين </a></li>
        <li><a href="{{ url('logout') }}"class="fa-solid fa-right-from-bracket">خروج </a></li>

    </ul>
</div>
