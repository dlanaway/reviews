<?php
include 'config/config.php';

$restSelected = $_GET['id'];

$restName = getRestaurant($restSelected);

$reviewList = getReviewIds($restSelected);


$returnstring = $restName . "<br><br>";


$us = getUs();


//echo names for table

$returnstring .= "<table><thead><tr><th></th>";

foreach ($us as $name){
	$returnstring .= "<th>" . $name['usName'] . "</th><th></th><th></th><th></th><th></th><th></th><th></th>";

}
$returnstring .= "<th></th></tr>";

$returnstring .= "<tr><th>Date</th><th>Taste</th><th>Price/Value</th><th>Convenience/<br>Location</th><th>Description to<br>Product</th><th>Service</th><th>Comments</th><th>Average</th><th>Taste</th><th>Price/Value</th><th>Convenience/<br>Location</th><th>Description to<br>Product</th><th>Service</th><th>Comments</th><th>Average</th><th>Total Score</th></tr></thead><tbody>";

foreach ($reviewList as $review){
	$i = 0;
	$cumAvg = 0;
	$reviewId = $review['reviewID'];


	$returnstring .= "<tr><td>" . $review['date'] . "</td>";

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


		$returnstring .= "<td>" . $taste . "</td><td>" . $priceVal . "</td><td>" . $convLoc . "</td><td>" . $descToProd . "</td><td>" . $service . "</td><td>" . $comments . "</td><td>" . round($persAvg, 3) . "</td>";



	}

	$avg = $cumAvg / $i;

	$returnstring .= "<td>" . round($avg, 3) . "</td></tr>";



}


$returnstring .= "</tbody></table>";
echo $returnstring;


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

		$stmt = $pdo->prepare("select restName from restaurants where restID = :restId");
		$stmt->bindParam(':restId', $restId);

		$stmt->execute();

		$restaurant = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$pdo = null;

		}
		catch(PDOException $e){
	       		echo $e;
	}

	$restName = $restaurant[0]['restName'];
	return $restName;
}


?>