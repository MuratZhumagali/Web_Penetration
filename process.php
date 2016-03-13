<?php
            if(isset($_GET["street_address"]) and isset($_GET["city_name"]) and isset($_GET["state_name"]) and isset($_GET["measurement"]))
            {
                        $err_string = "";
                        $street_address = $_GET["street_address"];
                        $city_name = $_GET["city_name"];
                        $state_name = $_GET["state_name"];
                        $measurement = $_GET["measurement"];
                
                        function getGeoCode ($street_address, $city_name, $state_name) {
                            $gmaps_url = 'https://maps.googleapis.com/maps/api/geocode/xml?address=' . urlencode(trim($street_address) . "," . trim($city_name) . "," . $state_name) . "&key=AIzaSyBlP8q-xaBig9FnerD3Cvxkoo6qBM8uzHY";
                            $xmlArray = simplexml_load_string(file_get_contents($gmaps_url)) or die("Cannot load xml from GMAPS API");
                            if ($xmlArray === false) {
                                $err_string .= "Failed loading XML.";
                                foreach(libxml_get_errors() as $error) {
                                    $err_string .= "'" . $error->message . "'";
                                }
                            } elseif ($xmlArray->status == 'OK') {
                                $latitude = $xmlArray->result[0]->geometry->location->lat;
                                $longitude = $xmlArray->result[0]->geometry->location->lng;
                            }
                            if (isset($latitude) and isset($longitude)) {
                                return array($latitude, $longitude);
                            } else {
                                throw new Exception("Google Maps. No coordinates were retrieve. Must be Google Maps API did not return coordinates. Please specify your request, change it or try later.");
                            }
                        }

                        function getForecastbyCode($lat, $lng, $measurement) {
                            if ($lat != "" and $lng != "") {
                                $io_url = 'https://api.forecast.io/forecast/b0648fd9fa93c6a7669ed60694c4bea6/' . $lat . ',' . $lng . "?units=" . $measurement . "&exclude=flags,minutely,alerts";
                                $response = file_get_contents($io_url) or die("Cannot retrieve data from Forecast API. Please try later.");
                                if (isset($response)) {
                                    print_r($response);
                                } else {
                                    throw new Exception("Forecast.io. No received data from Forecast.io API. Please try later.");
                                }
                            } else {
                                throw new Exception("Forecast.io. No coordinates were supplied. Must be Google Maps API did not return coordinates. Please try later.");
                            }
                        }
                        try {
                            list($lat, $lng) = getGeoCode($street_address, $city_name, $state_name);
                            getForecastbyCode($lat, $lng, $measurement);
                        } catch (Exception $ex) {
                            $err_string .= "'ErrorMessage:" . $ex->getMessage() . "'";
                            echo "{'Error': " . $err_string . "}";
                        }
            }
?>