function success(position) {
	var latitude  = position.coords.latitude.toFixed(2);
	var longitude = position.coords.longitude.toFixed(2);
	if (window.location.href.indexOf("timeline-new-post.html") != -1) {
		var htmlElement = document.getElementById("postLocation");
	} else if (window.location.href.indexOf("timeline-new-image.html") != -1) {
		var htmlElement = document.getElementById("imageLocation");
	}

	if (htmlElement) {
		htmlElement.innerHTML = "You have published a post from this location (" + latitude + "°, " + longitude + "°).";
		var img = new Image();
		img.src = "https://maps.googleapis.com/maps/api/staticmap?center=" + latitude + "," + longitude + "&zoom=13&size=400x100&sensor=false";
		htmlElement.appendChild(img);
	}
}

navigator.geolocation.getCurrentPosition(success);