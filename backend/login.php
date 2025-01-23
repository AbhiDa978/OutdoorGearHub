<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if user exists
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $password == $user['password']) { // Direct comparison
        // Store user session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];

        echo "Login successful! Welcome, " . $user['name'];
        // Redirect to a different page (for example, dashboard)
        header('Location: ../frontend/admin_dashboard.html');
        exit();
    } else {
        echo "Invalid credentials!";
    }
}
?>
