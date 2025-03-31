<html lang="en">
<head>
    <link rel="stylesheet" href="../css/base-style.css">
    <style>
        body {
            padding: 0;
            margin: 0;
            font-size: 15px;
        }
    </style>
</head>
<body>
    <?php
        include("../external-php-scripts/database.php");
        include("../external-php-scripts/security.php");

        // Handle form submission accordingly
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $name = $_POST['name'];
            $email = $_POST['email'];
            $tel_no = $_POST['phone_number'];
            $login_id = $_POST['username'];
            $password = $_POST['password'];
            $user_role = isset($_POST['account_type']) ? (int) $_POST['account_type'] : 1;  // 0 = Admin, 1 = User, Default = 1

            $validateUser = validateUser($login_id,$password);

            if ($validateUser === true) { 
                echo '<p style="color: red; text-align: center;">You already have an account. Please <a href="../osp_spa/osp_spa.php#!/signin" target="_top">sign in</a>.</p>';
            }
            else if ($validateUser === false) {
                insertUser($name, $email, $tel_no, $login_id, $password, $user_role);
                echo '<p style="color: green; text-align: center;">You have created an account. Please <a href="../osp_spa/osp_spa.php#!/signin" target="_top">sign in</a>.</p>';
                //echo "<script>window.top.location.href = \"../osp_spa/osp_spa.php#!/signup\";</script>";
                //header("Location: ../osp_spa/osp_spa.php#!/signup"); // redirects to sign up page
                exit();
            }
        }
    ?>
</body>
</html>


