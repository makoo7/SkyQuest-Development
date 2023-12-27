<div class="nav flex-column nav-pills link-list">
    <a href="{!! route('my-reports') !!}" class="nav-link @if(request()->segment(1) == 'my-reports') active @endif" title="My Reports">
        <span>My Reports</span>
        <i class="fa-solid fa-file-lines"></i>
    </a>
    <a href="{!! route('my-bookmarks') !!}" class="nav-link @if(request()->segment(1) == 'my-bookmarks') active @endif" title="My Bookmarks">
        <span>My Bookmarks</span>
        <i class="fas fa-bookmark"></i>
    </a>    
    <a href="{!! route('settings') !!}" class="nav-link @if(request()->segment(1) == 'settings') active @endif" title="Settings">
        <span>Settings</span>
        <i class="fa-solid fa-gear"></i>
    </a>
    <a href="javascript:void(0)" class="nav-link" onclick="javascript:$('#frmLogout').submit();" title="Logout">
        <span>Logout</span>
        <i class="fa-solid fa-right-from-bracket"></i>
    </a>
</div>