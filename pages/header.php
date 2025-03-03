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

                // submits the dragged item's ID in the invisible form
                $("#droppedItemID").val(data);
                $("#updateCart").submit();
            }
    </script>

    <header id="top-navbar">
        <div class="logo">
            <a href="index.php">
                <img src="../img/logo.png" alt="Logo" class="logo-img">
                <p>Name</p>
            </a>
        </div>

        <form class="search-container" action="search.php" method="GET">
            <input type="text" name="query" placeholder="Search by User-Id, Order-Id">
            <button type="submit">Go</button>
        </form>

        <div class="login">
            <a href="cart.php" class="cart-icon" id="cartIcon">
                <div id="cart" class="cart-dropzone" ondrop="drop(event)" ondragover="allowDrop(event)">
                    <img src="../img/shopping-cart.png" alt="Shopping Cart">
                </div>
            </a>
    
            <?php if (isset($_SESSION['account_type'])) { ?>
                <!-- If the user is logged in, show the Logout button -->
                <a href="logout.php" class="signin-btn">Logout</a>
            <?php } else { ?>
                <!-- If the user is not logged in, show the Sign In button -->
                <a href="signin.php" class="signin-btn">Sign In</a>
            <?php } ?>
        </div>
    </header>

    <div id="bottom-navbar">
        <ul class="navlink">
            <li><a href="index.php">Home</a></li>
            <li><a href="services.php">Types of Services</a></li>
            <li><a href="reviews.php">Reviews</a></li>
            <li><a href="about.php">About Us</a></li>
            <li> 
            <?php if (isset($_SESSION['account_type']) && $_SESSION['account_type'] == 0) { ?>
                <div class="dbmaintain">
                    <a href="">DB Maintain</a>
                    <div class="dbmaintain-options">
                        <a href="db_insert.php">Insert</a>
                        <a href="db_delete.php">Delete</a>
                        <a href="db_select.php">Select</a>
                        <a href="db_update.php">Update</a>
                    </div>
                </div>
            <?php } ?>
            </li>
        </ul>
    </div>

    <!-- 
    Invsible form to trigger php script that handles items dropped into the cart
    -->
    <form action="cart.php" method="POST" id="updateCart" style="display:none;">
        <input type="text" name="droppedItemID" id="droppedItemID">
        <input type="submit">
    </form>

    <!-- continuation in other php files -->
