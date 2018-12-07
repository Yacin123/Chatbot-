<?php

function clean($string){
    return htmlentities($string);
}

function redirect($location){
    
    return header("Location: {$location}");
}

function set_message($message){
    if (!empty($message)){
        $_SESSION['message'] = $message;
    }else{
        $message = "";
    }
}

function display_message(){
    if (isset( $_SESSION['message'])){
        echo  $_SESSION['message'];
        unset  ($_SESSION['message']);
    }
}

function token_generator(){
    //  Creates a unique ID with a random prefix more secure than a static prefix  
    $token = $_SESSION['token'] =  md5(uniqid(mt_rand(), true));
    
    return $token;
}

function validation_errors($error_message){
    $error_message = '
    <div class="alert alert-danger alert-danger" role="alert">
         <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
         <strong>Warning!</strong> '.$error_message.'
    </div>';
    return $error_message;
}

//  Testing if the email already exists  in the database
function email_exists($email){
    $sql = "SELECT id FROM users WHERE email = '$email' ";
    $result = query($sql);
    if (row_count($result) == 1){
        return true;
    }else{
        return false;
    }
}

//  Testing is the username already exists in the database
function username_exists($username){
    $sql = "SELECT id FROM users WHERE username = '$username' ";
    $result = query($sql);
    if (row_count($result) == 1){
        return true;
    }else{
        
        return false;
    }
}

//  Validation functions
function validate_user_registration(){
    
    $errors = [];
    $min = 3;
    $max = 8;
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        $last_name  =     clean($_POST['last_name']);
        $first_name =     clean($_POST['first_name']);
        $email      =     clean($_POST['email']);
        $username   =     clean($_POST['username']);
        $password   =     clean($_POST['password']);
        
        if (empty($first_name)){
            $errors[] = "Your name can't be empty"; 
        }else if (strlen($first_name) < $min){
            $errors[] = "Your name's length can't be less then ".$min." characters.";
        }
        if (empty($email)){
            $errors[] = "Your email can't be empty";
        }else if (strlen($email) < $min){
            $errors[] = "Your email's length can't be less then ".$min." characters.";
        }else if (email_exists($email)){
            $errors[] = "This email already exists";
        }
        if (empty($username)){
            $errors[] = "Your username can't be empty";
        }else if (strlen($username) < $min){
            $errors[] = "Your username's length can't be less then ".$min." characters.";
        }else if (username_exists($username)){
            $errors[] = "This username already exists";
        }
        if (empty($password)){
            $errors[] = "Password can't be empty";
        }else if (strlen($password) < $max){
            $errors[] = "Password must be at least ".$max." characters";
        }
        //  Checking if there's errors             
        if (!empty($errors)){
            foreach($errors as $error){
                //  Display errors
                echo validation_errors($error);
            }
        }else if ( register_user($first_name, $last_name, $username ,$email, $password)){
            set_message("<p class='bg-success text-center' >welcome $first_name we are happy you joined us ,
            Please check your email for confirmation</p>");
            redirect("index.php");
        }
    }
}
//  Registration function   
function register_user($first_name, $last_name, $username ,$email, $password){
       
    //  escaping the data helps us prevent SQL injection
            $last_name      =     escape($_POST['last_name']);
            $first_name     =     escape($_POST['first_name']);
            $email          =     escape($_POST['email']);
            $username       =     escape($_POST['username']);
            $password       =     escape($_POST['password']);

        if(email_exists($email)){
            return false;
        }else if(username_exists($username)){
            return false;
        }else{
            //  Encrypting the password
            $password = md5($password);
            $validation_code = md5($username + microtime());
            $sql  = "INSERT INTO users(first_name, last_name ,username, password,active) VALUES('$first_name','$last_name','$username','$password',1) ";
            $result = query($sql);
            confirm($result);
            
            $subject = "Activate Acount";
            $msg     = " Please click the link below to activate your account   
                        http://localhost/login/activate.php?email=$email&code=$validation_code
            ";
            $headers = "From: noreplay@mywebsite.com";

            send_email( $email, $subject, $msg, $headers);

            return true;
        }
}

function send_email( $email, $subject, $msg, $headers){

    return mail($email, $subject, $msg, $headers);
}


function validate_user_login(){
    
    $errors = [];
    $min = 3;
    $max = 8;
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        $username = clean($_POST['username']);
        $password = clean($_POST['password']);

        if (empty($username)){
            $errors[] = "Username field can't be empty"; 
        }
        if (empty($password)){
            $errors[] = "Password filed can't be empty"; 
        }
        if (!empty($errors)){
            foreach($errors as $error){
                //  Display errors
                echo validation_errors($error);
            }
    }else{
            
            if ( login_user($username,$password)){
                redirect("admin.php");
            }else{
                echo validation_errors("Your credentials are not correct!!");
       }
    }
}    

}

function login_user($username,$password){

    $sql = "SELECT id, password FROM users WHERE username = '".$username."' ";

    $result = query($sql);

    confirm($result);

    if (row_count($result) == 1){
        $row = fetch_data($result);
        $db_password = $row['password'];

        if (md5($password) === $db_password){
            $_SESSION['username'] = $username;
            return true;
        }else{
            return false;
        }
    }
    echo "user not found";
    return false;
}

function logged_in(){
    if (isset($_SESSION['username'])){

        return true;
    }else{
        return false;
    }
}




?>