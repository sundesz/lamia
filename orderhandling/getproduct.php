<?php

include 'Includes/autoload.inc.php';

use orderhandling\Classes\ProductController;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

//get raw posted data
$data = json_decode(file_get_contents("php://input"));

$product = new ProductController();

echo $product->processInvoice($data);