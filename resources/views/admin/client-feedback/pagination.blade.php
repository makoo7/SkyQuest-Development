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
        <th class="btns-col">Is Active?</th>
        @if(auth('admin')->user()->can('client-feedback-edit') || auth('admin')->user()->can('client-feedback-delete'))
        <th class="btns-col">Action</th>        
        @endif
    </thead>
    <tbody>
        @forelse ($clientfeedbackData as $clientfeedback)
            <tr id="item_{{ $clientfeedback->id }}">
                <td scope="row" class="w-8 name-col">{{ $clientfeedback->name }}</td>
                <td scope="row" class="w-10">
                    @if(auth('admin')->user()->can('client-feedback-edit'))
                    <a href="javascript:void(0);" data-id="{{$clientfeedback->id}}" class="btn action-btn updateStatus" role="button" aria-pressed="true">
                    @endif
                    @if($clientfeedback->is_active)
                    <i class="fa fa-check-circle green" aria-hidden="true"></i>
                    @else
                    <i class="fa fa-check-circle red" aria-hidden="true"></i>
                    @endif
                    @if(auth('admin')->user()->can('client-feedback-edit'))
                    </a>
                    @endif
                </td>
                @if(auth('admin')->user()->can('client-feedback-edit') || auth('admin')->user()->can('client-feedback-delete'))
                <td>
                    @if(auth('admin')->user()->can('client-feedback-edit'))
                    <a id="edit_row" href="{{ url('admin/client-feedback/edit/' . $clientfeedback->id) }}"
                        class="btn action-btn" role="button" aria-pressed="true" title="Edit">                        
                        <i class="fa fa-pen green" aria-hidden="true"></i>
                    </a>
                    @endif
                    @if(auth('admin')->user()->can('client-feedback-delete'))
                    <a href="javascript:void(0);" data-id="{{ $clientfeedback->id }}" class="btn action-btn deleteData" title="Delete" role="button" aria-pressed="true">
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
<div id="clientfeedback_nav">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            Total no. of records = <span class="text-theme-color">{!! $clientfeedbackData_count !!}</span>
        </div>
        <div>{{ $clientfeedbackData->links() }}</div>
    </div>
</div>