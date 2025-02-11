<?php 

include "../../connect.php" ; 
  
getAllData('ordersView' , "1 = 1 AND orders_status =  2") ; 

?>