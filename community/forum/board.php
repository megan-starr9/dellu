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
        
        if(!$board->valid_board())
        {
            echo "<p>That board does not exist!</p>";
        }
        else
        { ?>
            
                <?php
                // Retrieve board information
                
                $board->breadcrumbs();
                echo "<h1 class='pagetitle'>".$board->board_name."</h1>";
                
                // Display sub-boards
                echo "<div id='catcontainer'>Sub Boards</div>";
                if (sizeof($board->board_subboards) != 0)
                {
                    foreach ($board->board_subboards as $subboard) // Loop for sub-boards
                    {
                        echo "<div id='boardcontainer'><table class='boardtable'>
                        <tr>
                            <td class='title'><p><a href='".$site->baseurl."/community/forum/board/".$subboard->board_id."'>---".$subboard->board_name."</a></p></td>
                            <td class='desc'><p>".$subboard->board_description."</p></td>
                            <td class='poster'><p>";
                            if (sizeof($subboard->board_normaltopics) != 0 || $subboard->board_stickytopics != 0)
                            {
                                echo "Newest response in <a href='".$site->baseurl."/community/forum/topic/".$subboard->board_recenttopic->topic_id."'>".$subboard->board_recenttopic->topic_name."</a>
                                posted by <a href='".$site->baseurl."/user/index/".$subboard->board_recenttopic->topic_recentresponse->post_author->userid."'>".$subboard->board_recenttopic->topic_recentresponse->post_author->username."</a>
                                <br>Updated ".$subboard->board_recenttopic->topic_recentresponse->post_datestyledday." at ".$subboard->board_recenttopic->topic_recentresponse->post_datestyledtime;
                            }
                            else
                                echo "No Posts";
                            
                        echo "</p></td>
                        </tr>
                        </table></div>";
                    }
                }
                echo "<br>";

                // Display Topics
                echo "<div id='catcontainer'>Threads</div>";
                if (sizeof($board->board_stickytopics) != 0)
                {
                    foreach ($board->board_stickytopics as $stucktopic) // Loop for stickied topics!
                    {
                        echo "<div id='threadcontainer'><table class='threadtable'>
                            <tr>
                                <td class='topic'><p>STICKY! - <a href=".$site->baseurl."/community/forum/topic/".$stucktopic->topic_id.">".$stucktopic->topic_name."</a></p></td>
                                <td class='author'><p><a href='".$site->baseurl."/user/index/".$stucktopic->topic_starter->userid."'>".$stucktopic->topic_starter->username."</a></p></td>
                                <td class='response'><p>
                                    Latest response made by <a href='".$site->baseurl."/user/index/".$stucktopic->topic_recentresponse->post_author->userid."'>".$stucktopic->topic_recentresponse->post_author->username."</a>
                                    on ".$stucktopic->topic_recentresponse->post_datestyledday." at ".$stucktopic->topic_recentresponse->post_datestyledtime."
                                </p></td>
                            </tr>
                        </table></div>";
                    }
                }
                if (sizeof($board->board_normaltopics) != 0)
                {
                    foreach ($board->board_normaltopics as $topic ) // Loop for topics
                    {
                        echo "<div id='threadcontainer'><table class='threadtable'>
                            <tr>
                                <td class='topic'><p><a href=".$site->baseurl."/community/forum/topic/".$topic->topic_id.">".$topic->topic_name."</a></p></td>
                                <td class='author'><p><a href='".$site->baseurl."/user/index/".$topic->topic_starter->userid."'>".$topic->topic_starter->username."</a></p></td>
                                <td class='response'><p>
                                    Latest response made by <a href='".$site->baseurl."/user/index/".$topic->topic_recentresponse->post_author->userid."'>".$topic->topic_recentresponse->post_author->username."</a>
                                    on ".$topic->topic_recentresponse->post_datestyledday." at ".$topic->topic_recentresponse->post_datestyledtime."
                                </p></td>
                            </tr>
                        </table></div>";
                    }
                }
                else
                    echo "This board has no topics yet.";
                
                echo "<br><br><a class='mainlink' href='".$site->baseurl."/community/forum/newtopic/".$boardid."'>Post New Topic</a>";
                ?>

<?php   }
    }
include('../../footer.php'); ?>