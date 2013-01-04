<?php include('../header.php');
    if (($id == -1) || ($currentuser->userlevel < 3))
    {
        header('Location:'.$site->baseurl.'/');
    }
    else
    {
        if ($currentuser->userlevel >= 4)
        {
            if(isset($_POST['postnews']))
            {
                $title = mysql_real_escape_string($_POST['newstitle']);
                $post = mysql_real_escape_string($_POST['newspost']);
                
                $error = submit_news($id,$title,$post);
                echo $error;
            }
        ?>
            <h4>Post News Update</h4>
            <form class="prettyform" method="post" action="">
                <p>Title: <input type="text" name="newstitle"></input></p>
                <textarea id="bbcode" name="newspost"></textarea><br>
                <input type="submit" value="Post Update" name="postnews"></input>
            </form>
            
        <?php
        }
    }
include('../footer.php');

    /******************************************* News Admin Functions *********************************************/
    function duplicate_news($user,$title,$post)
    {
        $query = mysql_query("SELECT * FROM news__update WHERE updatetitle = '$title' AND updatepost = '$post' AND updateposter = '$user'");
        $num = mysql_num_rows($query);
        return ($num != 0);
    }
    function submit_news($user,$title,$post)
    {
        if (!duplicate_news($user,$title,$post))
        {
            $query = "INSERT INTO news__update (updatetitle,updatepost,updateposter) VALUES ('$title','$post','$user')";
            $error = (!mysql_query($query)) ? "<div id='warning'>There has been an error: ".mysql_error()."</div>" : "<div id='alert'>Update successfully posted!</div>";
        }
        else
        {
            $error = "That news update would be a duplicate.";
        }
        return $error;
    }
    
?>