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
                    @if ($request->sort_by == 'phone' && $request->sort_order == 'asc')
                    <i class="fa fa-sort-asc" aria-hidden="true"></i>
                    @elseif ($request->sort_by == 'phone' && $request->sort_order == 'desc')
                    <i class="fa fa-sort-desc" aria-hidden="true"></i>
                    @endif
                </div>
            </div>
        </th>
        <th class="sortColumn" data-column="company_name">
            <div class="sortHeading">
                Company Name
                <div id="">
                    @if ($request->sort_by == 'company_name' && $request->sort_order == 'asc')
                    <i class="fa fa-sort-asc" aria-hidden="true"></i>
                    @elseif ($request->sort_by == 'company_name' && $request->sort_order == 'desc')
                    <i class="fa fa-sort-desc" aria-hidden="true"></i>
                    @endif
                </div>
            </div>
        </th>
        <th class="sortColumn" data-column="appointment_time">
            <div class="sortHeading">
                Appointment Date/Time
                <div id="">
                    @if ($request->sort_by == 'appointment_time' && $request->sort_order == 'asc')
                    <i class="fa fa-sort-asc" aria-hidden="true"></i>
                    @elseif ($request->sort_by == 'appointment_time' && $request->sort_order == 'desc')
                    <i class="fa fa-sort-desc" aria-hidden="true"></i>
                    @endif
                </div>
            </div>
        </th>
        @if(auth('admin')->user()->can('appointment-view'))
        <th class="btns-col">Action</th>        
        @endif
    </thead>
    <tbody>
        @forelse ($appointments as $appointment)
            <tr id="item_{{ $appointment->id }}">
                <td scope="row" class="w-8">{{ $appointment->name }}</td>
                <td scope="row" class="w-10">{{ $appointment->email }}</td>
                <td scope="row" class="w-10">{{ $appointment->phone }}</td>
                <td scope="row" class="w-8">{{ $appointment->company_name }}</td>
                <td scope="row" class="w-8">{!! convertUtcToIst($appointment->appointment_time, config('constants.DISPLAY_DATE_TIME_FORMAT')) !!}</td>
                @if(auth('admin')->user()->can('appointment-view'))
                <td>                    
                    <a id="edit_row" href="{{ url('admin/appointment/view/' . $appointment->id) }}"
                        class="btn action-btn" role="button" aria-pressed="true" title="View">
                        <i class="fa fa-eye blue" aria-hidden="true"></i>
                    </a>
                </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="6" class="no-record">
                    No record found.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
<div id="appointment_nav">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            Total no. of records = <span class="text-theme-color">{!! $appointments_count !!}</span>
        </div>
        <div>{{ $appointments->links() }}</div>
    </div>
</div>