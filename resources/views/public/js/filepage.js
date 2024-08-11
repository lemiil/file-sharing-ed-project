function toggleDescription() {
    const descriptionElement = document.getElementById("description");
    if (descriptionElement.style.display === "none") {
        descriptionElement.style.display = "block";
    } else {
        descriptionElement.style.display = "none";
    }
}

$(document).ready(function () {
    AOS.init({
        duration: 1000,
    });
});
