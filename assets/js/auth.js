// assets/js/auth.js

document.addEventListener('DOMContentLoaded', () => {
    const registerForm = document.getElementById('registerForm');
    const loginForm = document.getElementById('loginForm');
  
    if (registerForm) {
      registerForm.addEventListener('submit', async (e) => {
        e.preventDefault();
  
        const formData = new FormData(registerForm);
  
        try {
          const response = await fetch('api/register.php', {
            method: 'POST',
            body: formData,
          });
  
          const data = await response.json();
  
          if (data.status === 'success') {
            alert(data.message);
            window.location.href = 'login.html'; // Redirect to login page after successful registration
          } else {
            alert(data.message);
          }
        } catch (error) {
          console.error('Error registering:', error);
          alert('An error occurred during registration.');
        }
      });
    }
  
    if (loginForm) {
      loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
  
        const formData = new FormData(loginForm);
  
        try {
          const response = await fetch('api/login.php', {
            method: 'POST',
            body: formData,
          });
  
          const data = await response.json();
  
          if (data.status === 'success') {
            // Store the token in local storage
            localStorage.setItem('token', data.token);
            localStorage.setItem('userId', data.userId);


            alert(data.message);
            window.location.href = 'home.html'; // Redirect to home page after successful login
          } else {
            alert(data.message);
          }
        } catch (error) {
          console.error('Error logging in:', error);
          alert('An error occurred during login.');
        }
      });
    }
  
    // Logout function
    window.logout = async () => {
        try {
          const response = await fetch('api/logout.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Authorization': 'Bearer ' + localStorage.getItem('token') // Include token for authentication
            }
          });
    
          const data = await response.json();
    
          if (data.status === 'success') {
            // Clear token from local storage
            localStorage.removeItem('token');
            localStorage.removeItem('userId');
    
            alert(data.message);
            window.location.href = 'index.html'; // Redirect to index page after successful logout
          } else {
            alert(data.message);
          }
        } catch (error) {
          console.error('Error logging out:', error);
          alert('An error occurred during logout.');
        }
      };

    // Check if a logout button exists and attach the logout function to it
    const logoutButton = document.getElementById('logoutButton');
    if (logoutButton) {
      logoutButton.addEventListener('click', logout);
    }
  });