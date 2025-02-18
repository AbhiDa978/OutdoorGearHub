<header>
    <div class="logo-search">
        <div class="logo" onclick="window.location.href = 'index.php';">OutdoorGear Hub</div>
        <div class="search-bar">
            <form action="index.php" method="GET">
                <input type="text" name="search" placeholder="Search gear..." value="<?php echo htmlspecialchars($search_query ?? ''); ?>">
            </form>
        </div>
    </div>
    <div class="header-icons">
        <a href="cartpage.php" class="cart-icon" id="cart-icon">
            <i class="fas fa-shopping-cart"></i>
            <span class="cart-badge" id="cart-badge" style="display: none;">0</span>
        </a>
        
        <?php if (isset($_SESSION['username'])): ?>
            <!-- Logout Button (appears only when logged in) -->
            <a href="logout.php" style="display: inline-block; padding: 8px 15px; background-color: #f44336; color: white; text-decoration: none; border-radius: 5px; margin-left: 10px;">Logout</a>
        <?php endif; ?>

        <div class="profile-icon" id="profile-icon">
            <?php if (isset($_SESSION['username'])): ?>
                <div class="user-id" id="user-name"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
            <?php else: ?>
                <a href="login.php" style="color: inherit;">Login</a>
            <?php endif; ?>
            <i class="fas fa-user"></i>
        </div>
        
        <div class="menu-icon" id="menu-icon">
            <i class="fas fa-bars"></i>
        </div>
    </div>
</header>

<div class="sidebar" id="sidebar">
    <a href="about.php">About Us</a>
    <a href="contact.php">Contact Us</a>
    <a href="terms.php">Terms & Conditions</a>
</div>
