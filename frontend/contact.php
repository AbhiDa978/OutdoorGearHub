<?php
session_start();
include('../backend/db_connection.php'); 

error_reporting(E_ALL);
ini_set('display_errors', 1);

$error_message = '';
$success_message = '';

try {
    // Establish database connection using PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get form data
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $message = htmlspecialchars($_POST['message']);
        
        // Validate input
        if (empty($name) || empty($email) || empty($message)) {
            $error_message = "All fields are required.";
        } else {
            // Prepare and execute the INSERT statement
            $stmt = $pdo->prepare("INSERT INTO contact_form (name, email, message) VALUES (:name, :email, :message)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':message', $message);
            
            // Execute the query
            $stmt->execute();
            
            // Success message
            $success_message = "Thank you for contacting us! We will get back to you soon.";
        }
    }
} catch (PDOException $e) {
    // Handle any errors
    $error_message = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">

    <style>
    /* Reset and Global Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Arial', sans-serif;
    background-color: #f9f9f9;
    color: #333;
    line-height: 1.6;
    overflow-x: hidden;
}

h1, h2, h3 {
    font-weight: 600;
    color: #2c3e50;
}

/* Container */
.container {
    width: 90%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 30px 0;
}

/* Main Section Styles */
main {
    padding: 40px 0;
    background-color: #fff;
}

/* Contact Section */
.contact-section {
    padding: 60px 0;
    background-color: #ecf0f1;
    text-align: center;
}

.contact-section h1 {
    font-size: 2.5rem;
    margin-bottom: 20px;
}

.contact-section p {
    font-size: 1rem;
    max-width: 800px;
    margin: 0 auto 30px;
    line-height: 1.8;
}

/* Contact Form */
.contact-form {
    background-color: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    max-width: 700px;
    margin: 0 auto;
}

.contact-form label {
    font-weight: bold;
    display: block;
    margin-bottom: 8px;
}

.contact-form input, .contact-form textarea {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.contact-form button {
    background-color: #e74c3c;
    color: #fff;
    font-size: 1rem;
    padding: 12px 25px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
}

.contact-form button:hover {
    background-color: #c0392b;
}

/* Address Section */
.address-section {
    padding: 60px 0;
    background-color: #fff;
    text-align: center;
}

.address-section h2 {
    font-size: 2rem;
    margin-bottom: 20px;
}

.address-section p {
    font-size: 1rem;
    max-width: 800px;
    margin: 0 auto;
    line-height: 1.8;
}

.address-section .address {
    font-style: italic;
    color: #7f8c8d;
}

/* Overlay Popup */
.overlay-popup {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.popup-message {
    background-color: #fff;
    padding: 20px 30px;
    border-radius: 8px;
    text-align: center;
    width: 300px;
}

.popup-message p {
    font-size: 1rem;
    margin-bottom: 20px;
}

.popup-message button {
    background-color: #e74c3c;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.popup-message button:hover {
    background-color: #c0392b;
}

/* Responsive Design */
@media screen and (max-width: 768px) {
    .contact-form button {
        padding: 12px 20px;
    }

    .address-section h2 {
        font-size: 1.5rem;
    }
}

@media screen and (max-width: 480px) {
    .contact-form label,
    .contact-form input,
    .contact-form textarea {
        font-size: 0.9rem;
    }

    .contact-section h1 {
        font-size: 2rem;
    }
}

    </style>

</head>

<body>

<?php include('header.php'); ?>

<main>
    <section class="contact-section">
        <div class="container">
            <h1>Contact Us</h1>
            <p>If you have any questions or need support, feel free to reach out to us. Our team is ready to assist you.</p>

            <!-- Error message section -->
            <?php if ($error_message): ?>
                <div class="error-message" style="color: red; font-weight: bold;"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form action="contact.php" method="POST" class="contact-form">
                <label for="name">Your Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required>

                <label for="email">Your Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>

                <label for="message">Your Message</label>
                <textarea id="message" name="message" rows="5" placeholder="Enter your message" required></textarea>

                <button type="submit">Send Message</button>
            </form>
        </div>
    </section>

    <section class="address-section">
        <div class="container">
            <h2>Our Address</h2>
            <p>We are located in the heart of Kathmandu, Nepal. Visit us at the following address for any in-person queries or concerns:</p>
            <p class="address">
                Outdoor Gear Hub<br>
                123 Adventure Street,<br>
                Kathmandu, Nepal
            </p>
        </div>
    </section>
</main>

<?php include('footer.php'); ?>

<!-- Success Message Overlay -->
<?php if ($success_message): ?>
    <div class="overlay-popup" id="successPopup">
        <div class="popup-message">
            <p><?php echo $success_message; ?></p>
            <button onclick="closePopup()">Close</button>
        </div>
    </div>
<?php endif; ?>

<script>
// Function to close the success popup
function closePopup() {
    document.getElementById('successPopup').style.display = 'none';
}

// Show popup on page load if success message is set
<?php if ($success_message): ?>
    document.getElementById('successPopup').style.display = 'flex';
<?php endif; ?>
</script>

</body>
</html>
