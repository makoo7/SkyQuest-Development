<table class="table text-center" id="data-table">
    <thead>
        <th class="btns-col">Report</th>
        <th class="btns-col">Page No.</th>
        <th class="btns-col">Client Email</th>
        <th class="btns-col">Start Time</th>   
        <th class="btns-col">End Time</th>
    </thead>
    <tbody>
        @forelse ($email_restrictions as $email_restriction)
            <tr id="item_{{ $email_restriction->id }}">
                <td scope="row" class="name-col">Report</td>
                <td scope="row" class="name-col">Page {{ $email_restriction->page_id }}</td>
                <td scope="row" class="name-col">{{ $email_restriction->client->name }}&nbsp;({{ $email_restriction->client->email }})</td>
                <td scope="row" class="name-col">{{ \Carbon\Carbon::parse($email_restriction->start_time)->format('Y-m-d H:i:s') }}</td>
                <td scope="row" class="name-col">{{ \Carbon\Carbon::parse($email_restriction->end_time)->format('Y-m-d H:i:s') }}</td>
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
<div id="email-restrictions_nav">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            Total no. of records = <span class="text-theme-color">{{ $email_restrictions_count }}</span>
        </div>
        <div>{{ $email_restrictions->links() }}</div>
    </div>
</div>