<?php include('../../header.php');
    if ($id == -1)
    {
        header('Location:'.$site->baseurl.'/');
    }
    else
    {
        $uri = $_SERVER['REQUEST_URI'];
        $segment = explode("/", $uri);
        $convoid = (isset($segment[4])) ? $segment[4] : 0;
        $toid = (isset($segment[5])) ? $segment[5] : "";
        
        if ($convoid == 0 || $convoid == 'user')
        {
            if (isset($_POST['startconvo']))
            {
                $convosubject = mysql_escape_string($_POST['convosubject']);
                $convoto = mysql_escape_string($_POST['convoto']);
                $convotext = mysql_escape_string($_POST['convotext']);
                
                $error = start_convo($id,$convosubject,$convoto,$convotext,$site);
                echo $error;
            }
            
            // Start a new Conversation?>
            <h1 class='pagetitle'>Start a Conversation</h1>
            
            <!-- Conversation Start Form -->
            <form class="prettyform" method="post" action="">
                <p>To: <input type="text" name="convoto" value='<?php echo $toid; ?>'></p>
                <p>Subject: <input type="text" name="convosubject"></p>
                <p>Message: <textarea id="bbcode" name="convotext"></textarea></p>
                <input type="submit" name="startconvo" value="Converse">
            </form>
            
        <?php }
        else if(!valid_convo($convoid))
        {
            echo "<p>That conversation does not exist!</p>";
        }
        else
        {
            $convoinfo = fetch_convo($convoid);
            if (!user_valid($id, $convoinfo['convostarter'], $convoinfo['convoreceiver']))
            {
                echo "<p>You do not have permission to post in that conversation</p>";
            }
            else
            {
                if (isset($_POST['postreply']))
                {
                    $postmessage = mysql_escape_string($_POST['posttext']);
                    $error = reply_convo($id,$convoid,$postmessage,$site);
                    echo $error;
                }
                
                $to = ($id == $convoinfo['convostarter']) ? $convoinfo['convoreceiver'] : $convoinfo['convostarter'];
                $toauth = new User($to);
                // Display Message 
                echo "<h2>Post in conversation '".$convoinfo['convosubject']."'</h2>
                    <p>Between you and ".$toauth->username."</p>";?>
            
                <form class="prettyform" action="" method="post">
                    <p>Message: <textarea id="bbcode" name="posttext"></textarea></p>
                    <input type="submit" value="Reply" name="postreply">
                </form>
                
                <div id='generalcontainer'><h3>Most Recent Statements</h3>
                <?php
                $grabrecentquery = mysql_query("SELECT * FROM pm__post WHERE pmconvo = '$convoid' ORDER BY pmdate DESC");
                $i = 0;
                while(($recent = mysql_fetch_array($grabrecentquery)) && ($i<5))
                {
                    $author = new User($recent['pmauthor']);
                    echo "<a href='".$site->baseurl."/user/index/".$recent['pmauthor']."'>".$author->username."</a><p>".$recent['pmtext']."</p><hr class='light'>";
                    $i++;
                }
                echo "</div>";
            }
        }
    }
