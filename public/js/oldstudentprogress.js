document.getElementById('importOldForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);
    const url = form.dataset.url;

    const progressContainer = document.getElementById('progress-container');
    const progressBar = document.getElementById('progress-bar');
    const progressPercent = document.getElementById('progress-percent');
    const progressTitle = document.getElementById('progress-title');
    

    progressContainer.style.display = 'block';
    progressTitle.innerText = "Uploading...";

    const xhr = new XMLHttpRequest();

    xhr.open('POST', url, true);

    // CSRF Token
    xhr.setRequestHeader('X-CSRF-TOKEN',
        document.querySelector('input[name="_token"]').value
    );

    // Upload progress
    xhr.upload.addEventListener('progress', function(e) {
        if (e.lengthComputable) {
            let percent = Math.round((e.loaded / e.total) * 100);
            progressBar.style.width = percent + '%';
            progressPercent.innerText = percent + '%';
        }
    });

    // When upload complete
xhr.onload = function() {
    if (xhr.status >= 200 && xhr.status < 300) {

        const response = JSON.parse(xhr.responseText);

        progressBar.style.width = '100%';
        progressPercent.innerText = '100%';

        progressTitle.innerText = "Import Complete";

        document.getElementById('progress-text').innerText =
            response.processed + " / " + response.total + " processed";

        // REMOVE reload ❌
        // setTimeout(() => {
        //     location.reload();
        // }, 1500);

    } else {
        progressTitle.innerText = "Upload Failed";
        console.error(xhr.responseText);
    }
};

    xhr.send(formData);
});