<?php
    include "include/connection.php";

    $intUserId = $_POST['UserId'];
    $intProductId = $_POST['ProductId']; 
    $table = $_POST['tablename'];
    $flag = $_POST['flags'];
    $arrCart = [];
    $arrProduct = [];
    if($flag == 'user'):
        $sql1 = "Select * from tbl_cart where int_user_id = ".$intUserId." order by int_cart_id desc Limit 1";
        $arrCartQuery = mysqli_query($con, $sql1);
        $arrCart = mysqli_fetch_assoc($arrCartQuery);
    else:
        $sql1 = "Select * from tbl_cart where txt_session_id = '".$intUserId."' order by int_cart_id desc Limit 1";
        $arrCartQuery = mysqli_query($con, $sql1);
        $arrCart = mysqli_fetch_assoc($arrCartQuery);
    endif;
    if(empty($arrCart)):
        if($flag == 'user'):
            $sql = "INSERT INTO tbl_cart (int_user_id) VALUES (".$intUserId.")";
            $blCartQuery = mysqli_query($con, $sql);
        else:
            $sql = "INSERT INTO tbl_cart (txt_session_id) VALUES ('".$intUserId."')";
            $blCartQuery = mysqli_query($con, $sql);
        endif;
        if($blCartQuery):
         if($flag == 'user'):
            $sql1 = "Select * from tbl_cart where int_user_id = ".$intUserId." order by int_cart_id desc Limit 1";
            $arrCartQuery = mysqli_query($con, $sql1);
            $arrCart = mysqli_fetch_assoc($arrCartQuery);
         else:
            $sql1 = "Select * from tbl_cart where txt_session_id = '".$intUserId."' order by int_cart_id desc Limit 1";
            $arrCartQuery = mysqli_query($con, $sql1);
            $arrCart = mysqli_fetch_assoc($arrCartQuery);
         endif;
        endif;
    endif;
    if(!empty($arrCart)):
        if($table == 'product'){
            $sqlProduct = "Select *,substring_index(product.product_img, ',', 1) as product_img from product where id = ".$intProductId."";
            $arrProductquery = mysqli_query($con, $sqlProduct);
            $arrProduct= mysqli_fetch_assoc($arrProductquery);
            $sqlCartItem = "Select * from tbl_cart_item where int_product_id = ".$intProductId." and int_cart_id = ".$arrCart['int_cart_id']."";
            $arrCartItemQuery = mysqli_query($con, $sqlCartItem);
            $arrCartItem = mysqli_fetch_assoc($arrCartItemQuery);
            $int_product_id = $arrProduct['id'];
            $int_shop_images_id = null;
            $txt_title = $arrProduct['product_title'];
            $img = $arrProduct['product_img'];
        }
        if($table == 'shop_images'){
            $sqlProduct = "Select * from shop_images where id = ".$intProductId."";
            $arrProductquery = mysqli_query($con, $sqlProduct);
            $arrProduct= mysqli_fetch_assoc($arrProductquery);
            $sqlCartItem = "Select * from tbl_cart_item where int_shop_images_id = ".$intProductId." and int_cart_id = ".$arrCart['int_cart_id']."";
            $arrCartItemQuery = mysqli_query($con, $sqlCartItem);
            $arrCartItem = mysqli_fetch_assoc($arrCartItemQuery);
            $int_shop_images_id = $arrProduct['id'];
            $int_product_id = null;
            $txt_title = $arrProduct['title'];
            $img = $arrProduct['image'];
        }
           
        if(empty($arrCartItem)):
            if(empty($int_product_id)):
                $sql = "INSERT INTO tbl_cart_item (int_cart_id,txt_title,txt_image,size_width,dbl_quantity,dbl_base_price,dbl_total_price,int_shop_images_id) VALUES (".$arrCart['int_cart_id'].",'".$txt_title."','".$img."',".$arrProduct['size_width'].",1,".$arrProduct['price'].",".$arrProduct['price'].",".$int_shop_images_id.")";
                $arrCartItem = mysqli_query($con, $sql);
            else:
                $sql = "INSERT INTO tbl_cart_item (int_cart_id,txt_title,txt_image,size_width,dbl_quantity,dbl_base_price,dbl_total_price,int_product_id) VALUES (".$arrCart['int_cart_id'].",'".$txt_title."','".$img."',".$arrProduct['size_width'].",1,".$arrProduct['price'].",".$arrProduct['price'].",".$int_product_id.")";
                $arrCartItem = mysqli_query($con, $sql);
            endif; 
        endif;
    endif;
    if(!empty($arrCartItem)):
        echo "Data Inserted Successfully";
    endif;

?>