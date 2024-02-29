<!DOCTYPE>
<html>
    <head>
        <title>Get Visitor's Current Location using PHP and JQuery</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <style type="text/css">
            #container{ color:  #116997; border: 2px solid #0b557b; border-radius: 5px;}
            p{ font-size: 15px; font-weight: bold;}
        </style>
    </head>
    <body>
        <script type="text/javascript">
            function getlocation() {
                if (navigator.geolocation) { 
                    if(document.getElementById('location').innerHTML == '') {
                        navigator.geolocation.getCurrentPosition(visitorLocation);
                    } 
                } else { 
                    $('#location').html('This browser does not support Geolocation Service.');
                }
            }
            function visitorLocation(position) {
                var lat = position.coords.latitude;
                var long = position.coords.longitude;
                $.ajax({
                    type:'POST',
                    url:'get_location.php',
                    data:'latitude='+lat+'&longitude='+long,
                    success:function(address){
                        if(address){
                           $("#location").html(address);
                        }else{
                            $("#location").html('Not Available');
                        }
                    }
                });
            }
        </script>
        <input type="button" onclick="return getlocation()" value="Get Current Location" />
        <div id="container"><p>Your Current Location: <span id="location"></span></p></div>
    </body>
</html>
    <?php
    if(!empty($_POST['latitude']) && !empty($_POST['longitude'])){
        //Send request and receive latitude and longitude
        $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($_POST['latitude']).','.trim($_POST['longitude']).'&sensor=false';
        $json = @file_get_contents($url);
        $data = json_decode($json);
        $status = $data->status;
        if($status=="OK"){
            $location = $data->results[0]->formatted_address;
        }else{
            $location =  'No location found.';
        }
        echo $location; 
    } 
?>