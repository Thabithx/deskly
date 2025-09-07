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
