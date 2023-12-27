<table class="table text-center" id="data-table">
    <thead> 
        <th class="sortColumn" data-column="name">
            <div class="sortHeading">
                Role Name
                <div id="">
                    @if ($request->sort_by == 'name' && $request->sort_order == 'asc')
                    <i class="fa fa-sort-asc" aria-hidden="true"></i>
                    @elseif ($request->sort_by == 'name' && $request->sort_order == 'desc')
                    <i class="fa fa-sort-desc" aria-hidden="true"></i>
                    @endif                
                </div>
            </div>
        </th>
        @if(auth('admin')->user()->can('role-edit') || auth('admin')->user()->can('role-delete'))
        <th class="btns-col">Action</th>        
        @endif
    </thead>
    <tbody>
        @forelse ($roles as $role)
            <tr id="item_{{ $role->id }}">
                <td scope="row" class="w-8">{{ $role->name }}</td>
                @if(auth('admin')->user()->can('role-edit') || auth('admin')->user()->can('role-delete'))
                <td>
                    @if(auth('admin')->user()->can('role-edit'))
                    <a id="edit_row" href="{{ url('admin/roles/edit/' . $role->id) }}"
                        class="btn action-btn" role="button" aria-pressed="true" title="Edit">                        
                        <i class="fa fa-pen green" aria-hidden="true"></i>
                    </a>
                    @endif
                    @if(auth('admin')->user()->can('role-delete'))
                        @if($role->name!='Marketing Admin')
                        <a href="javascript:void(0);" data-id="{{ $role->id }}" class="btn action-btn deleteData" title="Delete" role="button" aria-pressed="true">
                            <i class="fa fa-trash red" aria-hidden="true"></i>
                        </a>
                        @endif
                    @endif
                </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="2" class="no-record">
                    No record found.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
<div id="roles_nav">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            Total no. of records = <span class="text-theme-color">{!! $roles_count !!}</span>
        </div>
        <div>{{ $roles->links() }}</div>
    </div>
</div>