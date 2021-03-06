
<?php
require_once 'Connection.php';

class Food{
    public $ID;
    public $Provider;
    public $Name;
    public $Quantity;
    public $UnitPrice;
    public $Image;
    public $LocationX;
    public $LocationY;
    public $Date;
    public $Description;
}
$conn = new mysqli($servername, $username, $password,$DB);
$sql = "select * from foods";
$result = $conn->query($sql);
$Foods = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $f = new Food();
        $f->ID = $row['ID'];
        $f->Provider = $row['Provider'];
        $f->Name = $row['Name'];
        $f->Quantity = $row['Quantity'];
        $f->UnitPrice = $row['UnitPrice'];
        $f->Image = $row['Image'];
        $f->LocationX = $row['LocationX'];
        $f->LocationY = $row['LocationY'];
        $f->Date = $row['Date'];
        $f->Description = $row['Description'];
        array_push($Foods, $f);
    }
}
else {
    echo "0 results";
}
$conn->close();
$JObject = json_encode($Foods);
?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title></title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script>
        function getLocation() {
            // Geolocation

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(ShowPosition);
            } else {
                //Geolocation is not supported by this browser
            }
        }
        var X = 0;
        var Y = 0;
        var MarkCount = <?php echo count($Foods)?>;
        var uluru;
        var map;
        var marker;
        var infowindow;
		var markers = new Array();
		var indexes = new Array();
        function ShowPosition(Position) {
            var jo = <?php echo json_encode($Foods);?>;


				uluru = { lat: Position.coords.latitude, lng: Position.coords.longitude };
				map = new google.maps.Map(
		                document.getElementById('map'), { zoom: 13, center: uluru });
				marker = new google.maps.Marker({ position: uluru, map: map, icon:'CorrentLocation.png'});


	            for(i = 0;i<MarkCount;i++)
	            {
	            	//alert(typeof jo[i].LocationX);
	            	var u = { lat: parseFloat(jo[i].LocationX), lng:parseFloat(jo[i].LocationY) };

					marker = new google.maps.Marker({ position: u, map: map, icon:'fooddish.svg'});
					markers.push(marker);
					indexes.push(i);

		            marker.addListener('click', function () {
		                // 3 seconds after the center of the map has changed, pan back to the
		                // marker.

		                SetModel(this);
		            	modal.style.display = "block";

		            });
		           // markers.push(marker);
		            //indexes.push(i);
	            }

	        function SetModel(obj)
	        {
		        alert(obj);
		        for(i = 0;i<indexes.length;i++)
		        {
			        if(marker[i]===obj){
						alert("yes");
				        }
		        }
	        }
        }
    </script>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCMuKlMQOFDBbMSALD9aLojY7upNA9xGBk&callback=initMap">
    </script>
    <style>
        /* Set the size of the div element that contains the map */
        html, body {
            height: 100%;
            margin: 0;
          }
          .navbar {
            background-color: #000;
            position: fixed;
            top: 0;
            opacity: 0.5;
            filter: alpha(opacity=50); /* For IE8 and earlier */
            width: 100%;
            direction: rtl;
        }
          .navbar a, img {
            float: right;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 27px;
          }

          .navbar a:hover {
            background: #ddd;
            color: black;
          }

        #map {
            height: 100%; /* The height is 400 pixels */
            width: 100%; /* The width is the width of the web page */
        }
        /* The Modal (background) */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            -webkit-animation-name: fadeIn; /* Fade in the background */
            -webkit-animation-duration: 0.4s;
            animation-name: fadeIn;
            animation-duration: 0.4s
        }

        /* Modal Content */
        .modal-content {
            position: fixed;
            bottom: 0;
            background-color: #fefefe;
            width: 100%;
            -webkit-animation-name: slideIn;
            -webkit-animation-duration: 0.4s;
            animation-name: slideIn;
            animation-duration: 0.4s
        }

        /* The Close Button */
        .close {
            color: white;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-header {
            padding: 2px 16px;
            background-color: #5cb85c;
            color: white;
        }

        .modal-body {padding: 2px 16px;}

        .modal-footer {
            padding: 2px 16px;
            background-color: #5cb85c;
            color: white;
        }

        /* Add Animation */
        @-webkit-keyframes slideIn {
            from {bottom: -300px; opacity: 0}
            to {bottom: 0; opacity: 1}
        }

        @keyframes slideIn {
            from {bottom: -300px; opacity: 0}
            to {bottom: 0; opacity: 1}
        }

        @-webkit-keyframes fadeIn {
            from {opacity: 0}
            to {opacity: 1}
        }

        @keyframes fadeIn {
            from {opacity: 0}
            to {opacity: 1}
        }
    </style>

</head>
<body onload="getLocation()">
    <div id="map"></div>
    <div class="navbar">
           <a href="#home"></a>
           <img src="flat-11-512.png" width="60px;"/>
           <a href="#home">ADD</a>
    </div>



    <!-- The Modal -->
    <<div id="myModal" class="modal">

      <!-- Modal content -->
      <div class="modal-content">
        <div class="modal-header">
          <span class="close">&times;</span>
        </div>
        <div class="modal-body">
          <p style="width: 60%; text-align: right; padding: 16px; float:right">Some text in the Modal Body</p>
          <p style="width: 40%;"><img src="kjj" wwidth="200px" height="200px"</p>
        </div>
      </div>

    </div>

    <script>
    // Get the modal
    var modal = document.getElementById('myModal');

    // Get the button that opens the modal

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal


    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
    </script>


</body>
</html>
