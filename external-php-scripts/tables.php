<?php 

include("database.php");

try {
    // Create table
    $sql = "CREATE TABLE IF NOT EXISTS Item (
        item_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        item_name VARCHAR(30) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        made_in VARCHAR(50), 
        department_code VARCHAR(10),
        image_url VARCHAR(100)
    )";
    $conn->query($sql);

    $sql = "CREATE TABLE IF NOT EXISTS Users (
        user_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        account_type INT(1) NOT NULL DEFAULT 1,
        name VARCHAR(30) NOT NULL,
        tel_no VARCHAR(12) NOT NULL,
        email VARCHAR(254),
        address VARCHAR(100),
        city_code CHAR(3),
        login_id VARCHAR(30) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        salt VARCHAR(64) NOT NULL,
        balance DECIMAL(10,2) DEFAULT 0
    )";
    $conn->query($sql);

    $sql = "CREATE TABLE IF NOT EXISTS Branch (
        store_code VARCHAR(30) PRIMARY KEY,
        branch_address VARCHAR(60) NOT NULL
    )";
    $conn->query($sql);

    $sql = "CREATE TABLE IF NOT EXISTS Truck (
        truck_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        truck_code VARCHAR(30) NOT NULL,
        avail_code VARCHAR(30) NOT NULL
    )";
    $conn->query($sql);

    $sql = "CREATE TABLE IF NOT EXISTS Trips (
        trip_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        source_code VARCHAR(50) NOT NULL,
        dest_code VARCHAR(50) NOT NULL,
        distance DECIMAL(10,2),
        truck_id INT(6) UNSIGNED,
        price DECIMAL(10,2),
        FOREIGN KEY (truck_id) REFERENCES Truck(truck_id) ON DELETE CASCADE
    )";
    $conn->query($sql);

    $sql = "CREATE TABLE IF NOT EXISTS Shopping (
        receipt_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        store_code VARCHAR(30) NOT NULL,
        total_price DECIMAL(10,2) NOT NULL,
        FOREIGN KEY (store_code) REFERENCES Branch(store_code) ON DELETE CASCADE
    )";
    $conn->query($sql);

    $sql = "CREATE TABLE IF NOT EXISTS Orders (
        order_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        date_issued DATE NOT NULL,
        date_received DATE NOT NULL,
        total_price DECIMAL(10, 2),
        payment_code VARCHAR(10),
        user_id INT(6) UNSIGNED,
        trip_id INT(6) UNSIGNED,
        receipt_id INT(6) UNSIGNED,
        payment_info VARCHAR(20) NOT NULL,
        payment_salt VARCHAR(64),
        FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
        FOREIGN KEY (trip_id) REFERENCES Trips(trip_id) ON DELETE CASCADE, 
        FOREIGN KEY (receipt_id) REFERENCES Shopping(receipt_id) ON DELETE CASCADE
    )";
    $conn->query($sql);

    $sql = "CREATE TABLE IF NOT EXISTS ShoppingCart (
        item_id INT(6) UNSIGNED NOT NULL,
        user_id INT(6) UNSIGNED,
        quantity INT NOT NULL,
        price DECIMAL(10, 2),
        FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
        FOREIGN KEY (item_id) REFERENCES Item(item_id) ON DELETE CASCADE
    )";
    $conn->query($sql);

    //Added Reviews Table
    $sql = "CREATE TABLE IF NOT EXISTS Reviews (
        review_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT(6) UNSIGNED, 
        item_id INT(6) UNSIGNED NOT NULL,
        rating INT CHECK (rating>=1 AND rating<=5), 
        review_text VARCHAR(60) NOT NULL,
        time_stamp DATE NOT NULL,
        FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
        FOREIGN KEY (item_id) REFERENCES Item(item_id) ON DELETE CASCADE
    )";

} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage() . "<br>";
}

// Inserting Products
try {
    $sql = "INSERT INTO Item (item_name, price, made_in, department_code, image_url) VALUES
        ('Chapmans Ice Cream', 7.28, 'Canada', 'FRU01', 'ice-cream.webp'),
        ('Great Value Chicken Nuggets', 9.77, 'Canada', 'FRU02', 'chicken-nuggets.jpg'),
        ('Sealtest Partly Skimmed Milk', 6.25, 'Canada', 'DAI01', 'sealtest-milk.webp'),
        ('Philadelphia Cream Cheese', 4.58, 'UK', 'DAI02' ,'cheese.png')";
    
    $conn->query($sql);


    /*
    $sql = "INSERT INTO Shopping (store_code, total_price) VALUES
        ('STR001', 'Available'),
        ('STR002', 'Available'),
        ('STR003', 'Unavailable')";
    $conn->query($sql);

    $sql = "INSERT INTO Truck (truck_code, avail_code) VALUES
        ('TRK001', 'Available'),
        ('TRK002', 'Available'),
        ('TRK003', 'Unavailable')";
    $conn->query($sql);

    $sql = "INSERT INTO Trips (source_code, dest_code, distance, truck_id, price) VALUES
    ('Toronto', 'Montreal', 540.00, 1, 120.50),
    ('Vancouver', 'Calgary', 970.00, 2, 250.75),
    ('Montreal', 'Calgary', 970.00, 3, 250.75)";
    $conn->query($sql);


    $sql = "INSERT INTO Orders (date_issued, date_received, total_price, payment_code, user_id, trip_id, receipt_id) VALUES 
    ('2024-02-20', '2024-02-22', 150.75, 'PAY123', 1, 1, 1),
    ('2024-02-21', '2024-02-23', 200.50, 'PAY456', 2, 2, 2),
    ('2024-02-22', '2024-02-24', 99.99, 'PAY789', 3, 3, 3)";
    $conn->query($sql);

    */


    // temp user for now
    /* $sql = "INSERT INTO Users (user_name, tel_no, email, address, city_code, login_id, password, balance) VALUES
        ('gensanchi','416-123-4444','gvsanchi@gmail.com','100 Brimley Rd S','ABC','ABCDEFG','y33-h@w',400)";
    $conn->query($sql); */

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

//inserting Branch info
try {
    $sql = "INSERT INTO Branch VALUES 
    ('STR001', '300 Borough Dr, Scarborough, ON'),
    ('STR002', '900 Dufferin St, Toronto, ON'),
    ('STR003', '100 City Centre Dr, Mississauga, ON')";

    $conn->query($sql);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


?>
