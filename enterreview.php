<?php
include 'config/config.php';

$restaurantId = getPostData('restaurant');
$date = getPostData('date');

if ($restaurantId == ''){
	$newRestName = getPostData('newRest');
	$newLocationId = getPostData('location');
	if ($newLocationId == ''){
		$newLocation = getPostData('newLocation');
		$newLocationId = nextLocationId();
		enterNewLocation($newLocationId, $newLocation);

	}
	$restaurantId = nextRestId();


	enterNewRest($restaurantId, $newRestName, $newLocationId);
}

$usIds = getUsIds();
$nextReviewId = nextReviewId();
//echo($nextReviewId . " " . $restaurantId);
enterNewReview($nextReviewId, $restaurantId, $date);

foreach ($usIds as $usId){
	$id = $usId['usID'];

	$taste = getPostData($id . 'taste');
	$priceVal = getPostData($id . 'priceVal');
	$convLoc = getPostData($id . 'convLoc');
	$descToProd = getPostData($id . 'descToProd');
	$service = getPostData($id . 'service');
	$comments = getPostData($id . 'comments');
	//echo ($nextReviewId . " " . $id . " " . $taste . " " . $priceVal . " " . $convLoc . " " . $descToProd . " " . $service . " ");
	enterScores($nextReviewId, $id, $taste, $priceVal, $convLoc, $descToProd, $service, $comments);
}


header("Location: http://localhost/reviews/index.php");

exit;







function getPostData($name){
	if (!isset($_POST[$name])){
		$postVal = 'undefined';
	} else {
		$postVal = $_POST[$name];
	}
	return $postVal;
}

function nextRestId(){
	try{
		$pdo = new PDO(PDO_DSN, PDO_USERNAME, PDO_PASSWORD);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$stmt = $pdo->prepare("select max(restID) from restaurants");

		$stmt->execute();

		$lastId = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$pdo = null;

		}
		catch(PDOException $e){
       			echo $e;
	}

	$nextId = $lastId[0]['max(restID)'] + 1;
	return $nextId;
}


function nextReviewId(){
	try{
		$pdo = new PDO(PDO_DSN, PDO_USERNAME, PDO_PASSWORD);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$stmt = $pdo->prepare("select max(reviewID) from review");

		$stmt->execute();

		$lastId = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$pdo = null;

		}
		catch(PDOException $e){
       			echo $e;
	}

	$nextId = $lastId[0]['max(reviewID)'] + 1;
	return $nextId;
}


function enterNewRest($restaurantId, $newRestName, $newRestLocation){
	try{
		$pdo = new PDO(PDO_DSN, PDO_USERNAME, PDO_PASSWORD);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$stmt = $pdo->prepare("insert into restaurants (restID, restName, locationID) values (:restId, :restName, :restLocation)");
		$stmt->bindParam(':restId', $restaurantId);
		$stmt->bindParam(':restName', $newRestName);
		$stmt->bindParam(':restLocation', $newRestLocation);

		$stmt->execute();

		$pdo = null;

		}
		catch(PDOException $e){
       			echo $e;
	}
}

function enterNewReview($nextReviewId, $restaurantId, $date){
	try{
		$pdo = new PDO(PDO_DSN, PDO_USERNAME, PDO_PASSWORD);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$stmt = $pdo->prepare("insert into review (reviewID, restID, date) values (:reviewId, :restId, :date)");
		$stmt->bindParam(':reviewId', $nextReviewId);
		$stmt->bindParam(':restId', $restaurantId);
		$stmt->bindParam(':date', $date);


		$stmt->execute();

		$pdo = null;

		}
		catch(PDOException $e){
       			echo $e;
	}
}


function enterScores($nextReviewId, $id, $taste, $priceVal, $convLoc, $descToProd, $service, $comments){
	try{
		$pdo = new PDO(PDO_DSN, PDO_USERNAME, PDO_PASSWORD);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$stmt = $pdo->prepare("insert into scores (reviewID, usID, taste, priceValue, convenienceLocation, descToProduct, service, comments) values (:reviewId, :usId, :taste, :priceValue, :convenienceLocation, :descToProd, :service, :comments)");
		$stmt->bindParam(':reviewId', $nextReviewId);
		$stmt->bindParam(':usId', $id);
		$stmt->bindParam(':taste', $taste);
		$stmt->bindParam(':priceValue', $priceVal);
		$stmt->bindParam(':convenienceLocation', $convLoc);
		$stmt->bindParam(':descToProd', $descToProd);
		$stmt->bindParam(':service', $service);
		$stmt->bindParam(':comments', $comments);

		$stmt->execute();

		$pdo = null;

		}
		catch(PDOException $e){
       			echo $e;
	}
}

function getUsIds(){

	try{
		$pdo = new PDO(PDO_DSN, PDO_USERNAME, PDO_PASSWORD);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$stmt = $pdo->prepare("select * from us");

		$stmt->execute();

		$usIds = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$pdo = null;

		}
		catch(PDOException $e){
	       		echo $e;
	}

	return $usIds;
}


function nextLocationId(){
	try{
		$pdo = new PDO(PDO_DSN, PDO_USERNAME, PDO_PASSWORD);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$stmt = $pdo->prepare("select max(locationID) from locations");

		$stmt->execute();

		$lastId = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$pdo = null;

		}
		catch(PDOException $e){
       			echo $e;
	}

	$nextId = $lastId[0]['max(locationID)'] + 1;
	return $nextId;
}


function enterNewLocation($locationId, $newLocation){
	try{
		$pdo = new PDO(PDO_DSN, PDO_USERNAME, PDO_PASSWORD);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$stmt = $pdo->prepare("insert into locations (locationID, location) values (:locationId, :newLocation)");
		$stmt->bindParam(':locationId', $locationId);
		$stmt->bindParam(':newLocation', $newLocation);

		$stmt->execute();

		$pdo = null;

		}
		catch(PDOException $e){
       			echo $e;
	}
}















?>