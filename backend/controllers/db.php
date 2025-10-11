<?php

require __DIR__ . '/../../config/config.php';

function dbConnect(){
    $mysql = new mysqli(SERVER,USERNAME,PASSWORD,DATABASE);
    if($mysql->connect_errno !=0){
        die("Failed to connect: " . $mysql->connect_error);
    }
    else{
        return $mysql;
    }
}

function fetchFeaturedProducts($int){
    $mysql=dbConnect();
    $featuredProducts=$mysql->query("SELECT * FROM products ORDER BY rand() LIMIT $int");
    $data=[];
    while($row=$featuredProducts->fetch_assoc()){
        $data[] = $row;
    }
    return $data;
}

function fetchProducts(){
    $mysql=dbConnect();
    $featuredProducts=$mysql->query("SELECT * FROM products");
    $data=[];
    while($row=$featuredProducts->fetch_assoc()){
        $data[] = $row;
    }
    return $data;
}

function fetchSingleProduct($id){
    $mysql=dbConnect();
    $singleProduct=$mysql->query("SELECT * FROM products WHERE product_id=$id");
    return $singleProduct->fetch_assoc();
}

function fetchAccessories(){
    $mysql=dbConnect();
    $accessories=$mysql->query("SELECT * FROM products WHERE category='Accessories'");
    $data=[];
    while($row=$accessories->fetch_assoc()){
        $data[] = $row;
    }
    return $data;
}
function fetchWellness(){
    $mysql=dbConnect();
    $wellness=$mysql->query("SELECT * FROM products WHERE category='Wellness'");
    $data=[];
    while($row=$wellness->fetch_assoc()){
        $data[] = $row;
    }
    return $data;
}
function fetchDecors(){
    $mysql=dbConnect();
    $decors=$mysql->query("SELECT * FROM products WHERE category='Decor'");
    $data=[];
    while($row=$decors->fetch_assoc()){
        $data[] = $row;
    }
    return $data;
}
function fetchErgonomics(){
    $mysql=dbConnect();
    $ergonomics=$mysql->query("SELECT * FROM products WHERE category='Ergonomics'");
    $data=[];
    while($row=$ergonomics->fetch_assoc()){
        $data[] = $row;
    }
    return $data;
}

function fetchUsers(){
    $mysql=dbConnect();
    $users=$mysql->query("SELECT * FROM users");
    $data=[];
    while($row=$users->fetch_assoc()){
        $data[] = $row;
    }
    return $data;
}

function fetchUser($id){
    $conn = dbConnect();
    $stmt = $conn->prepare("SELECT id, first_name, last_name, email, password, address, city, postcode, country, landmark, phone, profile_pic FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    return $user;
}

