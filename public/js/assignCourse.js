document.getElementById('department-select').addEventListener('change', function() {

    let department = this.value;

    fetch('/admin/courses/department/' + department)
        .then(response => response.json())
        .then(data => {

            let courseSelect = document.getElementById('course-select');
            courseSelect.innerHTML = '<option value="">-- Select Course --</option>';

            data.forEach(course => {
                courseSelect.innerHTML += `
                    <option value="${course.program_id}">
                        ${course.name}
                    </option>
                `;
            });

        });

});




document.getElementById('course-select').addEventListener('change', loadSubjects);
document.getElementById('year-level').addEventListener('change', loadSubjects);
document.getElementById('semester').addEventListener('change', loadSubjects);

function loadSubjects() {

    let course = document.getElementById('course-select').value;
    let year = document.getElementById('year-level').value;
    let semester = document.getElementById('semester').value;

    if(!course || !year || !semester) return;

    fetch(`/admin/get-subjects/${course}/${year}`)
    .then(res => res.json())
    .then(data => {

        let subject = document.getElementById('subject');

        subject.innerHTML = '<option>-- Select Subject --</option>';

        data.forEach(s => {
            subject.innerHTML += `<option value="${s.id}">${s.name}</option>`;
        });

    });

}


function ordinalSuffix(n) {
    if (n % 100 >= 11 && n % 100 <= 13) return n + 'th';
    switch (n % 10) {
        case 1: return n + 'st';
        case 2: return n + 'nd';
        case 3: return n + 'rd';
        default: return n + 'th';
    }
}

document.getElementById('course-select').addEventListener('change', function () {

    fetch('/admin/get-years/' + this.value)
    .then(res => res.json())
    .then(data => {

        let yearSelect = document.getElementById('year-level');

        yearSelect.innerHTML = '<option value="">-- Select Year --</option>';

        data.forEach(y => {
            yearSelect.innerHTML += `<option value="${y}">${ordinalSuffix(y)} Year</option>`;
        });

    });

});


