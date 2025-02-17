<?php
session_start();
include('../backend/db_connection.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms and Policies</title>
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

/* Terms and Policies Section */
.terms-section {
    padding: 60px 0;
    background-color: #ecf0f1;
    text-align: center;
}

.terms-section h1 {
    font-size: 2.5rem;
    margin-bottom: 20px;
}

.terms-section p {
    font-size: 1rem;
    max-width: 800px;
    margin: 0 auto 30px;
    line-height: 1.8;
}

/* Content Box */
.content-box {
    background-color: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    max-width: 800px;
    margin: 30px auto;
    line-height: 1.6;
}

.content-box h2 {
    font-size: 2rem;
    margin-bottom: 15px;
}

.content-box p {
    margin-bottom: 15px;
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

/* Responsive Design */
@media screen and (max-width: 768px) {
    .content-box h2 {
        font-size: 1.8rem;
    }

    .content-box p {
        font-size: 1rem;
    }
}

@media screen and (max-width: 480px) {
    .content-box p {
        font-size: 0.9rem;
    }

    .terms-section h1 {
        font-size: 2rem;
    }
}

    </style>

</head>
<body>

<?php include('header.php'); ?>

<main>
    <section class="terms-section">
        <div class="container">
            <h1>Terms and Policies</h1>
            <p>Welcome to Outdoor Gear Hub! By using our platform, you agree to the following terms and policies. Please read them carefully.</p>
        </div>
    </section>

    <section class="content-box">
        <div class="container">
            <h2>1. Terms of Service</h2>
            <p>These Terms of Service govern your use of Outdoor Gear Hub's services. By using our website, you agree to comply with these terms. If you do not agree with these terms, please do not use our platform.</p>
            <p>Our platform connects customers to retail stores offering outdoor gear. We act as an intermediary, and all transactions are made directly with the store, not with Outdoor Gear Hub.</p>

            <h2>2. Privacy Policy</h2>
            <p>We value your privacy and are committed to protecting your personal information. When you use our platform, we collect certain personal data to facilitate your transactions and improve your experience. We will never share or sell your data without your consent, except where required by law.</p>
            <p>Your information is securely stored and processed. We use industry-standard encryption to ensure that your data is safe and protected.</p>

            <h2>3. Refund Policy</h2>
            <p>Refunds are handled by the individual retail stores through which the purchases were made. Outdoor Gear Hub does not process refunds directly. If you have an issue with a product or service, please contact the store directly to discuss your concerns.</p>
            <p>All refund requests must be made within 14 days of purchase. Conditions for refunds are subject to each store's individual policy, so we encourage you to review their refund terms before making a purchase.</p>

            <h2>4. User Responsibilities</h2>
            <p>As a user of our platform, you are responsible for providing accurate and complete information when making bookings or contacting stores. You must not engage in fraudulent activities, attempt to interfere with the website's operations, or violate the rights of others using the platform.</p>

            <h2>5. Limitation of Liability</h2>
            <p>Outdoor Gear Hub is not liable for any damages, losses, or injuries caused by the use of outdoor gear purchased through our platform. All products are sold by the respective retail stores, and we cannot guarantee the quality or safety of these products.</p>
            <p>We are also not liable for any errors, omissions, or interruptions in our platform's service. We strive to maintain a seamless experience, but technical difficulties may occasionally occur.</p>

            <h2>6. Changes to Terms</h2>
            <p>We reserve the right to update or modify these terms at any time. If any significant changes are made, we will notify users via email or through a prominent notice on our platform. It is your responsibility to review these terms periodically to stay informed of any updates.</p>

            <h2>7. Governing Law</h2>
            <p>These Terms of Service are governed by the laws of Nepal. Any disputes arising from the use of our platform will be subject to the exclusive jurisdiction of the courts of Nepal.</p>
        </div>
    </section>

    <section class="address-section">
        <div class="container">
            <h2>Our Address</h2>
            <p>We are located in the heart of Kathmandu, Nepal. For any inquiries related to our terms or policies, please reach out to us at the following address:</p>
            <p class="address">
                Outdoor Gear Hub<br>
                123 Adventure Street,<br>
                Kathmandu, Nepal
            </p>
        </div>
    </section>
</main>

<?php include('footer.php'); ?>

</body>
</html>
