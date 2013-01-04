<?php
    class Site
    {
        // ====================================== Properties ====================
        public $loggeduser; // User Class
        public $baseurl = '';
        
        //======================================== Methods =========================
        /* Constructor */
        public function __construct()
        {
            $this->mysql_connect();
            $this->loggeduser = (!isset($_SESSION['userid'])) ? NULL : new User($_SESSION['userid']);
        }
        
        /* Mysql Database connection initializer */
        private function mysql_connect()
        {
            $dbhost= 'localhost';  // Hostname of database
            $dbuser= 'allusion';  // Username
            $dbpass= 'ary';  // Password
            $dbname= 'dellusionary';  //Database Name
            
            $connect= mysql_connect($dbhost, $dbuser, $dbpass) or die   ('Error connecting to MySQL Database.');
            
            mysql_select_db($dbname);
        }
        
        /* User login to start site session! */
        public function login($username, $password)
        {
            if ($username != NULL) // Username entered?
            {
                if ($password != NULL) //Password entered?
                {
                    if (existing_username($username) == TRUE) // Does username exist?
                    {
                        if (valid_match($username, $password) == TRUE) // Is entered information valid?
                        {
                            $error = "<div id='alert'>You have successfully logged in!</div>";
                            
                            $query = mysql_query("SELECT * FROM users WHERE username = '$username'");
                            $userid = mysql_fetch_array($query);
                            
                            $_SESSION['userid']=$userid['userid']; // Set session variable
                            mysql_query("UPDATE users SET online = '1' WHERE userid = ".$userid['userid']);
                        }
                        else
                        {
                        $error = "<div id='warning'>That username/password combination is invalid.</div>";
                        }
                    }
                    else
                    {
                    $error = "<div id='warning'>That username does not exist.</div>";
                    }
                }
                else
                {
                    $error = "<div id='warning'>You must enter a password.</div>";
                }
            }
            else
            {
                $error = "<div id='warning'>You must enter a username.</div>";
            }
            return $error;
        }
        
        public function log($text, $type)
        {
            // Open Log File
            $directorypath = $_SERVER['DOCUMENT_ROOT']."/logs/".date("m-Y");
            if (!file_exists($directorypath))
                {
                    mkdir($directorypath);
                    $fh = fopen($_SERVER['DOCUMENT_ROOT']."/logs/".date("m-Y")."/index.php", "a+");
                    fwrite($fh, "<?php header('Location:/'); ?>");
                    fclose($fh);
                }
            $filename = $_SERVER['DOCUMENT_ROOT']."/logs/".date("m-Y")."/$type.txt";
            $fh = fopen($filename, "a+") or die("Could not open log file.");
            fwrite($fh, "\r\n========================\r\n".date("d-m-Y, H:i")." - $text") or die("Could not write file!");
            fclose($fh);
        }
        
        public function paypal_purch($postdata)
        {            
            // Choose url
            if(array_key_exists('test_ipn', $postdata) && 1 === (int) $postdata['test_ipn'])
                $url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
            else
                $url = 'https://www.paypal.com/cgi-bin/webscr';
            
            // Set up request to PayPal
            $request = curl_init();
            curl_setopt_array($request, array
            (
                CURLOPT_URL => $url,
                CURLOPT_POST => TRUE,
                CURLOPT_POSTFIELDS => http_build_query(array('cmd' => '_notify-validate') + $postdata),
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_HEADER => FALSE,
                CURLOPT_SSL_VERIFYPEER => TRUE,
                CURLOPT_CAINFO => 'cacert.pem',
            ));
            
            // Execute request and get response and status code
            $response = curl_exec($request);
            $status   = curl_getinfo($request, CURLINFO_HTTP_CODE);
            
            // Close connection
            curl_close($request);
            
            if($status == 200 && $response == 'VERIFIED')
            {
                // All good! Proceed...
                if(array_key_exists('charset', $postdata) && ($charset = $postdata['charset']))
                {
                    // Ignore if same as our default
                    if($charset == 'utf-8')
                        return "Characters Correct!";
                
                    // Otherwise convert all the values
                    foreach($postdata as $key => &$value)
                    {
                        $value = mb_convert_encoding($value, 'utf-8', $charset);
                    }
                    
                    // Set user
                    $user = new User($postdata['payer_id']);
                
                    // Check package and gift bobs accordingly
                    if($postdata['item_number'] == 'BOB_5_for_5')
                    {
                        $this->log("User ".$user->userid." purchased 5 Bobs!", "paypal");
                        $user->Giverealcash(5);
                    }
                    
                }
            }
            else
            {
                // Not good. Ignore, or log for investigation...
                return "Error!";
            }
        }
    }
?>