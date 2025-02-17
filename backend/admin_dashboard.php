<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

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
    <link rel="stylesheet" href="admin_styles.css">
</head>
<body>
<header>
    <h1>Admin Dashboard</h1>
    <nav>
        <ul>
            <li><a href="admin_logout.php">Logout</a></li>
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
        <form id="add-gear-form" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add">
            <label for="name">Gear Name</label>
            <input type="text" name="name" required>
            <label for="description">Description</label>
            <textarea name="description" required></textarea>
            <label for="category">Category</label>
            <input type="text" name="category" required>
            <label for="price">Price</label>
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
        <form id="edit-gear-form" enctype="multipart/form-data">
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Add Gear
        $("#add-gear-form").submit(function (e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: "ajax_handler.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "json", // Expect JSON response
                success: function (response) {
                    if (response.success) {
                        alert("Gear added successfully!");
                        $(".modal").hide(); // Close modal after success
                        location.reload();
                    } else {
                        alert("Error: " + response.message);
                    }
                },
                error: function () {
                    alert("Error adding gear.");
                }
            });
        });

        // Edit Gear
        $("#edit-gear-form").submit(function (e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: "ajax_handler.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "json", // Expect JSON response
                success: function (response) {
                    if (response.success) {
                        alert("Gear updated successfully!");
                        $(".modal").hide(); // Close modal after success
                        location.reload();
                    } else {
                        alert("Error: " + response.message);
                    }
                },
                error: function () {
                    alert("Error updating gear.");
                }
            });
        });

        // Delete Gear (Using Delegated Event Binding)
        $(document).on("click", ".delete-gear", function () {
            if (confirm("Are you sure you want to delete this gear?")) {
                var gearId = $(this).data("id");

                $.ajax({
                    url: "ajax_handler.php",
                    type: "POST",
                    data: { action: "delete", id: gearId },
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            alert("Gear deleted successfully!");
                            location.reload();
                        } else {
                            alert("Error: " + response.message);
                        }
                    },
                    error: function () {
                        alert("Error deleting gear.");
                    }
                });
            }
        });

        // Open Edit Modal with Data
        $(".edit-gear").click(function () {
            var gear = $(this).data("gear");

            $("#edit-id").val(gear.id);
            $("#edit-name").val(gear.name);
            $("#edit-description").val(gear.description);
            $("#edit-category").val(gear.category);
            $("#edit-price").val(gear.price);
            $("#edit-availability").prop("checked", Boolean(Number(gear.availability))); // Fix checkbox issue
            $("#edit-gear-modal").show();
        });

        // Close Modals
        $(".modal-close").click(function () {
            $(".modal").hide();
        });
    });
    function openAddModal() {
    $("#add-gear-modal").show();
}

function closeAddModal() {
    $("#add-gear-modal").hide();
}

function openEditModal(gear) {
    $("#edit-id").val(gear.id);
    $("#edit-name").val(gear.name);
    $("#edit-description").val(gear.description);
    $("#edit-category").val(gear.category);
    $("#edit-price").val(gear.price);
    $("#edit-availability").prop("checked", Boolean(Number(gear.availability)));
    $("#edit-gear-modal").show();
}

function closeEditModal() {
    $("#edit-gear-modal").hide();
}

</script>

</body>
</html>