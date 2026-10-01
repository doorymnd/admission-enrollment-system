function confirmUnlock(section, subjectId) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'If you unlock this, the faculty will be able to edit the uploaded grades.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, unlock it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('unlockForm-' + section + '-' + subjectId).submit();
        }
    });
}




function confirmSubmit(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "⚠️ Once you submit these grades, you will NOT be able to edit them anymore. You will need admin permission to change them.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Submit',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('submitGradesForm-' + id).submit();
        }
    });
}