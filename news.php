<?php include('header.php');
if ($id == -1)
    {
        header('Location:'.$site->baseurl.'/');
    }
    else
    {
                $uri = $_SERVER['REQUEST_URI'];
                $segment = explode("/", $uri);
                $newsid = (isset($segment[2])) ? $segment[2] : -1;
                $pageid = (isset($_GET['page'])) ? $_GET['page'] : 1 ;
                
                if ($newsid == -1)
                {
                    echo "<h1 class='pagetitle'>News Updates</h1>";
                    $i = 0;
                    $newsquery = mysql_query("SELECT * FROM news__update ORDER BY updatedate DESC");
                    $postnumber = mysql_num_rows($newsquery);
                        echo "Page: ";
                    for ($j=1; $j<=(($postnumber/7)+1);$j++)
                        echo "<a href='".$site->baseurl."/news?page=$j'>$j</a> || ";
                    while (($news = mysql_fetch_array($newsquery)))
                    {
                        $commnumquery = mysql_query("SELECT * FROM news__comments WHERE commentnews = '".$news['updateid']."'");
                        $commnum = mysql_num_rows($commnumquery);
                        $author = new User($news['updateposter']);
                        if (($i != 0)&&(($i%7)==0)&&($i>(($pageid-1)*7)))
                            break;
                        if (($i >= (($pageid-1)*7)) || ($pageid == 1))
                        {
                            echo "<h4>".$news['updatetitle']."</h4>
                            <p>Posted by <a href='".$site->baseurl."/user/index/".$author->userid."'>".$author->username."</a> (#".$author->userid."), ".$author->userrank."</p>
                            <p>".$news['updatepost']."</p>
                            <a href='".$site->baseurl."/news/".$news['updateid']."'>$commnum Comments</a>
                            <hr>";
                        }
                        $i++;
                    }
                }
                else
                {
                    
                if (isset($_POST['commentsubmit']))
                {
                    $comment = mysql_real_escape_string($_POST['comment']);
                    $error = submit_comment($id,$newsid,$comment);
                    
                    echo $error;
                }
                
                    $spnewsquery = mysql_query("SELECT * FROM news__update WHERE updateid = $newsid");
                    $spnews = mysql_fetch_array($spnewsquery);
                    $author2 = new User($spnews['updateposter']);
                    $commentquery = mysql_query("SELECT * FROM news__comments WHERE commentnews = '$newsid' ORDER BY commentdate DESC");
                    echo " <a href='".$site->baseurl."/news'>Back to News</a>
                        <h2 class='minortitle'>".$spnews['updatetitle']."</h2>
                        <p>Posted by <a href='".$site->baseurl."/user/index/".$author2->userid."'>".$author2->username."</a> (#".$author2->userid."), ".$author2->userrank."</p>
                        <p>".$spnews['updatepost']."</p><br><br>";
                    while ($comment = mysql_fetch_array($commentquery))
                    {
                        $user = new User($comment['commentuser']);
                        echo "<p>Posted by <a href='".$site->baseurl."/user/index/".$comment['commentuser']."'>".$user->username."</a></p>
                            <p>".$comment['commentpost']."</p>
                            <hr>";
                    }
            ?>
            
                <h4>Add a Comment</h4>
                <form class="prettyform" action="" method="post">
                    <input type="text" name="comment"></input><br>
                    <input type="submit" value="Post Comment" name="commentsubmit">
                </form>
            
            <?php        
                }
            ?>
                
<?php
    }
include('footer.php');

    /******************************************* News Functions *********************************************/
    
    function duplicate_comment($user,$news,$comment)
    {
        $query = mysql_query("SELECT * FROM news__comments WHERE commentuser = '$user' AND commentnews = '$news' AND commentpost = '$comment'");
        $num = mysql_num_rows($query);
        return ($num != 0);
    }
    
    function submit_comment($user,$news,$comment)
    {
        if ($comment != "")
        {
            if (!(duplicate_comment($user,$news,$comment)))
            {
                $query = "INSERT INTO news__comments (commentnews,commentpost,commentuser) VALUES ('$news','$comment','$user')";
                $error = (!mysql_query($query)) ? "<div id='warning'>I'm sorry, there's been an error: ".mysql_error()."</div>" : "<div id='alert'>Comment successfully posted.</div>";
            }
            else
            {
                $error = "That comment would be a duplicate!";
            }
        }
        else
        {
            $error = "You don't want to post a blank comment!";
        }
        
        return $error;
    }

?>