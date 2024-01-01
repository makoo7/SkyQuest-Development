<nav class="navbar navbar-expand-lg navbar-light bg-light">
    {{-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button> --}}
    @php
     $nextRoute = (app('request')->input('page')) ? app('request')->input('page') + 1 : 2;
     $prevRoute = (app('request')->input('page') > 1) ? app('request')->input('page') - 1 : 0;  
    @endphp
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Download
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="javascript:void(0);">Download As PPT</a>
            <a class="dropdown-item" href="javascript:void(0);">Download As PDF</a>
          </div>
        </li>
      </ul>
      <ul class="pagination justify-content-end">
        @if($prevRoute >= 1)
        <li class="page-item">
          <a class="page-link" href="{{ route('sample-report-page', ['slug' => $slug, 'report' => base64_encode($report), 'user' => base64_encode($user), 'sampleId' => base64_encode($sampleId), 'page' => $prevRoute]) }}" tabindex="-1" >Previous</a>
        </li>
        @endif
        {{-- disabled aria-disabled="true" <li class="page-item"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li> --}}
        <li class="page-item">
          <a class="page-link" href="{{ route('sample-report-page', ['slug' => $slug, 'report' => base64_encode($report), 'user' => base64_encode($user), 'sampleId' => base64_encode($sampleId), 'page' => $nextRoute]) }}">Next</a>
        </li>
      </ul>
      {{-- <form class="form-inline my-2 my-lg-0">
        <button class="btn btn-outline-success my-2 my-sm-0" type="button">Search</button>
      </form> --}}
    </div>
  </nav>