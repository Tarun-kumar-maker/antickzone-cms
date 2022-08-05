<?php
    include "../include/connection.php";
    if(isset($_POST['email_sign_in'])){
        $email_sign_in = $_POST['email_sign_in'];
        $pwd_sign_in = $_POST['pwd_sign_in'];

        $select = "SELECT * FROM `login` WHERE (`email`='$email_sign_in' or `phone`='$email_sign_in') and `password`='$pwd_sign_in' and `status`=1";
        $run = mysqli_query($con, $select);
        if(mysqli_num_rows($run) > 0){
            while($row = mysqli_fetch_assoc($run)){
                session_start();
                $session_id = session_id();
                $selectcart = "SELECT * FROM `tbl_cart` WHERE `txt_session_id`='$session_id'";
                $runcart = mysqli_query($con, $selectcart);
                $rowcart = mysqli_fetch_assoc($runcart);
                if(!empty($rowcart)):
                    $updatequery = "Update tbl_cart Set int_user_id = ".$row['id'].", txt_session_id = null";
                    $runupdatequery = mysqli_query($con,$updatequery);
                endif;
                $int_user_id = $row['id'];
                $_SESSION['userId'] = $int_user_id;
                $_SESSION['username'] = $row['username'];
                $_SESSION['cust_name'] = $row['cust_name'];
                $_SESSION['phone'] = $row['phone'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['password'] = $row['password'];
            }
            if(!empty($rowcart)){
                echo "checkout/".$int_user_id;
            }
            else{
               echo "1";
            }           
       }
       else{
            echo "0";
       }
    }
?>