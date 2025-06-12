# Project Plan: EchoSphere

**Description:** A social networking platform designed for sharing thoughts, ideas, and connecting with like-minded individuals. EchoSphere focuses on creating a safe and engaging online environment.

## Features to Test

- User Registration
- User Login
- Create Post (text and image)
- View Posts Feed
- Edit Profile (basic info, profile picture)
- Like Posts
- Comment on Posts
- View User Profiles
- Logout

## Development Goals

- [ ] Create the database schema for users, posts, comments, and likes in database.sql.
- [ ] Set up the database connection in api/config.php using provided credentials.
- [ ] Implement user registration functionality in api/register.php, including password hashing.
- [ ] Implement user login functionality in api/login.php, verifying credentials against the database.
- [ ] Implement user logout functionality in api/logout.php, handling session destruction.
- [ ] Implement post creation with text and image upload capabilities in api/create_post.php.
- [ ] Develop the logic in api/get_posts.php to retrieve posts for the main feed, ordered by creation date.
- [ ] Implement the profile update logic in api/update_profile.php, allowing users to modify their basic information and profile picture.
- [ ] Create an endpoint api/get_user.php to retrieve a user's profile data by ID.
- [ ] Create an endpoint api/like_post.php to handle post liking, adding a new record to the likes table.
- [ ] Create an endpoint api/unlike_post.php to handle post unliking, deleting the corresponding record from the likes table.
- [ ] Implement comment creation functionality in api/comment_post.php, adding new comments to the database.
- [ ] Develop the logic in api/get_comments.php to retrieve comments for a specific post, ordered by creation date.
- [ ] Build the HTML structure for the registration page in register.html.
- [ ] Build the HTML structure for the login page in index.html.
- [ ] Build the HTML structure for the main feed page in home.html.
- [ ] Build the HTML structure for the user profile page in profile.html.
- [ ] Build the HTML structure for the edit profile page in edit_profile.html.
- [ ] Write JavaScript in assets/js/auth.js to handle user registration, login, and logout interactions, using the implemented API endpoints.
- [ ] Write JavaScript in assets/js/main.js to handle post creation, feed display, liking, unliking, and commenting functionality, communicating with the respective API endpoints.
- [ ] Write JavaScript in assets/js/profile.js to handle profile updates and display user information, using the profile API endpoints.
- [ ] Implement Tailwind CSS styling in assets/css/tailwind.css for a responsive and visually appealing user interface.
