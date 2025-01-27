<?php
session_start();
include('../backend/db_connection.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch products from the database
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = trim($_GET['search']);
    $stmt = $pdo->prepare("SELECT * FROM gear WHERE name LIKE :query OR category LIKE :query OR description LIKE :query");
    $stmt->bindValue(':query', "%" . $search_query . "%");
} else {
    $stmt = $pdo->prepare("SELECT * FROM gear LIMIT 12");
}
$stmt->execute();
$gear_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outdoor Gear Hub</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Header Styling */
        header {
            position: sticky;
            top: 0;
            background: #000;
            color: white;
            z-index: 1000;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            transition: all 0.3s ease;
        }

        header.shrink {
            padding: 10px 30px;
        }

        .logo-search {
            display: flex;
            align-items: center;
            flex: 1;
            justify-content: flex-start;
        }

        .logo {
            font-size: 2em;
            font-weight: bold;
            color: #fff;
            cursor: pointer;
        }

        .search-bar {
            margin-left: 20px;
            max-width: 300px;
            flex: 1;
        }

        .search-bar input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .header-icons {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .cart-icon, .profile-icon, .menu-icon {
            font-size: 24px;
            cursor: pointer;
            position: relative;
            color: #fff;
        }

        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: red;
            color: white;
            padding: 2px 5px;
            border-radius: 50%;
            font-size: 12px;
        }

        .profile-icon {
            position: relative;
        }

        .user-id {
            position: absolute;
            top: 30px;
            background-color: #333;
            color: white;
            padding: 5px;
            border-radius: 5px;
            display: none;
        }

        .profile-icon:hover .user-id {
            display: block;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0px; 
            bottom: 0%;
            width: 250px;
            height: auto;
            background-color: #333;
            color: white;
            transition: right 0.3s ease;
            z-index: 999;
            padding:80px 20px 20px;
            overflow-y: auto;
            right: -250px;
        }
        
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px 0;
            font-size: 1.2em;
            border-bottom: 1px solid #444;
        }

        .sidebar a:hover {
            background-color: #444;
        }

        .menu-icon {
            font-size: 28px;
            color: #fff;
        }

        /* Hero Section */
        .hero {
            background: url('hero.jpg') no-repeat center;
            background-size: cover;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .hero h1 {
            color: white;
            font-size: 3em;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
        }

        /* Product Grid */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
            margin-bottom: 50px;
        }

        .product-card {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            text-align: center;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .product-card:hover {
            transform: translateY(-10px);
        }

        .product-card img {
            max-width: 100%;
            height: 180px;
            object-fit: cover;
            margin-bottom: 15px;
        }

        .product-card h3 {
            font-size: 1.2em;
            margin-bottom: 10px;
        }

        .product-card p {
            font-size: 0.9em;
            margin-bottom: 10px;
        }

        .product-card .add-to-cart {
            padding: 10px 20px;
            background-color: #5aaf3d;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .product-card .add-to-cart:hover {
            background-color: #4c9e2d;
        }

        /* Footer Styling */
        footer {
            background: #333;
            color: #fff;
            text-align: center;
            padding: 20px;
            font-size: 0.9em;
            position: relative;
            z-index: 1;
        }

        footer a {
            color: #fff;
            text-decoration: none;
            padding: 0 10px;
            transition: color 0.3s ease;
        }

        footer a:hover {
            color: #5aaf3d;
        }

        /* Spacing for main content */
        main {
            padding-bottom: 50px;
        }

    </style>
</head>
<body>
    <header>
        <div class="logo-search">
            <div class="logo" onclick="window.location.href = 'index.php';">OutdoorGear Hub</div>
            <div class="search-bar">
                <form action="index.php" method="GET">
                    <input type="text" name="search" placeholder="Search gear..." value="<?php echo htmlspecialchars($search_query); ?>">
                </form>
            </div>
        </div>
        <div class="header-icons">
            <a href="cart.php" class="cart-icon" id="cart-icon">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-badge" id="cart-badge" style="display: none;">0</span>
            </a>
            <div class="profile-icon" id="profile-icon">
                <?php if (isset($_SESSION['username'])): ?>
                    <div class="user-id" id="user-id"><?php echo htmlspecialchars($_SESSION['user_id']); ?></div>
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

    <main>
        <section class="hero">
            <div style="background: url('hero.jpg') no-repeat center; background-size: cover; height: 400px; display: flex; align-items: center; justify-content: center;">
                <h1 style="color: white; font-size: 3em; text-shadow: 2px 2px 5px rgba(0,0,0,0.7);">Adventure Begins Here</h1>
            </div>
        </section>

        <section class="product-grid">
            <?php foreach ($gear_items as $gear): ?>
            <div class="product-card" data-id="<?php echo $gear['id']; ?>" data-name="<?php echo $gear['name']; ?>" data-price="<?php echo $gear['price']; ?>">
                <img src="../uploads/<?php echo $gear['image']; ?>" alt="<?php echo $gear['name']; ?>">
                <h3><?php echo $gear['name']; ?></h3>
                <p><?php echo $gear['description']; ?></p>
                <p><strong>$<?php echo $gear['price']; ?>/day</strong></p>
                <button class="add-to-cart">Add to Cart</button>
            </div>
            <?php endforeach; ?>
        </section>

        <?php if (empty($gear_items)): ?>
            <p style="text-align: center;">No gear found. Try searching for something else!</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2025 Outdoor Gear Hub. All rights reserved.</p>
        <p>
            <a href="about.php">About Us</a> |
            <a href="contact.php">Contact Us</a> |
            <a href="terms.php">Terms & Conditions</a>
        </p>
    </footer>

    <script>
        // Sidebar toggle
        const menuIcon = document.getElementById('menu-icon');
        const sidebar = document.getElementById('sidebar');

        menuIcon.addEventListener('click', () => {
            sidebar.style.right = sidebar.style.right === '0px' ? '-250px' : '0px';
        });

        // Cart update logic
        const addToCartButtons = document.querySelectorAll('.add-to-cart');
        const cartBadge = document.getElementById('cart-badge');
        let cartCount = localStorage.getItem('cartCount') ? parseInt(localStorage.getItem('cartCount')) : 0;

        const updateCartBadge = () => {
            if (cartCount > 0) {
                cartBadge.style.display = 'inline-block';
                cartBadge.innerText = cartCount;
            } else {
                cartBadge.style.display = 'none';
            }
        };
        updateCartBadge();

        addToCartButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productCard = this.closest('.product-card');
                const productId = productCard.getAttribute('data-id');
                const productName = productCard.getAttribute('data-name');
                const productPrice = productCard.getAttribute('data-price');

                let cart = JSON.parse(localStorage.getItem('cart')) || [];
                cart.push({ id: productId, name: productName, price: productPrice });
                localStorage.setItem('cart', JSON.stringify(cart));

                cartCount++;
                localStorage.setItem('cartCount', cartCount);
                updateCartBadge();

                alert(productName + ' has been added to your cart!');
            });
        });

        // Profile hover
        const profileIcon = document.getElementById('profile-icon');
        const userIdDiv = document.getElementById('user-id');
        if (profileIcon && userIdDiv) {
            profileIcon.addEventListener('mouseover', () => {
                if (userIdDiv) {
                    userIdDiv.style.display = 'block';
                }
            });

            profileIcon.addEventListener('mouseout', () => {
                if (userIdDiv) {
                    userIdDiv.style.display = 'none';
                }
            });
        }

        // Shrink header on scroll
        window.addEventListener('scroll', function() {
            const header = document.querySelector('header');
            if (window.scrollY > 50) {
                header.classList.add('shrink');
            } else {
                header.classList.remove('shrink');
            }
        });

        window.addEventListener('scroll', function() {
            const header = document.querySelector('sidebar');
            if (window.scrollY < 50) {
                header.classList.add('larger');
            } else {
                header.classList.remove('larger');
            }
        });
    </script>
</body>
</html>
