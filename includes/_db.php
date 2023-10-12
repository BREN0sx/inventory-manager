<?php
$db = mysqli_connect("localhost", "root", "", "storagedb");
if(!$db){
echo "[DB-ERR] :: ".
mysqli_connect_error() ;
}
?>