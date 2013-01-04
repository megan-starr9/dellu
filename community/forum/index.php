<?php include('../../header.php');
    if ($id == -1)
    {
        header('Location:'.$site->baseurl.'/');
    }
    else
    {?>
                <h1 class='pagetitle'>Forum</h1>
                
                <?php
                    $forum = new Forum();
                    foreach($forum->forum_categories as $category) // Loop for category display
                    {
                        if (sizeof($category->category_boards) != 0)
                        {
                            if($category->category_accesslevel <= $currentuser->userlevel)
                            {
                                echo "<div id='catcontainer'>".$category->category_name."</div>";
                            }
                            
                            foreach($category->category_boards as $board) // Loop for board display
                            {
                                if ($board->board_accesslevel <= $currentuser->userlevel)
                                {
                                    echo "<div id='boardcontainer'><table class='boardtable'>
                                    <tr>
                                        <td class='title'><p><a href='".$site->baseurl."/community/forum/board/".$board->board_id."'>---".$board->board_name."</a></p></td>
                                        <td class='desc'><p>".$board->board_description."</p></td>
                                        <td class='poster'><p>";
                                            if (sizeof($board->board_normaltopics) != 0 || $board->board_stickytopics != 0)
                                            {
                                                echo "Newest response in <a href='".$site->baseurl."/community/forum/topic/".$board->board_recenttopic->topic_id."'>".$board->board_recenttopic->topic_name."</a>
                                                posted by <a href='".$site->baseurl."/user/index/".$board->board_recenttopic->topic_recentresponse->post_author->userid."'>".$board->board_recenttopic->topic_recentresponse->post_author->username."</a>
                                                <br>Updated ".$board->board_recenttopic->topic_recentresponse->post_datestyledday." at ".$board->board_recenttopic->topic_recentresponse->post_datestyledtime;
                                            }
                                            else
                                                echo "No Posts";
                                            
                                        echo "</p></td>
                                    </tr>
                                    </table></div>";
                                }
                            }
                        }
                    }
                ?>

<?php
    }
include('../../footer.php');
?>