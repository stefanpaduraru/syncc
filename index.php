<?php
require_once 'locationServices.class.php';

if (isset($_GET['address'])) {
    $address = $_GET['address'];
    $x = new LocationServices();
    echo $x->geocode($address);
}



?>