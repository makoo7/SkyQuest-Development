<table class="table text-center" id="data-table">
    <thead>
        <th class="sortColumn name-col" data-column="email_domain">
            {{-- <div class="sortHeading"> --}}
                Report
                {{-- <div id="">
                    @if ($request->sort_by == 'email_domain' && $request->sort_order == 'asc')
                    <i class="fa fa-sort-asc" aria-hidden="true"></i>
                    @elseif ($request->sort_by == 'email_domain' && $request->sort_order == 'desc')
                    <i class="fa fa-sort-desc" aria-hidden="true"></i>
                    @endif                
                </div> --}}
            {{-- </div> --}}
        </th>
        <th class="btns-col">To</th>
        <th class="btns-col">StartDate</th>   
        <th class="btns-col">EndDate</th>
        <th class="btns-col">Status</th>
        <th class="btns-col">Action</th>
    </thead>
    <tbody>
        @forelse ($email_restrictions as $email_restriction)
            <tr id="item_{{ $email_restriction->id }}">
                <td scope="row" class="name-col">{{ $email_restriction->report->name }}</td>
                <td scope="row" class="name-col">Sales Dept.</td>
                <td scope="row" class="name-col">{{ $email_restriction->start_date }}</td>
                <td scope="row" class="name-col">{{ $email_restriction->end_date }}</td>
                <td scope="row" class="name-col">{{ ($email_restriction->status == 0) ? 'Pending' : 'Done' }}</td>
                <td scope="row" class="name-col">
                    <a href="javascript:void(0);" data-id="{{ $email_restriction->id }}" class="btn action-btn deleteData" title="Delete" role="button" aria-pressed="true">
                        <i class="fa fa-trash red" aria-hidden="true"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="no-record">
                    No record found.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
<div id="email-restrictions_nav">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            Total no. of records = <span class="text-theme-color">{{ $email_restrictions_count }}</span>
        </div>
        <div>{{ $email_restrictions->links() }}</div>
    </div>
</div>