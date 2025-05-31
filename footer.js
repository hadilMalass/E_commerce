// Function to load the footer dynamically
document.addEventListener("DOMContentLoaded", function () {
    // Create a container to load the footer
    const footerContainer = document.createElement('div');
    footerContainer.id = 'footer-container';

    // Fetch the footer.html file
    fetch('footer.html')
        .then(response => response.text())
        .then(data => {
            footerContainer.innerHTML = data;
            document.body.appendChild(footerContainer); // Append the footer at the end of the body
        })
        .catch(error => console.error('Error loading footer:', error));
});
