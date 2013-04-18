<?php
include 'config/config.php';

$users = $_GET['users'];
$restSelected = $_GET['rest'];

$users = explode("_", $users);

//validate users, restId


$restName = getRestaurant($restSelected);

$reviewList = getReviewIds($restSelected);

$userReviews = array();
foreach ($users as $user){
	array_push($userReviews, "");
}

//echo names for table

$response = "";




//foreach user

//	select reviewids where




foreach ($reviewList as $review){
	$i = 0;
	$cumAvg = 0;
	$reviewId = $review['reviewID'];


	foreach ($users as $person){
		$persId = $person['usID'];
		$review = getReview($persId, $reviewId);


		//create person total/average

		$taste = $review[0]['taste'];
		$priceVal = $review[0]['priceValue'];
		$convLoc = $review[0]['convenienceLocation'];
		$descToProd = $review[0]['descToProduct'];
		$service = $review[0]['service'];
		$comments = $review[0]['comments'];
		


		//create table string


		$tblString = "<tr><td class='taste'>" . $taste . "</td><td class='price'>" . $priceVal . "</td><td class='convloc'>" . $convLoc . "</td><td class='descprod'>" . $descToProd . "</td><td class='service'>" . $service . "</td><td class='comments'>" . $comments . "</td>";

		$userReviews[$i] = $userReviews[$i] . $tblString;
		

		$i++;


	}


}



$userNum = "1";
foreach ($userReviews as $review){
	$response .= "<table><tbody>";
	$response .= $review;
	$response .= "</tbody></table>";

	if ($userNum == "1"){
		$response .= "____";
	}
	$userNum++;

}

echo $response;


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