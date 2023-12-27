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
        @if(auth('admin')->user()->can('award-edit') || auth('admin')->user()->can('award-delete'))
        <th class="btns-col">Action</th>        
        @endif
    </thead>
    <tbody>
        @forelse ($awards as $award)
            <tr id="item_{{ $award->id }}">
                <td scope="row" class="w-8 name-col">{{ $award->title }}</td>
                <td scope="row" class="w-10">
                    @if(auth('admin')->user()->can('award-edit'))
                    <a href="javascript:void(0);" data-id="{{$award->id}}" class="btn action-btn updateStatus" role="button" aria-pressed="true">
                    @endif
                    @if($award->is_active)
                    <i class="fa fa-check-circle green" aria-hidden="true"></i>
                    @else
                    <i class="fa fa-check-circle red" aria-hidden="true"></i>
                    @endif
                    @if(auth('admin')->user()->can('award-edit'))
                    </a>
                    @endif
                </td>
                @if(auth('admin')->user()->can('award-edit') || auth('admin')->user()->can('award-delete'))
                <td>
                    @if(auth('admin')->user()->can('award-edit'))
                    <a id="edit_row" href="{{ url('admin/award/edit/' . $award->id) }}"
                        class="btn action-btn" role="button" aria-pressed="true" title="Edit">                        
                        <i class="fa fa-pen green" aria-hidden="true"></i>
                    </a>
                    @endif
                    @if(auth('admin')->user()->can('award-delete'))
                    <a href="javascript:void(0);" data-id="{{ $award->id }}" class="btn action-btn deleteData" title="Delete" role="button" aria-pressed="true">
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
<div id="award_nav">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            Total no. of records = <span class="text-theme-color">{!! $awards_count !!}</span>
        </div>
        <div>{{ $awards->links() }}</div>
    </div>
</div>