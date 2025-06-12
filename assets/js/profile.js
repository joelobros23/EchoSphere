document.addEventListener('DOMContentLoaded', () => {
    const profilePicture = document.getElementById('profilePicture');
    const usernameElement = document.getElementById('username');
    const firstNameElement = document.getElementById('firstName');
    const lastNameElement = document.getElementById('lastName');
    const bioElement = document.getElementById('bio');
    const editProfileForm = document.getElementById('editProfileForm');
    const messageDiv = document.getElementById('message');

    // Function to display messages to the user
    function displayMessage(message, type = 'success') {
        messageDiv.textContent = message;
        messageDiv.className = `message ${type}`; // Add 'message' class for styling
    }

    // Function to fetch and display user profile
    async function fetchUserProfile() {
        try {
            const response = await fetch('api/get_user.php');
            const data = await response.json();

            if (data.status === 'success') {
                profilePicture.src = data.profile.profile_picture ? data.profile.profile_picture : 'assets/images/default.jpg'; //Use a relative path

                usernameElement.textContent = data.profile.username;
                firstNameElement.textContent = data.profile.first_name || 'N/A';
                lastNameElement.textContent = data.profile.last_name || 'N/A';
                bioElement.textContent = data.profile.bio || 'N/A';
                // Pre-populate the edit profile form (if it exists)
                if (editProfileForm) {
                    document.getElementById('editFirstName').value = data.profile.first_name || '';
                    document.getElementById('editLastName').value = data.profile.last_name || '';
                    document.getElementById('editBio').value = data.profile.bio || '';
                }

            } else {
                displayMessage(data.message, 'error');
            }
        } catch (error) {
            displayMessage('Failed to fetch profile. Please try again.', 'error');
        }
    }

    // Function to handle profile update
    async function handleProfileUpdate(event) {
        event.preventDefault();

        const formData = new FormData(editProfileForm);

        try {
            const response = await fetch('api/update_profile.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.status === 'success') {
                displayMessage(data.message);
                fetchUserProfile(); // Refresh the profile data
            } else {
                displayMessage(data.message, 'error');
            }
        } catch (error) {
            displayMessage('Failed to update profile. Please try again.', 'error');
        }
    }

    // Event listener for profile update form submission
    if (editProfileForm) {
        editProfileForm.addEventListener('submit', handleProfileUpdate);
    }

    // Call fetchUserProfile on page load
    fetchUserProfile();
});