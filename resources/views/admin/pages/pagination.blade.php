<table class="table text-center" id="data-table">
    <thead>
        <th class="sortColumn name-col" data-column="slug">
            <div class="sortHeading">
                Slug
                <div id="">
                    @if ($request->sort_by == 'slug' && $request->sort_order == 'asc')
                    <i class="fa fa-sort-asc" aria-hidden="true"></i>
                    @elseif ($request->sort_by == 'slug' && $request->sort_order == 'desc')
                    <i class="fa fa-sort-desc" aria-hidden="true"></i>
                    @endif                
                </div>
            </div>
        </th>
        <th class="btns-col">Page Name</th>        
        @if(auth('admin')->user()->can('pages-edit') || auth('admin')->user()->can('pages-delete'))
        <th class="btns-col">Action</th>   
        @endif     
    </thead>
    <tbody>
        @forelse ($pages as $page)
            <tr id="item_{{ $page->id }}">
                <td scope="row" class="w-8 name-col">{{ $page->slug }}</td>
                <td scope="row" class="w-8 name-col">{{ $page->page_name }}</td>
                @if(auth('admin')->user()->can('pages-edit') || auth('admin')->user()->can('pages-delete'))
                <td>
                    @if(auth('admin')->user()->can('pages-edit'))
                    <a id="edit_row" href="{{ url('admin/pages/edit/' . $page->id) }}"
                        class="btn action-btn" role="button" aria-pressed="true" title="Edit">                        
                        <i class="fa fa-pen green" aria-hidden="true"></i>
                    </a>
                    @endif
                    @if(auth('admin')->user()->can('pages-delete'))
                    <a href="javascript:void(0);" data-id="{{ $page->id }}" class="btn action-btn deleteData" title="Delete" role="button" aria-pressed="true">
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
<div id="pages_nav">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            Total no. of records = <span class="text-theme-color">{!! $pages_count !!}</span>
        </div>
        <div>{{ $pages->links() }}</div>
    </div>
</div>