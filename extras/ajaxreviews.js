<!-- 
//Browser Support Code
function ajaxFunction(){
	var ajaxRequest;  // The variable that makes Ajax possible!
	
	try{
		// Opera 8.0+, Firefox, Safari
		ajaxRequest = new XMLHttpRequest();
	} catch (e){
		// Internet Explorer Browsers
		try{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e){
				// Something went wrong
				alert("Your browser broke!");
				return false;
			}
		}
	}
	// Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){

			var reviews = ajaxRequest.responseText;
			var eaReview = reviews.split("____", 2);
			document.getElementById('current1').innerHTML = eaReview[0];
			document.getElementById('current2').innerHTML = eaReview[1];
		}
	}

	var u1 = document.getElementById('us1').value;
	var u2 = document.getElementById('us2').value;
	var rest = document.getElementById('restaurant').value;

	var vals = u1 + "_" + u2;

	var queryString = "?users=" + vals + "&rest=" + rest;

	ajaxRequest.open("GET", "showreview.php" + queryString, true);
	ajaxRequest.send(null);
 

}