document.getElementById("contactForm").addEventListener("submit", function(e) {
    e.preventDefault();

    let name = document.getElementById("name").value.trim();
    let email = document.getElementById("email").value.trim();
    let message = document.getElementById("message").value.trim();
    let status = document.getElementById("status");

    if (name === "" || email === "" || message === "") {
        status.textContent = "All fields are required!";
        status.style.color = "red";
        return;
    }

    if (!email.includes("@")) {
        status.textContent = "Invalid email address!";
        status.style.color = "red";
        return;
    }

    status.textContent = "Message sent successfully!";
    status.style.color = "lightgreen";
});
