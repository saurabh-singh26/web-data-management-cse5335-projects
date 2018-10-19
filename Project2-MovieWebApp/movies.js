function initialize () {
}

function sendRequest () {
	var xhr = new XMLHttpRequest();
	var query = encodeURI(document.getElementById("form-input").value);
	xhr.open("GET", "proxy.php?method=/3/search/movie&query=" + query);
	xhr.setRequestHeader("Accept","application/json");
	xhr.onreadystatechange = function () {
		if (this.readyState == 4) {
			var json = JSON.parse(this.responseText);
			// console.log(json)
			tabularInfo = getTableWithTitleAndReleaseDate(json)
			document.getElementById("output").innerHTML = tabularInfo;
		}
	};
	xhr.send(null);
}

function getTableWithTitleAndReleaseDate(json){
	all_results = json["results"]
	if(all_results.length == 0) return "</br>No info found!!</br>Please try something else."
	itemizedList = "<table style=\"width:100%\" id=\"info\"><tr><td style=\"width:30%\" rowspan=\"2\">"
	itemizedList += "<table id=\"list\" style=\"width:100%\"><tr><th style=\"width:75%\">Title</th><th style=\"width:25%\">Year of release</th></tr>"
	for(var i=0;i<all_results.length;i++){
		itemizedList += "<tr><td>"
		itemizedList += "<span id=\"" + all_results[i]["id"] + "\" onclick=\"getDetailedInfo(this.id)\">"
		itemizedList += all_results[i]["title"]
		itemizedList += "</span>"
		itemizedList += "</td><td style=\"text-align: center;\">"
		itemizedList += all_results[i]["release_date"].split("-")[0]
		itemizedList += "</td></tr>"
	}
	itemizedList += "</table></td>"
	itemizedList += "<td style=\"width:20%\" rowspan=\"2\"><div id=\"imgInfoDiv\" style=\"text-align:center\"></div></td>"
	itemizedList += "<td id=\"basicInfoTD\" style=\"vertical-align: bottom;height: 50%\"></td></tr><tr><td id=\"castInfoTD\" style=\"vertical-align: top;\"></td></tr></table>"
	return itemizedList
}

function getDetailedInfo(movieID){
	// console.log(movieID)
	getBasicMovieInfo(movieID)
	getCastInformation(movieID)
}

function getBasicMovieInfo(movieID){
	var xhr = new XMLHttpRequest();
	xhr.open("GET", "proxy.php?method=/3/movie/" + movieID);
	xhr.setRequestHeader("Accept","application/json");
	xhr.onreadystatechange = function () {
		if (this.readyState == 4) {
			var json = JSON.parse(this.responseText);
			// console.log(json)
			imgInfoContent =	"<img src=\"http://image.tmdb.org/t/p/w185/" + json["poster_path"] + "\">"
			basicInfoContent = "<table style=\"text-align: bottom;\"><tr><td><strong>Title: </strong></td>"
			basicInfoContent += "<td>" + json["title"] + "</td></tr><tr><td><strong>Summary: </strong></td>"
			basicInfoContent += "<td>" + json["overview"] + "</td></tr><tr><td><strong>Genres: </strong></td>"
			genresArray = json["genres"]
			genresContent = ""
			for(var i=0;i<genresArray.length;i++){
				genresContent += genresArray[i]["name"]
				if(i != genresArray.length-1) {
					genresContent += ", "
				}
			}
			basicInfoContent += "<td>" + genresContent + "</td></tr></table>"
			document.getElementById("basicInfoTD").innerHTML = basicInfoContent;
			document.getElementById("imgInfoDiv").innerHTML = imgInfoContent;
		}
	};
	xhr.send(null);
}

function getCastInformation(movieID){
	var xhr = new XMLHttpRequest();
	xhr.open("GET", "proxy.php?method=/3/movie/" + movieID + "/credits");
	xhr.setRequestHeader("Accept","application/json");
	xhr.onreadystatechange = function () {
		if (this.readyState == 4) {
			var json = JSON.parse(this.responseText);
			// console.log(json)
			castArray = json["cast"]
			castInfoContent = "<strong>Top 5 cast:</strong></br></br>"
			for(var i=0;i<castArray.length && i<5;i++){
				castInfoContent += castArray[i]["name"] + "</br>"
			}
			document.getElementById("castInfoTD").innerHTML = castInfoContent;
		}
	};
	xhr.send(null);
}