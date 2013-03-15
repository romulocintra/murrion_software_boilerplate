function bindInfoWindow(marker, map, infoWindow, html) {
  google.maps.event.addListener(marker, 'click', function() {
	infoWindow.setContent(html);
	
	if (typeof vinfowindow != 'undefined') {
		vinfowindow.close();
	}
	vinfowindow = infoWindow;
	
	infoWindow.open(map, marker);
	map.setCenter(marker);
	map.addOverlay(marker);
  });
}

if ($(".gmap_list").length) {
	$(".gmap_list").each(function() {
		associated_map = $(this).attr("id");
		
		coordinates = $("#"+associated_map+"_coordinates").val();
		info_message = $("#"+associated_map+"_infowindow").val();
		
	    var coords = coordinates.split(',');
	    var centerMap = new google.maps.LatLng(parseFloat(coords[0]), parseFloat(coords[1]));

		var mapOpt = {
			zoom: 10,
			center: centerMap,
			mapTypeControl: false,
			streetViewControl: false,
			navigationControlOptions: {
			style: google.maps.NavigationControlStyle.ZOOM_PAN
			},
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		map = new google.maps.Map(document.getElementById(associated_map), mapOpt);
	
		marker = new google.maps.Marker({
			position: centerMap,
			map: map,
		});
	
		if (info_message) {
			var infowindow = new google.maps.InfoWindow();
			var info = info_message;
			bindInfoWindow(marker, map, infowindow, info);
		}
	});
};

if (typeof search_coordinates_fields != "undefined" || $("#infowindow").length) {
	if ($("#gps_coordinates").val()) {
	    var coords = $("#gps_coordinates").val().split(',');
	    var centerMap = new google.maps.LatLng(parseFloat(coords[0]), parseFloat(coords[1]));		
	    var predefCoords = true;
	} else {
	    var centerMap = new google.maps.LatLng(53.34410399999999, -6.267493699999932);
	    var predefCoords = false;
	}

	var mapOpt = {
		zoom: predefCoords ? 10 : 5,
		center: centerMap,
		mapTypeControl: false,
		streetViewControl: false,
		navigationControlOptions: {
		style: google.maps.NavigationControlStyle.ZOOM_PAN
		},
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	map = new google.maps.Map(document.getElementById("gmap"), mapOpt);

	if ($("#infowindow").length) {
		draggable = false;
	} else {
		draggable = true;
	}

	marker = new google.maps.Marker({
		position: centerMap,
		map: map,
		draggable: draggable
	});

	if ($("#infowindow").length) {
		var infowindow = new google.maps.InfoWindow();
		var info = $("#infowindow").val();
		bindInfoWindow(marker, map, infowindow, info);
	}

	google.maps.event.addListener(marker, 'dragend', function() {
		$("#gps_coordinates").val(marker.getPosition().lat()+", "+marker.getPosition().lng());
	});
	
	if (typeof search_coordinates_fields != "undefined") {
		$(search_coordinates_fields).live("change", function() {
			var geocoder = new google.maps.Geocoder();
			
			address_elements = [];
			
			field_list = search_coordinates_fields.split(",");
			
			for (var i=0; i < field_list.length; i++) {
				if ($(field_list[i]).val() != "") {
					address_elements.push($(field_list[i]).val());
				}
			}		
	
			if (address_elements) {
				address_elements.push("Ireland");
				
				geocoder.geocode({
					'address' : address_elements.join(", ")
				}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						if (results.length > 0) {
							map.setCenter(results[0].geometry.location);
							map.setZoom(12);
							marker.setPosition(results[0].geometry.location);
							$("#gps_coordinates").val(results[0].geometry.location.lat()+", "+results[0].geometry.location.lng());
						}
					}
				});
			}
		});
	}
}