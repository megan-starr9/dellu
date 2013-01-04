<?php include('../header.php');
    if (($id == -1) || ($currentuser->userlevel < 4)) // If user is not Admin or higher
    {
        header('Location:'.$site->baseurl.'/');
    }
    else
    {
    ?>
                <h3>Admin CP: Pets</h3>
            <?php
                $error = "";
                if (isset($_POST['addspecies'])) // If submitting new species
                {
                    $speciesname = mysql_real_escape_string($_POST['speciesname']);
                    $speciesdesc = mysql_real_escape_string($_POST['speciesdesc']);
                    $speciesrarity = mysql_real_escape_string($_POST['speciesrarity']);
                    $speciesartist = mysql_real_escape_string($_POST['speciesartist']);
                    $error = add_species($speciesname,$speciesdesc,$speciesrarity,$speciesartist);
                }
                else if (isset($_POST['addcolor']))
                {
                    $colorspecies = mysql_real_escape_string($_POST['colorspecies']);
                    $colorname = mysql_real_escape_string($_POST['colorname']);
                    $colorimg = $_FILES['colorpic']['tmp_name'];
                    $colordesc = mysql_real_escape_string($_POST['colordesc']);
                    $colorrarity = mysql_real_escape_string($_POST['colorrarity']);
                    $colorartist = mysql_real_escape_string($_POST['colorartist']);
                    $error = add_coloration($colorspecies,$colorname,$colorimg,$colordesc,$colorrarity,$colorartist);
                }
                else if(isset($_POST['shopaddpet']))
                {
                    $shop = new Shop(mysql_real_escape_string($_POST['shop']));
                    $color = mysql_real_escape_string($_POST['color']);
                    $currency = mysql_real_escape_string($_POST['currency']);
                    if($currency == 2)
                    {
                        $pricesite = 0;
                        $pricereal = mysql_real_escape_string($_POST['price']);
                    }
                    else
                    {
                        $pricesite = mysql_real_escape_string($_POST['price']);
                        $pricereal = 0;
                    }
                    $quantity = mysql_real_escape_string($_POST['quantity']);
                    
                    $error = $shop->add_pet_to_inventory($color,$pricesite,$pricereal,$quantity,$quantity);
                }
                echo $error;
            ?>
                <table width="100%">
                    <tr>
                        <td>
                            <h4>Add a Species</h4>
                            <form class="prettyform" action="" method="post">
                                <p>Name: <input type="text" name="speciesname"></input></p>
                                <p>Description: <input type="text" name="speciesdesc"></input></p>
                                <p>Rarity (0 to 100): <input type="text" name="speciesrarity"></input></p>
                                <p>PSD Artist ID #: <input type="text" name="speciesartist"></input></p>
                                <input type="submit" name="addspecies" value="Submit Species"></input>
                            </form>
                        </td>
                        <td>
                            <h4>Add a Coloration</h4>
                            <form class="prettyform" action="" enctype="multipart/form-data" method="post">
                                <p>Species: <select name="colorspecies">
                                    <?php
                                    $query = mysql_query("SELECT * FROM pets__species");
                                    while($species = mysql_fetch_array($query))
                                    {
                                        echo "<option value='".$species['speciesid']."'>".$species['speciesname']."</a>";
                                    }
                                    ?>
                                </select></p>
                                <p>Name: <input type="text" name="colorname"></input></p>
                                <p>Description: <input type="text" name="colordesc"></input></p>
                                <p>Image: <input type="file" name="colorpic"></input></p>
                                <p>Rarity (0 to 100): <input type="text" name="colorrarity"></input></p>
                                <p>Color Artist ID #: <input type="text" name="colorartist"></input></p>
                                <input type="submit" name="addcolor" value="Submit Coloration"></input>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h4>Modify a Species</h4>
                            <form class="prettyform" action="" method="post">
                                <p>Coming...</p>
                            </form>
                        </td>
                        <td>
                            <h4>Modify a Coloration</h4>
                            <form class="prettyform" action="" method="post">
                                <p>Coming...</p>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h4>Gift a Pet</h4>
                            <form class="prettyform" action="" method="post">
                                <p>Coming...</p>
                            </form>
                        </td>
                        <td>
                            <h4>Modify a Pet</h4>
                            <form class="prettyform" action="" method="post">
                                <p>Coming...</p>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h4>Add Pet to Shop</h4>
                            <form class="prettyform" action="" method="post">
                                <p>Shop: <select name="shop">
                                <?php $listshopquery = mysql_query("SELECT * FROM shop__list");
                                while($listshop = mysql_fetch_array($listshopquery))
                                {
                                    echo "<option value='".$listshop['id']."'>".$listshop['name']."</option>";
                                } ?>
                                </select></p>
                                <p>Coloration: <select name='color'>
                                    <?php
                                    $query = mysql_query("SELECT * FROM pets__colorations WHERE colorspecies != 1");
                                    while ($pet = mysql_fetch_array($query))
                                    {
                                        $petob = new Creature($pet['colorid']);
                                        echo "<option value=".$petob->colorid.">".$petob->color." ".$petob->species."</option>";
                                    }
                                    ?>
                                </select></p>
                                <p>Currency: <select name='currency'>
                                    <option value='1'>Bits</option>
                                    <option value='2'>Bobs</option>
                                </select></p>
                                <p>Price: <input type='text' name='price'></input></p>
                                <p>Restock Amount: <input type='text' name='quantity'></input></p>
                                <input type='submit' name='shopaddpet' value='Add Pet to Shop'></input>
                            </form>
                        </td>
                        <td>
                            <h4>Remove Pet from Shop</h4>
                            <form class="prettyform" action="" method="post">
                                <p>Coming...</p>
                            </form>
                        </td>
                    </tr>
                    </tr>
                </table>
                
<?php
    }
include('../footer.php');

    /******************************************* Pet Admin Functions *********************************************/
    function add_species($name,$desc,$rarity,$artist)
    {
        $query = "INSERT INTO pets__species (speciesname,speciesdesc,speciesrarity,speciesartist) VALUES ('$name','$desc','$rarity','$artist')";
        $error = (!mysql_query($query)) ? "<div id='warning'>There has been an error: ".mysql_error()."</div>" : "<div id='alert'>Species successfully added!</div>";
        mkdir ("../images/pets/$name");
        $fh = fopen("../images/pets/$name/index.php", "a+");
        fwrite($fh, "<?php header('Location:/'); ?>");
        fclose($fh);
        return $error;
    }
    function add_coloration($species,$name,$tmpimage,$desc,$rarity, $artist)
    {
        $query = "INSERT INTO pets__colorations (colorspecies,colorname,colordesc,colorrarity,colorartist) VALUES ('$species','$name','$desc','$rarity','$artist')";
        $error = (!mysql_query($query)) ? "<div id='warning'>There has been an error: ".mysql_error()."</div>" : "<div id='alert'>Coloration successfully added!</div>";
        
        // Image upload for coloration
        $speciesquery = mysql_query("SELECT * FROM pets__species WHERE speciesid = '$species'");
        $speciesinfo = mysql_fetch_array($speciesquery);
        $speciesname = $speciesinfo['speciesname'];
        $colorpath = "../images/pets/$speciesname/$name.png";
        if(move_uploaded_file($tmpimage, $colorpath))
        {
            $error = $error."<br><div id='alert'>Image successfully uploaded!</div>";
            chmod($colorpath, 0755);
        }
        else
        {
            $error = $error."<br><div id='warning'>Image upload unsuccessful =[</div>";
        }
        
        return $error;
    }
?>