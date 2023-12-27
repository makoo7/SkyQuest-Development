<table class="table text-center" id="data-table">
    <thead>
        <th class="sortColumn name-col" data-column="email_domain">
            <div class="sortHeading">
                Email Domain
                <div id="">
                    @if ($request->sort_by == 'email_domain' && $request->sort_order == 'asc')
                    <i class="fa fa-sort-asc" aria-hidden="true"></i>
                    @elseif ($request->sort_by == 'email_domain' && $request->sort_order == 'desc')
                    <i class="fa fa-sort-desc" aria-hidden="true"></i>
                    @endif                
                </div>
            </div>
        </th>
        <th class="btns-col">Email Category</th>        
        @if(auth('admin')->user()->can('email-restriction-edit') || auth('admin')->user()->can('email-restriction-delete'))
        <th class="btns-col">Action</th>   
        @endif     
    </thead>
    <tbody>
        @forelse ($email_restrictions as $email_restriction)
            <tr id="item_{{ $email_restriction->id }}">
                <td scope="row" class="w-8 name-col">{{ $email_restriction->email_domain }}</td>
                <td scope="row" class="w-8 name-col">{{ $email_restriction->email_category }}</td>
                @if(auth('admin')->user()->can('email-restriction-edit') || auth('admin')->user()->can('email-restriction-delete'))
                <td>
                    @if(auth('admin')->user()->can('email-restriction-edit'))
                    <a id="edit_row" href="{{ url('admin/email-restriction/edit/' . $email_restriction->id) }}"
                        class="btn action-btn" role="button" aria-pressed="true" title="Edit">                        
                        <i class="fa fa-pen green" aria-hidden="true"></i>
                    </a>
                    @endif
                    @if(auth('admin')->user()->can('email-restriction-delete'))
                    <a href="javascript:void(0);" data-id="{{ $email_restriction->id }}" class="btn action-btn deleteData" title="Delete" role="button" aria-pressed="true">
                        <i class="fa fa-trash red" aria-hidden="true"></i>
                    </a>
                    @endif
                </td>
                @endif
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
            Total no. of records = <span class="text-theme-color">{!! $email_restrictions_count !!}</span>
        </div>
        <div>{{ $email_restrictions->links() }}</div>
    </div>
</div>