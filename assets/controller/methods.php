<?php
include_once 'config.php';
include_once 'Semej.php';


// signup method 
function signup($data) {
    $username = validate($data['username']);
    $email    = validate($data['email']);
    $password = validate($data['password']);
    $passwordConfirm = validate($data['passwordConfirm']);

    if($password !== $passwordConfirm) {
        Semej::set('Error','Error',"Passwords doesn't match");
        header('Location: index.php');die;
    }

    if(checkUsername($username)) {
        Semej::set('Error','Error',"Username Exist!");
        header('Location: index.php');die;
    }

    if(checkEmail($email)) {
        Semej::set('Error','Error',"Email Exist!");
        header('Location: index.php');die;
    }

    $password = sha1($password.SALT);

    $dbs = dbsConnection();
    $sql = "INSERT INTO users_tbl (username, email, password) VALUES ('$username', '$email', '$password')";

    if(mysqli_query($dbs, $sql)) {
        Semej::set('OK','OK',"Don!");
        header('Location: index.php');die;
    }else{
        Semej::set('Error','Error',"Signup Failed!");
        header('Location: index.php');die;
    }

}

// validate data
function validate($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


// database connection
function dbsConnection() {
    $server = DB_SERVER;
    $username = DB_USER;
    $password = DB_PASS;
    $dbName = DB_NAME;

    $connection = mysqli_connect($server, $username, $password, $dbName);

    if(!$connection) {
        die('DBS Connection Error!');
    }

    return $connection;
}

// check username exist
function checkUsername($username) {
    $username = validate($username);
    $dbs = dbsConnection();
    $sql = "SELECT id FROM users_tbl WHERE username='$username'";
    $result = mysqli_query($dbs, $sql);

    if(mysqli_num_rows($result) > 0) {
        return true;
    }else{
        return false;
    }
}

function checkEmail($email) {
    $email = validate($email);
    $dbs = dbsConnection();
    $sql = "SELECT id FROM users_tbl WHERE email='$email'";
    $result = mysqli_query($dbs, $sql);

    if(mysqli_num_rows($result) > 0) {
        return true;
    }else{
        return false;
    }
}