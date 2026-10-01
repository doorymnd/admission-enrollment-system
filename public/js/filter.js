$(document).ready(function() {
    function fetchCourses() {
        var year_level = $('#year_level').val();
        var semester   = $('#semester').val();

        if(year_level && semester){
            $.ajax({
                url: '{{ route("admin.courses.filter") }}',
                type: 'GET',
                data: { year_level: year_level, semester: semester },
                success: function(data){
                    $('#course_code').empty().append('<option value="">Select Course Code</option>');
                    $('#course_name').empty().append('<option value="">Select Course Name</option>');
                    $.each(data, function(index, course){
                        $('#course_code').append('<option value="'+course.code+'">'+course.code+'</option>');
                        $('#course_name').append('<option value="'+course.name+'">'+course.name+'</option>');
                    });
                }
            });
        } else {
            $('#course_code').empty().append('<option value="">Select Year & Semester First</option>');
            $('#course_name').empty().append('<option value="">Select Year & Semester First</option>');
        }
    }

    $('#year_level, #semester').change(fetchCourses);
});