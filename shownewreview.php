<?php
include 'config/config.php';

$returnstring = "<form name='newReview' method='post' action='enterreview.php'>Restaurant<br><select id='restaurant' name='restaurant' onChange='ajaxFunction()'><option value='' selected='selected'></option>";



try{
	$pdo = new PDO(PDO_DSN, PDO_USERNAME, PDO_PASSWORD);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$stmt = $pdo->prepare("select * from restaurants");

	$stmt->execute();

	$restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);

	foreach($restaurants as $row){

		$id=$row['restID'];
		$name=$row['restName'];
		$returnstring .= "<option value='". $id . "'>" . $name . "</option>";
	}

		$pdo = null;

	}
	catch(PDOException $e){
       		echo $e;
}

$returnstring .= "</select><input type='text' name='newRest' id='newRest'><br><br>Date: <input type='text' id='date' name='date'><br>";

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
		$returnstring .= "<table class='newReview'><tbody><tr><td>" . $name . "<input id='us" . $i . "' name='us" . $i . "' type='hidden' value='" . $id . "'></td><td></td><td></td><td></td><td></td></tr>";
		$returnstring .= "<tr><td class='taste'>Taste</td><td class='price'>Price/Value</td><td class='convloc'>Convenience/Location</td><td class='descprod'>Description To Product</td><td class='service'>Service</td><td class='comments'>Comments</td></tr></tbody></table>";


		$returnstring .= "<div id='current" . $i . "'></div>";

		$returnstring .= "<table><tbody><tr><td class='taste'><input type='text' name='" . $id . "taste' id='" . $id . "taste' size='4'></td><td class='price'><input type='text' name='" . $id . "priceVal' id='" . $id . "priceVal' size='4'></td><td class='convloc'><input type='text' name='" . $id . "convLoc' id='" . $id . "convLoc' size='4'></td><td class='descprod'><input type='text' name='" . $id . "descToProd' id='" . $id . "descToProd' size='4'></td><td class='service'><input type='text' name='" . $id . "service' id='" . $id . "service' size='4'></td><td class='comments'><textarea name='" . $id . "comments' id='" . $id . "comments'></textarea></td></tr></tbody></table>";

		$i++;
	}






		$pdo = null;

	}
	catch(PDOException $e){
       		echo $e;
}




$returnstring .= "<br><br><input style='color:blue' type='submit' value='Donate Now'>";



$returnstring .= "</form>";

echo $returnstring;

?>