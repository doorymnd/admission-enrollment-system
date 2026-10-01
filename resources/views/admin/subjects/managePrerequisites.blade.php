@extends('layouts.adminLayout')

@section('content')

<div class="main-content">
    <h2>Manage Prerequisites</h2>
    <p><strong>Subject:</strong> {{ $subject->name }}</p>

    @if(session('success'))
        <div style="color: green;">
            {{ session('success') }}
        </div>
    @endif

    {{-- SEARCH BAR --}}
    <div style="margin-top:15px;">
        <label><strong>Search Subject:</strong></label>
        <input 
            type="text" 
            id="subjectSearch" 
            placeholder="Type subject code or name..."
            style="width:100%; padding:8px; margin-top:5px; border:1px solid #ccc;"
        >
    </div>

    <form action="{{ route('admin.subject.prerequisites.update', $subject->id) }}" method="POST">
        @csrf

        <div style="margin-top:20px;">
            <label><strong>Select Prerequisites:</strong></label>

            <div 
                id="subjectList"
                style="margin-top:10px; padding:10px; border:1px solid #ccc; max-height:300px; overflow-y:auto;"
            >

                @foreach($subjects as $group => $groupSubjects)

                    {{-- YEAR + SEMESTER HEADER --}}
                    <div style="margin-top:10px; padding:6px; background:#f0f0f0; font-weight:bold;">
                        {{ $group }}
                    </div>

                    {{-- SUBJECT LIST --}}
                    @foreach($groupSubjects as $sub)

                        <div class="subject-item" style="margin-left:15px; margin-bottom:5px;">
                            <label>
                                <input 
                                    type="checkbox" 
                                    name="prerequisites[]" 
                                    value="{{ $sub->id }}"
                                    {{ in_array($sub->id, $selected) ? 'checked' : '' }}
                                >
                                {{ $sub->code }} - {{ $sub->name }}
                            </label>
                        </div>

                    @endforeach

                @endforeach

            </div>
        </div>

        <br>

        <button type="submit" class="btn btn-save">
            Save Prerequisites
        </button>

    </form>
</div>

{{-- SEARCH SCRIPT --}}
<script>
document.getElementById('subjectSearch').addEventListener('keyup', function () {
    let filter = this.value.toLowerCase();
    let items = document.querySelectorAll('.subject-item');

    items.forEach(function (item) {
        let text = item.innerText.toLowerCase();

        if (text.includes(filter)) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
});
</script>

@endsection