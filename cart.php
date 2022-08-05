<?php

    include "include/check_login.php";
    include "include/connection.php";
    $arrProduct = [];
    $strquery = '';
    $flag = $_GET['flag'];

        if(($_GET['flag'] == 'session') && !empty($_GET['intUserId'])):
            $strquery =  " tbl_cart.txt_session_id = '".$_GET['intUserId']."'";
        else:
            $intUserId = $_GET['intUserId'];
            $strquery =  " tbl_cart.int_user_id = ".$_GET['intUserId']."";
        endif;

        if(!empty($strquery)):

                          $query =  "SELECT 
                                tbl_cart_item.txt_title,
                                tbl_cart_item.size_width,
                                tbl_cart_item.dbl_base_price,
                                tbl_cart_item.dbl_total_price,
                                tbl_cart_item.txt_image as product_img,
                                tbl_cart.int_cart_id,
                                tbl_cart_item.int_cart_item_id,
                                tbl_cart_item.dbl_quantity
                        FROM  
                                tbl_cart_item 
                                left join tbl_cart on tbl_cart_item.int_cart_id = tbl_cart.int_cart_id
                        WHERE 
                                 ".$strquery."";
                        $query = mysqli_query($con, $query);
                        $arrCartData = mysqli_fetch_all($query,MYSQLI_ASSOC); 
            endif;

					    $arrtotalprice = [];
					    $dbltotalprice = 0.00;
					    $dbltotalPriceWithGST = 0.00;
					    $query2 = "SELECT 
					                        sum(tbl_cart_item.dbl_total_price) as totalprice
					                FROM  
					                        tbl_cart_item 
					                        left join tbl_cart on tbl_cart.int_cart_id = tbl_cart_item.int_cart_id
					                where   
					                        ".$strquery."
					                GROUP BY
					                        tbl_cart_item.int_cart_id";
					    
					    $query2 = mysqli_query($con, $query2);
					    $arrtotalprice = mysqli_fetch_ASSOC($query2); 
					    if(!empty($arrtotalprice)):
					    $dbltotalproductprice = $arrtotalprice['totalprice'];
					    $dbltotalGST = (18 / 100) * $dbltotalproductprice;
					    $dbltotalPriceWithGST = $dbltotalproductprice + $dbltotalGST;
					    endif;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AntickZONE</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"/>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"/>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-straight/css/uicons-regular-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-solid-straight/css/uicons-solid-straight.css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"/>
</head>

