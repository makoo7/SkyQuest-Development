<table class="table text-center" id="data-table">
    <thead>
        <th class="sortColumn name-col" data-column="name">
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
        <th class="btns-col">Image</th>
        <th class="btns-col">Image URL</th>
        <th class="btns-col">Action</th>        
    </thead>
    <tbody>
        @forelse ($galleryData as $gallery)
            <tr id="item_{{ $gallery->id }}">
                <td scope="row" class="w-8 name-col">{{ $gallery->name }}</td>
                <td scope="row" class="w-8 name-col">
                <img src="{!! $gallery->image_url !!}" class="img-fluid rounded-circle" id='image_preview'>
                </td>
                <td scope="row" class="w-8 name-col"><a href="{{ $gallery->image }}" target="_blank" id="link_{{ $gallery->id }}">{{ $gallery->image }}</a></td>
                <td>
                    @if(auth('admin')->user()->can('gallery-view'))
                    <a id="view_row" href="{{ url('admin/gallery/view/' . $gallery->id) }}"
                        class="btn action-btn" role="button" aria-pressed="true" title="View">
                        <i class="fa fa-eye blue" aria-hidden="true"></i>
                    </a>
                    @endif
                    @if(auth('admin')->user()->can('gallery-edit'))
                    <a id="edit_row" href="{{ url('admin/gallery/edit/' . $gallery->id) }}"
                        class="btn action-btn" role="button" aria-pressed="true" title="Edit">                        
                        <i class="fa fa-pen green" aria-hidden="true"></i>
                    </a>
                    @endif
                    <a id="copyLink" href="javascript:void(0)" data-id="{{ $gallery->id }}"
                        class="btn action-btn" role="button" aria-pressed="true" title="Copy Image URL">                        
                        <i class="fa fa-copy" aria-hidden="true"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="no-record">
                    No record found.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
<div id="gallery_nav">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            Total no. of records = <span class="text-theme-color">{!! $galleryData_count !!}</span>
        </div>
        <div>{{ $galleryData->links() }}</div>
    </div>
</div>