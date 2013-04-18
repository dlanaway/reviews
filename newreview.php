<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Our Restaurant Reviews</title>
<link rel="stylesheet" href="extras/reviews.css" type="text/css">
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

	$stmt = $pdo->prepare("select * from restaurants order by restName");

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

echo "</select><input type='text' name='newRest' id='newRest'> Location:";

echo "<select id='location' name='location'>
<option value='' selected='selected'></option>";


try{
	$pdo = new PDO(PDO_DSN, PDO_USERNAME, PDO_PASSWORD);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$stmt = $pdo->prepare("select * from locations");

	$stmt->execute();

	$locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

	foreach($locations as $row){

		$location=$row['location'];
		$id = $row['locationID'];
		echo "<option value='". $id . "'>" . $location . "</option>";
	}

		$pdo = null;

	}
	catch(PDOException $e){
       		echo $e;
}

echo "</select>";





echo "<input type='text' name='newLocation' id='newLocation'><br><br>Date: <input type='text' id='date' name='date'><br>";

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
		echo "<table class='newReview'><tbody><tr><td>" . $name . "<input id='us" . $i . "' name='us" . $i . "' type='hidden' value='" . $id . "'></td><td></td><td></td><td></td><td></td></tr>";
		echo "<tr><td class='taste'>Taste</td><td class='price'>Price/Value</td><td class='convloc'>Convenience/Location</td><td class='descprod'>Description To Product</td><td class='service'>Service</td><td class='comments'>Comments</td></tr></tbody></table>";


		echo "<div id='current" . $i . "'></div>";

		echo "<table><tbody><tr><td class='taste'><input type='text' name='" . $id . "taste' id='" . $id . "taste' size='4'></td><td class='price'><input type='text' name='" . $id . "priceVal' id='" . $id . "priceVal' size='4'></td><td class='convloc'><input type='text' name='" . $id . "convLoc' id='" . $id . "convLoc' size='4'></td><td class='descprod'><input type='text' name='" . $id . "descToProd' id='" . $id . "descToProd' size='4'></td><td class='service'><input type='text' name='" . $id . "service' id='" . $id . "service' size='4'></td><td class='comments'><textarea name='" . $id . "comments' id='" . $id . "comments'></textarea></td></tr></tbody></table>";

		$i++;
	}






		$pdo = null;

	}
	catch(PDOException $e){
       		echo $e;
}




echo "<br><br><input style='color:blue' type='submit' value='Donate Now'>";



echo "</form>";


?>


  
</div>
</body>
</html>
