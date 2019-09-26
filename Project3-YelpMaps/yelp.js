// Saurabh Singh
var markers = [];
function initialize () {
	limit = 10
}

function sendRequest () {
	var xhr = new XMLHttpRequest();
	var query = encodeURI(document.getElementById("search").value);
	bounds = map.getBounds()
	// Returns distance between the diagonal points of bounding box in metres
	// https://developers.google.com/maps/documentation/javascript/reference/geometry
	diagonalDistance = google.maps.geometry.spherical.computeDistanceBetween(bounds.getNorthEast(), bounds.getCenter())
	// console.log("Radius: " + diagonalDistance)
	// Can change the diagonal to min side divide by 2 for getting the noumber of markers in the box same as list
	xhr.open("GET", "proxy.php?term=" + query + "&limit=" + limit + "&radius=" + parseInt(diagonalDistance) + "&latitude=" + bounds.getCenter().lat() + "&longitude=" + bounds.getCenter().lng());
	xhr.setRequestHeader("Accept","application/json");
	xhr.onreadystatechange = function () {
		if (this.readyState == 4) {
			var json = JSON.parse(this.responseText);
			// console.log(json)
			if(json.hasOwnProperty("error")) document.getElementById("output").innerHTML = json.error.code+"</br>"+json.error.description;
			else{
				if(json["businesses"].length==0) document.getElementById("output").innerHTML = "No results found";
				else{
					plotInMap(json)
					displayItemizedList(json)
				}
			}
		}
	};
	xhr.send(null);
}

function initMap(){
	center = {lat: 32.75, lng: -97.13} //Initially centered at this point given in the project
	map = new google.maps.Map(document.getElementById('map'), {
	  center: center, 
	  zoom: 16
	});
}

function deleteMarkers() {
	for (var i = 0; i < markers.length; i++) {
	  markers[i].setMap(null);
	}
	markers = [];
}

function plotInMap(json){
	deleteMarkers();
	for(var i=0;i<json["businesses"].length;i++){
		pos = new google.maps.LatLng({lat: json["businesses"][i]["coordinates"]["latitude"], lng: json["businesses"][i]["coordinates"]["longitude"]}); 
		markers.push(new google.maps.Marker({position: pos, map: map, label: String(i+1)}));
		// console.log("For " + i + ", distance is: " + google.maps.geometry.spherical.computeDistanceBetween(pos, bounds.getCenter()))
	}
}

function displayItemizedList(json){
	output = "<table><tr><th>S.No.</th><th>Image</th><th>Name</th><th>Rating</th></tr>"
	for(var i=0;i<json["businesses"].length;i++){
		output += "<tr><td>"
		output += (i+1) + ".</td><td>"
		output += "<img src=\"" + json["businesses"][i]["image_url"] + "\" style=\"height:100px;width:100px;\"></td><td>"
		output += "<a href=\"" + json["businesses"][i]["url"] + "\">" + json["businesses"][i]["name"] + "</a></td><td>"
		output += json["businesses"][i]["rating"] + "</td><td>"
		output += "</td></tr>"
	}
	output += "</table>"
	document.getElementById("output").innerHTML = output;
}