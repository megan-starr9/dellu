<?php
    include('header.php');
    
    $ipn_post_data = $_POST;
    
    $site->paypal_purch($ipn_post_data);
    
?>