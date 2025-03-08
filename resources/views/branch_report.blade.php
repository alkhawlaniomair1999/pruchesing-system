@extends('include.app')
@section('title')
    التقارير
@endsection

@section('page')
    تقارير
@endsection
@section('main')
<button class="print_btn" onclick="window.print()">طباعة<i class="fa-solid fa-print"></i></button>


    <h2>تقرير شهر: 
    @switch( $month )
    @case(1)
        يناير
        @break
    @case(2)
        فبراير
        @break
    @case(3)
        مارس
        @break
    @case(4)
        أبريل
        @break
    @case(5)
        مايو
        @break
    @case(6)
        يونيو
        @break
    @case(7)
        يوليو
        @break
    @case(8)
        أغسطس
        @break
    @case(9)
        سبتمبر
        @break
    @case(10)
        أكتوبر
        @break
    @case(11)
        نوفمبر
        @break
    @case(12)
        ديسمبر
        @break
    @default
        رقم الشهر غير صحيح
@endswitch
</h2>
<p>عدد أيام الشهر: {{ $daysInMonth }}</p>
@php
    $sum_total=0;
    $sum_bank=0;
    $sum_cash=0;
    $sum_out=0;
    $sum_plus=0;
@endphp




<table>
        <thead>
            <tr>
                <th>التاريخ</th>
                <th>المبيعات</th>
                <th>البنك</th>
                <th>الكاش</th>
                <th>المصروفات</th>
                <th>عجز-زيادة</th>
            </tr>

        </thead>
        <tbody>
        
        @if (isset($operations))
                @foreach ($operations as $op)
                   
                    <tr>

                        <td>{{ $op->date}}</td>
                        <td>{{ $op->total }}</td>

                        <td>{{ $op->bank }}</td>
                        <td>{{ $op->cash }}</td>
                        <td>{{ $op->out }}</td>
                        <td>{{ $op->plus }}</td>


            </tr>
            @endforeach
            {{ $operations->links() }}
            @endif

        

</tbody>
</table>



    @endsection