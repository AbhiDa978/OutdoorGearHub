<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - Outdoor Gear Hub</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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

    <script>
        const cartItemsContainer = document.getElementById('cart-items-container');
        const cartSummary = document.getElementById('cart-summary');
        const emptyCartMessage = document.getElementById('empty-cart-message');
        const totalPriceElement = document.getElementById('total-price');
        const cartBadge = document.getElementById('cart-badge');
        const checkoutButton = document.getElementById('checkout-button');
        const clearCartButton = document.getElementById('clear-cart-button');

        const updateCartDisplay = () => {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            cartItemsContainer.innerHTML = '';

            if (cart.length === 0) {
                cartSummary.style.display = 'none';
                emptyCartMessage.style.display = 'block';
                cartBadge.style.display = 'none';
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
            cartBadge.style.display = 'inline-block';
            cartBadge.innerText = cart.length;
        };

        // Remove item from cart
        cartItemsContainer.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-item')) {
                const itemId = e.target.getAttribute('data-id');
                let cart = JSON.parse(localStorage.getItem('cart')) || [];
                cart = cart.filter(item => item.id !== itemId);
                localStorage.setItem('cart', JSON.stringify(cart));
                updateCartDisplay();
            }
        });

        // Clear cart
        clearCartButton.addEventListener('click', () => {
            localStorage.removeItem('cart');
            updateCartDisplay();
        });

        // Checkout with popup
        checkoutButton.addEventListener('click', () => {
            const popup = document.createElement('div');
            popup.id = 'checkout-popup';
            popup.innerHTML = `
                <div class="popup-content">
                    <h2>Confirm Payment</h2>
                    <p>Are you ready to proceed with your payment?</p>
                    <button id="pay-now-button">Pay Now</button>
                    <button id="close-popup-button">Cancel</button>
                </div>
            `;
            document.body.appendChild(popup);

            document.getElementById('pay-now-button').addEventListener('click', () => {
                alert('Booking confirmed! Thank you for your payment.');
                popup.remove();
                localStorage.removeItem('cart');
                updateCartDisplay();
            });

            document.getElementById('close-popup-button').addEventListener('click', () => {
                popup.remove();
            });
        });

        updateCartDisplay();
    </script>
</body>
</html>
