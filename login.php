<?php
	// See all errors and warnings
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	$server = "localhost";
	$username = "root";
	$password = "";
	$database = "dbUser";
	$mysqli = mysqli_connect($server, $username, $password, $database);

	$email = isset($_POST["loginEmail"]) ? $_POST["loginEmail"] : false;
	$pass = isset($_POST["loginPass"]) ? $_POST["loginPass"] : false;	
	
	// if email and/or pass POST values are set, set the variables to those values, otherwise make them false
?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 2</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="Name Surname">
	<!-- Replace Name Surname with your name and surname -->
</head>
<body>
	<div class="container">
		<?php
			if($email && $pass){
				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$res = $mysqli->query($query);
				if($row = mysqli_fetch_array($res)){
					echo 	"<table class='table table-bordered mt-3'>
								<tr>
									<td>Name</td>
									<td>" . $row['name'] . "</td>
								<tr>
								<tr>
									<td>Surname</td>
									<td>" . $row['surname'] . "</td>
								<tr>
								<tr>
									<td>Email Address</td>
									<td>" . $row['email'] . "</td>
								<tr>
								<tr>
									<td>Birthday</td>
									<td>" . $row['birthday'] . "</td>
								<tr>
							</table>";
				
					echo 	"<form method='POST' action='login.php' enctype='multipart/form-data'>
								<div class='form-group'>
									<input type='file' class='form-control' name='picToUpload' id='picToUpload' /><br/>
									<input type='hidden'  id='loginPass' name='loginPass' value='".$pass."'>
									<input type='hidden' id='loginEmail' name='loginEmail' value='".$email."'>
									<input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
								</div>
							  </form>";
					echo "<h1>Image Gallery</h1>" ;
					$uid ="" ;
					$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
					$res = mysqli_query($mysqli, $query); 
					if($row = mysqli_fetch_array($res)){
						$uid = $row['user_id'] ;
					}
					$q= "SELECT * FROM tbgallery WHERE user_id = '$uid'";
					$r = mysqli_query($mysqli, $q); 
					
					echo 	 "<div class='container'><div class='row imageGallery'>" ;
					if($r->num_rows){
						while($row = $r->fetch_array()){
							$src = "gallery/".$row['filename'] ;
							$style = " style='background-image: url(".$src.")' " ;
							echo	"<div class='col-4' ".$style."></div>";
						}
					}
					echo	  "</div> </div>";
					
				}
				else{
					echo 	'<div class="alert alert-danger mt-3" role="alert">
	  							You are not registered on this site!
	  						</div>';
				}
			} 
			else{
				echo 	'<div class="alert alert-danger mt-3" role="alert">
	  						Could not log you in
	  					</div>';
			}
		?>
	</div>
</body>
</html>

<?php

if(isset($_POST["submit"])){
$target_dir = "gallery/";
$uploadFile = $_FILES["picToUpload"];
$target_file = $target_dir . basename($uploadFile["name"]);
$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
	if(($uploadFile["type"] == "image/jpeg" || $uploadFile["type"] == "image/pjpeg") && $uploadFile["size"] <= 1048576){
		if($uploadFile["error"] > 0){
			echo "Error: " . $uploadFile["error"] . "<br/>";
		} 
		else {	
			$fileName = $uploadFile["name"] ;
			move_uploaded_file($uploadFile["tmp_name"], "gallery/" . $uploadFile["name"]);
			echo "Stored in: " . "gallery/" . $uploadFile["name"];
			$query = "INSERT INTO tbgallery (user_id, filename) VALUES ('$uid', '$fileName');";
			$res = mysqli_query($mysqli, $query) == TRUE;
		}
	} else {
		echo "Invalid file";
	}
}


?>