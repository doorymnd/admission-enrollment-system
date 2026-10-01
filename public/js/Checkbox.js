document.getElementById('noMiddle').addEventListener('change', function () {
    const middleInput = document.getElementById('middle_name');

    if (this.checked) {
        middleInput.value = 'N/A';
        middleInput.readOnly = true;
        middleInput.disabled = true;

        // Remove name so it won't be submitted
        middleInput.dataset.name = middleInput.name;
        middleInput.removeAttribute('name');
    } else {
        middleInput.value = '';
        middleInput.readOnly = false;
        middleInput.disabled = false;

        // Restore name so it will be submitted
        middleInput.name = middleInput.dataset.name;
    }
});

document.getElementById('noSuffix').addEventListener('change', function () {
    const suffixInput = document.getElementById('suffix');

    if (this.checked) {
        suffixInput.value = 'N/A';
        suffixInput.readOnly = true;
        suffixInput.disabled = true;

        // Remove name so it won't be submitted
        suffixInput.dataset.name = suffixInput.name;
        suffixInput.removeAttribute('name');
    } else {
        suffixInput.value = '';
        suffixInput.readOnly = false;
        suffixInput.disabled = false;

        // Restore name so it will be submitted
        suffixInput.name = suffixInput.dataset.name;
    }
});

function togglePassword(id) {
    const field = document.getElementById(id);
    field.type = field.type === 'password' ? 'text' : 'password';
}

