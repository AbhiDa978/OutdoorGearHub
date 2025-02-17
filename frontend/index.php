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
    
</head>
<body>
<?php include('header.php'); ?>
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


    <?php include('footer.php'); ?>
    <script src="script.js"></script>

</body>
</html>
