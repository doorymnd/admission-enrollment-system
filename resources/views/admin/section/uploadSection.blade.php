@extends('layouts.adminLayout')

@section('content')

<div class="main-content">
    <div class="header-inline">
        <h1>Import Sections</h1>
        <p>Upload Excel file contains sections available.</p>
    </div>

    <hr>

    <div id="success-container"></div>

    @if($errors->any())
        <div class="error-message">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h4>Excel Format</h4>
    <p>The Excel file must contain columns like this:</p>
    <p><strong style="color: red">Required format header:</strong> course_id | year_level | section | semester</p>

    <form 
    id="importForm"
    action="{{ route('sections.import') }}" 
    method="POST" enctype="multipart/form-data">
        @csrf

        <div class="drop-zone" id="dropZone">
            <input type="file" name="excel_file" id="excelInput" hidden required>

            <div class="drop-zone-content">
                <p class="drop-text">
                    Drag & Drop Excel File Here
                </p>
                <p class="drop-subtext">
                    or click to browse (.xlsx, .xls)
                </p>
                <p id="file-name" style="margin-top:10px; color:green;"></p>
            </div>
        </div>

        <br>

        <button type="submit" class="btn-view-approved" id="uploadBtn">
            Upload Sections
        </button>

    </form>

    <div id="progress-container" style="display:none; margin-top:20px;">
        <p><strong id="progress-text">Uploading...</strong></p>

        <div class="progress-wrapper">
            <div id="progress-bar" class="progress-bar" style="width:0%; height:20px; background:green;">
            </div>
        </div>
    </div>
</div>

<script>
const dropZone = document.getElementById('dropZone');
const fileInput = document.getElementById('excelInput');
const fileName = document.getElementById('file-name');
const form = document.getElementById('importForm');
const uploadBtn = document.getElementById('uploadBtn');

const progressContainer = document.getElementById('progress-container');
const progressBar = document.getElementById('progress-bar');
const progressText = document.getElementById('progress-text');

dropZone.addEventListener('click', () => fileInput.click());

fileInput.addEventListener('change', function () {
    fileName.textContent = this.files[0].name;
});

dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.style.background = '#eef';
});

dropZone.addEventListener('dragleave', () => {
    dropZone.style.background = '#f9f9f9';
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.style.background = '#f9f9f9';

    fileInput.files = e.dataTransfer.files;
    fileName.textContent = fileInput.files[0].name;
});

form.addEventListener('submit', function(e) {
    e.preventDefault();

    let formData = new FormData(this);
    let xhr = new XMLHttpRequest();

    progressContainer.style.display = 'block';
    progressBar.style.width = '0%';
    progressText.innerText = 'Uploading...';

    uploadBtn.disabled = true;

    xhr.upload.addEventListener('progress', function(e) {
        if (e.lengthComputable) {
            let percent = Math.round((e.loaded / e.total) * 100);
            progressBar.style.width = percent + '%';
        }
    });

    xhr.open('POST', "{{ route('sections.import') }}", true);
    xhr.setRequestHeader('X-CSRF-TOKEN', "{{ csrf_token() }}");

    xhr.onload = function() {
        uploadBtn.disabled = false;

        if (xhr.status === 200) {
            progressBar.style.width = '100%';
            progressText.innerText = 'Upload Complete ✅';

            showSuccessMessage('Sections imported successfully!');

            form.reset();
            fileName.textContent = '';

        } else {
            progressText.innerText = 'Upload Failed ❌';
            console.error(xhr.responseText);
        }
    };

    xhr.send(formData);
});


function showSuccessMessage(message) {
    let container = document.getElementById('success-container');

    container.innerHTML = `
        <p class="success-message" style="color: green; margin-bottom:10px;">
            ${message}
        </p>
    `;

    setTimeout(() => {
        container.innerHTML = '';
    }, 3000);
}
</script>

@endsection