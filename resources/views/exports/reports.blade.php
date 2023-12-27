<table>
    <thead>
    <tr>
    @foreach($selectedFields as $field)
        <th>{{ $field }}</th>
    @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($merged_collection as $report)
        <tr>
            @if(in_array('title', $fields))
            <td>{{ $report->title }}</td>
            @endif

            @if(in_array('url', $fields))
            <td>{{ $report->url }}</td>
            @endif

            @if(in_array('product_code', $fields))
            <td>{{ $report->product_code }}</td>
            @endif

            @if(in_array('date', $fields))
            <td>{{ $report->date }}</td>
            @endif

            @if(in_array('length', $fields))
            <td>{{ $report->length }}</td>
            @endif

            @if(in_array('single_price', $fields))
            <td>{{ $report->single_price }}</td>
            @endif

            @if(in_array('site_price', $fields))
            <td>{{ $report->site_price }}</td>
            @endif

            @if(in_array('enterprise_price', $fields))
            <td>{{ $report->enterprise_price }}</td>
            @endif

            @if(in_array('toc', $fields))
            <td>{!! $report->toc !!}</td>
            @endif

            @if(in_array('categories', $fields))
            <td>{{ $report->categories }}</td>
            @endif

            @if(in_array('countries_covered', $fields))
            <td>{!! $report->countries_covered !!}</td>
            @endif

            @if(in_array('companies_mentioned', $fields))
            <td>{!! $report->companies_mentioned !!}</td>
            @endif

            @if(in_array('products_mentioned', $fields))
            <td>{{ $report->products_mentioned }}</td>
            @endif

            @if(in_array('2021', $fields))
            <td>{{ $report->value_for_2021 }}</td>
            @endif

            @if(in_array('2022', $fields))
            <td>{{ $report->value_for_2022 }}</td>
            @endif

            @if(in_array('2030', $fields))
            <td>{{ $report->value_for_2030 }}</td>
            @endif

            @if(in_array('cagr', $fields))
            <td>{{ $report->cagr }}</td>
            @endif

            @if(in_array('currency', $fields))
            <td>{{ $report->currency }}</td>
            @endif

            @if(in_array('report_type', $fields))
            <td>{{ $report->report_type }}</td>
            @endif

            @if(in_array('sector', $fields))
            <td>{{ $report->sector }}</td>
            @endif

            @if(in_array('region', $fields))
            <td>{{ $report->region }}</td>
            @endif

            @if(in_array('1st_2_lines', $fields))
            <td>{{ $report->first_2_lines }}</td>
            @endif

            @if(in_array('market_insights', $fields))
            <td>{{ $report->market_insights }}</td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>