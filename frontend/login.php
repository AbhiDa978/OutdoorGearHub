<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include('../backend/db_connection.php');

$loginError = ""; // Initialize error variable
$registerMessage = ""; // Message for successful registration

// Handle Registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $username = $_POST['register_username'];
    $name = $_POST['register_name']; // Combine first name and last name into a single field
    $email = $_POST['register_email'];
    $password = $_POST['register_password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    if ($name && $email && $password) {
        // Insert into database
        $query = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, 'user')";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $hashedPassword
        ]);

        if ($stmt) {
            $registerMessage = "Registration successful! Please login.";
            header("Location: login.php"); // Redirect to login page after successful registration
            exit();
        } else {
            $registerMessage = "Error: " . $pdo->errorInfo()[2];
        }
    } else {
        $registerMessage = "All fields are required!";
    }
}

// Handle Login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $usernameOrEmail = $_POST['login_username'];
    $password = $_POST['login_password'];

    $query = "SELECT * FROM users WHERE name = :username OR email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':username' => $usernameOrEmail, ':email' => $usernameOrEmail]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            header("Location: index.php");
            exit();
        } else {
            $loginError = "Incorrect password!";
        }
    } else {
        $loginError = "No user found with that username/email.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #eaeaea;
        }
        .container {
            width: 800px;
            display: flex;
            background: white;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.5s ease-in-out;
        }
        .left-panel, .right-panel {
            flex: 1;
            padding: 40px;
            text-align: center;
        }
        .left-panel {
            background: #4a4a4a;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .right-panel {
            background: #ffffff;
            transition: opacity 0.5s ease-in-out;
        }
        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #4a4a4a;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }
        button:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        button:hover {
            background: #2f2f2f;
        }
        p {
            margin-top: 10px;
            font-size: 14px;
        }
        a {
            color: #007bff;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        .hidden {
            opacity: 0;
            pointer-events: none;
            position: absolute;
        }
        .error-message {
            color: red;
            font-size: 14px;
            margin: 10px 0;
            text-align: center;
        }
        .success-message {
            color: green;
            font-size: 14px;
            margin: 10px 0;
            text-align: center;
        }
        .red-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: red;
            position: absolute;
            top: 0;
            right: -5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <h2 id="left-header">Welcome Back!</h2>
            <p id="left-text">To stay connected with us, please login with your personal info</p>
            <button id="toggle-btn" onclick="toggleForms()">Sign In</button>
        </div>
        <div class="right-panel">
            <!-- Login Form -->
            <div id="login-container">
                <h2>Login</h2>
                <?php if ($loginError): ?>
                    <p class="error-message"><?php echo $loginError; ?></p>
                <?php endif; ?>
                <form method="POST">
                    <input type="text" name="login_username" placeholder="Username or Email" required>
                    <input type="password" name="login_password" placeholder="Password" required>
                    <button type="submit" name="login">Login</button>
                </form>
            </div>

            <!-- Registration Form -->
            <div id="register-container" class="hidden">
                <h2>Register</h2>
                <?php if ($registerMessage): ?>
                     <script>alert("<?php echo $registerMessage; ?>");</script>
                <?php endif; ?>

                <form method="POST">
                    <input type="text" name="register_username" placeholder="Username" required>
                    <input type="text" name="register_name" placeholder="Full Name" required>
                    <input type="email" name="register_email" placeholder="Email Address" required>
                    <input type="password" name="register_password" placeholder="Password" required>
                    <button type="submit" name="register">Register</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Toggle forms between Login and Register
        function toggleForms() {
            const loginContainer = document.getElementById('login-container');
            const registerContainer = document.getElementById('register-container');
            const leftHeader = document.getElementById('left-header');
            const leftText = document.getElementById('left-text');
            const toggleBtn = document.getElementById('toggle-btn');
            
            loginContainer.classList.toggle('hidden');
            registerContainer.classList.toggle('hidden');
            
            if (loginContainer.classList.contains('hidden')) {
                leftHeader.innerText = "Hello, Friend!";
                leftText.innerText = "Enter your details and start your journey with us";
                toggleBtn.innerText = "Sign Up";
            } else {
                leftHeader.innerText = "Welcome Back!";
                leftText.innerText = "To stay connected with us, please login with your personal info";
                toggleBtn.innerText = "Sign In";
            }
        }
    </script>
</body>
</html>
