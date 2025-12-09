// ========== POST PAGE COMMENTS ==========
// Get post slug from URL
const urlParams = new URLSearchParams(window.location.search);
const POST_ID = urlParams.get('slug') || 'finding-flow-modern-workspace';

async function loadComments() {
  const res = await fetch(`api/comments.php?action=get&post_id=${POST_ID}`);
  const data = await res.json();
  
  const container = document.getElementById('commentsList');
  if (data.success && data.comments.length > 0) {
    let html = '';
    data.comments.forEach(comment => {
      const date = new Date(comment.created_at).toLocaleDateString();
      html += `
        <div class="comment">
          <div class="commentHeader">
            <strong>${comment.username}</strong>
            <span class="commentDate">${date}</span>
          </div>
          <p class="commentBody">${comment.comment}</p>
        </div>`;
    });
    container.innerHTML = html;
  } else {
    container.innerHTML = '<p style="text-align: center; color: #475569;">No comments yet. Be the first to comment!</p>';
  }
}

async function addComment(e) {
  e.preventDefault();
  const formData = new FormData(e.target);
  formData.append('post_id', POST_ID);
  
  const res = await fetch('api/comments.php?action=add', { method: 'POST', body: formData });
  const data = await res.json();
  
  if (data.success) {
    e.target.reset();
    loadComments();
  } else {
    alert(data.message);
  }
}

// Load comments when page loads
loadComments();
