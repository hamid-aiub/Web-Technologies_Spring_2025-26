<?php 
include "../Model/DatabaseConnection.php";
session_start();

$username = $_POST["username"] ?? "";
$password = $_POST["password"] ?? "";
$hasUsernameError = true;
$hasPasswordError = true;
echo "<h1>Hello Mr, $username</h1>";
echo "<h3>We know your password...$password, right?</h3>";

// if(!$username){
//     $_SESSION["usernameErr"] = "Username is required";
//     $hasUsernameError = true;
// }else{
//     unset($_SESSION["usernameErr"]);
//     $hasUsernameError = false;
// }

if(!isset($_POST["username"])){
     unset($_SESSION["usernameErr"]);
    $hasUsernameError = false;
    
}else{
   $_SESSION["usernameErr"] = "Username is required";
    $hasUsernameError = true;
}

if(!$password){
    $_SESSION["passwordErr"] = "Password is required";
    $hasPasswordError = true;
}else{
   unset($_SESSION["passwordErr"]); 
   $hasPasswordError = false;
}

if($hasUsernameError || $hasPasswordError){
    $_SESSION["username"] = $username;
    // $_SESSION["password"] = $password;
    Header("Location: ../View/login.php");
}else{
    // Now doing using dummy array. Later, it will be converted by database. 

    $db = new DatabaseConnection();
    $connection = $db->openConnection();
    $result = $db->LoginWithPrepareStmt($connection, "users", $username, $password);
    if($result->num_rows == 1){
        while($row = $result->fetch_assoc()){
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["username"] = $row["username"];
            $_SESSION["image_path"] = $row["image_path"];
            $_SESSION["isLoggedIn"] = true;
            Header("Location: ../View/dashboard.php");
        }
    }else{
        $_SESSION["loginErr"] = "Username or password doesn't match. Please try again.";
        Header("Location: ../View/login.php");
    }


}

?>