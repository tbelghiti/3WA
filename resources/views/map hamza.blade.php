<!DOCTYPE html>
<html>
<head>
	<link href="../assets/styles.min.css" rel="stylesheet">
	   
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
   
	<title>Google Maps: Latitude-Longitude Finder Tool</title>
	<style>
		#map-search { position: absolute; top: 10px; left: 10px; right: 10px; margin-top: 100%}
		#search-txt { float: left; width: 60%;  margin-top: 15%}
		#search-btn { float: left; width: 19%;  margin-top: 15%}
		#detect-btn { float: right; width: 19%;  margin-top: 15%}
		#map-canvas { position: absolute; top: 40px; bottom: 65px; left: 10px; right: 10px; margin-top: 15% }
		#map-output { position: absolute; bottom: 10px; left: 10px; right: 10px;  margin-top: 15%}
		#map-output a { float: right;  }
	</style>
</head>
<body>

	<form calss="mt-3" action="{{route('weatherLL')}}" method="post">
		@csrf
		<input id="lon1" name="lon1" value="" type="hidden">
		<input id="lng1" name="lng1" value="" type="hidden">

		<button type="submit" class="btn btn-primary mb-3"> Find weather </button>
	</form>
	<br>
	<br>
	
	<div id="map-search">
		<input id="search-txt" type="text" value="Disneyland, 1313 S Harbor Blvd, Anaheim, CA 92802, USA" maxlength="100">
		<input id="search-btn" type="button" value="Locate Address">
		<input id="detect-btn" type="button" value="Detect Location" disabled>
	</div>
	<div id="map-canvas"></div>
	<div id="map-output"></div>




	<script type="text/javascript">
		/*
		 * Google Maps: Latitude-Longitude Finder Tool
		 * https://salman-w.blogspot.com/2009/03/latitude-longitude-finder-tool.html
		 */
		function loadmap() {
			// initialize map
			var map = new google.maps.Map(document.getElementById("map-canvas"), {
				center: new google.maps.LatLng(33.808678, -117.918921),
				zoom: 13,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			});
			// initialize marker
			var marker = new google.maps.Marker({
				position: map.getCenter(),
				draggable: true,
				map: map
			});
			// intercept map and marker movements
			google.maps.event.addListener(map, "idle", function() {
				marker.setPosition(map.getCenter());
				document.getElementById("map-output").innerHTML = "Latitude:  " + map.getCenter().lat().toFixed(6) + "<br>Longitude: " + map.getCenter().lng().toFixed(6) + "<a href='https://www.google.com/maps?q=" + encodeURIComponent(map.getCenter().toUrlValue()) + "' target='_blank'>Go to maps.google.com</a>";
				console.log(map.getCenter().lat().toFixed(6));
				document.getElementById("lon1").value = map.getCenter().lat().toFixed(6);
				document.getElementById("lng1").value = map.getCenter().lng().toFixed(6);

			});
			google.maps.event.addListener(marker, "dragend", function(mapEvent) {
				map.panTo(mapEvent.latLng);
			});
			// initialize geocoder
			var geocoder = new google.maps.Geocoder();
			google.maps.event.addDomListener(document.getElementById("search-btn"), "click", function() {
				geocoder.geocode({ address: document.getElementById("search-txt").value }, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						var result = results[0];
						document.getElementById("search-txt").value = result.formatted_address;
						if (result.geometry.viewport) {
							map.fitBounds(result.geometry.viewport);
						} else {
							map.setCenter(result.geometry.location);
						}
					} else if (status == google.maps.GeocoderStatus.ZERO_RESULTS) {
						alert("Sorry, geocoder API failed to locate the address.");
					} else {
						alert("Sorry, geocoder API failed with an error.");
					}
				});
			});
			google.maps.event.addDomListener(document.getElementById("search-txt"), "keydown", function(domEvent) {
				if (domEvent.which === 13 || domEvent.keyCode === 13) {
					google.maps.event.trigger(document.getElementById("search-btn"), "click");
				}
			});
			// initialize geolocation
			if (navigator.geolocation) {
				google.maps.event.addDomListener(document.getElementById("detect-btn"), "click", function() {
					navigator.geolocation.getCurrentPosition(function(position) {
						map.setCenter(new google.maps.LatLng(position.coords.latitude, position.coords.longitude));
					}, function() {
						alert("Sorry, geolocation API failed to detect your location.");
					});
				});
				document.getElementById("detect-btn").disabled = false;
			}
		}
	</script>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script src="//maps.googleapis.com/maps/api/js?v=3&amp;sensor=false&amp;key=AIzaSyCJO83xzvE1FS7Otuu2Suujgy4aX5f1fqQ&amp;callback=loadmap" defer></script>
</body>
</html>