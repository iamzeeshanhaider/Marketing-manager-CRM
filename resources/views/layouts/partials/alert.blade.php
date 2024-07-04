<div id="jsMessage"></div>

@if (count($errors) > 0)
    <div class="alert bg-danger alert-icon-left alert-arrow-left alert-dismissible mb-2 text-white" role="alert">
        <span class="alert-icon"><i class="la la-thumbs-o-down"></i></span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>Oh !</strong> Please fix the following issues to continue
        <ul class="error">
            @foreach ($errors->all() as $error)
                <li style="list-style: circle">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if(session()->has('error'))
    <div class="alert bg-danger alert-icon-left alert-arrow-left alert-dismissible mb-2 text-white" role="alert">
        <span class="alert-icon"><i class="la la-thumbs-o-down"></i></span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>Oh !</strong> {{ session('error') }}
    </div>
@endif
@if(session()->has('systemError'))
    <div class="alert bg-danger alert-icon-left alert-arrow-left alert-dismissible mb-2 text-white" role="alert">
        <span class="alert-icon"><i class="la la-thumbs-o-down"></i></span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>Oh ! Please contact system administrator.</strong><br>{{ session('message') }}
        <br><br>
        <strong>Sorry for Inconvenience</strong>.
    </div>
@endif
@if(session()->has('success'))
    <div class="alert bg-success alert-icon-left alert-arrow-left alert-dismissible mb-2 text-white" role="alert">
        <span class="alert-icon"><i class="la la-thumbs-o-up"></i></span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>Yeah !</strong> {{ session('success') }}
    </div>
@endif
