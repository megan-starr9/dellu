<?php
    /* ITEM CLASS */
    class Item extends Site
    {
        // ====================================== Properties ====================
        public $id;
        public $name;
        public $type;
        public $typename;
        public $rarity;
        
        public $region;
        public $shop;
        public $shopquantity;
        public $pricereal;
        public $pricesite;
        
        //======================================== Methods =========================
        /* Constructor */
        public function __construct($itemid)
        {
            parent::__construct();
            $this->id = $itemid;
            
            $itemarray = mysql_fetch_array(mysql_query("SELECT * FROM item__list WHERE itemid = $itemid"));
            $this->name = $itemarray['itemname'];
            $this->type = $itemarray['itemtype'];
            $typearray = mysql_fetch_array(mysql_query("SELECT * FROM item__type WHERE typeid = ".$this->type));
            $this->typename = $typearray['typename'];
            $this->rarity = $itemarray['itemrarity'];
            
            $explorequery = mysql_query("SELECT * FROM item__explore WHERE itemid = $itemid");
            if (mysql_num_rows($explorequery) == 0)
                $this->region = NULL;
            else
            {
                $explorearray = mysql_fetch_array($explorequery);
                $this->region = $explorearray['locationid'];
            }
            
            $shopquery = mysql_query("SELECT * FROM item__shop WHERE itemid = $itemid");
            if (mysql_num_rows($shopquery) == 0)
            {
                $this->shop = NULL;
                $this->pricereal = NULL;
                $this->pricesite = NULL;
                $this->shopquantity = NULL;
            }
            else
            {
                $shoparray = mysql_fetch_array($shopquery);
                $this->shop = $shoparray['shopid'];
                $this->pricereal = $shoparray['pricereal'];
                $this->pricesite = $shoparray['pricesite'];
                $this->shopquantity = $shoparray['quantity'];
                
            }
            
        }
        
        public function gift($userid,$quantity)
        {
            $itemquery = mysql_query("SELECT * FROM item__user WHERE itemid = ".$this->id." AND userid = $userid");
            if (mysql_num_rows($itemquery) != 0)
            {
                $itemarray = mysql_fetch_array($itemquery);
                $query = "UPDATE item__user SET quantity= ($quantity+".$itemarray['quantity'].") WHERE itemid = '".$this->id."' AND userid = '$userid'";
            }
            else
                $query = "INSERT INTO item__user (itemid, userid, quantity) VALUES (".$this->id.", $userid, $quantity)";
                
            $error = (!mysql_query($query)) ? "<div id='warning'>There has been an error: ".mysql_error()."</div>" : "<div id='alert'>Gift Successfully Delivered!</div>";
            return $error;
        }
        
        public function purchase($quantity)
        {
            if($this->shopquantity < $quantity)
            {
                return "<div id='warning'>Sorry, there aren't that many in stock!</div>";
            }
            if($this->pricereal != 0)
            {
                if($this->pricereal > $this->loggeduser->realcash)
                {
                    return "<div id='warning'>I'm sorry, you don't seem to have enough Bobs.</div>";
                }
                $quant = $this->shopquantity;
                $this->loggeduser->GiveRealcash(-1*$this->pricereal);
                $newquantity = $quant - $quantity;
                mysql_query("UPDATE item__shop SET quantity = $newquantity WHERE itemid =".$this->id);
                $this->gift($this->loggeduser->userid, $quantity);
                return "<div id='alert'>Item(s) successfully purchased!</div>";
            }
            else
            {
                if($this->pricesite > $this->loggeduser->sitecash)
                {
                    return "<div id='warning'>I'm sorry, you don't seem to have enough Bits.</div>";
                }
                $quant = $this->shopquantity;
                $this->loggeduser->GiveSitecash(-1*$this->pricesite);
                $newquantity = $quant - $quantity;
                mysql_query("UPDATE item__shop SET quantity = $newquantity WHERE itemid =".$this->id);
                $this->gift($this->loggeduser->userid, $quantity);
                return "<div id='alert'>Item(s) successfully purchased!</div>";
            }
        }
        
    }
    
    /* SHOP CATEGORY CLASS */
    class Shop_Category extends Site
    {
        // ====================================== Properties ====================
        public $id;
        public $name;
        public $shops; //Array of shops in category
        
        //======================================== Methods =========================
        /* Constructor */
        public function __construct($catid)
        {
            $query = mysql_query("SELECT * FROM shop__category WHERE id = $catid");
            $result = mysql_fetch_array($query);
            $this->id = $result['id'];
            $this->name = $result['name'];
            
            $shopquery = mysql_query("SELECT * FROM shop__list WHERE category = $catid");
            while($shop = mysql_fetch_array($shopquery))
            {
                $this->shops[] = new Shop($shop['id']);
            }
        }
        
        public function add_to_category($shop)
        {
            // TODO
        }
        public function remove_from_category($shop)
        {
            // TODO
        }
        
        public function new_shop($shopname, $shopdesc)
        {
            $query = "INSERT INTO shop__list (name,category,description) VALUES ('$shopname',".$this->id.",'$shopdesc')";
            $error = (!mysql_query($query)) ? "<div id='warning'>There has been an error: ".mysql_error()."</div>" : "<div id='alert'>Shop Successfully Added!</div>";
            return $error;
        }
    }
    
    /* SHOP CLASS */
    class Shop extends Site
    {
        // ====================================== Properties ====================
        public $id;
        public $category; // ID of category
        public $name;
        public $items; //Array of items in shop
        public $pets; //Array of pets in shop
        
        //======================================== Methods =========================
        /* Constructor */
        public function __construct($shopid)
        {
            $query = mysql_query("SELECT * FROM shop__list WHERE id = $shopid");
            $result = mysql_fetch_array($query);
            $this->id = $shopid;
            $this->category = $result['category'];
            $this->name = $result['name'];
            
            $itemquery = mysql_query("SELECT * FROM item__shop WHERE shopid = $shopid");
            while($item = mysql_fetch_array($itemquery))
            {
                $this->items[] = new Item($item['itemid']);
            }
            $petquery = mysql_query("SELECT * FROM pets__shop WHERE shopid = $shopid");
            while($pet = mysql_fetch_array($petquery))
            {
                $this->pets[] = new Creature($pet['petid']);
            }
        }
        
        public function add_to_inventory($item,$pricesite,$pricereal,$pricebonus,$quantity,$restock)
        {
            $query = "INSERT INTO item__shop (shopid,itemid,pricesite,pricereal,quantity) VALUES (".$this->id.",$item,$pricesite,$pricereal,$quantity)";
            $error = (!mysql_query($query)) ? "<div id='warning'>There has been an error: ".mysql_error()."</div>" : "<div id='alert'>Inventory Updated!</div>";
            $this->items[] = new Item($item);
            return $error;
        }
        public function add_pet_to_inventory($creatureid,$pricesite,$pricereal,$quantity,$restock)
        {
            $query = mysql_query("SELECT * FROM pets__shop WHERE petid = $creatureid AND shopid = ".$this->id);
            if(mysql_num_rows($query))
            {
                return "<div id='warning'>That pet is already sold in that shop!</div>";
            }
            $query = "INSERT INTO pets__shop (petid,shopid,pricesite,pricereal,quantity) VALUES ($creatureid,".$this->id.",$pricesite,$pricereal,$quantity)";
            $error = (!mysql_query($query)) ? "<div id='warning'>There has been an error: ".mysql_error()."</div>" : "<div id='alert'>Inventory Updated!</div>";
            $this->pets[] = new Creature($creatureid);
            return $error;
        }
        public function remove_from_inventory($item)
        {
            // TODO
        }
        
    }
?>