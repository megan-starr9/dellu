<?php
    class User extends Site
    {
        // ====================================== Properties ====================
        public $userid; // User's id
        public $username; // User's display name
        public $bio; // User's Profile Bio
        public $userdate; // User's join date
        public $userrank; // User's rank title
        public $userlevel; // User's access level
        public $staffbio; // If user is staff, the bio that displays
        public $template; // User's layout of choice
        public $fluid; // is user set for fluid layout or static?
        public $online; // User's online status
        public $rankid;
        
        public $sitecash;
        public $realcash;
        
        public $activepet; // User's active pet, possibly move..
        
        public $postcount;
        
        private $loginname; //User's login
        private $password; // User's password
        
        //======================================== Methods =========================
        
        public function User($id)
        {
            $this->userid = $id;
            
            $userinfo = mysql_fetch_array(mysql_query("SELECT * FROM users WHERE userid = '$id'"));
            $this->username = $userinfo['displayname'];
            $this->bio = $userinfo['userbio'];
            $this->userdate = date("F j, Y", strtotime($userinfo['joindate']));
            $this->template = $userinfo['template'];
            $this->fluid = $userinfo['fluid'];
            $this->sitecash = $userinfo['bits'];
            $this->realcash = $userinfo['bobs'];
            $this->activepet = $userinfo['activepet'];
            
            $lastactivity = strtotime($userinfo['recentactivity']);
            $currenttime = time();
            $this->online = ($userinfo['online'] && (($currenttime-$lastactivity) < 1800));
            $this->rankid = $userinfo['rank'];
            
            $rankinfo = mysql_fetch_array(mysql_query("SELECT * FROM ranks WHERE rankid = '".$userinfo['rank']."'"));
            $this->userrank = $rankinfo['ranktitle'];
            $this->userlevel = $rankinfo['ranklevel'];
            $this->staffbio = $userinfo['staffbio'];
            
            $postcountquery = mysql_query("SELECT * FROM forum__post WHERE postuser = ".$this->userid);
            $this->postcount = mysql_num_rows($postcountquery);
            
        }
        
        public function activity()
        {
            mysql_query("UPDATE users SET recentactivity = CURRENT_TIMESTAMP WHERE userid = ".$this->userid);
        }
        
        public function GiveSitecash($amount)
        {
            $total = $this->sitecash + $amount;
            $query = "UPDATE users SET bits = $total WHERE userid =".$this->userid;
            $error = (!mysql_query($query)) ? "<div id='warning'>There has been an error! ".mysql_errror()."</div>" : "<div id='alert'>$amount Bits successfully gifted</div>";
            return $error;
        }
        public function GiveRealcash($amount)
        {
            $total = $this->realcash + $amount;
            $query = "UPDATE users SET bobs = $total WHERE userid =".$this->userid;
            $error = (!mysql_query($query)) ? "<div id='warning'>There has been an error! ".mysql_errror()."</div>" : "<div id='alert'>$amount Bobs successfully gifted</div>";
            return $error;
        }
        
        public function set_template($template, $fluid)
        {
        	$newtemp = (file_exists('../styles/'.$template)) ? $template : 'default';
        	$query = "UPDATE users SET template='$newtemp', fluid='$fluid' WHERE userid = '".$this->userid."'";
       		$error = (!mysql_query($query)) ? "<div id='warning'>There has been an error: ".mysql_error()."</div>" : "<div id='alert'>Template successfully set!</div>";
        	return $error;
        }
    }
?>