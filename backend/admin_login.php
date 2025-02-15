<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require 'db_connection.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Fetch admin from the database using PDO
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ? AND password = ?");
        $stmt->execute([$username, $password]);
        $admin = $stmt->fetch();

        if ($admin) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;
            header("Location: admin_dashboard.php"); 
            exit();
        } else {
            $error = "Invalid Username or Password";
        }
    } catch (PDOException $e) {
        $error = "Database error: " . htmlspecialchars($e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        }

        body {
        font-family: Arial, sans-serif;
        background-color: #f0f4f8;
        display: flex;
        height: 100vh;
        align-items: center;
        justify-content: center;
        padding: 20px;
        flex-direction: column;
        }

        .container {
        text-align: center;
        margin-bottom: 50px;
        }

        h2 {
        font-size: 28px;
        color: #333;
        font-weight: bold;
        margin-bottom: 20px;
        }

        form {
        background-color: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        width: 320px;
        transition: transform 0.3s ease-in-out;
        }

        form:hover {
        transform: translateY(-5px);
        }

        label {
        font-size: 14px;
        color: #555;
        font-weight: 600;
        margin-bottom: 5px;
        display: block;
        }

        input {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 6px;
        transition: border-color 0.3s ease-in-out;
        }

        input:focus {
        outline: none;
        border-color: #4a90e2;
        box-shadow: 0 0 5px rgba(74, 144, 226, 0.3);
        }

        button {
        width: 100%;
        padding: 10px;
        background-color: #4a90e2;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease-in-out;
        }

        button:hover {
        background-color: #357ab7;
        }

        p {
        text-align: center;
        color: #e74c3c;
        font-size: 14px;
        margin-bottom: 15px;
        }
</style>

</head>
<body>
    <h2>Admin Login</h2>
    
    <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>

    <form method="POST">
        <label>Username:</label>
        <input type="text" name="username" required>
        <br>
        
        <label>Password:</label>
        <input type="password" name="password" required>
        <br>
        
        <button type="submit">Login</button>
    </form>
</body>
</html>
