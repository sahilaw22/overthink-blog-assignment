<?php 
require_once 'config/database.php';

// Get post slug from URL
$slug = $_GET['slug'] ?? 'finding-flow-modern-workspace';

// Fetch post from database
$conn = getDbConnection();
$stmt = $conn->prepare("SELECT * FROM posts WHERE slug = ?");
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

// If post not found, redirect to main page
if (!$post) {
    header('Location: main.php');
    exit;
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($post['title']); ?> | Blog Website</title>
    <link rel="stylesheet" href="style.css" />
    <script src="https://kit.fontawesome.com/7a4b62b0a4.js" crossorigin="anonymous"></script>
  </head>
  <body>
    <header>
      <nav>
        <div class="navLeft">
          <h1>OverThink</h1>
          <div class="navPages">
            <a class="navButton" href="main.php">Home</a>
            <a class="navButton navButtonActive" href="post.php">Posts</a>
          </div>
        </div>
        <div class="navRight">
          <div class="navAuth">
            <?php if (isset($_SESSION['user_id'])): ?>
              <span class="userGreeting">Hi, <?php echo htmlspecialchars($_SESSION['name']); ?></span>
              <a class="navButton" href="api/logout.php">Logout</a>
            <?php else: ?>
              <a class="navButton" href="#" onclick="openAuthModal('login')">Login</a>
              <a class="navButton navButtonPrimary" href="#" onclick="openAuthModal('signup')">Sign Up</a>
            <?php endif; ?>
          </div>
        </div>
      </nav>
    </header>
    <main class="post">
      <section>
        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
        <div class="aboutAuthor">
          <h3>Written by OverThink Team</h3>
          <p><?php echo date('l M d', strtotime($post['created_at'])); ?></p>
        </div>
      </section>
      <hr />
      <article>
        <?php if ($post['image_url']): ?>
        <div class="postImageBlock">
          <img src="<?php echo htmlspecialchars($post['image_url']); ?>?auto=format&fit=crop&w=800&q=80" alt="<?php echo htmlspecialchars($post['title']); ?>" loading="lazy" />
        </div>
        <?php endif; ?>
        
        <?php 
        // Simple content parser for better formatting
        $content = htmlspecialchars($post['content']);
        $lines = explode("\n", $content);
        $inParagraph = false;
        
        foreach ($lines as $line) {
          $line = trim($line);
          
          if (empty($line)) {
            if ($inParagraph) {
              echo '</p>';
              $inParagraph = false;
            }
            continue;
          }
          
          // Check if line looks like a heading (all caps or title case with no punctuation at end)
          if (preg_match('/^[A-Z][A-Za-z\s]+$/', $line) && strlen($line) < 60) {
            if ($inParagraph) {
              echo '</p>';
              $inParagraph = false;
            }
            echo '<h2>' . $line . '</h2>';
          } else {
            if (!$inParagraph) {
              echo '<p>';
              $inParagraph = true;
            } else {
              echo ' ';
            }
            echo $line;
          }
        }
        
        if ($inParagraph) {
          echo '</p>';
        }
        ?>
      </article>

      <div class="commentsSection">
        <h2>Comments</h2>
        <div class="commentsList" id="commentsList">
          <p>Loading comments...</p>
        </div>
        
        <?php if (isset($_SESSION['user_id'])): ?>
          <div class="commentForm">
            <h3>Add a Comment</h3><br>
            <form onsubmit="addComment(event)">
              <div class="formGroup">
                <textarea rows = 4 name="comment" required placeholder="Share your thoughts..."></textarea>
              </div>
              <button type="submit" class="submitButton">Post Comment</button>
            </form>
          </div>
        <?php else: ?>
          <p style="text-align: center; padding: 2rem; background: #f9fafb; border-radius: 0.8rem;">
            <a href="#" onclick="openAuthModal('login')" style="color: #1d4ed8; font-weight: 600;">Login</a> to add a comment
          </p>
        <?php endif; ?>
      </div>
    </main>
    <hr />

    <div id="authModal" class="authModal">
      <div class="authBox">
        <span class="closeModal" onclick="closeAuthModal()">&times;</span>
        <div id="loginForm">
          <h2>Login</h2>
          <form onsubmit="handleLogin(event)">
            <div class="formGroup">
              <label>Username</label>
              <input type="text" name="username" required />
            </div>
            <div class="formGroup">
              <label>Password</label>
              <input type="password" name="password" required />
            </div>
            <button type="submit" class="authBtn">Login</button>
          </form>
          <p class="switchAuth" onclick="switchToSignup()">Don't have an account? Sign up</p>
        </div>
        <div id="signupForm" style="display: none;">
          <h2>Sign Up</h2>
          <form onsubmit="handleSignup(event)">
            <div class="formGroup">
              <label>Full Name</label>
              <input type="text" name="name" required />
            </div>
            <div class="formGroup">
              <label>Username</label>
              <input type="text" name="username" required />
            </div>
            <div class="formGroup">
              <label>Password</label>
              <input type="password" name="password" minlength="6" required />
            </div>
            <button type="submit" class="authBtn">Sign Up</button>
          </form>
          <p class="switchAuth" onclick="switchToLogin()">Already have an account? Login</p>
        </div>
      </div>
    </div>

    <footer>
      <div class="footerLayout">
        <div class="footerBrand">
          <h2 class="footerLogo">OverThink</h2>
          <p class="footerTagline">Where deep thinkers come to explore ideas worth pondering.</p>
          <p class="footerDescription">Join 30,000+ subscribers who receive thoughtful essays on philosophy, productivity, design, and intentional living every Sunday morning.</p>
        </div>
        <div class="footerLinks">
          <div class="footerColumn">
            <h4>Explore</h4>
            <ul>
              <li><a href="#">Latest Posts</a></li>
              <li><a href="#">Popular Essays</a></li>
              <li><a href="#">Deep Dives</a></li>
              <li><a href="#">Book Notes</a></li>
            </ul>
          </div>
          <div class="footerColumn">
            <h4>Resources</h4>
            <ul>
              <li><a href="#">Thinking Tools</a></li>
              <li><a href="#">Reading List</a></li>
              <li><a href="#">Frameworks</a></li>
              <li><a href="#">Newsletter</a></li>
            </ul>
          </div>
          <div class="footerColumn">
            <h4>Connect</h4>
            <ul class="footerSocialLinks">
              <li><a href="#" aria-label="Instagram"><i class="fa fa-instagram"></i> Instagram</a></li>
              <li><a href="#" aria-label="Twitter"><i class="fa fa-twitter"></i> Twitter</a></li>
              <li><a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin"></i> LinkedIn</a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="footerBottomBar">
        <p>© 2025 OverThink · Crafted with curiosity and intention</p>
        <div class="footerLegalLinks">
          <a href="#">Privacy Policy</a>
          <span>·</span>
          <a href="#">Terms of Use</a>
        </div>
      </div>
    </footer>

    <script src="js/main.js"></script>
    <script src="js/post.js"></script>
  </body>
</html>
