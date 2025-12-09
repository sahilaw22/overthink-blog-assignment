// ========== AUTHENTICATION MODAL ==========
function openAuthModal(type) {
  document.getElementById('authModal').classList.add('active');
  if (type === 'login') switchToLogin();
  else switchToSignup();
}

function closeAuthModal() {
  document.getElementById('authModal').classList.remove('active');
}

function switchToLogin() {
  document.getElementById('loginForm').style.display = 'block';
  document.getElementById('signupForm').style.display = 'none';
}

function switchToSignup() {
  document.getElementById('loginForm').style.display = 'none';
  document.getElementById('signupForm').style.display = 'block';
}

// ========== AUTHENTICATION HANDLERS ==========
async function handleLogin(e) {
  e.preventDefault();
  const formData = new FormData(e.target);
  const res = await fetch('api/login.php', { method: 'POST', body: formData });
  const data = await res.json();
  alert(data.message);
  if (data.success) location.reload();
}

async function handleSignup(e) {
  e.preventDefault();
  const formData = new FormData(e.target);
  const res = await fetch('api/signup.php', { method: 'POST', body: formData });
  const data = await res.json();
  alert(data.message);
  if (data.success) location.reload();
}

// ========== SEARCH FUNCTIONALITY ==========
async function searchPosts(e) {
  e.preventDefault();
  const query = document.getElementById('searchInput').value;
  if (!query) return;
  
  const res = await fetch(`api/search.php?q=${encodeURIComponent(query)}`);
  const data = await res.json();
  
  if (data.success && data.posts.length > 0) {
    let html = '<h3>Search Results (' + data.count + ')</h3><div class="articleGrid">';
    data.posts.forEach(post => {
      html += `
        <div class="articleGridItem">
          <div class="articleCard">
            <div class="cardDetails">
              <h2>${post.title}</h2>
              <p>${post.excerpt}</p>
              <div class="cardMeta">
                <p>${new Date(post.created_at).toLocaleDateString()}</p>
                <a href="post.php?slug=${encodeURIComponent(post.slug)}"><button class="readMoreButton">Read More</button></a>
              </div>
            </div>
            ${post.image_url ? `<img src="${post.image_url}?auto=format&fit=crop&w=800&q=80" loading="lazy" />` : '<img src="images/hero.png" loading="lazy" />'}
          </div>
        </div>`;
    });
    html += '</div>';
    document.getElementById('searchResults').innerHTML = html;
    document.getElementById('postsContainer').style.display = 'none';
  } else {
    document.getElementById('searchResults').innerHTML = '<p>No results found.</p>';
    document.getElementById('postsContainer').style.display = 'block';
  }
}
