<?php include('../header.php');

    if ($id == -1)
    {
        header('Location:'.$site->baseurl.'/');
    }
    else
    {
        echo "<h1 class='pagetitle'>Inventory</h1>";
        
        $useritemquery = mysql_query("SELECT * FROM item__user WHERE userid = '$id'");
        $num = mysql_num_rows($useritemquery);
        if ($num == 0)
        {
            echo "<p>You have no items.</p>";
        }
        else
        {
            while ($item = mysql_fetch_array($useritemquery))
            {
                $itemquery = mysql_query("SELECT * FROM item__list WHERE itemid = '".$item['itemid']."'");
                $iteminfo = mysql_fetch_array($itemquery);
                $typequery = mysql_query("SELECT * FROM item__type WHERE typeid = ".$iteminfo['itemtype']);
                $typeinfo = mysql_fetch_array($typequery);
                echo "<div id='itembox'>
                    <img width='150' src='".$site->baseurl."/images/items/".$typeinfo['typename']."/".$iteminfo['itemname'].".png'>
                    <p>".$iteminfo['itemname']."<br>
                    Quantity: ".$item['quantity']."</p>
                </div>";
            }
        }
        
    }

include('../footer.php')

    /******************************************* Inventory Functions *********************************************/
    
?>