include('../../footer.php');

    
    /******************************************* Post Creation Functions *********************************************/
    
    function valid_convo($convoid)
    {
        $query = mysql_query("SELECT * FROM pm__post WHERE pmconvo = $convoid");
        $num = mysql_num_rows($query);
        if ($num != 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    function user_valid($userid, $recieverid, $senderid)
    {
        return ($userid == $recieverid || $userid == $senderid);
    }
    
    function fetch_convo($convoid)
    {
        $query = mysql_query("SELECT * FROM pm__convo WHERE convoid = $convoid");
        $result = mysql_fetch_array($query);
        return $result;
    }
    
    function start_convo($user, $subject, $to,$text,$site)
    {
        $usercheckquery = mysql_query("SELECT * FROM users WHERE userid = '$to'");
        $usercheck = mysql_num_rows($usercheckquery);
        $duplicatepostquery = mysql_query("SELECT * FROM pm__post WHERE pmtext = '$text' AND pmauthor = '$user'");
        $duplicateconvoquery = mysql_query("SELECT * FROM pm__convo WHERE convosubject = '$subject' AND convoreceiver = '$to'");
        $duplicatecheck = mysql_num_rows($duplicatepostquery) + mysql_num_rows($duplicateconvoquery);
        
        if ($user == "")
        {
            $error = "<div id='warning'>You must enter a recipient.</div>";
        }
        else if ($usercheck == 0)
        {
            $error = "<div id='warning'>The user you are trying to start a conversation with does not exist!</div>";
        }
        else if ($subject == "")
        {
            $error = "<div id='warning'>You must enter a subject.</div>";
        }
        else if ($text == "")
        {
            $error = "<div id='warning'>Surely you don't want to send a bank message!</div>";
        }
        else if ($duplicatecheck != 0)
        {
            $error = "<div id='warning'>That message would be a duplicate.</div>";
        }
        else
        {
            $submitconvo = "INSERT INTO pm__convo (convosubject,convostarter,convoreceiver) VALUES ('$subject',$user,$to)";
            $error = (!mysql_query($submitconvo)) ? "<div id='warning'>I'm sorry, there has been an error: ".mysql_error()."</div>" : "";
            $convo = mysql_insert_id();
            $from = new User($user);
            mysql_query("INSERT INTO notifications (userid,message,new) VALUES ('$to','<a href=".$site->baseurl."/user/index/$user>$from->username</a> has sent you a new message! <a href=".$site->baseurl."/community/pm/index/$convo>View conversation.</a>','1')");
            $submitpost = "INSERT INTO pm__post (pmconvo,pmauthor,pmtext,new) VALUES ($convo,$user,'$text','1')";
            $error = (!mysql_query($submitpost)) ? $error."<div id='warning'>I'm sorry, there has been an error: ".mysql_error()."</div>" : "<div id='alert'>Conversation successfully started!</div>";
        }
        
        return $error;
        
    }
    
    function reply_convo($user,$convo,$text,$site)
    {
        $duplicatepostquery = mysql_query("SELECT * FROM pm__post WHERE pmauthor = '$user' AND pmconvo = '$convo' AND pmtext = '$text'");
        $duplicatecheck = mysql_num_rows($duplicatepostquery);
        
        if ($text == "")
        {
            $error = "<div id='warning'>Surely you don't want to send a blank response!</div>";
        }
        else if ($duplicatecheck != 0)
        {
            $error = "<div id='warning'>That response would be a duplicate!</div>";
        }
        else
        {
            $convoquery = mysql_query("SELECT * FROM pm__convo WHERE convoid = $convo");
            $convoresult = mysql_fetch_array($convoquery);
            $to = ($convoresult['convostarter'] == $user) ? $convoresult['convoreceiver'] : $convoresult['convostarter'];
            $from = new User($user);
            mysql_query("INSERT INTO notifications (userid,message,new) VALUES ('$to','<a href=".$site->baseurl."/user/index/$user>".$from->username."</a> has sent you a response! <a href=".$site->baseurl."/community/pm/index/$convo>View conversation.</a>','1')");
            $submitpost = "INSERT INTO pm__post (pmconvo,pmauthor,pmtext,new) VALUES ($convo,$user,'$text','1')";
            $error = (!mysql_query($submitpost)) ? "<div id='warning'>I'm sorry, there has been an error: ".mysql_error()."</div>" : "";
            $updateconvo = "UPDATE pm__convo set convodate = CURRENT_TIMESTAMP WHERE convoid = '$convo'";
            $error = (!mysql_query($updateconvo)) ? "<div id='warning'>I'm sorry, there has been an error: ".mysql_error()."</div>" : "<div id='alert'>Response successfully sent!</div>";
        }
        
        return $error;
    }

    
?>