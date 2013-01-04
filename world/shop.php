<?php include('../header.php');
    if ($id == -1)
    {
        header('Location:'.$site->baseurl.'/');
    }
    else
    {
            $uri = $_SERVER['REQUEST_URI'];
            $segment = explode("/", $uri);
            $shopid = (isset($segment[3])) ? $segment[3] : 0;
            
            $error = '';
            if ($shopid == 0)
            {
                ?>
                
                <h1 class='pagetitle'>Shops</h1>
                
                <?php
                $shopcatquery = mysql_query("SELECT * FROM shop__category");
                while ($shopcat = mysql_fetch_array($shopcatquery))
                {
                    $cat = new Shop_Category($shopcat['id']);
                    echo "<h2 class='minortitle'>".$cat->name."</h2>";
                    foreach ($cat->shops as $shop)
                    {
                        echo "<p><a href='".$site->baseurl."/world/shop/".$shop->id."'>".$shop->name."</a></p>";
                    }
                }
            }
            else
            {
                $shopquery = mysql_query("SELECT * FROM shop__list WHERE id = '$shopid'");
                if (mysql_num_rows($shopquery) == 0)
                {
                    echo "<p>This shop does not exist!</p>";
                }
                else
                {
                    if (isset($_POST['petchosen']))
                    {
                        $pet = new Creature(mysql_real_escape_string($_POST['shoppet']));
                        $gender = mysql_real_escape_string($_POST['gender']);
                        
                        $error = (valid_pet_purchase($shopid, $pet->colorid)) ? $pet->purchase($gender, 1) : "<div id='warning'>That pet isn't for sale here.</div>";
                    }
                    else if (isset($_POST['itemchosen']))
                    {
                        $item = new Item(mysql_real_escape_string($_POST['shopitem']));
                        $quantity = mysql_real_escape_string($_POST['quantity']);
                        
                        $error = (valid_item_purchase($shopid, $item->id)) ? $item->purchase($quantity) : "<div id='warning'>That item isn't for sale here.</div>";
                    }
                    echo $error;
                    
                    $shop = new Shop($shopid);
                    echo "<h1 class='pagetitle'>".$shop->name."</h1>";
                    
                    if(sizeof($shop->items) != 0)
                    {
                        echo "<h2 class='minortitle'>Items</h2><br>";
                        foreach($shop->items as $item)
                        {
                            if($item->pricereal == 0)
                            {
                                $itemprice = $item->pricesite." Bits ";
                            }
                            else
                            {
                                $itemprice = $item->pricereal." Bobs ";
                            }?>
                            <div id='itembox'><p><?php echo $item->name; ?><br>
                                <?php echo $itemprice; ?><br>
                                Quantity: <?php echo $item->shopquantity; ?>
                                <form class='prettyform' action='' method='post' onsubmit="return confirm('Are you sure you want to purchase this item?');">
                                    <input type='hidden' name='shopitem' value='<?php echo $item->id; ?>'>
                                    <p>Number of Items: <input style="width:30px" type='text' name='quantity'></input></p>
                                    <input type='submit' name='itemchosen' value='Purchase'>
                                </form>
                            </div>
                       <?php }
                    }
                    if (sizeof($shop->pets) != 0)
                    {
                        echo "<h2 class='minortitle'>Pets</h2><br>";
                        foreach($shop->pets as $pet)
                        {
                            if($pet->pricereal == 0)
                            {
                                $petprice = $pet->pricesite." Bits ";
                            }
                            else
                            {
                                $petprice = $pet->pricereal." Bobs ";
                            }?>
                            <div id='itembox'><img src='<?php echo $pet->Petimage(); ?>' width='150' />
                                <p><?php echo $pet->color." ".$pet->species; ?><br>
                                <?php echo $petprice; ?><br>
                                Quantity: <?php echo $pet->shopquantity; ?>
                                <form class='prettyform' action='' method='post' onsubmit="return confirm('Are you sure you want to purchase this pet?');">
                                    <input type='hidden' name='shoppet' value='<?php echo $pet->colorid; ?>'>
                                    <select name='gender'>
                                        <option value='Male'>Male</option>
                                        <option value='Female'>Female</option>
                                    </select>
                                    <input type='submit' name='petchosen' value='Purchase'>
                                </form>
                            </div>
                        <?php }
                    }
                    if(sizeof($shop->items) == 0 && sizeof($shop->pets) == 0)
                    {
                        echo "<p>This shop seems to be empty at the moment!  Perhaps come back?</p>";
                    }
                }
                
             }
    }
include('../footer.php');

    /******************************************* Shop Functions *********************************************/
    
    function valid_pet_purchase($shopid, $petid)
    {
        $num = mysql_num_rows(mysql_query("SELECT * FROM pets__shop WHERE shopid = '$shopid' AND petid = '$petid'"));
        return ($num != 0);
    }
    
    function valid_item_purchase($shopid, $itemid)
    {
        $num = mysql_num_rows(mysql_query("SELECT * FROM item__shop WHERE shopid = '$shopid' AND itemid = '$itemid'"));
        return ($num != 0);
    }
?>