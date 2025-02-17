<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - Outdoor Gear Hub</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Overlay Styles */
        #thankyou-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .overlay-content {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            max-width: 400px;
            margin: 0 auto;
        }

        .overlay-content h2 {
            margin-bottom: 20px;
        }

        .overlay-content button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .overlay-content button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<?php include('header.php'); ?>
    <main id="cartpage">
        <h1>Your Cart</h1>
        <div id="cart-items-container">
            <!-- Cart items will be dynamically populated here -->
        </div>
        <div id="cart-summary" style="display: none;">
            <h3>Total: $<span id="total-price">0.00</span></h3>
            <button id="checkout-button">Checkout</button>
            <button id="clear-cart-button">Clear Cart</button>
        </div>
        <p id="empty-cart-message" style="display: none;">Your cart is empty. <a href="index.php">Shop now!</a></p>
    </main>
    
    <?php include('footer.php'); ?>

    <div id="thankyou-overlay">
        <div class="overlay-content">
            <h2>Thank you for your booking!</h2>
            <p>You will be contacted shortly for delivery details. Our team is processing your order, and you will receive an update soon.</p>
            <button id="close-overlay-button">Close</button>
        </div>
    </div>

    <script>
        const cartItemsContainer = document.getElementById('cart-items-container');
        const cartSummary = document.getElementById('cart-summary');
        const emptyCartMessage = document.getElementById('empty-cart-message');
        const totalPriceElement = document.getElementById('total-price');
        const checkoutButton = document.getElementById('checkout-button');
        const clearCartButton = document.getElementById('clear-cart-button');
        const thankyouOverlay = document.getElementById('thankyou-overlay');
        const closeOverlayButton = document.getElementById('close-overlay-button');

        // Function to update the cart display
        const updateCartDisplay = () => {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            cartItemsContainer.innerHTML = '';  // Clear the container before rendering

            if (cart.length === 0) {
                cartSummary.style.display = 'none';
                emptyCartMessage.style.display = 'block';
                return;
            }

            let total = 0;
            cart.forEach(item => {
                total += parseFloat(item.price);

                const cartItem = document.createElement('div');
                cartItem.classList.add('cart-item');
                cartItem.innerHTML = `
                    <div class="item-details">
                        <h2>${item.name}</h2>
                        <p>Price: $${item.price}</p>
                    </div>
                    <button class="remove-item" data-id="${item.id}">Remove</button>
                `;
                cartItemsContainer.appendChild(cartItem);
            });

            totalPriceElement.textContent = total.toFixed(2);
            cartSummary.style.display = 'block';
            emptyCartMessage.style.display = 'none';
        };

        // Event listener to remove an item from the cart
        cartItemsContainer.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-item')) {
                const itemId = e.target.getAttribute('data-id');
                let cart = JSON.parse(localStorage.getItem('cart')) || [];
                cart = cart.filter(item => item.id !== itemId); // Remove item by ID
                localStorage.setItem('cart', JSON.stringify(cart));
                updateCartDisplay();
            }
        });

        // Event listener to clear the cart
        clearCartButton.addEventListener('click', () => {
            localStorage.removeItem('cart');
            updateCartDisplay(); // Refresh the cart display
        });

        // Checkout button and popup logic
        checkoutButton.addEventListener('click', () => {
            // Show thank you overlay
            thankyouOverlay.style.display = 'flex';
            localStorage.removeItem('cart'); // Clear cart after checkout
            updateCartDisplay(); // Update cart display to empty
        });

        // Close the overlay
        closeOverlayButton.addEventListener('click', () => {
            thankyouOverlay.style.display = 'none';
        });

        // Initial cart display when the page loads
        updateCartDisplay();
    </script>
</body>
</html>
