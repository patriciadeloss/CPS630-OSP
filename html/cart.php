<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Online Web Service Platform</title>
        <link rel="stylesheet" href="../css/base-style.css">
        <link rel="stylesheet" href="../css/cart-style.css">
    </head>

    <body>
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

        <div class="container">
            <h1>Your Shopping Cart</h1>
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Amount</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody class="cart-container">
                    <tr>
                        <td class="child-left">
                            <img src="../img/placeholder.jpg" alt="">
                            <div id="itemInfo">
                                <h3>Item name</h3>
                                <p>Item details</p>
                            </div>
                        </td>
                        <td id="price">$0.00</td>
                        <td id="amountSelector">
                            <button>-</button>
                            <p id="amount">1</p>
                            <button>+</button>
                        </td>
                        <td>$0.00</td>
                    </tr> 
                    <tr>
                        <td class="child-left">
                            <img src="../img/placeholder.jpg" alt="">
                            <div id="itemInfo">
                                <h3>Item name</h3>
                                <p>Item details</p>
                            </div>
                        </td>
                        <td id="price">$0.00</td>
                        <td id="amountSelector">
                            <button>-</button>
                            <p id="amount">1</p>
                            <button>+</button>
                        </td>
                        <td>$0.00</td>
                    </tr> 
                    <tr>
                        <td class="child-left">
                            <img src="../img/placeholder.jpg" alt="">
                            <div id="itemInfo">
                                <h3>Item name</h3>
                                <p>Item details</p>
                            </div>
                        </td>
                        <td id="price">$0.00</td>
                        <td id="amountSelector">
                            <button>-</button>
                            <p id="amount">1</p>
                            <button>+</button>
                        </td>
                        <td id="itemTotal">$0.00</td>
                    </tr> 
                </tbody>
            </table>
        </div>
        <footer class="overview">
            <div id="p1">
                <p>Number of items: <span id="numItems">3</span></p>
                <p>Discounts: $<span id="discount">0.00</span></p>
                <p>Tax: $<span id="tax">0.00</span></p>
            </div>
            <div id="p2">
                <h2>Grand Total: $<span id="grandTotal">0.00</span></h2>
                <button>Check Out</button>
            </div>
        </footer>
    </body>
</html>