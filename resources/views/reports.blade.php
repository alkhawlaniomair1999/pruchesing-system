@extends('include.app')
@section('title')
    التقارير
@endsection

@section('page')
    تقارير
@endsection
@section('main')

    <button class="print_btn" onclick="window.print()">طباعة<i class="fa-solid fa-print"></i></button>
    <button class="print_btn" onclick="window.location.href='{{ url('reports') }}'">
    عودة <i class="fa-solid fa-arrow-right"></i>
</button>
    <h2>تقرير شهر: {{ $month }}/{{ $year }}</h2>
    <table>
        <thead>
            <tr>
                <th>الصنف</th>
                <th>الاجمالي</th>
                @if (isset($branches))
                    @foreach ($branches as $b)
                        <th>{{ $b->branch }}</th>
                    @endforeach
                @endif

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
        </tbody>
        <tfoot>
            <tr>
                <th>الاجمالي: </th>
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
                <th>{{ $total }}</th>
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
                        <th>{{ $total }}</th>
                    @endforeach
                @endif
            </tr>
        </tfoot>

    </table>






@endsection