<body class="custom-neon-body">


    <?php
        include "include/whatsapp_social_media.php";
        include "include/mobile_header.php";
        include "include/preloader.php";
        include "include/login_modal.php";
    ?>




    
    <div class="outer-container">
        <!-- <div class="menu_heading"> -->
            <?php
                include "include/header.php";
            ?>
        <!-- </div> -->



        
        <div class="container-fluid cart-section">
            <div class="row">
                <div class="col-md-10 col-11 mx-auto">
                    <div class="row gx-3">
                        <!-- left side div -->
                        <div class="col-md-12 col-lg-8 col-11 mx-auto main_cart mb-lg-0 mb-5 shadow">
                            <div class="card p-4">
                                <h2 class="py-4 font-weight-bold">Cart (<?=mysqli_num_rows($query)?> items)</h2>

                               
                             <?php foreach( $arrCartData as $keys => $values):    ?>
                                <div class="row" id="row<?=$values['int_cart_item_id']?>">
                                    <!-- cart images div -->
                                    <div class="col-md-5 col-11 mx-auto d-flex justify-content-start align-items-center product_img">
                                        <img src=<?=$values['product_img']?> class="img-fluid w-75" alt="cart img">
                                    </div>

                                    <!-- cart product details -->
                                    <div class="col-md-7 col-11 mx-auto px-4 mt-2">
                                        <div class="row">
                                            <!-- product name  -->
                                            <div class="col-md-6 col-12 card-title">
                                                <h1 class="mb-4 product_name"><?=$values['txt_title']?></h1>
                                                <p class="mb-2">Size: <?=$values['size_width'].'mm'?></p>
                                                <p class="mb-3"></p>
                                            </div>
                                            <!-- quantity inc dec -->
                                            <div class="col-md-6 col-12">
                                                <ul class="pagination justify-content-end set_quantity">
                                                    <li class="page-item">
                                                        <button class="page-link "
                                                            onclick="decreaseNumber('<?='textbox'.$values['int_cart_item_id']?>','<?= 'itemval'.$values['int_cart_item_id']?>','<?=($values['dbl_base_price'])?>','<?= $values['int_cart_item_id']?>')">
                                                            <ion-icon name="remove-outline"></ion-icon>
                                                        </button>
                                                    </li>
                                                    <li class="page-item"><input type="text" name="" class="page-link"
                                                            value='<?= $values['dbl_quantity']?>' id='<?='textbox'.$values['int_cart_item_id']?>'>
                                                    </li>
                                                    <li class="page-item">
                                                        <button class="page-link"
                                                            onclick="increaseNumber('<?='textbox'.$values['int_cart_item_id']?>','<?= 'itemval'.$values['int_cart_item_id']?>','<?=($values['dbl_base_price'])?>','<?= $values['int_cart_item_id']?>')">
                                                            <ion-icon name="add-outline"></ion-icon>
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <!-- //remover move and price -->
                                        <div class="row remove-price">
                                            <div class="col-md-8 col-12 d-flex justify-content-between remove_wish">
                                             <p><a onclick="deleteItem('<?=$values['int_cart_item_id']?>')"><ion-icon name="trash-outline"></ion-icon></i>Remove</a></p>
                                                <p>
                                                    <ion-icon name="heart-outline"></ion-icon></i>Wish List
                                                </p>
                                            </div>
                                            <div class="col-md-4 col-12 d-flex justify-content-end price_money">
                                                <h3>&#8377<span id='<?='itemval'.$values['int_cart_item_id']?>'><?=$values['dbl_total_price']?></span></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <hr />
                       <?php endforeach; ?>
                      </div>
                     	</div>

                        <!-- right side div -->
                        <div class="col-md-12 col-lg-4 col-11 mx-auto mt-lg-0 mt-md-5">
                            <div class="right_side p-3 shadow bg-white">
                                <h2 class="product_name mb-5">Total Amount</h2>
                                <div class="price_indiv d-flex justify-content-between">
                                    <p>Product amount</p>
                                    <p>&#8377<span id="product_total_amt"><?=$dbltotalproductprice?></span></p>
                                </div>
                                <hr />
                                <div class="total-amt d-flex justify-content-between font-weight-bold">
                                    <p>Total amount of (including GST)</p>
                                    <p>&#8377<span id="total_cart_amt"><?=$dbltotalPriceWithGST?></span></p>
                                </div>
                              <?php
                                if($flag =='session'): ?>
                                <a data-bs-toggle="modal" data-bs-target="#login_modal" target="_blank" class="text-decoration-none">
                                     <button  class="btn btn-primary text-uppercase">Checkout</button>
                                </a>
                               <?php else: ?>
                                <a target="_blank" href="PHP_Kit/CUSTOM_CHECKOUT_FORM_KIT/dataFrom.htm" id = "checkout" class="text-decoration-none">
                                     <button  class="btn btn-primary text-uppercase">Checkout</button>
                                </a>
                               <?php endif;  ?>
                            </div>

                            <!-- discount code part -->
                            <div class="discount_code mt-3 shadow">
                                <div class="card">
                                    <div class="card-body">
                                        <a class="d-flex justify-content-between text-decoration-none text-black" data-bs-toggle="collapse"
                                            href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                            Add a discount code (optional)
                                            <span>
                                                <ion-icon name="chevron-down-circle-outline"></ion-icon>
                                            </span>
                                        </a>
                                        <div class="collapse" id="collapseExample">
                                            <div class="mt-3">
                                                <input type="text" name="" id="discount_code1"
                                                    class="form-control font-weight-bold"
                                                    placeholder="Enter the discount code">
                                                <small id="error_trw" class="text-dark mt-3">Error</small>
                                            </div>
                                            <button class="btn btn-primary btn-sm mt-3"
                                                onclick="discount_code()">Apply</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- discount code ends -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





    <?php
        include "include/footer.php";
    ?>

    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" ></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" type="text/css" href="semantic/dist/semantic.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
        <script>
         $('#checkout').click(function(){
           var custname = "<?= $_SESSION['cust_name'] ?>"; 
            var userid = <?= $_SESSION['userId']?>;
           var totalamount = $("#total_cart_amt").text();
            $.ajax({
                type: 'POST',
                url: 'saveOrder.php',
                data: { CustomerName: custname,
                        UserId: userid,
                        TotalAmount: totalamount},
                success: function(response) {
                   
                }
            });
        });

        function deleteItem(intCartItemId){
            $.ajax({
                type: 'POST',
                url: 'deleteitem.php',
                data: {intItemId: intCartItemId},
                success: function(response) {
                    location.reload();
                }
            });
        }
    </script>

    <script src="js/cart.js"></script>


    <?php
        include "include/script.php";
    ?>
</body>

</html>