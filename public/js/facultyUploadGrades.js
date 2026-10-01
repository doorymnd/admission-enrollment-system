document.addEventListener('DOMContentLoaded', () => {

    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('excelInput');
    const fileNameDisplay = document.getElementById('file-name');

    if (!dropZone || !fileInput) return;

    // CLICK = open file picker
    dropZone.addEventListener('click', () => {
        fileInput.click();
    });

    // SHOW FILE NAME (without removing UI)
    const showFileName = (file) => {
        fileNameDisplay.textContent = "Selected: " + file.name;
    };

    // WHEN FILE SELECTED
    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) {
            showFileName(fileInput.files[0]);
        }
    });

    // DRAG OVER
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });

    // DRAG LEAVE
    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('dragover');
    });

    // DROP FILE
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('dragover');

        const files = e.dataTransfer.files;

        if (files.length > 0) {
            fileInput.files = files;
            showFileName(files[0]);
        }
    });

});