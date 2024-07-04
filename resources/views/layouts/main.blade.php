<!-- - var navbarCustom = "fixed-top navbar-semi-dark navbar-shadow"-->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Guardians CRM') }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <meta name="description" content="Wariz - Customer Relationship Management">

    <meta name="keywords" content="crm, wariz, dashboard">
    <meta name="author" content="Jeremiah I, Faizan K, Zeeshan H">

    <link rel="apple-touch-icon" href="{{ asset('assets/img/icon.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/icon.png') }}">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Quicksand:300,400,500,700"
        rel="stylesheet">
    <link href="https://maxcdn.icons8.com/fonts/line-awesome/1.1/css/line-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/vendors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/app.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets/css/core/menu/menu-types/vertical-menu.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/forms/selects/select2.min.css') }}">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/w3-css/4.1.0/w3.css"
        integrity="sha512-Ef5r/bdKQ7JAmVBbTgivSgg3RM+SLRjwU0cAgySwTSv4+jYcVeDukMp+9lZGWT78T4vCUxgT3g+E8t7uabwRuw=="
        crossorigin="anonymous" />
    <script src="https://code.jquery.com/jquery-3.7.0.slim.js"
        integrity="sha256-7GO+jepT9gJe9LB4XFf8snVOjX3iYNb0FHYr5LI1N5c=" crossorigin="anonymous"></script>
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/modal/sweetalert.css') }}">
    {{-- Datatable --}}
    <script src="{{ asset('app-assets/vendors/js/ui/affix.js') }}" type="text/javascript"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/bs4/dt-1.10.22/fc-3.3.2/fh-3.1.7/r-2.2.6/rr-1.2.7/sc-2.0.3/sl-1.3.1/datatables.min.css" />

    @livewireStyles
    @yield('styles')
    <style>
        .company-header {
            background: linear-gradient(220deg, #292342, #6136aa);
            background-color: transparent;
        }

        .modal-header .close {
            color: whitesmoke;
            font-size: 30px;
            transition: color 0.3s;
        }

        .modal-header .close:hover {
            color: red !important;
        }

        .modal-content {
            border-radius: 10px;
        }
    </style>
</head>

<body class="vertical-layout vertical-menu 2-columns  fixed-navbar" data-open="click" data-menu="vertical-menu"
    data-col="2-columns">

    <!-- fixed-top-->
    @include('layouts.partials.sidebar')

    <div class="app-content content">

        <div class="content-wrapper">
            @include('layouts.partials.alert')

            <div class="main-content">
                @yield('content')
            </div>

        </div>
    </div>
    {{-- @include('layouts.partials.footer') --}}

    <script>
        const switchContainer = document.querySelector('.toggle-container');
        const switchIcon = document.getElementById('switch-icon');

        switchContainer.addEventListener('click', function() {
            switchIcon.classList.toggle('la-arrow-left');
            switchIcon.classList.toggle('la-arrow-right');
        });
    </script>

    <script>
        /* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */
        var dropdown = document.getElementsByClassName("dropdown-btn");
        var dropdown1 = document.getElementsByClassName("dropdown-btn1");

        var i;

        for (i = 0; i < dropdown.length; i++) {
            dropdown[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var dropdownContent = this.nextElementSibling;
                if (dropdownContent.style.display === "block") {
                    dropdownContent.style.display = "none";
                } else {
                    dropdownContent.style.display = "block";
                }
            });
        }
        for (i = 0; i < dropdown1.length; i++) {
            dropdown1[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var dropdownContent = this.nextElementSibling;
                if (dropdownContent.style.display === "block") {
                    dropdownContent.style.display = "none";
                } else {
                    dropdownContent.style.display = "block";
                }
            });
        }
    </script>
    <script src="{{ asset('app-assets/vendors/js/vendors.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/core/app-menu.js') }}"></script>
    <script src="{{ asset('app-assets/js/core/app.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/customizer.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous">
    </script>
    <script type="text/javascript"
        src="https://cdn.datatables.net/v/bs4/dt-1.10.22/fc-3.3.2/fh-3.1.7/r-2.2.6/rr-1.2.7/sc-2.0.3/sl-1.3.1/datatables.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    @livewireScripts
    @stack('scripts')
    <script>
        $(document).ready(function() {
            $('.modal .close').on('click', function() {
                $(this).closest('.modal').modal('hide');
            });
            $(".update_select").click(function(e) {
                e.preventDefault();
                var id = $(this).attr('id');
                let url = "{{ url('update/selected/company/') }}/" + id;
                $.ajax({
                    url: url,
                    type: "POST",
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === "calander_error") {
                            swal.fire({
                                title: "<strong>You haven't selected the calander for company</strong>",
                                icon: "info",
                                html: ` <b>Select The Comapny Calander</b><a href="{{ route('company.calender') }}">Click Here</a>`,

                            });
                            return;
                        } else if (response.status === "success") {
                            window.location.href = "{{ route('dashboard') }}";
                            return;
                        }
                    },
                    error: function(response) {
                        console.log(response);
                        return;
                    }

                });
            })
        })
    </script>
</body>

</html>
