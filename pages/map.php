<?php 
session_start();
include("../external-php-scripts/database.php");

if (isset($_SESSION['home_address']) && isset($_SESSION['branch_location'])) {
    $home_address = $_SESSION['home_address'];
    $branch_location = $_SESSION['branch_location'];
}

?>


<!DOCTYPE html>
<html>
<head>
    <title>Google Maps Directions</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        #map {
            height: 500px;
            width: 100%;
        }
    </style>
</head>
<body>

<div id="map"></div>

<script>
    function initMap() {
        var map = new google.maps.Map(document.getElementById("map"), {
            zoom: 12,
            center: { lat: 43.7, lng: -79.4 } // Default center (Toronto area)
        });
        

        var directionsService = new google.maps.DirectionsService();
        var directionsRenderer = new google.maps.DirectionsRenderer({map: map});

        // Use address names instead of coordinates
        var request = {
            origin: '<?php echo $branch_location; ?>', // branch address from session
            destination: '<?php echo $home_address; ?>', // home address from session
            travelMode: google.maps.TravelMode.DRIVING
        };

        directionsService.route(request, function (result, status) {
            if (status === 'OK') {
                directionsRenderer.setDirections(result);
            } else {
                alert('Directions request failed due to ' + status);
            }
        });
    }
</script>

<!-- Load Google Maps API -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBrIBP9HXZrhsDUA7bCPqn9S-33AwSiR5U&callback=initMap"></script>

</body>
</html>
