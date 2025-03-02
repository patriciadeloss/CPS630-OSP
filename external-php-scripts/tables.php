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
        balance DECIMAL(10,2) DEFAULT 0
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
        source_code VARCHAR(30) NOT NULL,
        dest_code VARCHAR(30) NOT NULL,
        distance DECIMAL(10,2),
        truck_id INT(6) UNSIGNED,
        price DECIMAL(10,2),
        FOREIGN KEY (truck_id) REFERENCES Truck(truck_id) ON DELETE CASCADE
    )";
    $conn->query($sql);

    $sql = "CREATE TABLE IF NOT EXISTS Shopping (
        receipt_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        store_code VARCHAR(30) NOT NULL,
        total_price DECIMAL(10,2) NOT NULL
    )";
    $conn->query($sql);

    $sql = "CREATE TABLE IF NOT EXISTS ShoppingCart (
        order_id INT(6) UNSIGNED,
        item_id INT(6) UNSIGNED NOT NULL,
        user_id INT(6) UNSIGNED,
        quantity INT NOT NULL,
        price DECIMAL(10, 2),
        FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
        FOREIGN KEY (item_id) REFERENCES Item(item_id) ON DELETE CASCADE,
        FOREIGN KEY (order_id) REFERENCES Orders(order_id) ON DELETE CASCADE,
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
        FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
        FOREIGN KEY (trip_id) REFERENCES Trips(trip_id) ON DELETE CASCADE, 
        FOREIGN KEY (receipt_id) REFERENCES Shopping(receipt_id) ON DELETE CASCADE
    )";
    $conn->query($sql);

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

    // temp user for now
    /* $sql = "INSERT INTO Users (user_name, tel_no, email, address, city_code, login_id, password, balance) VALUES
        ('gensanchi','416-123-4444','gvsanchi@gmail.com','100 Brimley Rd S','ABC','ABCDEFG','y33-h@w',400)";
    $conn->query($sql); */

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

?>
