<?php

$users = "CREATE TABLE IF NOT EXISTS users (
    username VARCHAR(20) PRIMARY KEY,
    pass VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    tel_number VARCHAR(12) NOT NULL,
    usr_type INTEGER NOT NULL,
    CONSTRAINT verify_type CHECK (usr_type IN (1, 2))
);";


?>