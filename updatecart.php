<?php
    include "include/connection.php";

    $intitemId = $_POST['intitemid'];
    $quantity = $_POST['quantity']; 
    $totalproductamount = $_POST['totalamount'];

    if(!empty($intitemId)):

        $sql1 = " UPDATE tbl_cart_item SET dbl_quantity = ".$quantity.", dbl_total_price = ".$totalproductamount." WHERE int_cart_item_id = ".$intitemId." ";
        $updateitemQuery = mysqli_query($con, $sql1);
        if($updateitemQuery):
            echo " Update Cart Item successfully";
        endif;
    
    endif;
?>