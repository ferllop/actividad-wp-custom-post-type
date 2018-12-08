// This example adds a search box to a map, using the Google Place Autocomplete
// feature. People can enter geographical searches. The search box will return a
// pick list containing a mix of places and predicted search terms.

// This example requires the Places library. Include the libraries=places
// parameter when you first load the API. For example:
// <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">


function initAutocomplete() {
// 	var lat = parseFloat(document.getElementById('lat').value);
// 	var lng = parseFloat(document.getElementById('lng').value);
// 	var mylatlng = {lat: lat, lng: lng};
// 	var map = new google.maps.Map(document.getElementById('map'), {
// 	  center: mylatlng,
// 	  zoom: 15,
// 	  mapTypeId: 'roadmap'
// 	});
// 	var title = document.getElementById('pac-input').value;
// 	//var markers = new google.maps.Marker({
//   	//	position: mylatlng,
//   	//	map: map,
//   	//});
// 	var actividad = []
// 	actividad.push(new google.maps.Marker({
// 		  map: map,
// 		  //icon: icon,
// 		  title: title,
// 		  position: mylatlng
// 		}));

    if ( document.getElementById('lat').value !== "") {
		var latitude = parseFloat(document.getElementById('lat').value);
		var longitud = parseFloat(document.getElementById('lng').value);
		var map = new google.maps.Map(document.getElementById('map'), {
	  		center: {lat: latitude, lng: longitud},
	  		zoom: 16,
	  		mapTypeId: 'roadmap'
		});
		var actividad = [];
		var title = document.getElementById('pac-input').value;
		actividad.push(new google.maps.Marker({
			  map: map,
			  //icon: icon,
			  title: title,
			  position: {lat: latitude, lng: longitud},
		}));
	} else {
		latitude = 33.1595107;
		longitud = 4.3963885;
		map = new google.maps.Map(document.getElementById('map'), {
	  		center: {lat: latitude, lng: longitud},
	  		zoom: 3,
	  		mapTypeId: 'roadmap'
		});
		var actividad = [];
	}
	// Create the search box and link it to the UI element.
	var input = document.getElementById('pac-input');
	var searchBox = new google.maps.places.SearchBox(input);
	map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

	// Bias the SearchBox results towards current map's viewport.
	map.addListener('bounds_changed', function() {
	  searchBox.setBounds(map.getBounds());
	});

	var markers = [];
	// Listen for the event fired when the user selects a prediction and retrieve
	// more details for that place.
	searchBox.addListener('places_changed', function() {
	  var places = searchBox.getPlaces();

	  if (places.length === 0) {
		return;
	  }

	  // Clear out the old markers.
	  actividad.forEach(function(activity) {
		activity.setMap(null);
	  });
	  markers.forEach(function(marker) {
		marker.setMap(null);
	  });
	  markers = [];

	  // For each place, get the icon, name and location.
	  var bounds = new google.maps.LatLngBounds();
	  places.forEach(function(place) {
		if (!place.geometry) {
		  console.log("Returned place contains no geometry");
		  return;
		}
		
		// Create a marker for each place.
		markers.push(new google.maps.Marker({
		  map: map,
		  //icon: icon,
		  title: place.name,
		  position: place.geometry.location
		}));
		  document.getElementById('lat').value = place.geometry.location.lat();
    	  document.getElementById('lng').value = place.geometry.location.lng();

		if (place.geometry.viewport) {
		  // Only geocodes have viewport.
		  bounds.union(place.geometry.viewport);
		} else {
		  bounds.extend(place.geometry.location);
		}
	  });
	  map.fitBounds(bounds);
	});
}