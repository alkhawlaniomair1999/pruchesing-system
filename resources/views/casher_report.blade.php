@extends('include.app')
@section('title')
    التقارير
@endsection

@section('page')
    تقارير
@endsection
@section('main')
<button class="print_btn" onclick="window.print()">طباعة<i class="fa-solid fa-print"></i></button>


    <h2>تقرير الشهر: {{ $month }}/{{ $year }} للموظف: {{ $c->casher }}</h2>
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
@php
    $sum_total=0;
    $sum_bank=0;
    $sum_cash=0;
    $sum_out=0;
    $sum_plus=0;
@endphp


            @if (isset($casher_proc))
                @foreach ($casher_proc as $cp)
                   
                    <tr>
                        <td>{{ $cp->date }}</td>

                        <td>{{ $cp->total }}</td>
                
                        <td>{{ $cp->bank }}</td>
                        <td>{{ $cp->cash }}</td>
                        <td>{{ $cp->out }}</td>
                        <td>{{ $cp->plus }}</td>


            </tr>
@php
    $sum_total+=$cp->total;
    $sum_bank+=$cp->bank;
    $sum_cash+=$cp->cash;
    $sum_out+=$cp->out;
    $sum_plus+=$cp->plus;
@endphp


@endforeach
@endif

<tfoot>
<tr>
    <th>الاجمالي:</th>
<th>{{$sum_total}}</th>
<th>{{$sum_bank}}</th>
<th>{{$sum_cash}}</th>
<th>{{$sum_out}}</th>
<th>{{$sum_plus}}</th>


</tr>


</tfoot>


        </tbody>
    </table>






@endsection