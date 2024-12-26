<?php 

include "../../connect.php" ; 
  
getAllData('ordersView' , "1 = 1 AND orders_status =  2 AND orders_type = 0") ; 

?>