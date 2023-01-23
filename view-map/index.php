
<?php
    if(isset($_GET['id'])){
        $postId = $_GET['id'];
        include('../php/connection.php');
        $con = connect();
        $query = $con->prepare('SELECT * FROM posts WHERE id = ?');
        $query->bind_param('i', $postId);
        $query->execute();
        $result = $query->get_result();
        $data = $result->fetch_assoc();
    }else{
        session_start();
        if(isset($_SESSION)){
            header('Location: ../panel.html');
        }else{
            header('Location: ../');
        }
        
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Map</title>
</head>
<body>
    <div id="map" style="width:100%;height:100vh; color: black"></div>
    <script>
        function myMap() {
            var mapProp= {
                center:new google.maps.LatLng(<?php echo $data['lat']?>,<?php echo $data['long']?>),
                zoom:5,
            };

            var marker = new google.maps.Marker({position: new google.maps.LatLng(<?php echo $data['lat']?>,<?php echo $data['long']?>)});

            
            var map = new google.maps.Map(document.getElementById('map'),mapProp);
            marker.setMap(map)
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCfmT-QA7SbXonWrfaQRNd5l-oraja6MHE&callback=myMap"></script>
</body>
</html>