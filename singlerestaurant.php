<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Our Restaurant Reviews</title>
<link rel="stylesheet" href="extras/reviews.css" type="text/css">


</head>

<body>
<div id="container">
    <div id="heading">
	<h1></h1>
    </div>
    <div id="content">

<?php
include 'config/config.php';

$restSelected = $_GET['id'];

$restBits = getRestaurant($restSelected);

$reviewList = getReviewIds($restSelected);


echo $restBits[0]['restName'] . " (" . $restBits[0]['location'] . ")<br><br>";


$us = getUs();


//echo names for table

echo "<table><thead><tr><th></th>";

foreach ($us as $name){
	echo "<th>" . $name['usName'] . "</th><th></th><th></th><th></th><th></th><th></th><th></th>";

}
echo "<th></th></tr>";

echo "<tr><th>Date</th><th>T</th><th>P/V</th><th>C/L</th><th>DTP</th><th>S</th><th>Comments</th><th>Avg</th><th>T</th><th>P/V</th><th>C/L</th><th>DTP</th><th>S</th><th>Comments</th><th>Avg</th><th>Total</th></tr></thead><tbody>";

foreach ($reviewList as $review){
	$i = 0;
	$cumAvg = 0;
	$reviewId = $review['reviewID'];


	$tblString = "<tr><td>" . $review['date'] . "</td>";

	foreach ($us as $person){
		$persId = $person['usID'];
		$review = getReview($persId, $reviewId);


		//create person total/average

		$taste = $review[0]['taste'];
		$priceVal = $review[0]['priceValue'];
		$convLoc = $review[0]['convenienceLocation'];
		$descToProd = $review[0]['descToProduct'];
		$service = $review[0]['service'];
		$comments = $review[0]['comments'];
		
		$persTot = ($taste + $priceVal + $convLoc + $descToProd + $service);
		if ($persTot != 0){
			$persAvg = $persTot/5;
		} else {
			$persAvg = 0;
		}
		$i++;
		//add avg to cumulative avg
		$cumAvg += $persAvg;


		//create table string


		$tblString .= "<td>" . $taste . "</td><td>" . $priceVal . "</td><td>" . $convLoc . "</td><td>" . $descToProd . "</td><td>" . $service . "</td><td>" . $comments . "</td><td>" . round($persAvg, 3) . "</td>";



	}

	$avg = $cumAvg / $i;

	$tblString .= "<td>" . round($avg, 3) . "</td></tr>";



	echo $tblString;


}


function getReview($persId, $reviewId){

	try{
		$pdo = new PDO(PDO_DSN, PDO_USERNAME, PDO_PASSWORD);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$stmt = $pdo->prepare("select taste, priceValue, convenienceLocation, descToProduct, service, comments from scores where reviewID = :reviewId and usID = :person");

		$stmt->bindParam(':reviewId', $reviewId);
		$stmt->bindParam(':person', $persId);

		$stmt->execute();

		$review = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$pdo = null;

		}
		catch(PDOException $e){
	       		echo $e;
	}

	return $review;
}

function getReviewIds($restId){

	try{
		$pdo = new PDO(PDO_DSN, PDO_USERNAME, PDO_PASSWORD);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$stmt = $pdo->prepare("select * from review where restID = :restId");
		$stmt->bindParam(':restId', $restId);

		$stmt->execute();

		$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$pdo = null;

		}
		catch(PDOException $e){
	       		echo $e;
	}

	return $reviews;
}




function getUs(){

	try{
		$pdo = new PDO(PDO_DSN, PDO_USERNAME, PDO_PASSWORD);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$stmt = $pdo->prepare("select * from us");

		$stmt->execute();

		$us = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$pdo = null;

		}
		catch(PDOException $e){
	       		echo $e;
	}

	return $us;
}

function getRestaurant($restId){

	try{
		$pdo = new PDO(PDO_DSN, PDO_USERNAME, PDO_PASSWORD);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$stmt = $pdo->prepare("select * from restaurants where restID = :restId");
		$stmt->bindParam(':restId', $restId);

		$stmt->execute();

		$restaurant = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$pdo = null;

		}
		catch(PDOException $e){
	       		echo $e;
	}

	return $restaurant;
}


?>


          </tbody>
        </table>
	<br><br>T - Taste, P/V - Price/Value, C/L - Convenience/Location, DTP - Description To Product, S - Service
    </div>
</div>
</body>
</html>
