@extends('include.app')
@section('title')
    التقارير
@endsection

@section('page')
    تقارير
@endsection
@section('main')


    <h2>تقرير الشهر: {{ $month }}/{{ $year }} للموظف: {{ $c->casher }}</h2>
    <table>
        <thead>
            <tr>
                <th>المبيعات</th>
                <th>البنك</th>
                <th>الكاش</th>
                <th>المصروفات</th>
                <th>عجز او زيادة</th>
                <th>التاريخ</th>
            </tr>

        </thead>
        <tbody>
            @if (isset($casher_proc))
                @foreach ($casher_proc as $cp)
                   
                    <tr>
                        <td>{{ $cp->total }}</td>
                
                        <td>{{ $cp->bank }}</td>
                        <td>{{ $cp->cash }}</td>
                        <td>{{ $cp->out }}</td>
                        <td>{{ $cp->plus }}</td>

                        <td>{{ $cp->date }}</td>

            </tr>
@endforeach
@endif
        </tbody>
    </table>






@endsection