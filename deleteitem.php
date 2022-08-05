<?php
    include "include/connection.php";

    $intItemId = $_POST['intItemId'];
    $sql = "Delete from tbl_cart_item where int_cart_item_id = ".$intItemId."";
    $blDelete = mysqli_query($con, $sql);
    if(empty($blDelete)):
        echo "Cart Item Delete Successfully";
    endif;
?>