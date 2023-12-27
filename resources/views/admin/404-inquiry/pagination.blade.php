<table class="table text-center" id="data-table">
    <thead>
        @if(auth('admin')->user()->hasAnyRole(['Marketing Admin']))
        <th class="sortColumn" data-column="country.name">
            <div class="sortHeading">
                Country Name
                <div id="">
                    @if ($request->sort_by == 'country.name' && $request->sort_order == 'asc')
                    <i class="fa fa-sort-asc" aria-hidden="true"></i>
                    @elseif ($request->sort_by == 'country.name' && $request->sort_order == 'desc')
                    <i class="fa fa-sort-desc" aria-hidden="true"></i>
                    @endif                
                </div>
            </div>
        </th>
        <th class="sortColumn" data-column="designation">
            <div class="sortHeading">
                Designation
                <div id="">
                    @if ($request->sort_by == 'designation' && $request->sort_order == 'asc')
                    <i class="fa fa-sort-asc" aria-hidden="true"></i>
                    @elseif ($request->sort_by == 'designation' && $request->sort_order == 'desc')
                    <i class="fa fa-sort-desc" aria-hidden="true"></i>
                    @endif                
                </div>
            </div>
        </th>
        @else
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
        @endif
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
        @if(auth('admin')->user()->can('404-inquiry-view') || auth('admin')->user()->can('404-inquiry-delete'))
        <th class="btns-col">Action</th>        
        @endif
    </thead>
    <tbody>
        @forelse ($pagenotfoundData as $pagenotfound)
            <tr id="item_{{ $pagenotfound->id }}">
                @if(auth('admin')->user()->hasAnyRole(['Marketing Admin']))
                <td scope="row" class="w-8">{{ $pagenotfound->country->name }}</td>
                <td scope="row" class="w-8">{{ $pagenotfound->designation }}</td>
                @else
                <td scope="row" class="w-8">{{ $pagenotfound->name }}</td>
                <td scope="row" class="w-10">{{ $pagenotfound->email }}</td>
                <td scope="row" class="w-10">{{ $pagenotfound->phone }}</td>
                <td scope="row" class="w-10">{{ $pagenotfound->company_name }}</td>
                @endif
                <td scope="row" class="w-10">{!! convertUtcToIst($pagenotfound->created_at, config('constants.DISPLAY_DATE_TIME_FORMAT')) !!}</td>
                @if(auth('admin')->user()->can('404-inquiry-view') || auth('admin')->user()->can('404-inquiry-delete'))
                <td>
                    @if(auth('admin')->user()->can('404-inquiry-view'))
                    <a id="edit_row" href="{{ url('admin/404-inquiry/view/' . $pagenotfound->id) }}"
                        class="btn action-btn" role="button" aria-pressed="true" title="View">
                        <i class="fa fa-eye blue" aria-hidden="true"></i>
                    </a>
                    @endif
                    @if(auth('admin')->user()->can('404-inquiry-delete'))
                    <a href="javascript:void(0);" data-id="{{ $pagenotfound->id }}" class="btn action-btn deleteData" title="Delete" role="button" aria-pressed="true">
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
                <td colspan="6" class="no-record">
                @endif
                    No record found.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
<div id="404-inquiry_nav">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            Total no. of records = <span class="text-theme-color">{!! $pagenotfoundData_count !!}</span>
        </div>
        <div>{{ $pagenotfoundData->links() }}</div>
    </div>
</div>