<?php include('../../header.php');
    if ($id == -1)
    {
        header('Location:'.$site->baseurl.'/');
    }
    else
    {
        
        $uri = $_SERVER['REQUEST_URI'];
        $segment = explode("/", $uri);
        $boardid = (isset($segment[4])) ? $segment[4] : 0;
        $board = new Board($boardid);
        
        if (!$board->valid_board())
        {
            echo "The board you are trying to post in was not found.";
        }
        else
        { ?>
            
                <?php
                // Create New Topic
                if(isset($_POST['topic']))
                {
                    $title = mysql_real_escape_string($_POST['title']);
                    $message = mysql_real_escape_string($_POST['message']);
                    
                    $newtopicid = $board->submit_topic($title,$message);
                    if(is_numeric($newtopicid))
                    {
                        header("Location:".$site->baseurl."/community/forum/topic/$newtopicid");
                    }
                    else
                    {
                        echo "<div id='warning'>I'm sorry, there has been an error with your topic posting. $newtopicid</div>";
                    }
                }
                
                ?>
                <h1 class='pagetitle'>Start a Topic</h1>
                <!-- Response Form-->
                <form class="prettyform" action="" method="post">
                    <p>Title: <input type="text" name="title"></input></p>
                    <p>Post: <textarea id="bbcode" name="message"></textarea></p>
                    <input type="submit" Value="Start Topic" name="topic"></input>
                </form>

<?php   }
    }
include('../../footer.php'); ?>