<?php
session_start();
include('db_connection.php'); // Ensure this sets up $pdo properly

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'];

        if ($action == "add") {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $category = $_POST['category'];
            $price = $_POST['price'];
            $availability = isset($_POST['availability']) ? 1 : 0;
            $new_image_name = null;

            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $image_name = $_FILES['image']['name'];
                $image_tmp = $_FILES['image']['tmp_name'];
                $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

                $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array($image_ext, $allowed_ext)) {
                    $new_image_name = uniqid('gear_', true) . '.' . $image_ext;
                    $upload_dir = '../uploads/';
                    move_uploaded_file($image_tmp, $upload_dir . $new_image_name);
                } else {
                    echo json_encode(["success" => false, "message" => "Invalid image type."]);
                    exit();
                }
            } else {
                echo json_encode(["success" => false, "message" => "Image upload failed."]);
                exit();
            }

            // Insert into database
            $stmt = $pdo->prepare("INSERT INTO gear (name, description, category, price, availability, image) 
                                   VALUES (:name, :description, :category, :price, :availability, :image)");
            $stmt->execute([
                ':name' => $name,
                ':description' => $description,
                ':category' => $category,
                ':price' => $price,
                ':availability' => $availability,
                ':image' => $new_image_name
            ]);

            echo json_encode(["success" => true, "message" => "Gear added successfully!"]);
        }

        elseif ($action == "edit") {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $category = $_POST['category'];
            $price = $_POST['price'];
            $availability = isset($_POST['availability']) ? 1 : 0;
            $new_image_name = null;

            // Check if an image is uploaded
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $image_name = $_FILES['image']['name'];
                $image_tmp = $_FILES['image']['tmp_name'];
                $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

                $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array($image_ext, $allowed_ext)) {
                    $new_image_name = uniqid('gear_', true) . '.' . $image_ext;
                    $upload_dir = '../uploads/';
                    move_uploaded_file($image_tmp, $upload_dir . $new_image_name);

                    // Update record with new image
                    $stmt = $pdo->prepare("UPDATE gear SET name = :name, description = :description, category = :category, 
                                           price = :price, availability = :availability, image = :image WHERE id = :id");
                    $stmt->execute([
                        ':name' => $name,
                        ':description' => $description,
                        ':category' => $category,
                        ':price' => $price,
                        ':availability' => $availability,
                        ':image' => $new_image_name,
                        ':id' => $id
                    ]);
                } else {
                    echo json_encode(["success" => false, "message" => "Invalid image type."]);
                    exit();
                }
            } else {
                // Update record without changing the image
                $stmt = $pdo->prepare("UPDATE gear SET name = :name, description = :description, category = :category, 
                                       price = :price, availability = :availability WHERE id = :id");
                $stmt->execute([
                    ':name' => $name,
                    ':description' => $description,
                    ':category' => $category,
                    ':price' => $price,
                    ':availability' => $availability,
                    ':id' => $id
                ]);
            }

            echo json_encode(["success" => true, "message" => "Gear updated successfully!"]);
        }

        elseif ($action == "delete") {
            $id = $_POST['id'];

            // Get current image filename before deletion
            $stmt = $pdo->prepare("SELECT image FROM gear WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $gear = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($gear && $gear['image']) {
                $image_path = '../uploads/' . $gear['image'];
                if (file_exists($image_path)) {
                    unlink($image_path); // Delete image from server
                }
            }

            // Delete record
            $stmt = $pdo->prepare("DELETE FROM gear WHERE id = :id");
            $stmt->execute([':id' => $id]);

            echo json_encode(["success" => true, "message" => "Gear deleted successfully!"]);
        }

        else {
            echo json_encode(["success" => false, "message" => "Invalid action."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Invalid request method."]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>
