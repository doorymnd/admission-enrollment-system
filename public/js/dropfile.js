document.addEventListener('DOMContentLoaded', () => {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('excelInput');
    const dropContent = document.getElementById('dropContent');

    if (!dropZone || !fileInput || !dropContent) return;

    const showFileName = (file) => {
        dropContent.innerHTML = `
            <p class="drop-text" style="font-size: 18px; font-weight: 600;">
                ${file.name}
            </p>
        `;
    };

    // When file selected via click
    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) {
            showFileName(fileInput.files[0]);
        }
    });

    // Drag over
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });

    // Drag leave
    dropZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropZone.classList.remove('dragover');
    });

    // Drop file
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('dragover');

        if (e.dataTransfer.files.length > 0) {
            const file = e.dataTransfer.files[0];
            fileInput.files = e.dataTransfer.files;
            showFileName(file);
        }
    });
});