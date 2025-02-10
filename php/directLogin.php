<?php

session_start();

        $_SESSION["user"] = "3858";          
        $_SESSION["name"] = "david";
        $_SESSION["section"] = "12";
        $_SESSION["sectionName"] = "";
        $_SESSION["division"] = "6"; 
        $_SESSION["password"] = "3858";     
        $_SESSION["Authorized"] = "Yes";
        $_SESSION["role"] = "admin";

        echo "/views/home.php";
?>