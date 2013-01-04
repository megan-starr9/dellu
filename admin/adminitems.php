<?php include('../header.php');
    if (($id == -1) || ($currentuser->userlevel < 3))
    {
        header('Location:'.$site->baseurl.'/');
    }
    else
    {
        $error = "";
        if (isset($_POST['addtype']) && $currentuser->userlevel >= 4)
        {
            $typename = mysql_real_escape_string($_POST['typename']);
            $typedesc = mysql_real_escape_string($_POST['typedesc']);
            
            $error = addtype($typename,$typedesc);
        }
        else if (isset($_POST['additem']))
        {
            $itemname = mysql_real_escape_string($_POST['itemname']);
            $itemdesc = mysql_real_escape_string($_POST['itemdesc']);
            $itemimg = $_FILES['itemimg']['tmp_name'];
            $itemtype = mysql_real_escape_string($_POST['itemtype']);
            $itemrarity = mysql_real_escape_string($_POST['itemrarity']);
            $itemartist = mysql_real_escape_string($_POST['itemartist']);
            
            $error = additem($itemname,$itemdesc,$itemimg,$itemtype,$itemrarity);
        }
        else if (isset($_POST['giftitem']))
        {
            $userid = mysql_real_escape_string($_POST['giftitemuser']);
            $itemid = mysql_real_escape_string($_POST['giftitemitem']);
            $quantity = mysql_real_escape_string($_POST['giftitemquantity']);
            $giftitem = new Item($itemid);
            
            $error = $giftitem->gift($userid,$quantity);
            $site->log("User ".$site->loggeduser->userid." gifted user $userid with $quantity ".$giftitem->name, "item_admin");
        }
        else if (isset($_POST['giftcurr']))
        {
            $user = new User(mysql_real_escape_string($_POST['giftcurruser']));
            $type = mysql_real_escape_string($_POST['giftcurrtype']);
            $amount = mysql_real_escape_string($_POST['giftcurramount']);
            
            $error = ($type == 2) ? $user->GiveRealcash($amount) : $user->GiveSitecash($amount);
        }
        else if(isset($_POST['addcat']))
        {
            $catname = mysql_real_escape_string($_POST['newcatname']);
            
            $error = addcat($catname);
        }
        else if(isset($_POST['addshop']))
        {
            $shopname = mysql_real_escape_string($_POST['newshopname']);
            $shopdesc = mysql_real_escape_string($_POST['newshopdesc']);
            $shopcat = mysql_real_escape_string($_POST['newshopcat']);
            
            $cat = new Shop_Category($shopcat);
            $error = $cat->new_shop($shopname,$shopdesc);
        }
        else if(isset($_POST['addtoshop']))
        {
            $shopid = mysql_real_escape_string($_POST['stockshop']);
            $itemid = mysql_real_escape_string($_POST['stockitem']);
            $currency = mysql_real_escape_string($_POST['stockcurr']);
            if($currency == 1)
            {
                $pricereal = 0;
                $pricesite = mysql_real_escape_string($_POST['stockprice']);
            }
            else
            {
                $pricereal = mysql_real_escape_string($_POST['stockprice']);
                $pricesite = 0;
            }
            $quantity = mysql_real_escape_string($_POST['stockquantity']);
            
            $shop = new Shop($shopid);
            $error = $shop->add_to_inventory($itemid,$pricesite,$pricereal,$quantity,$quantity);
        }
        
        echo $error;
    ?>
                <h3>Admin CP: Items/Currency</h3>
                
                <?php if($currentuser->userlevel >= 4)
                { ?>
                <table width='100%'>
                    <tr>
                        <td>
                            <h4>Add an Item Type</h4>
                            <form class="prettyform" method="post">
                                <p>Type Name: <input type="text" name="typename"></input></p>
                                <p>Type Description: <input type="text" name="typedesc"></input></p>
                                <input type="submit" name="addtype" value="Add Type"></input>
                            </form>
                        </td>
                        <td>
                            <h4>Add an Item</h4>
                            <form class="prettyform" method="post" enctype="multipart/form-data">
                                <p>Item Name: <input type="text" name="itemname"></input></p>
                                <p>Item Description: <input type="text" name="itemdesc"></input></p>
                                <p>Item Image: <input type="file" name="itemimg"></input></p>
                                <p>Item Type: <select name="itemtype">
                                    <?php $itemtypequery = mysql_query("SELECT * FROM item__type");
                                    while ($itemtype = mysql_fetch_array($itemtypequery))
                                    {
                                        echo "<option value='".$itemtype['typeid']."'>".$itemtype['typename']."</option>";
                                    } ?>
                                </select></p>
                                <p>Item Rarity: <input type="text" name="itemrarity"></input></p>
                                <p>Item Artist ID #: <input type="text" name="itemartist"></input></p>
                                <input type="submit" name="additem" value="Add Item"></input>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h4>Modify an Item Type</h4>
                            <form class="prettyform" method="post">
                                <p>Coming...</p>
                            </form>
                        </td>
                        <td>
                            <h4>Modify an Item</h4>
                            <form class="prettyform" method="post">
                                <p>Coming...</p>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h4>Add a Shop Category</h4>
                            <form class="prettyform" method="post">
                                <p>Category Name: <input type='text' name='newcatname'></input></p>
                                <input type='submit' name='addcat' value='Add New Category'>
                            </form>
                        </td>
                        <td>
                            <h4>Remove a Shop Category</h4>
                            <form class="prettyform" method="post">
                                <p>Coming...</p>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h4>Add a Shop</h4>
                            <form class="prettyform" method="post">
                                <p>Shop Name: <input type='text' name='newshopname'></input></p>
                                <p>Shop Description: <input type='text' name='newshopdesc'></input></p>
                                <p>Shop Category: <select name='newshopcat'>
                                    <?php $catquery = mysql_query("SELECT * FROM shop__category");
                                    while($category = mysql_fetch_array($catquery))
                                    {
                                        echo "<option value='".$category['id']."' >".$category['name']."</option>";
                                    } ?>
                                </select></p>
                                <input type='submit' name='addshop' value='Add New Shop'>
                            </form>
                        </td>
                        <td>
                            <h4>Remove a Shop</h4>
                            <form class="prettyform" method="post">
                                <p>Coming...</p>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h4>Modify a Shop Category</h4>
                            <form class="prettyform" method="post">
                                <p>Coming...</p>
                            </form>
                        </td>
                        <td>
                            <h4>Modify a Shop</h4>
                            <form class="prettyform" method="post">
                                <p>Coming...</p>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h4>Remove Item from Shop Stock</h4>
                            <form class="prettyform" method="post">
                                <p>Coming...</p>
                            </form>
                        </td>
                        <td>
                            <h4>Add Item to Shop Stock</h4>
                            <form class="prettyform" method="post">
                                <p>Shop: <select name="stockshop">
                                <?php $listshopquery = mysql_query("SELECT * FROM shop__list");
                                while($listshop = mysql_fetch_array($listshopquery))
                                {
                                    echo "<option value='".$listshop['id']."'>".$listshop['name']."</option>";
                                } ?>
                                </select></p>
                                <p>Item: <select name="stockitem">
                                    <?php $listitemquery = mysql_query("SELECT * FROM item__list");
                                    while ($listitem = mysql_fetch_array($listitemquery))
                                    {
                                        echo "<option value='".$listitem['itemid']."'>".$listitem['itemname']."</option>";
                                    } ?>
                                </select></p>
                                <p>Price: <input type='text' name='stockprice'></input></p>
                                <p>Currency Type: <select name='stockcurr'>
                                    <option value='1'>Bits</option>
                                    <option value='2'>Bobs</option>
                                </select></p>
                                <p>Restock Quantity: <input type="text" name="stockquantity"></input></p>
                                <input type="submit" name="addtoshop" value="Stock Shop">
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td>
                            <h4>Gift an Item</h4>
                            <form class="prettyform" method="post">
                                <p>Recipient User ID: <input type="text" name="giftitemuser"></input></p>
                                <p>Item: <select name="giftitemitem">
                                    <?php $listitemquery2 = mysql_query("SELECT * FROM item__list");
                                    while ($listitem2 = mysql_fetch_array($listitemquery2))
                                    {
                                        echo "<option value='".$listitem2['itemid']."'>".$listitem2['itemname']."</option>";
                                    } ?>
                                </select></p>
                                <p>Quantity: <input type="text" name="giftitemquantity"></input></p>
                                <input type="submit" name="giftitem" value="Gift Item">
                            </form>
                        </td>
                        <td>
                            <h4>Gift Currency</h4>
                            <form class="prettyform" method="post">
                                <p>Recipient User ID: <input type="text" name="giftcurruser"></input></p>
                                <p>Type: <select name="giftcurrtype">
                                    <option value="1">Bits</option>
                                    <option value="2">Bobs</option>
                                </select></p>
                                <p>Amount: <input type="text" name="giftcurramount"></input></p>
                                <input type="submit" name="giftcurr" value="Gift Currency">
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h4>Revoke an Item</h4>
                            <form class="prettyform" method="post">
                                <p>Coming...</p>
                            </form>
                        </td>
                        <td>
                            <h4>Revoke Currency</h4>
                            <form class="prettyform" method="post">
                                <p>Coming...</p>
                            </form>
                        </td>
                    </tr>
                </table>

<?php
    }
include('../footer.php');

    /******************************************* Item/Currency Admin Functions *********************************************/
    function addcat($catname)
    {
        $query = "INSERT INTO shop__category (name) VALUES ('$catname')";
        $error = (!mysql_query($query)) ? "<div id='warning'>There has been an error: ".mysql_error()."</div>" : "<div id='alert'>Category Successfully Added!</div>";
        return $error;
    }
    
    function addtype($typename, $typedesc)
    {
        $query = "INSERT INTO item__type (typename,typedesc) VALUES ('$typename','$typedesc')";
        $error = (!mysql_query($query)) ? "<div id='warning'>There has been an error: ".mysql_error()."</div>" : "<div id='alert'>Type Successfully Added!</div>";
        $error = (!mkdir ("../images/items/$typename")) ? $error."<br><div id='warning'>Directory was not successfully added.</div>" : $error."<br><div id='alert'>Directory successfully created!</div>";
        $fh = fopen("../images/items/$typename/index.php", "a+");
        fwrite($fh, "<?php header('Location:/'); ?>");
        fclose($fh);
        return $error;
    }
    function additem($itemname,$itemdesc,$itemimg,$itemtype,$itemrarity,$itemartist)
    {
        $query = "INSERT INTO item__list (itemname,itemdesc,itemtype,itemrarity,itemartist) VALUES ('$itemname','$itemdesc','$itemtype','$itemrarity','$itemartist')";
        $error = (!mysql_query($query)) ? "<div id='warning'>There has been an error: ".mysql_error()."</div>" : "<div id='alert'>Item Successfully Added!</div>";
        
        $typequery = mysql_query("SELECT * FROM item__type WHERE typeid = '$itemtype'");
        $typeinfo = mysql_fetch_array($typequery);
        $itempath = "../images/items/".$typeinfo['typename']."/$itemname.png";
        $error = (!move_uploaded_file($itemimg, $itempath)) ? $error."<br><div id='warning'>Image upload error.</div>" : $error."<br><div id='alert'>Image uploaded succesfully!</div>";
        return $error;
    }
    function giftcurrency($user,$type,$amount)
    {
        if ($type == 1) // Site Currency
        {
            $currtype = 'bits';
        }
        else if ($type == 2) // Cash Currency
        {
            $currtype = 'bobs';
        }
        else // Default Site Currency if something wierd happens...
        {
            $currtype = 'bits';
        }
        
        $currinfo = mysql_fetch_array(mysql_query("SELECT * FROM users WHERE userid = '$user'"));
        $current = $currinfo[$currtype];
        $query = "UPDATE users SET $currtype = ($current+$amount) WHERE userid = '$user'";
        $error = (!mysql_query($query)) ? "<div id='warning'>There has been an error: ".mysql_error()."</div>" : "<div id='alert'>Currency Successfully Gifted!</div>";
        
        return $error;
    }
?>