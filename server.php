<?php

session_start();

//intialising variables

$username = "";
$email ="";
$errors = array();
//connect to db
$db = mysqli_connect('localhost','root','','practice') or die("could not connect to database");

// Register users
if(isset($_POST["reg_user"])) {
if(isset($_POST["username"])) {
$username = mysqli_real_escape_string($db, $_POST['username']);
}
if(isset($_POST["email"])) {
$email = mysqli_real_escape_string($db, $_POST['email']);
}
if(isset($_POST["password"])) {
$password = mysqli_real_escape_string($db, $_POST['password']);
}
if(empty($username)) {array_push($errors,"Username is required");}
if(empty($email)) {array_push($errors,"Email is required");}
if(empty($password)) {array_push($errors,"Password is required");}


// check db for existing user with same username

$user_check_query = "SELECT * FROM user WHERE username = '$username' or email = '$email' LIMIT 1";

$results = mysqli_query($db, $user_check_query);
$user = mysqli_fetch_assoc($results);

if($user) {
	if($user['username'] === $username){array_push($errors, "Username already exists");}
	if($user['email'] === $email){array_push($errors, "Email already exists");}
			
	}

//Register the user if no error 


		if(count($errors) == 0){

			$password = md5($password);//this will encrpt password
			$query = "INSERT INTO user (username,email,password) VALUES ('$username' ,'$email','$password')";
			mysqli_query($db,$query); //Run the query 
			$_SESSION['username'] = $username;
			$_SESSION['success'] = "You are now logged in";

			header('location: index.php');

		}
}


//login user

if(isset($_POST['login_user'])){

if(isset($_POST["username"])) {
$username = mysqli_real_escape_string($db, $_POST['username']);

}
if(isset($_POST["password"])) {
$password = mysqli_real_escape_string($db, $_POST['password']);

}

if(empty($username)){
	array_push($errors, "Username is required");
}
if(empty($password)){
	array_push($errors, "Password is required");
}

if(count($errors) == 0){
	$password = md5($password);
	$query = "SELECT * FROM user WHERE username='$username' AND password='$password' ";
	$results=mysqli_query($db,$query);


	if(mysqli_num_rows($results)){
		$_SESSION['username'] = $username;
		$_SESSION['success'] = "Logged in successfully";
		header('location: index.php');
	}else{
		array_push($errors,"Wrong Credentials. Please type again .");
	}
}

}
?>