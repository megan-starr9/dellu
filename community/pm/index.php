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
        
        if ($convoid == 0)
        {
            // Display Conversations
            echo "<h1 class='pagetitle'>Conversations</h1>";
            
            $convoquery = mysql_query("SELECT * FROM pm__convo WHERE convostarter = $id OR convoreceiver = $id ORDER BY convodate DESC");
            echo "<div id='generalcontainer'>
            <table style='text-align:center'>
                <tr>
                    <td width='250px' class='pmlist'><h5>Subject</h5></td>
                    <td width='175px' class='pmlist'><h5>Conversing With</h5></td>
                    <td width='175px' class='pmlist'><h5>Last Updated</h5></td>
                </tr>";
            while ($convo = mysql_fetch_array($convoquery))
            {
                $convoid2 = $convo['convoid'];
                $pmquery2 = mysql_query("SELECT * FROM pm__post WHERE pmconvo = '$convoid2'");
                $status = "";
                $other = ($convo['convostarter'] == $id) ? $convo['convoreceiver'] : $convo['convostarter'];
                $otheruser = new User($other);
                while ($pm2 = mysql_fetch_array($pmquery2))
                {
                    if ($pm2['new'] == '1' && $pm2['pmauthor'] != $id)
                    {
                        $status = " *New!";
                        break;
                    }
                }
                echo "<tr class='pmlist'>
                    <td class='pmlist'><p><a href='".$site->baseurl."/community/pm/index/".$convo['convoid']."'>".$convo['convosubject']."</a>$status</p></td>
                    <td class='pmlist'><p><a href='".$site->baseurl."/user/index/".$otheruser->userid."'>".$otheruser->username."</a></p></td>
                    <td class='pmlist'><p>".$convo['convodate']."</p></td>
                </tr>";
            }
            echo "</table>
            <a href='".$site->baseurl."/community/pm/new'>Start a Conversation</a>
            </div>";
        }
        else if(!valid_convo($convoid))
        {
            echo "<p>That message does not exist!</p>";
        }
        else
        {
            $convoinfo = fetch_convo($convoid);
            if (!user_valid($id, $convoinfo['convostarter'], $convoinfo['convoreceiver']))
            {
                echo "<p>You do not have access to that message.</p>";
            }
            else
            {
                // Display Message
                $starter = new User($convoinfo['convostarter']);
                $receiver = new User($convoinfo['convoreceiver']);
                echo "<div id='convocontainer'><h2 class='minortitle'>".$convoinfo['convosubject']."</h2>
                    <p>Between ".$starter->username." and ".$receiver->username."</p>
                    <a class='mainlink' href='".$site->baseurl."/community/pm/new/$convoid'>Respond</a><br><br>";
                
                $pmquery = mysql_query("SELECT * FROM pm__post WHERE pmconvo = $convoid ORDER BY pmid DESC");
                while ($pm = mysql_fetch_array($pmquery))
                {
                    $pmid = $pm['pmid'];
                    $auth = new User($pm['pmauthor']);
                    echo "<a href='".$site->baseurl."/user/index/".$pm['pmauthor']."'>".$auth->username."</a><p>".$pm['pmtext']."</p><hr>";
                    if ($pm['pmauthor'] != $id)
                        mysql_query("UPDATE pm__post SET new = '0' WHERE pmid = $pmid");
                }
                echo "</div>";
            }
        }
    }
include('../../footer.php');

    
    /******************************************* Inbox Functions *********************************************/
    
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

    
?>