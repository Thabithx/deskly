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
