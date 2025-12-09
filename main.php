<?php
require_once 'config/database.php';

$conn = getDbConnection();
$posts = [];
$result = $conn->query("SELECT slug, title, excerpt, image_url, created_at FROM posts ORDER BY created_at DESC");

if ($result) {
  while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
  }
  $result->free();
}

$conn->close();

$primaryPost = $posts[0] ?? null;
$gridPosts = array_slice($posts, 1, 3);
$secondaryPost = $posts[4] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home Page | Blog Website</title>
    <link rel="stylesheet" href="style.css" />
    <script src="https://kit.fontawesome.com/7a4b62b0a4.js" crossorigin="anonymous"></script>
  </head>
  <body>
    <header>
      <nav>
        <div class="navLeft">
          <h1>OverThink</h1>
          <div class="navPages">
            <a class="navButton navButtonActive" href="main.php">Home</a>
            <a class="navButton" href="post.php">Posts</a>
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
    <main>
      <section id="hero">
        <div class="heroText">
          <h1>Think Deeper, Create Better</h1>
          <h4>Exploring ideas, productivity, design, and the art of intentional living through thoughtful analysis.</h4>
        </div>
        <div class="heroImage">
          <img src="images/hero.png" alt="hero" />
        </div>
      </section>

      <div class="searchBar">
        <form onsubmit="searchPosts(event)">
          <input type="text" id="searchInput" placeholder="Search posts..." />
          <button type="submit">Search</button>
        </form>
      </div>

      <div class="searchResults" id="searchResults"></div>

      <div id="postsContainer">
        <?php if ($primaryPost): ?>
          <div class="featuredCard">
            <div class="cardDetails">
              <h2><?php echo htmlspecialchars($primaryPost['title']); ?></h2>
              <p><?php echo htmlspecialchars($primaryPost['excerpt']); ?></p>
              <div class="cardMeta">
                <p><?php echo date('d M Y', strtotime($primaryPost['created_at'])); ?></p>
                <a href="post.php?slug=<?php echo urlencode($primaryPost['slug']); ?>">
                  <button class="readMoreButton">Read More</button>
                </a>
              </div>
            </div>
            <img src="<?php echo htmlspecialchars($primaryPost['image_url'] ?: 'images/hero.png'); ?>" alt="<?php echo htmlspecialchars($primaryPost['title']); ?>" loading="lazy" />
          </div>
        <?php else: ?>
          <p style="text-align:center; color:#475569;">No posts available yet. Check back soon!</p>
        <?php endif; ?>

        <?php if (!empty($gridPosts)): ?>
          <div class="articleGrid">
            <?php foreach ($gridPosts as $postItem): ?>
              <div class="articleGridItem">
                <div class="articleCard">
                  <div class="cardDetails">
                    <h2><?php echo htmlspecialchars($postItem['title']); ?></h2>
                    <p><?php echo htmlspecialchars($postItem['excerpt']); ?></p>
                    <div class="cardMeta">
                      <p><?php echo date('d M Y', strtotime($postItem['created_at'])); ?></p>
                      <a href="post.php?slug=<?php echo urlencode($postItem['slug']); ?>">
                        <button class="readMoreButton">Read More</button>
                      </a>
                    </div>
                  </div>
                  <img src="<?php echo htmlspecialchars($postItem['image_url'] ?: 'images/hero.png'); ?>" alt="<?php echo htmlspecialchars($postItem['title']); ?>" loading="lazy" />
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <?php if ($secondaryPost): ?>
          <div class="featuredCard">
            <div class="cardDetails">
              <h2><?php echo htmlspecialchars($secondaryPost['title']); ?></h2>
              <p><?php echo htmlspecialchars($secondaryPost['excerpt']); ?></p>
              <div class="cardMeta">
                <p><?php echo date('d M Y', strtotime($secondaryPost['created_at'])); ?></p>
                <a href="post.php?slug=<?php echo urlencode($secondaryPost['slug']); ?>">
                  <button class="readMoreButton">Read More</button>
                </a>
              </div>
            </div>
            <img src="<?php echo htmlspecialchars($secondaryPost['image_url'] ?: 'images/hero.png'); ?>" alt="<?php echo htmlspecialchars($secondaryPost['title']); ?>" loading="lazy" />
          </div>
        <?php endif; ?>
      </div>
    </main>

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
  </body>
</html>
