<?php
    include('header.php');
    
    if (isset($_GET['tx']))
    {
        $pp_hostname = "www.paypal.com"; // Change to www.sandbox.paypal.com to test against sandbox
     
        // read the post from PayPal system and add 'cmd'
        $req = 'cmd=_notify-synch';
         
        $tx_token = $_GET['tx'];
        $auth_token = "L96pA2g05__BsPPZOH3kEOdeXbKUEl3KKn6McW4uC6qFgPh0SXD3EDLpmAG";
        $req .= "&tx=$tx_token&at=$auth_token";
         
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://$pp_hostname/cgi-bin/webscr");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        //set cacert.pem verisign certificate path in curl using 'CURLOPT_CAINFO' field here,
        //if your server does not bundled with default verisign certificates.
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: $pp_hostname"));
        $res = curl_exec($ch);
        curl_close($ch);
         
        if(!$res){
            //HTTP ERROR
        }else{
            $error = '';
             // parse the data
            $lines = explode("\n", $res);
            $keyarray = array();
            if (strcmp ($lines[0], "SUCCESS") == 0) {
                for ($i=1; $i<count($lines);$i++){
                list($key,$val) = explode("=", $lines[$i]);
                $keyarray[urldecode($key)] = urldecode($val);
            }
            // check the payment_status is Completed
            // check that txn_id has not been previously processed
            // check that receiver_email is your Primary PayPal email
            // check that payment_amount/payment_currency are correct
            // process payment
            $firstname = $keyarray['first_name'];
            $lastname = $keyarray['last_name'];
            $itemname = $keyarray['item_name'];
            $amount = $keyarray['payment_gross'];
            
            // TEMP
            if ($keyarray['item_id'] == 'BOB_5_for_5')
            {
                $query = "UPDATE users SET sitecash = (".$currentuser->sitecash."+5) WHERE userid = ".$currentuser->userid;
                $error = (!mysql_query($query)) ? "<div id='warning'>Oh no, there has been an issue delivering your Bobs! <br>Take a screen capture of this message that
                    includes your system's clock so that an administrator may get you your 5 Bobs!" : "<div id='alert'><p>Thank you for your purchase! An email from Paypal will be sent to you with details of the transaction.
                    <br>5 bobs have been credited to your account! (You may need to navigate away from this page to see this).</p></div>";
            }
            if ($keyarray['item_id'] == 'BOB_10_for_10')
            {
                $query = "UPDATE users SET sitecash = (".$currentuser->sitecash."+10) WHERE userid = ".$currentuser->userid;
                $error = (!mysql_query($query)) ? "<div id='warning'>Oh no, there has been an issue delivering your Bobs! <br>Take a screen capture of this message that
                    includes your system's clock so that an administrator may get you your 10 Bobs!" : "<div id='alert'><p>Thank you for your purchase! An email from Paypal will be sent to you with details of the transaction.
                    <br>10 bobs have been credited to your account! (You may need to navigate away from this page to see this).</p></div>";
            }
            
            // PACKAGES
            if ($keyarray['item_id'] == 'BOB_30_for_25')
            {
                $query = "UPDATE users SET sitecash = (".$currentuser->sitecash."+30) WHERE userid = ".$currentuser->userid;
                $error = (!mysql_query($query)) ? "<div id='warning'>Oh no, there has been an issue delivering your Bobs! <br>Take a screen capture of this message that
                    includes your system's clock so that an administrator may get you your 30 Bobs!" : "<div id='alert'><p>Thank you for your purchase! An email from Paypal will be sent to you with details of the transaction.
                    <br>30 bobs have been credited to your account! (You may need to navigate away from this page to see this).</p></div>";
            }
            else if ($keyarray['item_id'] == 'BOB_60_for_50')
            {
                $query = "UPDATE users SET sitecash = (".$currentuser->sitecash."+60) WHERE userid = ".$currentuser->userid;
                $error = (!mysql_query($query)) ? "<div id='warning'>Oh no, there has been an issue delivering your Bobs! <br>Take a screen capture of this message that
                    includes your system's clock so that an administrator may get you your 60 Bobs!" : "<div id='alert'><p>Thank you for your purchase! An email from Paypal will be sent to you with details of the transaction.
                    <br>60 bobs have been credited to your account! (You may need to navigate away from this page to see this).</p></div>";
            }
            else if ($keyarray['item_id'] == 'BOB_120_for_100')
            {
                $query = "UPDATE users SET sitecash = (".$currentuser->sitecash."+120) WHERE userid = ".$currentuser->userid;
                $error = (!mysql_query($query)) ? "<div id='warning'>Oh no, there has been an issue delivering your Bobs! <br>Take a screen capture of this message that
                    includes your system's clock so that an administrator may get you your 120 Bobs!" : "<div id='alert'><p>Thank you for your purchase! An email from Paypal will be sent to you with details of the transaction.
                    <br>120 bobs have been credited to your account! (You may need to navigate away from this page to see this).</p></div>";
            }
            echo $error;
            }
            else if (strcmp ($lines[0], "FAIL") == 0) {
                // log for manual investigation
            }
        }
    }
    
    if (isset($_POST['purchase']))
    {
        $error = '';
        // Temporary until selection fixed
        if (isset($_POST['temp1']))
        {
            $url = "https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=YPC4L5NBJBYFW";
        }
        if (isset($_POST['temp2']))
        {
            $url = "https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=2WAPE5BK6SQVA";
        }
        
        // Package deals
        if (isset($_POST['package1']))
        {
            $url = "https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=59HNHGA5G3894";
        }
        else if (isset($_POST['package2']))
        {
            $url = "https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=NJY5P68HMN93L";
        }
        else if (isset($_POST['package3']))
        {
            $url = "https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7NBEPF5LVJUVW";
        }
        
        if (!isset($url))
        {
            $error = "<div id='warning'>You must select a package!</div>";
        }
        else if (!isset($_POST['tos']))
        {
            $error = "<div id='warning'>You must agree to the Terms of Service.</div>";
        }
        else
        {
            header('Location:'.$url);
        }
        
        echo $error;
    }
    
?>
        <h1 class='pagetitle'>Purchase Bobs</h1>
        <form class='prettyform' action='' method='post'>
            <table style='vertical-align:top;'>
            <tr>
                <td width='300px'>
                    <h4>Purchase a specific number...</h4>
                    <p>COMING SOON!</p>
                    <!-- <br># of Bobs: <input type='text' name='quantity'></input></p> -->
                    <p>For now, these two options are available:
                    <br> 5 Bobs for 5$ <input type='checkbox' name='temp1'></input>
                    <br> 10 Bobs for 10$ <input type='checkbox' name='temp2'></input>
                </td>
                <td width='100px'>
                    <p>or...</p>
                </td>
                <td width='300px'>
                    <h4>Select a package</h4>
                    <p>30 Bobs for 25$ <input type='checkbox' name='package1'></input>
                    <br>60 Bobs for 50$ <input type='checkbox' name='package2'></input>
                    <br>120 Bobs for 100$ <input type='checkbox' name='package3'></input></p>
                </td>
            </tr>
            </table>
            <br>
            <p>Send Bobs to another user: #<input type='text' name='user' style='width:60px'></input>
            <br>(If left blank they will go to your account.)</p>
            <p><input type='checkbox' name='tos'></input>I have read and understand the Terms of Service and how they apply to this transaction.</p>
            <input type='submit' value='Purchase Bobs' name='purchase'>
        </form>