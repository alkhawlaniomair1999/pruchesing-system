<div class="sidebar" id="sidebar">
    <h3>القائمة الجانبية</h3>
    <ul>
        <li> مرحبا:   {{ Auth::user()->name }}<br> </li>
        <li><hr></li>
        <li><a href="{{url('init')}}" class="fa-solid fa-gear">التهيئة  </a></li>
        <li><a href="#" class="fa-solid fa-circle-user">المستخدمين </a></li>
        <li><a href="{{url('logout')}}"class="fa-solid fa-right-from-bracket">خروج </a></li>
    
    </ul>
</div>
