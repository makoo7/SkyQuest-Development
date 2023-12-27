<table class="table text-center" id="data-table">
    <thead>
        @if(auth('admin')->user()->hasAnyRole(['Marketing Admin']))
        <th class="sortColumn" data-column="subject">
            <div class="sortHeading">
                Subject
                <div id="">
                    @if ($request->sort_by == 'subject' && $request->sort_order == 'asc')
                    <i class="fa fa-sort-asc" aria-hidden="true"></i>
                    @elseif ($request->sort_by == 'subject' && $request->sort_order == 'desc')
                    <i class="fa fa-sort-desc" aria-hidden="true"></i>
                    @endif                
                </div>
            </div>
        </th>
        <th class="sortColumn" data-column="email">
            <div class="sortHeading">
                Legal Category
            </div>
        </th>
        @else
        <th class="sortColumn" data-column="name">
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
        <th class="sortColumn" data-column="email">
            <div class="sortHeading">
                Legal Category
            </div>
        </th>
        <th class="sortColumn" data-column="phone">
            <div class="sortHeading">
                Phone
                <div id="">
                    @if ($request->sort_by == 'phone' && $request->sort_order == 'asc')
                    <i class="fa fa-sort-asc" aria-hidden="true"></i>
                    @elseif ($request->sort_by == 'phone' && $request->sort_order == 'desc')
                    <i class="fa fa-sort-desc" aria-hidden="true"></i>
                    @endif
                </div>
            </div>
        </th>
        @endif
        @if(auth('admin')->user()->can('contactus-view'))
        <th class="btns-col">Action</th>        
        @endif
    </thead>
    <tbody>
        @forelse ($contactusData as $contactus)
        @php
            $emailDomain = explode('@', $contactus->email);
            $emailParts = end($emailDomain);
            if (isset($emailRestrictions[$emailParts])) 
            {
                $category = $emailRestrictions[$emailParts];
            } 
            else 
            {
                $subparts = explode('.', $emailParts); // Split the domain by .
                $domain = end($subparts);
                if (count($subparts) >= 2) {
                    $subdomain = $subparts[count($subparts) - 2] . '.' . $domain;
            
                    if (isset($emailRestrictions[$subdomain])) {
                        $category = $emailRestrictions[$subdomain];
                    } else {
                        $category = 'Corporate';
                    }
                } else {
                    $category = 'Corporate';
                }
            }
        @endphp
            <tr id="item_{{ $contactus->id }}">
                @if(auth('admin')->user()->hasAnyRole(['Marketing Admin']))
                <td  class="w-8">{{ $contactus->subject }}</td>
                <td  class="w-10">{{ $category }}</td>
                @else
                <td  class="w-8">{{ $contactus->name }}</td>
                <td  class="w-10">{{ $contactus->email }}</td>
                <td  class="w-10">{{ $category }}</td>
                <td  class="w-10">{{ $contactus->phone }}</td>
                @endif
                @if(auth('admin')->user()->can('contactus-view'))
                <td>
                    <a id="edit_row" href="{{ url('admin/contactus/view/' . $contactus->id) }}"
                        class="btn action-btn" role="button" aria-pressed="true" title="View">
                        <i class="fa fa-eye blue" aria-hidden="true"></i>
                    </a>
                </td>
                @endif
            </tr>
        @empty
            <tr>
                @if(auth('admin')->user()->hasAnyRole(['Marketing Admin']))
                <td colspan="1" class="no-record">
                @else
                <td colspan="4" class="no-record">
                @endif
                    No record found.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
<div id="contactus_nav">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            Total no. of records = <span class="text-theme-color">{!! $contactusData_count !!}</span>
        </div>
        <div>{{ $contactusData->links() }}</div>
    </div>
</div>