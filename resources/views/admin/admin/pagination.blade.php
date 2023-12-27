<table class="table text-center" id="data-table">
    <thead>
        <th>Profile Pic</th>  
        <th class="sortColumn" data-column="user_name">
            <div class="sortHeading">
                User Name
                <div id="">
                    @if ($request->sort_by == 'user_name' && $request->sort_order == 'asc')
                    <i class="fa fa-sort-asc" aria-hidden="true"></i>
                    @elseif ($request->sort_by == 'user_name' && $request->sort_order == 'desc')
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
        <th class="sortColumn" data-column="role_id">
            <div class="sortHeading">
                Role
                <div id="">
                    @if ($request->sort_by == 'role_id' && $request->sort_order == 'asc')
                    <i class="fa fa-sort-asc" aria-hidden="true"></i>
                    @elseif ($request->sort_by == 'role_id' && $request->sort_order == 'desc')
                    <i class="fa fa-sort-desc" aria-hidden="true"></i>
                    @endif
                </div>
            </div>
        </th>
        <th class="btns-col">Is Active?</th>
        @if(auth('admin')->user()->can('admin-edit') || auth('admin')->user()->can('admin-delete'))
        <th class="btns-col">Action</th>
        @endif
    </thead>
    <tbody>
        @forelse ($admins as $admin)
            <tr id="item_{{ $admin->id }}">
                <td scope="row" class="w-8">
                    @php
                    if($admin->image!='')
                    $url = str_replace("/upload/","/upload/w_50,q_auto/",$admin->image_url);
                    else
                    $url = $admin->image_url;
                    @endphp
                    <img src="{!! $url !!}" class="img-fluid rounded-circle image_preview">
                </td>
                <td scope="row" class="w-8">{{ $admin->user_name }}</td>
                <td scope="row" class="w-10">{{ $admin->email }}</td>
                <td scope="row" class="w-10">
                    @if(!empty($admin->getRoleNames()))
                        @foreach($admin->getRoleNames() as $v)
                            <span>{{ $v }}</span>
                        @endforeach
                    @endif
                </td>
                <td scope="row" class="w-10">
                    @if(auth('admin')->user()->can('admin-edit'))
                    <a href="javascript:void(0);" data-id="{{$admin->id}}" class="btn action-btn updateStatus" role="button" aria-pressed="true">
                    @endif
                    @if($admin->is_active)
                    <i class="fa fa-check-circle green" aria-hidden="true"></i>
                    @else
                    <i class="fa fa-check-circle red" aria-hidden="true"></i>
                    @endif
                    @if(auth('admin')->user()->can('admin-edit'))
                    </a>
                    @endif
                </td>
                @if(auth('admin')->user()->can('admin-edit') || auth('admin')->user()->can('admin-delete'))
                <td>
                    @if(auth('admin')->user()->can('admin-edit'))
                    <a id="edit_row" href="{{ url('admin/admin/edit/' . $admin->id) }}"
                        class="btn action-btn" role="button" aria-pressed="true" title="Edit">                        
                        <i class="fa fa-pen green" aria-hidden="true"></i>
                    </a>
                    @endif
                    @if(auth('admin')->user()->can('admin-delete'))
                    <a href="javascript:void(0);" data-id="{{ $admin->id }}" class="btn action-btn deleteData" title="Delete" role="button" aria-pressed="true">
                        <i class="fa fa-trash red" aria-hidden="true"></i>
                    </a>
                    @endif
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
<div id="admin_nav">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            Total no. of records = <span class="text-theme-color">{!! $admins_count !!}</span>
        </div>
        <div>{{ $admins->links() }}</div>
    </div>
</div>