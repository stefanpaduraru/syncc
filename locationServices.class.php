<?php
/**
 * Please write a PHP/Golang based solution without using any frameworks to fulfil following needs:
 
 Requirements: 
 - Establish a connection to google maps API 
 - Provide an interface accepting any location request (New York city, Berlin Germany, Mystreet 14 Frankfurt Germany, etc.) 
 - Implement data response (country, state, county, city, etc.) 
 - No frontend needed.
 
 Bonus: 
 - Possibility to run the solution in Docker container 
 - Documentation 
 - Tests

Stefan P:
 - What I understand from this request is that I should create some geocoding functionality using the Google Maps API.
*/

/**
 * geocodeResponse class. Provides structure for geocode operations response.
 *
 */
class geocodeResponse {
    private $status;
    private $description;
    private $results;
    
    public function __construct() {
        $this->status = '';
        $this->description = '';
        $this->results = [];
    }
    
    /**
     * Sets response status
     * @param string $status
     */
    public function setStatus($status) {
        $this->status = $status;
    }
    
    /**
     * Sets response status to 'error'
     */
    public function setStatusError() {
        $this->status = 'error';
    }
    
    /**
     * Sets response status to 'OK'
     */
    public function setStatusOK() {
        $this->status = 'OK';
    }
    
    /**
     * Sets response description
     */
    public function setDescription($description) {
        $this->description = $description;
    }
    
    /**
     * Sets google maps api results
     */
    public function setResults($results) {
        $this->results = $results;
    }
    
    /**
     * Outputs object in json format
     * @return string
     */
    public function __toString() {
        try {
            $data = (object)array('status' => $this->status, 'description'=>$this->description, 'results' => $this->results);
            return json_encode($data);
        } catch (Exception $exception) {
            return '';
        }
    }
}


/**
 *
 * Location Services class. Provides geolocation services based on the google maps api
 *
 */
class LocationServices {
    private $gmapsEndPoint = 'https://maps.googleapis.com/maps/api/geocode/json?address=%s&key=%s';
    private $gmapsApiKey = 'AIzaSyC7o7H3OJZoWMTq5-JdRIwsFpyTptAUa_4';
    
    public function __construct() {
       $this->gmapsEndPoint = sprintf($this->gmapsEndPoint, '%s', $this->gmapsApiKey);  
    }
    
    /**
     * /**
     * Geocodes an address using the google maps api. Returns geocodeResponse object (status, description, google response) 
     * @param string $address
     * @return geocodeResponse
     */
    public function geocode($address = '') {
        $response = new geocodeResponse();
        
        # return error if invalid address
        if (!strlen($address)) {
           $response->setStatusError();
           $response->setDescription('Please input a valid address.');
        }
        
        # prepare url to fetch
        $endPoint = sprintf($this->gmapsEndPoint, urlencode($address));
        
        # call google maps api
        try {
            $jsonResponse = file_get_contents($endPoint);
            $geoResponse = json_decode($jsonResponse);
        } catch (Exception $e) {
            $response->setStatusError();
            $response->setDescription('Couldn\'t get response from google api.');
            return $response;
        }
        
        switch ($geoResponse->status) {
            case 'OK':
                $response->setStatusOK();
                $response->setResults($geoResponse->results);
                return $response;
                break;
                
            case 'ZERO_RESULTS':
                $response->setStatusError();
                $response->setDescription('Request returned no results.');
                $response->setResults($geoResponse->results);
                return $response;
                break;
                
            default:
                $response->setStatusError();
                $response->setDescription($geoResponse->status);
                $response->setResults($geoResponse->results);
                return $response;
                break;
        }
    }
}
?>