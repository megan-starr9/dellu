<?php include('../../header.php');
    if ($id == -1)
    {
        header('Location:'.$site->baseurl.'/');
    }
    else
    {
        $uri = $_SERVER['REQUEST_URI'];
        $segment = explode("/", $uri);
        $postid = (isset($segment[4])) ? $segment[4] : 0;
        $post = new Post($postid);
        
        if(!$post->valid_post())
        {
            echo "<p>That post does not exist!</p>";
        }
        else
        {
            $error = '';
            if (isset($_POST['modifypost']))
            {
                $message = mysql_real_escape_string($_POST['message']);
                $error = $post->modify_post($message);
            }
            if (is_numeric($error))
                header("Location: ".$site->baseurl."/community/forum/topic/".$error);
            else
                echo $error;
            ?>
                <h2 class='minortitle'>Modify a Forum Post</h2>
                
                <!-- Response Form-->
                <form class="prettyform" action="" method="post">
                    <textarea id="bbcode" name="message"><?php echo $post->post_content; ?></textarea>
                    <input type="submit" Value="Submit Modifications" name="modifypost" ></input>
                </form>


<?php   }
    }
include('../../footer.php');?>