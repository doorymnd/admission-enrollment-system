@extends('layouts.adminLayout')
<style>
#departmentField {
    max-height: 0;
    opacity: 0;
    overflow: hidden;
    transition: all 0.3s ease;
}

#departmentField.show {
    max-height: 120px;
    opacity: 1;
    margin-top: 10px;
}

.form-control {
    width: 100%;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    transition: 0.2s;
}

.form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 5px rgba(40,167,69,0.3);
    outline: none;
}
</style>
@section('content')

<div class="main-content">
    <div class="header-inline">
        <h1>Create Admin Accounts</h1>
        <p>Manage all of the admin accounts available.</p>
    </div>

    <hr>
    <br>

    <form action="{{ route('store.admin.acc') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" name="first_name" id="first_name"
                   value="{{ old('first_name') }}" required>
        </div>

        <br>

        <div class="form-group">
            <label for="middle_name">Middle Name</label>
            <input type="text" name="middle_name" id="middle_name"
                   value="{{ old('middle_name') }}">
        </div>

        <br>

        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" name="last_name" id="last_name"
                   value="{{ old('last_name') }}" required>
        </div>

        <br>

        <div class="form-group">
            <label for="suffix">Suffix</label>
            <input type="text" name="suffix" id="suffix"
                   value="{{ old('suffix') }}" >
        </div>

        <br>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" name="email" id="email"
                   value="{{ old('email') }}" required>
        </div>

        <br>

        <div class="form-group">
            <label for="role">Role</label>
            <select name="role" id="role" required>
                <option value="super_admin" {{ old('role')=='super_admin' ? 'selected' : '' }}>Super Admin</option>
                <option value="dean" {{ old('role')== 'dean' ? 'selected' : '' }}>Dean</option>
            </select>
        </div>

        <br>

        <div class="form-group" id="departmentField">
            <label for="department">Department</label>
            <select name="department" id="department" class="form-control" required>
                <option value="">-- Select Department --</option>
                <option value="Criminology">Criminology</option>
                <option value="Management">Management</option>
                <option value="Education">Education</option>
                <option value="Governance">Governance</option>
            </select>
        </div>



        <br>

        <div class="form-actions">
            <button type="submit" class="btn-save">Create Admin</button>
        </div>
    </form>
</div>
<script>
const roleSelect = document.getElementById('role');
const deptField = document.getElementById('departmentField');

function toggleDepartment() {
    if (roleSelect.value === 'dean') {
        deptField.classList.add('show');
    } else {
        deptField.classList.remove('show');
        document.getElementById('department').value = '';
    }
}

// Run on change
roleSelect.addEventListener('change', toggleDepartment);

// Run on page load (for old values / validation errors)
window.addEventListener('load', toggleDepartment);
</script>
@endsection