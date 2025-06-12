/* Content for assets/js/main.js */

document.addEventListener('DOMContentLoaded', () => {
    const postForm = document.getElementById('post-form');
    const feedContainer = document.getElementById('feed-container');

    if (postForm) {
        postForm.addEventListener('submit', async (event) => {
            event.preventDefault();

            const content = document.getElementById('post-content').value;
            const image = document.getElementById('post-image').files[0];

            const formData = new FormData();
            formData.append('content', content);
            if (image) {
                formData.append('image', image);
            }

            try {
                const response = await fetch('api/create_post.php', {
                    method: 'POST',
                    body: formData,
                });

                const data = await response.json();

                if (data.status === 'success') {
                    document.getElementById('post-content').value = ''; // Clear the textarea
                    document.getElementById('post-image').value = null;    // Clear the file input
                    loadPosts(); // Reload the feed
                } else {
                    alert(data.message);
                }
            } catch (error) {
                console.error('Error creating post:', error);
                alert('Error creating post. Please try again.');
            }
        });
    }

    async function loadPosts() {
        if (!feedContainer) return;

        try {
            const response = await fetch('api/get_posts.php');
            const data = await response.json();

            if (data.status === 'success') {
                feedContainer.innerHTML = ''; // Clear existing posts
                data.posts.forEach(post => {
                    const postElement = createPostElement(post);
                    feedContainer.appendChild(postElement);
                });
            } else {
                feedContainer.innerHTML = `<p>${data.message}</p>`;
            }
        } catch (error) {
            console.error('Error loading posts:', error);
            feedContainer.innerHTML = '<p>Error loading posts. Please try again.</p>';
        }
    }


    function createPostElement(post) {
        const postDiv = document.createElement('div');
        postDiv.classList.add('post');
        postDiv.dataset.postId = post.id;

        // User Info
        const userInfoDiv = document.createElement('div');
        userInfoDiv.classList.add('user-info');
        userInfoDiv.innerHTML = `<a href="profile.html?id=${post.user_id}">User ID: ${post.user_id}</a>`;

        // Post Content
        const contentDiv = document.createElement('div');
        contentDiv.classList.add('content');
        contentDiv.textContent = post.content;

        // Image
        if (post.image_url) {
            const imageElement = document.createElement('img');
            imageElement.src = post.image_url;
            imageElement.alt = 'Post Image';
            imageElement.classList.add('post-image');
            contentDiv.appendChild(imageElement);
        }

        // Timestamp
        const timestampDiv = document.createElement('div');
        timestampDiv.classList.add('timestamp');
        timestampDiv.textContent = `Posted on: ${post.created_at}`;

        // Like/Unlike buttons
        const likeButton = document.createElement('button');
        likeButton.classList.add('like-button');
        likeButton.textContent = post.is_liked ? 'Unlike' : 'Like';
        likeButton.addEventListener('click', () => toggleLike(post.id, likeButton));

        // Comment Section
        const commentSectionDiv = document.createElement('div');
        commentSectionDiv.classList.add('comment-section');

        const commentInput = document.createElement('textarea');
        commentInput.classList.add('comment-input');
        commentInput.placeholder = 'Add a comment...';

        const commentButton = document.createElement('button');
        commentButton.classList.add('comment-button');
        commentButton.textContent = 'Comment';
        commentButton.addEventListener('click', () => addComment(post.id, commentInput.value, commentSectionDiv));

        const commentsContainer = document.createElement('div');
        commentsContainer.classList.add('comments-container');

        commentSectionDiv.appendChild(commentInput);
        commentSectionDiv.appendChild(commentButton);
        commentSectionDiv.appendChild(commentsContainer);

        // Append elements
        postDiv.appendChild(userInfoDiv);
        postDiv.appendChild(contentDiv);
        postDiv.appendChild(timestampDiv);
        postDiv.appendChild(likeButton);
        postDiv.appendChild(commentSectionDiv);

        loadComments(post.id, commentsContainer);

        return postDiv;
    }


    async function toggleLike(postId, button) {
        const isLiked = button.textContent === 'Unlike';
        const endpoint = isLiked ? 'api/unlike_post.php' : 'api/like_post.php';

        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `post_id=${postId}`,
            });

            const data = await response.json();

            if (data.status === 'success') {
                button.textContent = isLiked ? 'Like' : 'Unlike';
            } else {
                alert(data.message);
            }
        } catch (error) {
            console.error('Error toggling like:', error);
            alert('Error toggling like. Please try again.');
        }
    }

    async function addComment(postId, content, commentsContainer) {
        if (!content.trim()) {
            alert('Comment cannot be empty.');
            return;
        }

        try {
            const response = await fetch('api/comment_post.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `post_id=${postId}&content=${encodeURIComponent(content)}`,
            });

            const data = await response.json();

            if (data.status === 'success') {
                const commentInput = commentsContainer.querySelector('.comment-input');
                commentInput.value = ''; // Clear comment input
                loadComments(postId, commentsContainer); // Reload comments
            } else {
                alert(data.message);
            }
        } catch (error) {
            console.error('Error adding comment:', error);
            alert('Error adding comment. Please try again.');
        }
    }

    async function loadComments(postId, commentsContainer) {
        try {
            const response = await fetch(`api/get_comments.php?post_id=${postId}`);
            const data = await response.json();

            if (data.status === 'success') {
                commentsContainer.innerHTML = ''; // Clear existing comments
                data.comments.forEach(comment => {
                    const commentDiv = document.createElement('div');
                    commentDiv.classList.add('comment');
                    commentDiv.innerHTML = `<p><strong>User ${comment.user_id}:</strong> ${comment.content} - ${comment.created_at}</p>`;
                    commentsContainer.appendChild(commentDiv);
                });
            } else {
                commentsContainer.innerHTML = '<p>No comments yet.</p>';
            }
        } catch (error) {
            console.error('Error loading comments:', error);
            commentsContainer.innerHTML = '<p>Error loading comments.</p>';
        }
    }

    // Initial load of posts
    if (feedContainer) {
        loadPosts();
    }
});