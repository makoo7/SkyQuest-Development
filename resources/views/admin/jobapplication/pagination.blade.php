<table class="table text-center" id="data-table">
    <thead>
        <th class="sortColumn" data-column="first_name">
            <div class="sortHeading">
                First Name
                <div id="">
                    @if ($request->sort_by == 'first_name' && $request->sort_order == 'asc')
                    <i class="fa fa-sort-asc" aria-hidden="true"></i>
                    @elseif ($request->sort_by == 'first_name' && $request->sort_order == 'desc')
                    <i class="fa fa-sort-desc" aria-hidden="true"></i>
                    @endif                
                </div>
            </div>
        </th>
        <th class="sortColumn" data-column="last_name">
            <div class="sortHeading">
                Last Name
                <div id="">
                    @if ($request->sort_by == 'last_name' && $request->sort_order == 'asc')
                    <i class="fa fa-sort-asc" aria-hidden="true"></i>
                    @elseif ($request->sort_by == 'last_name' && $request->sort_order == 'desc')
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
        @if(auth('admin')->user()->can('job-application-view') || auth('admin')->user()->can('job-application-delete'))
        <th class="btns-col">Action</th>   
        @endif     
    </thead>
    <tbody>
        @forelse ($jobapplications as $jobapplication)
            <tr id="item_{{ $jobapplication->id }}">
                <td scope="row" class="w-8">{{ $jobapplication->first_name }}</td>
                <td scope="row" class="w-8">{{ $jobapplication->last_name }}</td>
                <td scope="row" class="w-10">{{ $jobapplication->email }}</td>
                <td scope="row" class="w-10">{{ $jobapplication->phone }}</td>
                @if(auth('admin')->user()->can('job-application-view') || auth('admin')->user()->can('job-application-delete'))
                <td>
                    @if(auth('admin')->user()->can('job-application-view'))
                    <a id="edit_row" href="{{ url('admin/job-application/view/' . $jobapplication->id) }}"
                        class="btn action-btn" role="button" aria-pressed="true" title="View">
                        <i class="fa fa-eye blue" aria-hidden="true"></i>
                    </a>
                    @endif
                    @if(auth('admin')->user()->can('job-application-delete'))
                    <a href="javascript:void(0);" data-id="{{ $jobapplication->id }}" class="btn action-btn deleteData" title="Delete" role="button" aria-pressed="true">
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
<div id="job-application_nav">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            Total no. of records = <span class="text-theme-color">{!! $jobapplications_count !!}</span>
        </div>
        <div>{{ $jobapplications->links() }}</div>
    </div>
</div>