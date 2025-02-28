<header id="top-navbar">
    <div class="logo">
        <img src="../img/logo.png" alt="Logo" class="logo-img">
        Name
    </div>

    <form class="search-container" action="search.php" method="GET">
        <input type="text" name="query" placeholder="Search">
        <button type="submit">Go</button>
    </form>

    <div class="login">
        <a href="cart.php" class="cart-icon">
            <img src="../img/shopping-cart.png" alt="Cart">
        </a>
        <a href="signin.php" class="signin-btn">Sign In</a>
    </div>
</header>

<div id="bottom-navbar">
    <ul class="navlink">
        <li><a href="index.php">Home</a></li>
        <li><a href="services.php">Types of Services</a></li>
        <li><a href="reviews.php">Reviews</a></li>
        <li><a href="about.php">About Us</a></li>
    </ul>
</div>

<script>
    function allowDrop(ev) {
        ev.preventDefault(); // Enables dropping
    }

    function drop(ev) {
        ev.preventDefault();
        var data = ev.dataTransfer.getData("text"); // Retrieves the dragged item's ID
        var draggedElement = document.getElementById(data); // Gets the dragged element
        alert("Added to cart: " + draggedElement.querySelector("h3").innerText);
    }

</script>

<!-- continuation in other php files -->
