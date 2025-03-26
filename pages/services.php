<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Online Web Service Platform</title>
        <link rel="stylesheet" href="../css/base-style.css">

        <style>
            .services-page .container {
                width: 90%;
                padding: 20px;
                text-align: center;
                margin: 0 auto;
            } 
            /* Updated Styling of Services */
            .services-page .services-container {
                display: grid;
                grid-template-columns: auto auto auto;
                column-gap: 20px;
                padding: 20px;
                text-align: center;
                margin: 0 auto;
            } 

            .services-page .title {
                font-size: 2.5rem;
                font-weight: 600;
                color: #333;
                margin-bottom: 20px;
            }

            .services-page p {
                font-size: 1rem;
                color: #777;
            }

            .services-page .service {
                background-color: white;
                margin: 20px 0;
                padding: 20px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                border-radius: 8px;
            }

            .services-page .service h2 {
                font-size: 1.5rem;
                color: #7FA06D;
                margin-bottom: 10px;
                width: 100%;
                max-width: 100%;
            }
        </style>
    </head>
    <body>
        <?php include("header.php"); ?>
        <div class="services-page">
            <div class="container">
                <h1 class="title">Our Services</h1>
                <p>Don't want to leave the house? No problem! We offer a variety of services to meet your needs. Now you can get your grocery shopping needs in one place, all from the convenience of a web browser. From browsing and shopping, to payments and delivery, we'll handle it all. </p>
                <div class="services-container">
                    <div class="service">
                        <h2>Order Management</h2>
                        <p>Manage and track your orders efficiently with features like placing, updating, and confirming your orders. Stay informed at every stage of your order's journey.</p>
                    </div>

                    <div class="service">
                        <h2>Inventory Management</h2>
                        <p>Efficiently track your products and manage stock levels. Our platform ensures that you always have accurate and real-time information about your inventory.</p>
                    </div>

                    <div class="service">
                        <h2>Delivery to Your Destination</h2>
                        <p>We offer convenient delivery services from your selected branch to your preferred destination, ensuring that your items reach you on time and in perfect condition.</p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
