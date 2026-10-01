@extends('layouts.adminLayout')

@section('content')

<div class="main-content">
    <h2>Import Sections</h2>

    @if(session('success'))
        <div style="color: green;">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('sections.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" required>
        <button type="submit">Upload Sections</button>
    </form>
</div>

@endsection