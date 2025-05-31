
        </div>
    <div class="header-icons">
        <?php if (isset($_SESSION['user_id'])): ?>
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
            <a href="logout.php" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
        <?php else: ?>
            <a href="login1.php" title="Login"><i class="fas fa-user"></i></a>
        <?php endif; ?>
        <a href="favorites2.php" title="Favorites"><i class="fas fa-heart"></i></a>
        <a href="buyproduct.php" title="Cart"><i class="fas fa-shopping-cart"></i></a>
    </div>
</header>
