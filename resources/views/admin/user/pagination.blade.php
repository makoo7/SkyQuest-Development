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
        <th class="btns-col">Is Active?</th>
        @if(auth('admin')->user()->can('user-edit'))
        <th class="btns-col">Action</th> 
        @endif       
    </thead>
    <tbody>
        @forelse ($users as $user)
            <tr id="item_{{ $user->id }}">
                <td scope="row" class="w-8">
                    @php
                    if($user->image!='')
                    $url = str_replace("/upload/","/upload/w_50,q_auto/",$user->image_url);
                    else
                    $url = $user->image_url;
                    @endphp
                    <img src="{!! $url !!}" class="img-fluid rounded-circle image_preview">
                </td>
                <td scope="row" class="w-8">{{ $user->user_name }}</td>
                <td scope="row" class="w-10">{{ $user->email }}</td>
                <td scope="row" class="w-10">
                    @if(auth('admin')->user()->can('user-edit'))
                    <a href="javascript:void(0);" data-id="{{$user->id}}" class="btn action-btn updateStatus" role="button" aria-pressed="true">
                    @endif
                    @if($user->is_active)
                    <i class="fa fa-check-circle green" aria-hidden="true"></i>
                    @else
                    <i class="fa fa-check-circle red" aria-hidden="true"></i>
                    @endif
                    @if(auth('admin')->user()->can('user-edit'))
                    </a>
                    @endif
                </td>
                @if(auth('admin')->user()->can('user-edit'))
                <td>
                    <a id="edit_row" href="{{ url('admin/user/edit/' . $user->id) }}"
                        class="btn action-btn" role="button" aria-pressed="true" title="Edit">                        
                        <i class="fa fa-pen green" aria-hidden="true"></i>
                    </a>
                    <!-- <a href="javascript:void(0);" data-id="{{ $user->id }}" class="btn action-btn deleteData" title="Delete" role="button" aria-pressed="true">
                        <i class="fa fa-trash red" aria-hidden="true"></i>
                    </a> -->
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
<div id="user_nav">    
    <div class="d-flex align-items-center justify-content-between">
        <div>
            Total no. of records = <span class="text-theme-color">{!! $users_count !!}</span>
        </div>
        <div>{{ $users->links() }}</div>
    </div>
</div>