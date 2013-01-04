<?php
    class Pet extends Site
    {
        // ====================================== Properties ====================
        public $petid;
        public $petname;
        public $petgender;
        public $petcolor;
        public $petspecies;
        public $petowner; // User class!
        public $petdate;
        
        //======================================== Methods =========================
        /* Constructor */
        public function Pet($id)
        {
            $petarray = mysql_fetch_array(mysql_query("SELECT * FROM pets__owned WHERE id = $id"));
            $this->petid = $id;
            $this->petname = $petarray['name'];
            $this->petgender = $petarray['gender'];
            
            $colorarray = mysql_fetch_array(mysql_query("SELECT * FROM pets__colorations WHERE colorid = ".$petarray['coloration']));
            $speciesarray = mysql_fetch_array(mysql_query("SELECT * FROM pets__species WHERE speciesid = ".$petarray['species']));
            $this->petcolor = $colorarray['colorname'];
            $this->petspecies = $speciesarray['speciesname'];
            
            $this->petowner = new User($petarray['owner']);
            $this->petdate = date("F j, Y", strtotime($petarray['birthdate']));;
        }
        
        public function Petimage()
        {
            if ($this->petspecies == "Custom")
            {
                return $this->baseurl."/images/pets/Custom/$this->petid.png";
            }
            else
            {
                return $this->baseurl."/images/pets/".$this->petspecies."/".$this->petcolor.".png";
            }
        }
        
        public function change_name($name)
        {
        	$query = "UPDATE pets__owned SET name='$name' WHERE id = ".$this->petid;
        	
        	if ($name == "")
        	{
        		$error = "<div id='warning'>Your pet must have a name!</div>";
        	}
        	else
        	{
        		$error = (!mysql_query($query)) ? "<div id='warning'>I'm sorry, there has been a problem: ".mysql_error()."</div>" : "<div id='alert'>Pet successfully updated!</div>";
        	}
        	return $error;
        }
        public function set_active()
        {
        	$query = "UPDATE users SET activepet = ".$this->petid." WHERE userid = ".$this->petowner->userid;
        	$error = (!mysql_query($query)) ? "<div id='warning'>I'm sorry, there has been a problem: ".mysql_error()."</div>" : "<div id='alert'>Your Active Pet has been successfully updated!</div>";
        	return $error;
        }
    }
    
    class Creature extends Site
    {
        // ====================================== Properties ====================
        public $colorid;
        public $color;
        public $speciesid;
        public $species;
        public $artist; // User Class
        public $rarity;
        public $pricereal;
        public $pricesite;
        public $shopquantity;
        
        //======================================== Methods =========================
        /* Constructor */
        public function Creature($id)
        {
            parent::__construct();
            $colorarray = mysql_fetch_array(mysql_query("SELECT * FROM pets__colorations WHERE colorid = $id"));
            $this->colorid = $id;
            $this->color = $colorarray['colorname'];
            $this->artist = new User($colorarray['colorartist']);
            
            $speciesarray = mysql_fetch_array(mysql_query("SELECT * FROM pets__species WHERE speciesid = ".$colorarray['colorspecies']));
            $this->speciesid = $colorarray['colorspecies'];
            $this->species = $speciesarray['speciesname'];
            
            $this->rarity = $colorarray['colorrarity']+$speciesarray['speciesrarity'];
            
            $shopquery = mysql_query("SELECT * FROM pets__shop WHERE petid = $id");
            $shoparray = mysql_fetch_array($shopquery);
            if(mysql_num_rows($shopquery) > 0) {
                $this->pricereal = $shoparray['pricereal'];
                $this->pricesite = $shoparray['pricesite'];
                $this->shopquantity = $shoparray['quantity'];
            }
            else {
                $this->pricesite = 0;
                $this->pricereal = 0;
                $this->shopquantity = 0;
            }
        }
        
        public function Petimage()
        {
            return $this->baseurl."/images/pets/".$this->species."/".$this->color.".png";
        }
        
        public function gift($userid, $gender, $quantity)
        {
            $error = "";
            for($i=0; $i<$quantity; $i++)
            {
                $query = "INSERT INTO pets__owned (name,gender,owner,species,coloration) VALUES ('Nameless','$gender',$userid,".$this->speciesid.",".$this->colorid.")";
                $error = (!mysql_query($query)) ? "<div id='warning'>There has been an error: ".mysql_error()."</div>" : "";
            }
            if ($error == "")
            {
                return "<div id='alert'>Pet(s) successfully gifted!</div>";
            }
            else
            {
                return $error;
            }
            
        }
        
        public function Purchase($gender, $quantity)
        {
            if($this->shopquantity < $quantity)
            {
                return "<div id='warning'>There are not that many in stock.</div>";
            }
            if($this->pricereal != 0)
            {
                if($this->pricereal > $this->loggeduser->realcash)
                {
                    return "<div id='warning'>You don't seem to have enough Bobs!</div>";
                }
                $this->loggeduser->GiveRealcash(-1*$this->pricereal);
                $quant = $this->shopquantity;
                $newquantity = $quant - $quantity;
                mysql_query("UPDATE pets__shop SET quantity = $newquantity WHERE petid =".$this->colorid);
                $this->gift($this->loggeduser->userid, $gender, $quantity);
                return "<div id='alert'>$quantity $gender ".$this->color." ".$this->species."(s) successfully purchased!</div>";
            }
        }
    }
?>