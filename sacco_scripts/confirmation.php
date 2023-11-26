<?php

include_once('config.php');
include_once('Utilities.php');

echo "Acccount Balance Response Entry Point\n";
$balanceResponse=file_get_contents('php://input');
$conn=SaccoDB();
$data  = json_decode($balanceResponse,true);
$file = fopen("b2c.txt", "w");
if(fwrite($file, $data) === FALSE){
  fwrite("Error: no data written");
}
fwrite("\r\n");
fclose($file);

print_r($data);