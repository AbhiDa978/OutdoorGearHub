<?php
session_start();
include('db_connection.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);


// Handle add, edit, and delete actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['action'])) {
    // Add gear
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $availability = isset($_POST['availability']) ? 1 : 0;

        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image_name = $_FILES['image']['name'];
            $image_tmp = $_FILES['image']['tmp_name'];
            $image_size = $_FILES['image']['size'];
            $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

            // Allowed image types
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($image_ext, $allowed_ext) && $image_size <= 5000000) {
                $new_image_name = uniqid('gear_', true) . '.' . $image_ext;
                $upload_dir = '../uploads/';
                $upload_file = $upload_dir . $new_image_name;

                if (move_uploaded_file($image_tmp, $upload_file)) {
                    $stmt = $pdo->prepare("INSERT INTO gear (name, description, category, price, availability, image) 
                                           VALUES (:name, :description, :category, :price, :availability, :image)");
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':description', $description);
                    $stmt->bindParam(':category', $category);
                    $stmt->bindParam(':price', $price);
                    $stmt->bindParam(':availability', $availability);
                    $stmt->bindParam(':image', $new_image_name);
                    $stmt->execute();
                    header("Location: admin_dashboard.php");
                    exit();
                } else {
                    echo "Failed to upload image.";
                }
            } else {
                echo "Invalid image or file size too large.";
            }
        } else {
            echo "No image uploaded or there was an error.";
        }
    }

    // Edit gear
    if (isset($_POST['action']) && $_POST['action'] == 'edit') {
        $gear_id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $availability = isset($_POST['availability']) ? 1 : 0;

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image_name = $_FILES['image']['name'];
            $image_tmp = $_FILES['image']['tmp_name'];
            $image_size = $_FILES['image']['size'];
            $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($image_ext, $allowed_ext) && $image_size <= 5000000) {
                $new_image_name = uniqid('gear_', true) . '.' . $image_ext;
                $upload_dir = '../uploads/';
                $upload_file = $upload_dir . $new_image_name;

                if (move_uploaded_file($image_tmp, $upload_file)) {
                    $stmt = $pdo->prepare("UPDATE gear SET name = :name, description = :description, category = :category, 
                                           price = :price, availability = :availability, image = :image WHERE id = :id");
                    $stmt->bindParam(':image', $new_image_name);
                } else {
                    echo "Failed to upload image.";
                }
            } else {
                echo "Invalid image or file size too large.";
            }
        } else {
            $stmt = $pdo->prepare("UPDATE gear SET name = :name, description = :description, category = :category, 
                                   price = :price, availability = :availability WHERE id = :id");
        }

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':availability', $availability);
        $stmt->bindParam(':id', $gear_id);
        $stmt->execute();
        header("Location: admin_dashboard.php");
        exit();
    }

    // Delete gear
    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
        $gear_id = $_GET['id'];
        $stmt = $pdo->prepare("DELETE FROM gear WHERE id = :id");
        $stmt->bindParam(':id', $gear_id);
        $stmt->execute();
        header("Location: admin_dashboard.php");
        exit();
    }
}

// Fetch all gear from the database
$stmt = $pdo->prepare("SELECT * FROM gear");
$stmt->execute();
$gear_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Outdoor Gear Hub</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        <style>
/* General Styles */
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
    margin: 0;
    padding: 0;
    color: #333;
    line-height: 1.6;
    overflow-x: hidden;
}

