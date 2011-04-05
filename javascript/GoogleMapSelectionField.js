(function($) {
	$(document).ready(function() {
        var methods = {
            appendResultToDom: function(point, zoom) {
                $("input[name=$Name_MapURL]").val("http://maps.google.com/?ll=" + point.toUrlValue() +"&q="+ point.toUrlValue() +"&z="+ zoom);
                $("input[name=$Name_MapLat]").val(point.lat());
                $("input[name=$Name_MapLng]").val(point.lng());
                $("input[name=$Name_MapZoom]").val(zoom);
            }
        }

		$("input[name=$Name_MapURL]").val("User did not generate a Url as the field is not required");
		$('#Form_Form').submit(function() {
			var checkval = $("input[name=$Name_MapURL]").val();
			if( checkval == "User did not generate a Url as the field is not required" && $("#EditableGoogleMapSelectableField38").attr("class") == "field googlemapselectable  requiredField"){
				alert("please click 'Go' to check address in the map");
				return false;
			}
		});

		var center = new google.maps.LatLng($DefaultLat, $DefaultLon);
		var map = new google.maps.Map(document.getElementById("map_$Name"), {
			zoom: $Zoom,
			center: center,
			scaleControl: 1,
			streetViewControl: false,
			mapTypeControlOptions: {
				style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
			},
			navigationControlOptions: {
				style: google.maps.NavigationControlStyle.SMALL
			},
			mapTypeId: google.maps.MapTypeId.ROADMAP
		});
        methods.appendResultToDom(center, map.getZoom());

		var geocoder = new google.maps.Geocoder();

		var marker = new google.maps.Marker({
			position: center,
			map: map,
			draggable: true
		});

		google.maps.event.addListener(marker, 'dragend', function(overlay, point) {
			var point = marker.getPosition();
			map.setCenter(point);
			geocoder.geocode( {'latLng': point } , function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					map.setCenter(results[0].geometry.location);
					$("input[name=$Name]").val(results[0].formatted_address);
					var loca = (results[0].geometry.location);
                    methods.appendResultToDom(point, map.getZoom());
				}
			});
		});

		$("input[name=$Name]").focus(function() {
			if($(this).val() == $(this).attr("value")) {
				$(this).val("");	
			}
		});
		$("input.googleMapAddressSubmit").click(function() {
			var address = $("input.googleMapAddressField").val();
			geocoder.geocode( { 'address': address }, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					var point = results[0].geometry.location;
					map.setCenter(results[0].geometry.location);
					marker.setPosition(results[0].geometry.location);
                    methods.appendResultToDom(point, map.getZoom());
				} else {
		 			alert(address + " not found");
		 		}
			});
			return false;
		});
	});
})(jQuery);