<table class="table text-center" id="data-table">
    <thead>
        @if(!auth('admin')->user()->hasAnyRole(['Marketing Admin']))
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
        <th class="sortColumn" data-column="email">
            <div class="sortHeading">
                Email
                <div id="">
                    @if ($request->sort_by == 'email' && $request->sort_order == 'asc')
                    <i class="fa fa-sort-asc" aria-hidden="true"></i>
                    @elseif ($request->sort_by == 'email' && $request->sort_order == 'desc')
                    <i class="fa fa-sort-desc" aria-hidden="true"></i>
                    @endif
                </div>
            </div>
        </th>
        <th class="sortColumn" data-column="phone">
            <div class="sortHeading">
                Phone
                <div id="">
                    @if ($request->sort_by == 'phonecode' && $request->sort_order == 'asc')
                    <i class="fa fa-sort-asc" aria-hidden="true"></i>
                    @elseif ($request->sort_by == 'phonecode' && $request->sort_order == 'desc')
                    <i class="fa fa-sort-desc" aria-hidden="true"></i>
                    @endif
                </div>
            </div>
        </th>
        @endif
        <th class="sortColumn" data-column="email">
            <div class="sortHeading">
               Legal Category
            </div>
        </th>
        <th class="sortColumn" data-column="report_name">
            <div class="sortHeading">
                Report Name
                <div id="">
                    @if ($request->sort_by == 'report_name' && $request->sort_order == 'asc')
                    <i class="fa fa-sort-asc" aria-hidden="true"></i>
                    @elseif ($request->sort_by == 'report_name' && $request->sort_order == 'desc')
                    <i class="fa fa-sort-desc" aria-hidden="true"></i>
                    @endif
                </div>
            </div>
        </th>
        <th class="sortColumn" data-column="payment_status">
            <div class="sortHeading">
                Payment Status
                <div id="">
                    @if ($request->sort_by == 'payment_status' && $request->sort_order == 'asc')
                    <i class="fa fa-sort-asc" aria-hidden="true"></i>
                    @elseif ($request->sort_by == 'payment_status' && $request->sort_order == 'desc')
                    <i class="fa fa-sort-desc" aria-hidden="true"></i>
                    @endif
                </div>
            </div>
        </th>
        <th class="sortColumn" data-column="created_at">
            <div class="sortHeading">
                Created Date/Time
                <div id="">
                    @if ($request->sort_by == 'created_at' && $request->sort_order == 'asc')
                    <i class="fa fa-sort-asc" aria-hidden="true"></i>
                    @elseif ($request->sort_by == 'created_at' && $request->sort_order == 'desc')
                    <i class="fa fa-sort-desc" aria-hidden="true"></i>
                    @endif
                </div>
            </div>
        </th>
        @if(auth('admin')->user()->can('report-order-view') || auth('admin')->user()->can('report-order-delete'))
        <th class="btns-col" style="width:95px">Action</th>
        @endif
    </thead>
    <tbody>
        @forelse ($report_orders as $report_order)
        @php
        $emailParts = explode('@',$report_order->email);
        $emailDomain = end($emailParts);
        if (isset($emailRestrictions[$emailDomain])) 
        {
            $category = $emailRestrictions[$emailDomain];
        } 
        else 
        {
            $subparts = explode('.', $emailDomain); // Split the domain by .
            $domain = end($subparts);
            if (count($subparts) >= 2) {
                $subdomain = $subparts[count($subparts) - 2] . '.' . $domain;
        
                if (isset($emailRestrictions[$subdomain])) {
                    $category = $emailRestrictions[$subdomain];
                } else {
                    $category = 'Corporate';
                }
            } else {
                $category = 'Corporate';
            }
        }
        @endphp
            <tr id="item_{{ $report_order->id }}">
                @if(!auth('admin')->user()->hasAnyRole(['Marketing Admin']))
                <td scope="row" class="w-8">{{ $report_order->name }}</td>
                <td scope="row" class="w-10">{{ $report_order->email }}</td>
                <td scope="row" class="w-8">{{ $report_order->phonecode }}{{ $report_order->phone }}</td>
                @endif
                <td scope="row" class="w-10">{{ $category }}</td>
                <td scope="row" class="w-10">{{ $report_order->report->name }}</td>
                <td scope="row" class="w-10">{{ ucfirst(strtolower($report_order->payment_status)) }}</td>
                <td scope="row" class="w-10">{!! convertUtcToIst($report_order->created_at, config('constants.DISPLAY_DATE_TIME_FORMAT')) !!}</td>
                @if(auth('admin')->user()->can('report-order-view') || auth('admin')->user()->can('report-order-delete'))
                <td>
                    @if(auth('admin')->user()->can('report-order-view'))
                    <a id="edit_row" href="{{ url('admin/report-order/view/' . $report_order->id) }}"
                        class="btn action-btn" role="button" aria-pressed="true" title="View">
                        <i class="fa fa-eye blue" aria-hidden="true"></i>
                    </a>
                    @endif
                    @if(auth('admin')->user()->can('report-order-delete'))
                    <a href="javascript:void(0);" data-id="{{ $report_order->id }}" class="btn action-btn deleteData" title="Delete" role="button" aria-pressed="true">
                        <i class="fa fa-trash red" aria-hidden="true"></i>
                    </a>
                    @endif
                </td>
                @endif
            </tr>
        @empty
            <tr>
                @if(auth('admin')->user()->hasAnyRole(['Marketing Admin']))
                <td colspan="4" class="no-record">
                @else
                <td colspan="7" class="no-record">
                @endif
                    No record found.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
<div id="report-order_nav">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            Total no. of records = <span class="text-theme-color">{!! $report_orders_count !!}</span>
        </div>
        <div>{{ $report_orders->links() }}</div>
    </div>
</div>