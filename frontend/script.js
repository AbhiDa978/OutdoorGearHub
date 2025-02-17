// Sidebar toggle
const menuIcon = document.getElementById('menu-icon');
const sidebar = document.getElementById('sidebar');

menuIcon.addEventListener('click', () => {
    sidebar.style.right = sidebar.style.right === '0px' ? '-250px' : '0px';
});

// Utility functions for cookies
const setCookie = (name, value, days) => {
    const expires = new Date();
    expires.setDate(expires.getDate() + days);
    document.cookie = `${name}=${encodeURIComponent(value)}; expires=${expires.toUTCString()}; path=/`;
};

const getCookie = (name) => {
    const cookies = document.cookie.split('; ');
    for (const cookie of cookies) {
        const [key, value] = cookie.split('=');
        if (key === name) {
            return decodeURIComponent(value);
        }
    }
    return null;
};

const deleteCookie = (name) => {
    document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
};

// Cart update logic
const addToCartButtons = document.querySelectorAll('.add-to-cart');
const cartBadge = document.getElementById('cart-badge');
let cartCount = getCookie('cartCount') ? parseInt(getCookie('cartCount')) : 0;

const updateCartBadge = () => {
    if (cartCount > 0) {
        cartBadge.style.display = 'inline-block';
        cartBadge.innerText = cartCount;
    } else {
        cartBadge.style.display = 'none';
    }
};
updateCartBadge();

addToCartButtons.forEach(button => {
    button.addEventListener('click', function () {
        const productCard = this.closest('.product-card');
        const productId = productCard.getAttribute('data-id');
        const productName = productCard.getAttribute('data-name');
        const productPrice = productCard.getAttribute('data-price');

        let cart = JSON.parse(getCookie('cart')) || [];
        cart.push({ id: productId, name: productName, price: productPrice });

        setCookie('cart', JSON.stringify(cart), 7);
        localStorage.setItem('cart', JSON.stringify(cart));

        cartCount++;
        setCookie('cartCount', cartCount, 7);
        localStorage.setItem('cartCount', cartCount);

        updateCartBadge();
        alert(`${productName} has been added to your cart!`);
    });
});

// Profile hover effect
const profileIcon = document.getElementById('profile-icon');
const userIdDiv = document.getElementById('user-id');

if (profileIcon && userIdDiv) {
    profileIcon.addEventListener('mouseover', () => {
        userIdDiv.style.display = 'block';
    });

    profileIcon.addEventListener('mouseout', () => {
        userIdDiv.style.display = 'none';
    });
}

// Header scroll behavior
window.addEventListener('scroll', function () {
    const header = document.querySelector('header');
    if (window.scrollY > 50) {
        header.classList.add('shrink');
    } else {
        header.classList.remove('shrink');
    }
});

// Sidebar scroll behavior correction
window.addEventListener('scroll', function () {
    const sidebar = document.getElementById('sidebar');
    if (window.scrollY < 50) {
        sidebar.classList.add('larger');
    } else {
        sidebar.classList.remove('larger');
    }
});

// Cart hover functionality
const cartIcon = document.getElementById('cart-icon');
const cartDropdown = document.createElement('div');
cartDropdown.id = 'cart-dropdown';
cartDropdown.style.display = 'none';
cartDropdown.style.position = 'absolute';
cartDropdown.style.top = '60px'; 
cartDropdown.style.right = '0px';
cartDropdown.style.backgroundColor = '#fff';
cartDropdown.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.2)';
cartDropdown.style.padding = '10px';
cartDropdown.style.borderRadius = '8px';
cartDropdown.style.width = '300px';
cartDropdown.style.zIndex = '1000';
document.body.appendChild(cartDropdown);

const renderCartItems = () => {
    const cart = JSON.parse(getCookie('cart')) || [];
    cartDropdown.innerHTML = '';

    if (cart.length === 0) {
        cartDropdown.innerHTML = '<p>Your cart is empty.</p>';
    } else {
        cart.forEach(item => {
            const cartItem = document.createElement('div');
            cartItem.style.display = 'flex';
            cartItem.style.justifyContent = 'space-between';
            cartItem.style.marginBottom = '8px';
            cartItem.innerHTML = `<span>${item.name}</span><span>$${item.price}</span>`;
            cartDropdown.appendChild(cartItem);
        });

        // Add Clear Cart and Rent Now buttons
        const actionButtons = document.createElement('div');
        actionButtons.style.display = 'flex';
        actionButtons.style.justifyContent = 'space-between';
        actionButtons.style.marginTop = '10px';

        const clearCartButton = document.createElement('button');
        clearCartButton.innerText = 'Clear Cart';
        clearCartButton.style.padding = '5px 10px';
        clearCartButton.style.backgroundColor = '#e74c3c';
        clearCartButton.style.color = '#fff';
        clearCartButton.style.border = 'none';
        clearCartButton.style.borderRadius = '5px';
        clearCartButton.style.cursor = 'pointer';

        clearCartButton.addEventListener('click', () => {
            deleteCookie('cart');
            deleteCookie('cartCount');
            localStorage.removeItem('cart');
            localStorage.removeItem('cartCount');
            cartCount = 0;
            renderCartItems();
            updateCartBadge();
        });

        const rentNowButton = document.createElement('button');
        rentNowButton.innerText = 'Rent Now';
        rentNowButton.style.padding = '5px 10px';
        rentNowButton.style.backgroundColor = '#27ae60';
        rentNowButton.style.color = '#fff';
        rentNowButton.style.border = 'none';
        rentNowButton.style.borderRadius = '5px';
        rentNowButton.style.cursor = 'pointer';

        rentNowButton.addEventListener('click', () => {
            window.location.href = 'cartpage.php'; 
        });

        actionButtons.appendChild(clearCartButton);
        actionButtons.appendChild(rentNowButton);
        cartDropdown.appendChild(actionButtons);
    }
};

cartIcon.addEventListener('mouseover', () => {
    renderCartItems();
    cartDropdown.style.display = 'block';
});

cartIcon.addEventListener('mouseout', () => {
    cartDropdown.style.display = 'none';
});

cartDropdown.addEventListener('mouseover', () => {
    cartDropdown.style.display = 'block';
});

cartDropdown.addEventListener('mouseout', () => {
    cartDropdown.style.display = 'none';
});
