document.getElementById("searchInput").addEventListener("keyup", function () {

    let input = this.value.toLowerCase();
    let table = document.getElementById("assignmentTable");
    let rows = table.getElementsByTagName("tr");

    for (let i = 1; i < rows.length; i++) {

        let rowText = rows[i].textContent.toLowerCase();

        if (rowText.includes(input)) {
            rows[i].style.display = "";
        } else {
            rows[i].style.display = "none";
        }

    }

});  