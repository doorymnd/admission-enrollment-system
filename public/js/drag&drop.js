const dropZone = document.getElementById('dropZone');
const fileInput = document.getElementById('excelInput');
const dropContent = document.getElementById('dropContent');
const form = document.getElementById('importForm');

// Click to open file browser
dropZone.addEventListener('click', () => {
    fileInput.click();
});

// File selected
fileInput.addEventListener('change', function () {
    if (this.files.length > 0) {
        updateDropZone(this.files[0]);
    }
});

// Drag over
dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('dragover');
});

// Drag leave
dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('dragover');
});

// Drop file
dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('dragover');

    if (e.dataTransfer.files.length > 0) {
        fileInput.files = e.dataTransfer.files;
        updateDropZone(e.dataTransfer.files[0]);
    }
});

// Show file name
function updateDropZone(file) {
    dropContent.innerHTML = `
        <p class="drop-text" style="font-size:18px; font-weight:600;">
            ${file.name}
        </p>
    `;
}

// SUBMIT WITH FETCH
form.addEventListener('submit', async function(e) {
    e.preventDefault();

    let url = form.action;
    let formData = new FormData(form);

    document.getElementById('progress-container').style.display = 'block';
    document.getElementById('progress-title').innerText = "Uploading...";

    try {
        const res = await fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json'
            }
        });

        if (!res.ok) {
            const errorText = await res.text();
            throw new Error(errorText);
        }

        const data = await res.json();

        let batchId = data.batch_id;

        document.getElementById('progress-title').innerText = "Processing & Sending Emails...";

        let interval = setInterval(async () => {

            const progressRes = await fetch(`/import-progress/${batchId}`);
            const progress = await progressRes.json();

            let percent = 0;

            if (progress.total > 0) {
                percent = Math.round((progress.processed / progress.total) * 100);
            }

            document.getElementById('progress-bar').style.width = percent + "%";
            document.getElementById('progress-percent').innerText = percent + "%";
            document.getElementById('progress-text').innerText =
                progress.processed + " / " + progress.total + " processed";

            if (progress.status === 'completed') {
                clearInterval(interval);
                document.getElementById('progress-title').innerText = "Import Completed ✅";
            }

        }, 1000);

    } catch (err) {
        console.error(err);
        alert("Upload failed: " + err.message);
    }
});