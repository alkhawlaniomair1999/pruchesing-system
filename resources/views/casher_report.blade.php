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
            @if (isset($items))
                @foreach ($items as $i)
                    @php
                        $total = 0;
                    @endphp
                    <tr>
                        <td>{{ $i->item }}</td>
                        @foreach ($details as $d)
                            @if ($d->item_id == $i->id)
                                @php
                                    $total += $d->total;
                                @endphp
                            @endif
                        @endforeach
                        <td>{{ $total }}</td>
                        @if (isset($branches))
                            @foreach ($branches as $b)
                                @php
                                    $total = 0;
                                @endphp
                                @foreach ($details as $d)
                                    @if ($d->item_id == $i->id && $d->branch_id == $b->id)
                                        @php
                                            $total += $d->total;
                                        @endphp
                                    @endif
                                @endforeach
                                <td>{{ $total }}</td>
                            @endforeach
                        @endif
                    </tr>
                @endforeach
            @endif
            <tr>
                <td>الاجمالي: </td>
                @php
                    $total = 0;
                @endphp
                @if (isset($details))
                    @foreach ($details as $det)
                        @php
                            $total += $det->total;
                        @endphp
                    @endforeach
                @endif
                <td>{{ $total }}</td>
                @if (isset($branches))
                    @foreach ($branches as $b)
                        @php
                            $total = 0;
                        @endphp
                        @if (isset($details))
                            @foreach ($details as $det)
                                @if ($det->branch_id == $b->id)
                                    @php
                                        $total += $det->total;
                                    @endphp
                                @endif
                            @endforeach
                        @endif
                        <td>{{ $total }}</td>
                    @endforeach
                @endif
            </tr>

        </tbody>
    </table>






@endsection
