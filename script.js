// Function to show event details in a popup
function showEventDetails(eventId) {
    // Hide any currently open event details
    document.querySelectorAll('.event-details').forEach(el => {
        el.classList.remove('show');
    });

    // Show the selected event details
    const eventElement = document.getElementById(eventId);
    eventElement.classList.add('show');
}

// Function to handle booking and redirect to signup page if user not logged in
function bookNow() {
    // Here, you could add code to check if the user is logged in
    // For now, just redirecting to signup page
    window.location.href = 'signup.php';
}
