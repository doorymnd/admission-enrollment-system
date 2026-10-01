$('#importForm').on('submit', function (e) {
    e.preventDefault();

    let formData = new FormData(this);

    $('#uploadBtn').prop('disabled', true);

    $('#progress-container').fadeIn();
    $('#progress-title').text('Uploading file...');
    $('#progress-bar').css('width', '5%');
    $('#progress-percent').text('5%');
    $('#progress-text').text('Preparing import...');

    $.ajax({
        url: $(this).data('url'),
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (res) {
            $('#progress-title').text('Processing & Sending Emails...');
            pollProgress(res.batch_id);
        },
        error: function () {
            $('#progress-title').text('❌ Upload Failed');
            $('#uploadBtn').prop('disabled', false);
        }
    });
});


function pollProgress(batchId) {

    let timer = setInterval(() => {

        $.get(`/admin/import-progress/${batchId}`, function (data) {

            console.log(data); // Debug (remove later)

            let total = parseInt(data.total) || 0;
            let processed = parseInt(data.processed) || 0;
            let failed = parseInt(data.failed) || 0;

            let completed = processed + failed;

            let percent = 0;



            if (total > 0) {
                percent = Math.round((completed / total) * 100);
            }

            // Prevent over 100%
            if (percent > 100) percent = 100;

            $('#progress-bar').css('width', percent + '%');
            $('#progress-percent').text(percent + '%');

            $('#progress-text').text(
                `${completed}/${total} completed | Failed: ${failed}`
            );

            // FORCE FINISH
            if (data.status === 'completed') {

                clearInterval(timer);

                $('#progress-bar').css('width', '100%');
                $('#progress-percent').text('100%');

                $('#progress-title').text('✅ Import Completed Successfully!');
                $('#progress-text').html(`${processed} / ${total} completed`);

                $('#uploadBtn').prop('disabled', false);
            }

        });

    }, 1000);
}