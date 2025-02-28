    <script>
        function allowDrop(ev) {
                ev.preventDefault(); // Enables dropping
            }

            function drag(ev) {
                ev.dataTransfer.setData("text", ev.target.id); // Stores the dragged item's ID
            }

            function drop(ev) {
                ev.preventDefault();
                var data = ev.dataTransfer.getData("text"); // Retrieves the dragged item's ID
                var draggedElement = document.getElementById(data); // Gets the dragged element
                alert("Added to cart: " + draggedElement.querySelector("h3").innerText);

                // new code !! -Gen
                // submits the dragged item's ID in the invisible form
                $("#itemID").val(data);
                $("#updateCart").submit();
            }
    </script>

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
            <a href="cart.php" class="cart-icon" ondragover="allowDrop(event)" ondrop="drop(event)">
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

    <!-- 
    new code !! -Gen
    Invsible form. It doesn't matter where this is located - as long as it's
    present
    -->
    <form action="../external-php-scripts/updateCart.php" method="POST" id="updateCart" style="display:none;">
        <input type="text" name="itemID" id="itemID">
        <input type="submit">
    </form>

    <!-- continuation in other php files -->
