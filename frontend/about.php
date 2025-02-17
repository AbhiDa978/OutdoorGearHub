<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Outdoor Gear Hub</title>
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

        /* About Section */
        .about-section {
            padding: 60px 0;
            background-color: #ecf0f1;
            text-align: center;
        }

        .about-section h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .about-section p {
            font-size: 1rem;
            max-width: 800px;
            margin: 0 auto 30px;
            line-height: 1.8;
        }

        /* Team Section */
        .team-section {
            padding: 60px 0;
            background-color: #fff;
            text-align: center;
        }

        .team-section h2 {
            font-size: 2rem;
            margin-bottom: 30px;
        }

        .team-members {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
        }

        .team-member {
            flex: 1 1 250px;
            background-color: #ecf0f1;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .team-member img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin-bottom: 15px;
        }

        .team-member h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .team-member p {
            font-size: 1rem;
        }

        /* Testimonial Section */
        .testimonial-section {
            padding: 60px 0;
            background-color: #ecf0f1;
            text-align: center;
        }

        .testimonial-section h2 {
            font-size: 2rem;
            margin-bottom: 30px;
        }

        .testimonials {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
        }

        .testimonial {
            flex: 1 1 300px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .testimonial p {
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .testimonial span {
            font-style: italic;
            color: #7f8c8d;
        }

        /* Call-to-Action Section */
        .cta-section {
            padding: 60px 0;
            background-color: #34495e;
            text-align: center;
            color: #fff;
        }

        .cta-section h2 {
            font-size: 2rem;
            margin-bottom: 15px;
        }

        .cta-section p {
            font-size: 1rem;
            margin-bottom: 25px;
        }

        .cta-button {
            background-color: #e74c3c;
            color: #fff;
            font-size: 1rem;
            padding: 10px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .cta-button:hover {
            background-color: #c0392b;
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            .team-members {
                flex-direction: column;
                align-items: center;
            }

            .testimonial-section {
                padding: 40px 0;
            }

            .cta-section h2 {
                font-size: 1.5rem;
            }

            .cta-button {
                padding: 12px 20px;
            }
        }

        @media screen and (max-width: 480px) {
            .about-section p,
            .team-member p,
            .testimonial p {
                font-size: 0.9rem;
            }

            .cta-section p {
                font-size: 0.9rem;
            }

            .team-member img {
                width: 100px;
                height: 100px;
            }

            .cta-button {
                padding: 8px 15px;
            }
        }
    </style>

</head>

<body>

    <?php include('header.php'); ?>

    <main>
        <!-- About Us Section -->
        <section class="about-section">
            <div class="container">
                <h1>About Us</h1>
                <p>We are a passionate team dedicated to providing outdoor enthusiasts with top-notch gear. Our mission is to connect you with the best products and services to enhance your adventures, whether it's camping, hiking, climbing, or anything in between.</p>

                <h2>Our Story</h2>
                <p>Outdoor Gear Hub started with the vision to bridge the gap between customers and outdoor gear retailers. Our founders, a group of adventure lovers, saw the potential in creating a seamless online platform to explore and rent high-quality outdoor gear.</p>

                <h2>Our Mission</h2>
                <p>Our mission is to bring the best outdoor gear to everyone, whether you're a beginner or a seasoned adventurer. We aim to provide an easy-to-use platform where you can find the perfect gear for your next adventure.</p>

                <h2>Why Choose Us?</h2>
                <p>With years of experience in the outdoor industry, our team has a deep understanding of what makes the perfect gear for any adventure. We handpick only the best products and work with trusted suppliers to ensure quality. Our platform allows you to easily browse, rent, or purchase gear for all your outdoor needs.</p>
            </div>
        </section>

        <!-- Team Section -->
        <section class="team-section">
            <div class="container">
                <h2>Meet The Team</h2>
                <div class="team-members">
                    <div class="team-member">
                        <img src="path_to_image.jpg" alt="CEO John Doe">
                        <h3>John Doe</h3>
                        <p>CEO - Leading the way with a passion for outdoor adventures. John ensures our vision stays strong and our team is always motivated.</p>
                    </div>
                    <div class="team-member">
                        <img src="path_to_image.jpg" alt="CTO Jane Smith">
                        <h3>Jane Smith</h3>
                        <p>CTO - The tech mastermind behind our platform, Jane makes sure the site runs smoothly and incorporates the latest technologies.</p>
                    </div>
                    <div class="team-member">
                        <img src="path_to_image.jpg" alt="CMO Emily Johnson">
                        <h3>Emily Johnson</h3>
                        <p>CMO - Connecting with customers and driving our brand forward, Emily leads marketing campaigns and ensures our brand stands out.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="testimonial-section">
            <div class="container">
                <h2>What Our Customers Say</h2>
                <div class="testimonials">
                    <div class="testimonial">
                        <p>"Outdoor Gear Hub provided me with the best camping gear I’ve ever used. The service was amazing, and the gear was top-notch!"</p>
                        <span>- Alex Turner, Frequent Camper</span>
                    </div>
                    <div class="testimonial">
                        <p>"I found everything I needed for my hiking trip in one place. The gear was delivered quickly, and the quality was exceptional."</p>
                        <span>- Sarah Lee, Hiking Enthusiast</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Call-to-Action Section -->
        <section class="cta-section">
            <div class="container">
                <h2>Ready to Gear Up?</h2>
                <p>Browse our wide selection of outdoor gear and get ready for your next adventure.</p>
                <a href="gear.php" class="cta-button">Browse Gear</a>
            </div>
        </section>
    </main>

    <?php include('footer.php'); ?>

    <script>
        // Fade-in effect for content
        window.addEventListener('load', function () {
            const sections = document.querySelectorAll('.about-section, .team-section, .testimonial-section, .cta-section');
            sections.forEach(section => {
                section.style.opacity = 0;
                section.style.transition = 'opacity 1s ease-in-out';
            });

            let fadeInObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = 1;
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });

            sections.forEach(section => fadeInObserver.observe(section));
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>

</html>
