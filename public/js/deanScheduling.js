$(document).ready(function () {

    // =========================
    // COURSE CHANGE
    // =========================
    $('#courseSelect').on('change', function () {

        let courseId = $(this).val();

        resetDropdowns();

        if (!courseId) return;

        // =========================
        // LOAD SUBJECTS
        // =========================
        $.ajax({
            url: '/dean/get-subjects/' + courseId,
            type: 'GET',
            success: function (data) {

                let subjectOptions = '<option value="">-- Select Subject --</option>';

                $.each(data, function (key, subject) {

                    let code = subject.code ?? 'N/A';

                    subjectOptions += `
                        <option 
                            value="${subject.id}" 
                            data-semester="${subject.semester}">
                            ${code} - ${subject.name}
                        </option>
                    `;
                });

                $('#subjectSelect')
                    .html(subjectOptions)
                    .prop('disabled', false);
            },
            error: function () {
                $('#subjectSelect')
                    .html('<option>Error loading subjects</option>')
                    .prop('disabled', false);
            }
        });


        // =========================
        // LOAD SECTIONS
        // =========================
        $.ajax({
            url: '/dean/get-sections/' + courseId,
            type: 'GET',
            success: function (data) {

                let sectionOptions = '<option value="">-- Select Section --</option>';

                let yearMap = {
                    1: 'First Year',
                    2: 'Second Year',
                    3: 'Third Year',
                    4: 'Fourth Year'
                };

                $.each(data, function (key, section) {

                    let yearText = yearMap[section.year_level] 
                        ?? `${section.year_level} Year`;

                    let sectionName = `${yearText} - Section ${section.section}`;

                    sectionOptions += `
                        <option value="${section.id}">
                            ${sectionName}
                        </option>
                    `;
                });

                $('#sectionSelect')
                    .html(sectionOptions)
                    .prop('disabled', false);
            },
            error: function () {
                $('#sectionSelect')
                    .html('<option>Error loading sections</option>')
                    .prop('disabled', false);
            }
        });

    });


    // =========================
    // SUBJECT → AUTO SEMESTER
    // =========================
    $('#subjectSelect').on('change', function () {

        let semester = $(this).find(':selected').data('semester');

        let semesterInput = $('#semesterSelect');

        let semesterText = '';

        switch (parseInt(semester)) {

            case 1:
                semesterText = 'First Semester';
                break;

            case 2:
                semesterText = 'Second Semester';
                break;

            case 3:
                semesterText = 'Summer';
                break;

            default:
                semesterText = 'Unknown';
        }

        // set value in input
        semesterInput.val(semesterText);
    });


    // =========================
    // RESET FUNCTION
    // =========================
    function resetDropdowns() {

        $('#subjectSelect')
            .html('<option value="">-- Select Subject --</option>')
            .prop('disabled', true);

        $('#sectionSelect')
            .html('<option value="">-- Select Section --</option>')
            .prop('disabled', true);

        $('#semesterSelect')
            .val('')
            .prop('readonly', true)
            .attr('placeholder', 'Select a subject first');
    }

});