@extends('layouts.main')
@section('content')

@section('breadcrumbs')
    <x-bread-crumb pageTitle='Profile Page' previous="" previousLink="" current="User Profile" />
@endsection

<div class="animated fadeIn">
    <div class="container px-4">
        <!-- Account page navigation-->
        <nav class="nav nav-borders">
            <a class="nav-link {{ $view === 'profile' ? 'active' : '' }} ms-0"
                href="{{ route('user.profile', ['view' => 'profile']) }}">Profile</a>
            <a class="nav-link {{ $view === 'security' ? 'active' : '' }} ms-0"
                href="{{ route('user.profile', ['view' => 'security']) }}">Security</a>
                <div class="w-100" style ="text-align: end">
                <a class="btn btn-primary" href="{{ route('google.calendar.connect') }}">Update Calender</a>
            </div>
        </nav>
        <hr class="mt-0 mb-4">

        @switch($view)
            @case('profile')
                <div class="row">
                    <div class="col-xl-4">
                        <!-- Profile picture card-->
                        <div class="card mb-4 border-0 shadow-lg">
                            <div class="card-header bg-light border-bottom">Profile Picture</div>
                            <div class="card-body text-center">
                                <!-- Profile picture image-->
                                <form action="{{ route('user.profile.photo') }}"
                                    onsubmit="$('#profile-picture-button').attr('disabled', true)" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <label for="profile-picture" class="profile_picture_container">
                                        <img class="img-account-profile rounded-circle mb-2" id="profile-picture-preview"
                                            src="{{$user}}" alt="">
                                        <input type="file" id="profile-picture" name="image" class="d-none"
                                            accept="image/*">
                                        <i class="ti-camera"></i>
                                    </label>

                                    <!-- Profile picture help block-->
                                    <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                                    <!-- Profile picture upload button-->
                                    <button class="btn btn-primary" type="submit" id="profile-picture-button">Upload new
                                        image</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8">
                        <!-- Account details card-->
                        <livewire:employee.user-bio-data/>
                    </div>
                </div>
            @break

            @case('security')
                <div>
                    <div class="row">
                        <div class="col-lg-8">
                            <!-- Change password card-->
                            <div class="card mb-4 border-0 shadow-lg">
                                <div class="card-header bg-light border-bottom">Change Password</div>
                                <div class="card-body">
                                    <form action="{{ route('user.profile.password') }}"
                                        onsubmit="$('#password-button').attr('disabled', true)" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <!-- Form Group (current password)-->
                                        <div class="mb-3">
                                            <label class="small mb-1" for="currentPassword">Current Password</label>
                                            <input class="form-control" id="currentPassword" type="password"
                                                name="current_password" placeholder="Enter current password" required>
                                        </div>
                                        <!-- Form Group (new password)-->
                                        <div class="mb-3">
                                            <label class="small mb-1" for="newPassword">New Password</label>
                                            <input class="form-control" id="newPassword" type="password" name="new_password"
                                                placeholder="Enter new password" required>
                                        </div>
                                        <!-- Form Group (confirm password)-->
                                        <div class="mb-3">
                                            <label class="small mb-1" for="confirmPassword">Confirm Password</label>
                                            <input class="form-control" id="confirmPassword" type="password"
                                                name="new_password_confirmation" placeholder="Confirm new password" required>
                                        </div>

                                        <!-- Save changes button-->
                                        <div class="mb-3">
                                            <button class="btn btn-lg btn-primary btn-block" id="password-button"
                                                type="submit">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <!-- Delete account card-->
                            <div class="card mb-4 border-0 shadow-lg">
                                <div class="card-header bg-light border-bottom">Delete Account</div>
                                <div class="card-body">
                                    <p>Deleting your account is a permanent action and cannot be undone. If you are sure you
                                        want to delete your account, select the button below.</p>
                                    <button class="btn btn-danger-soft text-danger" type="button">I understand, delete my
                                        account</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @break
        @endswitch

    </div>
</div>
<script>
    $(document).ready(function() {
        // Prepare the preview for profile picture
        $("#profile-picture").change(function() {
            readURL(this);
        });

        $('#profile-picture-button').hide()
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#profile-picture-preview').attr('src', e.target.result).fadeIn('slow');
            }
            reader.readAsDataURL(input.files[0]);
            $('#profile-picture-button').show(200)
        }
    }
</script>

<style>
    .profile_picture_container {
        position: relative;
    }

    .profile_picture_container i {
        position: absolute;
        top: 0;
        right: 15%;
    }

    .profile_picture_container i:hover {
        transform: scale(1.1)
    }

    .nav-link:hover {
        color: rgba(7, 41, 77, 0.8);
    }

    .nav-borders .nav-link.active {
        color: rgba(7, 41, 77, 0.8);
        border-bottom-color: rgba(7, 41, 77, 0.8);
    }

    .img-account-profile {
        width: 150px;
        height: 150px;
        object-fit: cover;
        object-position: center center;
    }

    .nav-borders .nav-link {
        color: #69707a;
        border-bottom-width: 0.125rem;
        border-bottom-style: solid;
        border-bottom-color: transparent;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
        padding-left: 0;
        padding-right: 0;
        margin-left: 1rem;
        margin-right: 1rem;
    }
</style>
@endsection
