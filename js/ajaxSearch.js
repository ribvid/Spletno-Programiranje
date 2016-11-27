var search = document.getElementById("searchInput");
search.addEventListener("keyup", ajaxSearch);
search.addEventListener("keypress", function(event) {
	var key = event.which || event.keyCode;
	if (key == 13) {
		event.preventDefault();
	}
});

function ajaxSearch(ev) {
	var request = new XMLHttpRequest();
	request.addEventListener("load", ajaxSearchHandlerJson);
	request.open("GET", "search.json");
	request.responseType = "text";
	request.send();
}

function ajaxSearchHandlerJson(ev) {
	var response = JSON.parse(this.responseText);
	var searchText = document.getElementById("searchInput").value;
	var searchResults = document.getElementById("results");
	var regex = new RegExp(searchText, "i");
	var output = ' ';
	response.forEach(function(item, index) {
		if (item.name.search(regex) != -1) {	
			output += '<a href="' + item.link + '"><li>' + item.name + '</li></a>';
		}
	});
	if (!searchText) {
		output = ' ';
	}
	searchResults.innerHTML = output;
}