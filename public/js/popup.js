
function handleEditClick(element) {
    // Retrieve data from the clicked element or use any other method to get the data
    var name = element.getAttribute("data-name");
    var code = element.getAttribute("data-code");

    // Set the values in the form fields
    document.getElementById("name").value = name;
    document.getElementById("code").value = code;

    // Display the edit modal
    document.getElementById("editModal").style.display = "block";
}

function closeEditModal() {
    document.getElementById("editModal").style.display = "none";
}




