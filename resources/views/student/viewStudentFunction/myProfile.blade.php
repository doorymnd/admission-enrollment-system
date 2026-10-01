@extends('layouts.studentLayout')

@section('content')

<link rel="stylesheet" href="{{ asset('css/studentCSS/myProfile.css') }}">

@php
    $info = $student->info ?? null;
    $enrollment = $student->enrollment ?? null;
    $course = $enrollment->courseInfo ?? null;

    $canEdit = $info && $info->edit_request_status === 'approved';
    $status = $info->edit_request_status ?? 'none';
@endphp

<div class="main-content">
    <div class="profile-container">
        <h2>My Profile</h2>

        <form class="profile-form"
              method="POST"
              action="{{ route('student.profile.update') }}"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- PROFILE IMAGE -->
            <div class="profile-image">
                <div class="avatar-box">
                    <img
                        src="{{ $info && $info->profile_picture
                            ? asset('storage/' . $info->profile_picture)
                            : asset('img/user.jpg') }}"
                        alt="Profile Picture"
                        id="profilePreview"
                    >

                    <label class="edit-icon">
                        ✎
                        <input type="file"
                               name="profile_picture"
                               hidden
                               accept="image/*"
                               onchange="previewImage(event)">
                    </label>
                </div>
            </div>

            <!-- FIRST & MIDDLE NAME -->
            <div class="form-row">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text"
                           name="first_name"
                           value="{{ old('first_name', $info->first_name ?? '') }}"
                           {{ !$canEdit ? 'disabled' : '' }}
                           required>
                </div>

                <div class="form-group">
                    <label>Middle Name</label>
                    <input type="text"
                           name="middle_name"
                           value="{{ old('middle_name', $info->middle_name ?? '') }}"
                           {{ !$canEdit ? 'disabled' : '' }}>
                </div>
            </div>

            <!-- LAST NAME & SUFFIX -->
            <div class="form-row">
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text"
                           name="last_name"
                           value="{{ old('last_name', $info->last_name ?? '') }}"
                           {{ !$canEdit ? 'disabled' : '' }}
                           required>
                </div>

                <div class="form-group">
                    <label>Suffix <small>(Leave it blank if not available)</small></label>
                    <input type="text"
                           name="suffix"
                           value="{{ old('suffix', $info->suffix ?? '') }}"
                           {{ !$canEdit ? 'disabled' : '' }}
                           placeholder="Jr, Sr, III">
                </div>
            </div>

            <!-- EMAIL & PHONE -->
            <div class="form-row">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email"
                           name="email"
                           value="{{ old('email', $student->email) }}"
                           {{ !$canEdit ? 'disabled' : '' }}
                           required>
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text"
                           name="phone"
                           value="{{ old('phone', $info->phone ?? '') }}"
                           {{ !$canEdit ? 'disabled' : '' }}>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Sex</label>
                    <div class="gender-options">
                        <label class="radio-label">
                            <input type="radio" 
                                name="gender" 
                                value="Male" 
                                {{ old('gender', $info->gender ?? '') == 'Male' ? 'checked' : '' }}
                                {{ !$canEdit ? 'disabled' : '' }}>
                            <span class="radio-btn"></span>
                            Male
                        </label>
                        <label class="radio-label">
                            <input type="radio" 
                                name="gender" 
                                value="Female" 
                                {{ old('gender', $info->gender ?? '') == 'Female' ? 'checked' : '' }}
                                {{ !$canEdit ? 'disabled' : '' }}>
                            <span class="radio-btn"></span>
                            Female
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>Birthdate</label>
                    <input type="date"
                           name="birthdate"
                           value="{{ old('birthdate', $info->birthdate ?? '') }}"
                           {{ !$canEdit ? 'disabled' : '' }}>
                </div>
            </div>


            <div class="form-row">
                <div class="form-group">
                    <label>Street</label>
                    <input type="text"
                        name="street"
                        value="{{ old('street', $info->street ?? '') }}"
                        {{ !$canEdit ? 'disabled' : '' }}>
                </div>

                <div class="form-group">
                    <label>Barangay</label>
                    <input type="text"
                        name="barangay"
                        value="{{ old('barangay', $info->barangay ?? '') }}"
                        {{ !$canEdit ? 'disabled' : '' }}>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Municipality</label>
                    <input type="text"
                        name="municipality"
                        value="{{ old('municipality', $info->municipality ?? '') }}"
                        {{ !$canEdit ? 'disabled' : '' }}>
                </div>

                <div class="form-group">
                    <label>City</label>
                    <input type="text"
                        name="city"
                        value="{{ old('city', $info->city ?? '') }}"
                        {{ !$canEdit ? 'disabled' : '' }}>
                </div>
            </div>
            
            @if($status === 'approved')
                <button type="submit" class="btn-save">
                    Save Changes
                </button>
            @endif  
        </form>

            @if($status === 'none')
                <form method="POST" action="{{ route('student.profile.request') }}">
                    @csrf
                    <button type="submit" class="btn-save" style="background:orange;">
                        Request Permission to Edit Profile
                    </button>
                </form>
            @endif

            {{-- ⏳ PENDING → SHOW WAITING --}}
            @if($status === 'pending')
                <button class="btn-save" style="background:gray; cursor:not-allowed;" disabled>
                    Waiting for Admin Approval...
                </button>
            @endif

            
    </div>
</div>

<!-- IMAGE PREVIEW -->
<script>
function previewImage(event) {
    const preview = document.getElementById('profilePreview');
    preview.src = URL.createObjectURL(event.target.files[0]);
}
</script>

@endsection