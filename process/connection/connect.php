<?php 
    $con = mysqli_connect("localhost", "root", "", "quicktalk");
    mysqli_query($con, "SET time_zone='+00:00'");

    date_default_timezone_set("UTC");

    if (mysqli_connect_errno()) {
        echo "Falha ao ligar o banco de dados: " . mysqli_connect_error();
        exit();
    }
?>