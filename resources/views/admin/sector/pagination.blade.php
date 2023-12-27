<table class="table text-center" id="data-table">
    <thead>
        <th class="sortColumn name-col" data-column="title">
            <div class="sortHeading">
                Title
                <div id="">
                    @if ($request->sort_by == 'title' && $request->sort_order == 'asc')
                    <i class="fa fa-sort-asc" aria-hidden="true"></i>
                    @elseif ($request->sort_by == 'title' && $request->sort_order == 'desc')
                    <i class="fa fa-sort-desc" aria-hidden="true"></i>
                    @endif                
                </div>
            </div>
        </th>
        <th class="btns-col">Is Active?</th>
        @if(auth('admin')->user()->can('sector-edit') || auth('admin')->user()->can('sector-delete'))   
        <th class="btns-col">Action</th>        
        @endif
    </thead>
    <tbody>
        @forelse ($sectors as $sector)
            <tr id="item_{{ $sector->id }}">
                <td scope="row" class="w-8 name-col">{{ $sector->title }}</td>
                <td scope="row" class="w-10">
                    @if(auth('admin')->user()->can('sector-edit'))  
                    <a href="javascript:void(0);" data-id="{{$sector->id}}" class="btn action-btn updateStatus" role="button" aria-pressed="true">
                    @endif
                    @if($sector->is_active)
                    <i class="fa fa-check-circle green" aria-hidden="true"></i>
                    @else
                    <i class="fa fa-check-circle red" aria-hidden="true"></i>
                    @endif
                    @if(auth('admin')->user()->can('sector-edit'))  
                    </a>
                    @endif
                </td>
                @if(auth('admin')->user()->can('sector-edit') || auth('admin')->user()->can('sector-delete'))   
                <td>
                    @if(auth('admin')->user()->can('sector-edit'))   
                    <a id="edit_row" href="{{ url('admin/sector/edit/' . $sector->id) }}"
                        class="btn action-btn" role="button" aria-pressed="true" title="Edit">                        
                        <i class="fa fa-pen green" aria-hidden="true"></i>
                    </a>
                    @endif
                    @if(auth('admin')->user()->can('sector-delete'))
                    <a href="javascript:void(0);" data-id="{{ $sector->id }}" class="btn action-btn deleteData" title="Delete" role="button" aria-pressed="true">
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
<div id="sector_nav">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            Total no. of records = <span class="text-theme-color">{!! $sectors_count !!}</span>
        </div>
        <div>{{ $sectors->links() }}</div>
    </div>
</div>