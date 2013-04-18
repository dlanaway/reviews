<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Our Restaurant Reviews</title>
<link rel="stylesheet" href="extras/reviewsmobp.css" type="text/css">
<script src="extras/ajaxreviews.js"></script>


</head>

<body>
<div id="container">
    <div id="heading">
	<h1>New Review</h1>
    </div>
    <div id="content">
<form name="newReview" method="post" action="enterreview.php">
Restaurant<br>
<select id="restaurant" name="restaurant" onChange="ajaxFunction()">
<option value="" selected="selected"></option>
<?php
include 'config/config.php';



try{
	$pdo = new PDO(PDO_DSN, PDO_USERNAME, PDO_PASSWORD);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$stmt = $pdo->prepare("select * from restaurants");

	$stmt->execute();

	$restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);

	foreach($restaurants as $row){

		$id=$row['restID'];
		$name=$row['restName'];
		echo "<option value='". $id . "'>" . $name . "</option>";
	}

		$pdo = null;

	}
	catch(PDOException $e){
       		echo $e;
}

echo "</select><input type='text' name='newRest' id='newRest'><br><br>Date: <input type='text' id='date' name='date'><br>";



$i = 1;

try{
	$pdo = new PDO(PDO_DSN, PDO_USERNAME, PDO_PASSWORD);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$stmt = $pdo->prepare("select * from us");

	$stmt->execute();

	$us = $stmt->fetchAll(PDO::FETCH_ASSOC);

	foreach($us as $row){
		$id=$row['usID'];
		$name=$row['usName'];
		echo "<div id='name" . $i . "'>" . $name . "<input id='us" . $i . "' name='us" . $i . "' type='hidden' value='" . $id . "'></div>";
		echo "<div class='cat1 head" . $i . "'>Taste</div><div class='cat2 head" . $i . "'>Price/Value</div><div class='cat3 head" . $i . "'>Convenience/Location</div><div class='cat4 head" . $i . "'>Description To Product</div><div class='cat5 head" . $i . "'>Service</div><div class='cat6 head" . $i . "'>Comments</div>";



		echo "<input class='cat1 pers" . $i . "' type='text' name='" . $id . "taste' id='" . $id . "taste' size='4'><input class='cat2 pers" . $i . "' type='text' name='" . $id . "priceVal' id='" . $id . "priceVal' size='4'><input class='cat3 pers" . $i . "' type='text' name='" . $id . "convLoc' id='" . $id . "convLoc' size='4'><input class='cat4 pers" . $i . "' type='text' name='" . $id . "descToProd' id='" . $id . "descToProd' size='4'><input class='cat5 pers" . $i . "' type='text' name='" . $id . "service' id='" . $id . "service' size='4'><textarea class='cat6 pers" . $i . "' name='" . $id . "comments' id='" . $id . "comments'></textarea>";

		$i++;
	}






		$pdo = null;

	}
	catch(PDOException $e){
       		echo $e;
}




echo "<div id='donate'><br><br><input style='color:blue' type='submit' value='Donate Now'></div>";



echo "</form>";


?>


  
</div>
</body>
</html>
