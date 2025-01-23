<?php
session_start();
include('db_connection.php');

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

            // Validate image type
            if (in_array($image_ext, $allowed_ext) && $image_size <= 5000000) {
                $new_image_name = uniqid('gear_', true) . '.' . $image_ext;
                $upload_dir = '../uploads/';
                $upload_file = $upload_dir . $new_image_name;

                // Move the uploaded file to the server's directory
                if (move_uploaded_file($image_tmp, $upload_file)) {
                    // Insert the new gear into the database
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
        }
    }

    // Edit gear
    if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
        $gear_id = $_GET['id'];
        $stmt = $pdo->prepare("SELECT * FROM gear WHERE id = :id");
        $stmt->bindParam(':id', $gear_id);
        $stmt->execute();
        $gear_to_edit = $stmt->fetch(PDO::FETCH_ASSOC);

        // Handle form submission for editing
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $category = $_POST['category'];
            $price = $_POST['price'];
            $availability = isset($_POST['availability']) ? 1 : 0;

            // Handle image upload for editing
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $image_name = $_FILES['image']['name'];
                $image_tmp = $_FILES['image']['tmp_name'];
                $image_size = $_FILES['image']['size'];
                $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

                // Allowed image types
                $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

                // Validate image type
                if (in_array($image_ext, $allowed_ext) && $image_size <= 5000000) {
                    $new_image_name = uniqid('gear_', true) . '.' . $image_ext;
                    $upload_dir = '../uploads/';
                    $upload_file = $upload_dir . $new_image_name;

                    // Move the uploaded file to the server's directory
                    if (move_uploaded_file($image_tmp, $upload_file)) {
                        // Update the gear details along with the new image in the database
                        $stmt = $pdo->prepare("UPDATE gear SET name = :name, description = :description, category = :category, 
                                               price = :price, availability = :availability, image = :image WHERE id = :id");
                        $stmt->bindParam(':name', $name);
                        $stmt->bindParam(':description', $description);
                        $stmt->bindParam(':category', $category);
                        $stmt->bindParam(':price', $price);
                        $stmt->bindParam(':availability', $availability);
                        $stmt->bindParam(':image', $new_image_name);
                        $stmt->bindParam(':id', $gear_id);
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
                // If no new image is uploaded, update the rest of the fields
                $stmt = $pdo->prepare("UPDATE gear SET name = :name, description = :description, category = :category, 
                                       price = :price, availability = :availability WHERE id = :id");
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
        }
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

// Fetch unique categories for the filter
$category_stmt = $pdo->prepare("SELECT DISTINCT category FROM gear");
$category_stmt->execute();
$categories = $category_stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Outdoor Gear Hub</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General body styling */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background: linear-gradient(120deg, #a8d5ba, #66a6ff);
            color: #333;
        }

        header {
            background: #114b5f;
            color: white;
            padding: 1rem;
            text-align: center;
        }

        header h1 {
            margin: 0;
            font-size: 2.5rem;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
            gap: 1rem;
        }

        nav ul li a {
            text-decoration: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: background 0.3s;
        }

        nav ul li a:hover {
            background: #1a936f;
        }

        main {
            padding: 2rem;
        }

        section {
            margin-bottom: 2rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            border-radius: 15px;
            overflow: hidden;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 0.5rem;
            text-align: left;
        }

        table th {
            background: #379683;
            color: white;
        }

        table img {
            border-radius: 10px;
        }

        .button {
            background: #45a29e;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }

        .button:hover {
            background: #3d5a80;
            transform: scale(1.05);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: #edf6f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.3s ease-out;
        }

        .modal-close {
            background-color: #ef476f;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        footer {
            background: #073b4c;
            color: white;
            text-align: center;
            padding: 1rem;
            position: relative;
            bottom: 0;
            width: 100%;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* New Animations for Buttons */
        button {
            transition: all 0.3s ease;
        }

        button:hover {
            background-color: #228B22;
            color: #fff;
            transform: scale(1.1);
        }
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
        <!-- Add Search and Filter -->
        <section>
            <h2>Search and Filter</h2>
            <input type="text" id="searchBar" placeholder="Search by name..." onkeyup="filterGear()">
            <select id="categoryFilter" onchange="filterGear()">
                <option value="">All Categories</option>
                <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
                <?php endforeach; ?>
            </select>
        </section>

        <!-- Add New Gear Form -->
        <section>
            <h2>Add New Gear</h2>
            <button onclick="openAddModal()">Add New Gear</button>
        </section>

        <!-- Manage Gear Section -->
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
                <tbody id="gearTableBody">
                    <?php foreach ($gear_items as $gear): ?>
                    <tr>
                        <td><?php echo $gear['name']; ?></td>
                        <td><?php echo $gear['description']; ?></td>
                        <td><?php echo $gear['category']; ?></td>
                        <td><?php echo $gear['price']; ?></td>
                        <td><?php echo $gear['availability'] ? 'Available' : 'Not Available'; ?></td>
                        <td><img src="../uploads/<?php echo $gear['image']; ?>" alt="Gear Image" width="100"></td>
                        <td>
                            <button class="button" onclick="openEditModal(<?php echo $gear['id']; ?>)">Edit</button>
                            <a href="admin_dashboard.php?action=delete&id=<?php echo $gear['id']; ?>" class="button" onclick="return confirm('Are you sure you want to delete this gear?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

    <!-- Add Gear Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeModal()">X</span>
            <h2>Add New Gear</h2>
            <form action="admin_dashboard.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add">
                <label for="name">Gear Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>

                <label for="category">Category:</label>
                <input type="text" id="category" name="category" required>

                <label for="price">Price:</label>
                <input type="number" id="price" name="price" required>

                <label for="availability">Availability:</label>
                <input type="checkbox" id="availability" name="availability">

                <label for="image">Image:</label>
                <input type="file" id="image" name="image" accept="image/*" required>

                <button type="submit">Add Gear</button>
            </form>
        </div>
    </div>

    <!-- Edit Gear Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeModal()">X</span>
            <h2>Edit Gear</h2>
            <form id="editGearForm" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" id="gearId" name="id">
                <label for="name">Gear Name:</label>
                <input type="text" id="editName" name="name" required>

                <label for="description">Description:</label>
                <textarea id="editDescription" name="description" required></textarea>

                <label for="category">Category:</label>
                <input type="text" id="editCategory" name="category" required>

                <label for="price">Price:</label>
                <input type="number" id="editPrice" name="price" required>

                <label for="availability">Availability:</label>
                <input type="checkbox" id="editAvailability" name="availability">

                <label for="image">Image:</label>
                <input type="file" id="editImage" name="image" accept="image/*">

                <button type="submit">Update Gear</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 OutdoorGearHub</p>
    </footer>

    <script>
        function openAddModal() {
            document.getElementById('addModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('addModal').style.display = 'none';
            document.getElementById('editModal').style.display = 'none';
        }

        function openEditModal(id) {
            // Fetch the gear data from the server
            var form = document.getElementById("editGearForm");
            form.action = "admin_dashboard.php?action=edit&id=" + id;

            <?php foreach ($gear_items as $gear): ?>
                if (id == <?php echo $gear['id']; ?>) {
                    document.getElementById('gearId').value = <?php echo $gear['id']; ?>;
                    document.getElementById('editName').value = '<?php echo $gear['name']; ?>';
                    document.getElementById('editDescription').value = '<?php echo $gear['description']; ?>';
                    document.getElementById('editCategory').value = '<?php echo $gear['category']; ?>';
                    document.getElementById('editPrice').value = '<?php echo $gear['price']; ?>';
                    document.getElementById('editAvailability').checked = <?php echo $gear['availability'] ? 'true' : 'false'; ?>;
                }
            <?php endforeach; ?>
            document.getElementById('editModal').style.display = 'block';
        }
    </script>
</body>
</html>
