<!DOCTYPE html>
<html>
    <head>

    </head>
    <body>

    <div id="geocoder">
        <label for="lat">Latitude</label>
        <input id="lat" type="textbox" value="40.8367348">
        <label for="lon">Longitude</label>
        <input id="lon" type="textbox" value="14.24910692">
        <input id="submit" type="button" value="Geolocalizza">
    </div>

    <script>


        function init() {
            var geocoder = new google.maps.Geocoder();

            document.getElementById('submit').addEventListener('click', function () {
                getLocation(geocoder);
            });
        }

        function getLocation(geocoder){
            var lat = document.getElementById('lat').value;
            var lon = document.getElementById('lon').value;
            var resultAddress = document.getElementById('result');

            var latlng = {lat: parseFloat(lat), lng: parseFloat(lon)};
            geocoder.geocode({'location': latlng}, function(results, status){
                if (status === 'OK'){
                    if (results[0]){
                        window.alert(results[0].address_components[4].long_name);
                        //document.getElementById('json').value = results[0].address_components[4].long_name;
                        //resultAddress.setContent(results[0].formatted_address);
                    } else {
                        window.alert('Nessun risultato');
                    }
                } else {
                    window.alert('Geocoder failed due to: ' + status);
                }
            });
        }
    </script>


    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCEM2YvE9T6KXE9vn4ErYss3Pwi0_XCr-I&callback=init">

    </script>

    </body>