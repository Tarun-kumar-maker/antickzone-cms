<?php
    include "include/connection.php";

     $UserId = $_POST['UserId'];
     $customerName = $_POST['CustomerName'];
     $totalAmount = $_POST['TotalAmount'];
     $arrCart = [];
     $arrCartItem = [];
     if(!empty($UserId)):
        $query = "select tbl_cart.int_cart_id from tbl_cart where tbl_cart.int_user_id = ".$UserId."";
        $queryCart = mysqli_query($con, $query);
        $arrCart = mysqli_fetch_assoc($queryCart);
     endif;
     if(!empty($arrCart)):
        $date =  date("Y-m-d H:i:s");
        $sql = "INSERT INTO tbl_order (int_user_id,txt_name,dbl_total_amount,dat_order) VALUES (".$UserId.",'".$customerName."',".$totalAmount.",'".$date."')";
        $sqlquery =  mysqli_query($con, $sql);
        $query2 = "select tbl_order.int_order_id from tbl_order where tbl_order.int_user_id = ".$UserId." order by int_order_id desc Limit 1";
        $queryOrder = mysqli_query($con, $query2);
        $arrOrder = mysqli_fetch_assoc($queryOrder);    
        $query = "select 
                         tbl_cart_item.dbl_base_price,
                         tbl_cart_item.dbl_total_price,
                         tbl_cart_item.dbl_quantity,
                         tbl_cart_item.txt_title,
                         tbl_cart_item.int_product_id,
                         tbl_cart_item.int_shop_images_id,
                         tbl_cart_item.txt_image
                  from 
                         tbl_cart_item
                  Where
                        tbl_cart_item.int_cart_id = ".$arrCart['int_cart_id']."
                  Order By
                        tbl_cart_item.int_cart_item_id asc";
       
        $query = mysqli_query($con, $query);
        $arrCartItem = mysqli_fetch_all($query,MYSQLI_ASSOC);
        if(!empty($arrCartItem)):
           foreach($arrCartItem as $key => $values):
           if(!empty($values['int_product_id'])):
            $sql3 = "INSERT 
                            INTO 
                    tbl_order_detail 
                    (int_order_id,int_product_id,txt_title,dbl_quantity,dbl_base_price,dbl_total_price,txt_image) VALUES (".$arrOrder['int_order_id'].",".$values['int_product_id'].",'".$values['txt_title']."',".$values['dbl_quantity'].",".$values['dbl_base_price'].",".$values['dbl_total_price'].",'".$values['txt_image']."')";
           else:
            $sql3 = "INSERT 
                                INTO 
                        tbl_order_detail 
                        (int_order_id,txt_title,dbl_quantity,dbl_base_price,dbl_total_price,txt_image,int_shop_images_id) VALUES (".$arrOrder['int_order_id'].",'".$values['txt_title']."',".$values['dbl_quantity'].",".$values['dbl_base_price'].",".$values['dbl_total_price'].",'".$values['txt_image']."',".$values['int_shop_images_id'].")";
           endif;
           $orderdetailquery = mysqli_query($con, $sql3);
           endforeach;
        endif;

        //    $sql4 = "Delete from tbl_cart_item where tbl_cart_item.int_cart_id = ".$arrCart['int_cart_id']."";
        //    $query = mysqli_query($con, $sql4);
        //    $sql5 = "Delete from tbl_cart where tbl_cart.int_cart_id = ".$arrCart['int_cart_id']."";
        //    $query = mysqli_query($con, $sql5);
     endif;
?>