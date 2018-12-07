<?php

    $con = mysqli_connect("127.0.0.1", "root", "", "login_db");

    function row_count($result){
        
        return mysqli_num_rows($result);
    }
    function escape($string){
        global $con;
        return mysqli_real_escape_string($con,$string);
    }
    function query($query){
        //  Grabing the connection to data base and global because it's inside a function
        global $con;
        return mysqli_query($con, $query);
    }
    function fetch_data($result){
        global $con;
        return mysqli_fetch_array($result);
    }
    function confirm($result){
        global $con;
        if (!$result){
            die("Query failed!!" . mysqli_error($con));
        }
    }




?>