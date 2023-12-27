<table class="table text-center" id="data-table">
    <thead>
        <th class="sortColumn" data-column="name">
            <div class="sortHeading">
                Name
                <div id="">
                    @if ($request->sort_by == 'name' && $request->sort_order == 'asc')
                    <i class="fa fa-sort-asc" aria-hidden="true"></i>
                    @elseif ($request->sort_by == 'name' && $request->sort_order == 'desc')
                    <i class="fa fa-sort-desc" aria-hidden="true"></i>
                    @endif                
                </div>
            </div>
        </th>
        <th class="sortColumn" data-column="product_id">
            <div class="sortHeading">
                Report Id / SKU
                <div id="">
                    @if ($request->sort_by == 'product_id' && $request->sort_order == 'asc')
                    <i class="fa fa-sort-asc" aria-hidden="true"></i>
                    @elseif ($request->sort_by == 'product_id' && $request->sort_order == 'desc')
                    <i class="fa fa-sort-desc" aria-hidden="true"></i>
                    @endif                
                </div>
            </div>
        </th>
        <th class="sortColumn" data-column="report_type">
            <div class="sortHeading">
                Report Type
                <div id="">
                    @if ($request->sort_by == 'report_type' && $request->sort_order == 'asc')
                    <i class="fa fa-sort-asc" aria-hidden="true"></i>
                    @elseif ($request->sort_by == 'report_type' && $request->sort_order == 'desc')
                    <i class="fa fa-sort-desc" aria-hidden="true"></i>
                    @endif                
                </div>
            </div>
        </th>
        <th class="sortColumn" data-column="publish_date">
            <div class="sortHeading">
                Publish Date
                <div id="">
                    @if ($request->sort_by == 'publish_date' && $request->sort_order == 'asc')
                    <i class="fa fa-sort-asc" aria-hidden="true"></i>
                    @elseif ($request->sort_by == 'publish_date' && $request->sort_order == 'desc')
                    <i class="fa fa-sort-desc" aria-hidden="true"></i>
                    @endif                
                </div>
            </div>
        </th>
        <th class="">Is Active?</th>
        @if(auth('admin')->user()->can('report-edit') || auth('admin')->user()->can('report-delete'))
        <th class="">Action</th>        
        @endif
    </thead>
    <tbody>
        @forelse ($reports as $report)
            <tr id="item_{{ $report->id }}">
                <td scope="row" class="w-8">{{ $report->name }}</td>
                <td scope="row" class="w-8">{{ $report->product_id }}</td>
                <td scope="row" class="w-8">{{ $report->report_type }}</td>
                <td scope="row" class="w-8">
                    @if(isset($report->report_type) && ($report->report_type=='Upcoming'))
                        Upcoming
                    @elseif(isset($report->publish_date))
                        {!! convertUtcToIst($report->publish_date, config('constants.DISPLAY_DATE_TIME_FORMAT')) !!}
                    @endif
                </td>
                <td scope="row" class="w-4">
                    @if(auth('admin')->user()->can('report-edit'))
                    <a href="javascript:void(0);" data-id="{{$report->id}}" class="btn action-btn updateStatus" role="button" aria-pressed="true">
                    @endif
                    @if($report->is_active)
                    <i class="fa fa-check-circle green" aria-hidden="true"></i>
                    @else
                    <i class="fa fa-check-circle red" aria-hidden="true"></i>
                    @endif
                    @if(auth('admin')->user()->can('report-edit'))
                    </a>
                    @endif
                </td>
                @if(auth('admin')->user()->can('report-edit') || auth('admin')->user()->can('report-delete'))
                <td>
                    @if(auth('admin')->user()->can('report-edit'))
                    <a id="edit_row" href="{{ url('admin/report/edit/' . $report->id) }}"
                        class="btn action-btn" role="button" aria-pressed="true" title="Edit">                        
                        <i class="fa fa-pen green" aria-hidden="true"></i>
                    </a>
                    @endif
                    @if(auth('admin')->user()->can('report-delete'))
                    <a href="javascript:void(0);" data-id="{{ $report->id }}" class="btn action-btn deleteData" title="Delete" role="button" aria-pressed="true">
                        <i class="fa fa-trash red" aria-hidden="true"></i>
                    </a>
                    @endif
                </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="5" class="no-record">
                    No record found.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
<hr/>
<div id="report_nav">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            Total no. of records = <span class="text-theme-color">{!! $reportCount !!}</span>
        </div>
        <div>
            {{ $reports->links() }}
        </div>
    </div>
</div>