/* Header */
header {
    background: linear-gradient(to right, #4CAF50, #81c784);
    color: white;
    padding: 20px;
    text-align: center;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    position: sticky;
    top: 0;
    z-index: 10;
}

header h1 {
    margin: 0;
    font-size: 2.5rem;
    letter-spacing: 2px;
}

header nav ul {
    list-style: none;
    margin: 10px 0 0;
    padding: 0;
    display: flex;
    justify-content: center;
    gap: 20px;
}

header nav ul li a {
    color: white;
    text-decoration: none;
    font-weight: bold;
    padding: 10px 20px;
    border-radius: 30px;
    background: rgba(255, 255, 255, 0.2);
    transition: background 0.3s, transform 0.2s;
}

header nav ul li a:hover {
    background: rgba(255, 255, 255, 0.4);
    transform: scale(1.1);
}

/* Main Section */
main {
    padding: 20px;
}

main section {
    margin: 30px auto;
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
}

main section:hover {
    transform: translateY(-5px);
}

main section h2 {
    margin-top: 0;
    color: #4CAF50;
    text-align: center;
    font-size: 1.8rem;
    border-bottom: 2px solid #81c784;
    display: inline-block;
    padding-bottom: 5px;
}

/* Table */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table th,
table td {
    text-align: left;
    padding: 12px;
    border-bottom: 1px solid #ddd;
}

table th {
    background: #4CAF50;
    color: white;
    text-transform: uppercase;
    font-size: 0.9rem;
}

table tr:nth-child(even) {
    background: #f9f9f9;
}

table tr:hover {
    background: #f1f1f1;
}

table img {
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

/* Buttons */
button,
a.button {
    display: inline-block;
    padding: 10px 20px;
    margin: 10px 5px 0;
    font-size: 1rem;
    color: white;
    background: #4CAF50;
    border: none;
    border-radius: 30px;
    cursor: pointer;
    transition: background 0.3s, transform 0.2s;
    text-decoration: none;
    text-align: center;
}

button:hover,
a.button:hover {
    background: #45a049;
    transform: scale(1.05);
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    animation: fadeIn 0.3s ease;
}

.modal-content {
    background: #ffffff;
    margin: 5% auto;
    padding: 20px 30px;
    border-radius: 15px;
    width: 80%;
    max-width: 600px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    animation: slideDown 0.4s ease;
}

.modal-close {
    background: #e74c3c;
    color: white;
    border: none;
    border-radius: 30px;
    padding: 5px 15px;
    cursor: pointer;
    font-size: 1rem;
    float: right;
    transition: background 0.3s ease;
}

.modal-close:hover {
    background: #c0392b;
}

label {
    font-weight: bold;
    color: #555;
    margin-top: 10px;
}

input[type="text"],
input[type="number"],
textarea,
input[type="file"] {
    width: 100%;
    padding: 10px;
    margin: 10px 0 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
    font-size: 1rem;
    transition: border 0.3s ease;
}

input:focus,
textarea:focus {
    border-color: #4CAF50;
    outline: none;
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideDown {
    from {
        transform: translateY(-20%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>

    </style>
</head>
<body>
<header>
    <h1>Admin Dashboard</h1>
    <nav>
        <ul>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<main>
    <section>
        <h2>Add New Gear</h2>
        <button onclick="openAddModal()">Add New Gear</button>
    </section>

    <section>
        <h2>Manage Gear</h2>
        <table id="gear-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Category</th>
                <th>Price</th>
                <th>Availability</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($gear_items as $gear): ?>
                <tr>
                    <td><?php echo $gear['name']; ?></td>
                    <td><?php echo $gear['description']; ?></td>
                    <td><?php echo $gear['category']; ?></td>
                    <td><?php echo $gear['price']; ?></td>
                    <td><?php echo $gear['availability'] ? 'Available' : 'Not Available'; ?></td>
                    <td><img src="../uploads/<?php echo $gear['image']; ?>" alt="Gear Image" width="100"></td>
                    <td>
                        <button class="button" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($gear)); ?>)">Edit</button>
                        <a href="admin_dashboard.php?action=delete&id=<?php echo $gear['id']; ?>" class="button" onclick="return confirm('Are you sure you want to delete this gear?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>

<!-- Add Gear Modal -->
<div id="add-gear-modal" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeAddModal()">&times;</span>
        <h2>Add Gear</h2>
        <form action="admin_dashboard.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add">
            <label for="name">Gear Name</label>
            <input type="text" name="name" required>
            <label for="description">Description</label>
            <textarea name="description" required></textarea>
            <label for="category">Category</label>
            <input type="text" name="category" required>
            <label for="price">Price</label>
            <label for="price">Price:</label>
            <input type="number" name="price" id="price" step="0.01" min="0" max="999999.99" required>
            <label for="availability">Available</label>
            <input type="checkbox" name="availability">
            <label for="image">Image</label>
            <input type="file" name="image" required>
            <button type="submit">Add Gear</button>
        </form>
    </div>
</div>

<!-- Edit Gear Modal -->
<div id="edit-gear-modal" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeEditModal()">&times;</span>
        <h2>Edit Gear</h2>
        <form action="admin_dashboard.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit-id">
            <label for="edit-name">Gear Name</label>
            <input type="text" name="name" id="edit-name" required>
            <label for="edit-description">Description</label>
            <textarea name="description" id="edit-description" required></textarea>
            <label for="edit-category">Category</label>
            <input type="text" name="category" id="edit-category" required>
            <label for="edit-price">Price</label>
            <input type="number" name="price" id="edit-price" step="0.01" required>
            <label for="edit-availability">Available</label>
            <input type="checkbox" name="availability" id="edit-availability">
            <label for="edit-image">Image</label>
            <input type="file" name="image">
            <button type="submit">Save Changes</button>
        </form>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('add-gear-modal').style.display = 'block';
    }

    function closeAddModal() {
        document.getElementById('add-gear-modal').style.display = 'none';
    }

    function openEditModal(gear) {
        document.getElementById('edit-id').value = gear.id;
        document.getElementById('edit-name').value = gear.name;
        document.getElementById('edit-description').value = gear.description;
        document.getElementById('edit-category').value = gear.category;
        document.getElementById('edit-price').value = gear.price;
        document.getElementById('edit-availability').checked = gear.availability === 1;
        document.getElementById('edit-gear-modal').style.display = 'block';
    }

    function closeEditModal() {
        document.getElementById('edit-gear-modal').style.display = 'none';
    }
</script>
</body>
</html>