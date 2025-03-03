<?php 
include("header.php"); 
$orderid = $_POST['orderid'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Web Service Platform</title>
    <link rel="stylesheet" href="../css/base-style.css">
    <style>
        .container {
            width: 90vw;
            height: auto;
            margin: 2vh 5vw;
            padding: 0 10vw;

            text-align: center;
        }
        .container h1 {
            margin-top: 10vh;
            color: #B3C995;
            font-size: 5rem;
            -webkit-text-stroke: 1px #7FA06D;
            overflow-wrap: break-word;  
        }
        .container p {
            margin-top: 10px;
            font-size: 1.2rem;
        }
        .container button {
            width: auto;
            height: 60px;
            padding: 5px 10px;
            margin-top: 25px;

            border: 2px solid #E7B76F;
            border-radius: 5px;
            background-color: #FFF1CB;

            font-family: 'Poppins', Arial, Helvetica, sans-serif;
            text-align: center;
            font-size: 1.2rem;
            
            transition: 0.3s ease-in-out;
        }

        .container button:hover {
            background-color: #C5E99B;
            border: 2px solid #7FA06D;
            height: 63px;
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>Thank you!</h1>
        <p>Your order [id #<?php echo $orderid; ?>] has been confirmed.</p>
        <a href="map.php"><button>See Delivery Details</button></a>
    </div>
</body>
</html>