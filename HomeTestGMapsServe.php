

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.4/jquery.mobile-1.4.4.min.css" />
<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="http://code.jquery.com/mobile/1.4.4/jquery.mobile-1.4.4.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">
<script src="https://maps.googleapis.com/maps/api/js?sensor=true"></script>
<script src = "Gmaps.js"></script>
<script type="text/javascript">
   
    $(document).ready(function(){
	var map;
    var Ulat,Blat;
    var Ulng,Blng;
    var address;
    var path = [];
    var ll;
    var interval;
      map = new GMaps({
        div: '#map',
        lat: 12.043333,
        lng: 77.028333,
        zoom: 15
      });
      GMaps.geolocate({
  success: function(position) {
    map.setCenter(position.coords.latitude, position.coords.longitude);
    Ulat = position.coords.latitude;
    Ulng = position.coords.longitude;
    map.addMarker({
    lat: position.coords.latitude,
    lng: position.coords.longitude,
    title: 'User',
    infoWindow: {
  content: '<p>You are here</p>'
}
    });
  },
  error: function(error) {
    alert('Geolocation failed: '+error.message);
  },
  not_supported: function() {
    alert("Your browser does not support geolocation");
  },
  always: function() {
	 
  }
});


$("#Srch").click(function(){
	
	busno = $("#busnum").val();
	
	function Repeat()
	{
	
	map.removeMarkers();
	map.removePolylines();
	GMaps.geolocate({
  success: function(position) {
    map.setCenter(position.coords.latitude, position.coords.longitude);
    Ulat = position.coords.latitude;
    Ulng = position.coords.longitude;
    map.addMarker({
    lat: position.coords.latitude,
    lng: position.coords.longitude,
    title: 'User',
    infoWindow: {
  content: '<p>You are here</p>'
}
    });
  },
  error: function(error) {
    alert('Geolocation failed: '+error.message);
  },
  not_supported: function() {
    alert("Your browser does not support geolocation");
  },
  always: function() {
	 
  }
});
	$.post("http://www.myexps93.esy.es/GetBusServe.php",{ val:busno},function(data,status){
		if(status == "success")
		{
			
			if(data == 1001)
			{
				
				alert("Please enter valid Bus number");
			}
		
			pos = data;
			posarr = pos.split("/");
			Blat = posarr[0];
			Blng = posarr[1];
			$.post("http://www.myexps93.esy.es/GBusRoutesServe.php",{val:busno},function(data,status){
			if((status == "success")&&(data!="{}"))
			{
				
				latlngs = data.split("--");
				
				for(i =0;i< latlngs.length-1;i++)
				{	ll = latlngs[i].split(",");
					
					path[i] = [ll[0], ll[1]];
				}
				map.drawPolyline({
				path: path,
				strokeColor: '#131540',
				strokeOpacity: 0.8,
				strokeWeight: 7
					});
			}
			
			});
				
			geocoder = new google.maps.Geocoder();
			var latlng = new google.maps.LatLng(parseFloat(Blat), parseFloat(Blng))
			geocoder.geocode({'latLng': latlng}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
			if (results[1]) {
				address = results[1].formatted_address;
				
				map.addMarker({
				lat: posarr[0],
				lng: posarr[1],
				animation: google.maps.Animation.BOUNCE,
				icon:'dboy.gif',
				title: busno,
				infoWindow: {
				content: '<p>'+address+'</p>'
}});
				map.setZoom(13);
				
				}
			
			}
			});
			
	

			
		}
		
		
		});
}	



Repeat();
interval = setInterval( Repeat, 36000);

	
	});      

$("#Rst").click(function(){
	
map.removeMarkers();
map.removePolylines();
map.setZoom(12);
clearInterval(interval);
});

$("#Find").click(function(){

      GMaps.geolocate({
  success: function(position) {
    map.setCenter(position.coords.latitude, position.coords.longitude);
    Ulat = position.coords.latitude;
    Ulng = position.coords.longitude;
    map.addMarker({
    lat: position.coords.latitude,
    lng: position.coords.longitude,
    title: 'User',
    infoWindow: {
  content: '<p>You are here</p>'
}
    });
  },
  error: function(error) {
    alert('Geolocation failed: '+error.message);
  },
  not_supported: function() {
    alert("Your browser does not support geolocation");
  },
  always: function() {
	 
  }
});
map.setZoom(15);	
});
});
  </script>
</head>
<body>
<div data-role = "page">
			  				<div data-role="panel" id="myPanel" data-display = "overlay">
		
		  <a href="#" data-rel="close" class="ui-btn  ui-shadow" id = "Find" >Find me</a>
  <a href="#" data-rel="close" class="ui-btn  ui-shadow" id = "Rst" name = "Rst">Reset Map</a>
  <a href="#OptPage" data-rel = "popup" data-transition = "flow" class="ui-btn  ui-shadow" name = "Opt" id = "Opt">Search</a>
  <a href="#pageone" data-rel="close" class="ui-btn  ui-shadow ui-corner-all ui-btn-a ui-icon-delete ui-btn-icon-left">Close</a>
</div>
    <div data-role = "header">
		
		 
		<div data-role="popup" id="OptPage">
			<div data-role="header">
			<h1>Enter Bus:</h1>
			</div>
			<div data-role="main" class="ui-content">
			<form>
			   <div class="ui-field-contain">
					<label for="busnum">Bus Number:</label>
					<input type="text" name="busnum" id="busnum">
				</div>
		   </form>
		   <a href = "#" data-rel = "back" class="ui-btn ui-shadow ui-corner-all ui-icon-search ui-btn-icon-left" id = "Srch" name = "Srch">Search</a>
		   </div>
		</div>
<h2>MapTest</h2>
</div>
<div data-role = "main" class = "ui-content">
		<a href="#myPanel" class="ui-btn">Menu</a>
<div id = "map" style = "height:400px;width:100%"></div>	 
</div> 
<div data-role = "footer">
		<h2> Footer-text</h2>
    </div>
</div>
	  
   
</body>

</html>

