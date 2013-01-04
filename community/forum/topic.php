<?php include('../../header.php');
    if ($id == -1)
    {
        header('Location:'.$site->baseurl.'/');
    }
    else
    {
        $uri = $_SERVER['REQUEST_URI'];
        $segment = explode("/", $uri);
        $topicid = (isset($segment[4])) ? $segment[4] : 0;
        $pageid = (isset($segment[5])) ? $segment[5] : 1 ;
        $topic = new Topic($topicid);
        
        if(!$topic->valid_topic())
        {
            echo "<p>That topic does not exist!</p>";
        }
        else
        { ?>
            
                <?php
                $board = new Board($topic->topic_board);
                $board->breadcrumbs();
                
                $error = "";
                // Topic Forms
                if (isset($_POST['topicbump']))
                {
                    $topicid = mysql_real_escape_string($_POST['topic']);
                    $topicaffected = new Topic($topicid);
                    if ($topicaffected->valid_topic())
                    {
                        $error = $topicaffected->bump_topic();
                    }
                    else
                        $error = "<div id='warning'>You cannot bump a topic that does not exist!</div>";
                }
                else if(isset($_POST['topiclock']))
                {
                    $topicid = mysql_real_escape_string($_POST['topic']);
                    $topicaffected = new Topic($topicid);
                    if ($topicaffected->valid_topic())
                    {
                        $error = $topicaffected->lock_topic();
                    }
                    else
                        $error = "<div id='warning'>You cannot lock a topic that does not exist!</div>";
                }
                else if(isset($_POST['topicunlock']))
                {
                    $topicid = mysql_real_escape_string($_POST['topic']);
                    $topicaffected = new Topic($topicid);
                    if ($topicaffected->valid_topic())
                    {
                        $error = $topicaffected->unlock_topic();
                    }
                    else
                        $error = "<div id='warning'>You cannot unlock a topic that does not exist!</div>";
                }
                else if(isset($_POST['topicsticky']))
                {
                    $topicid = mysql_real_escape_string($_POST['topic']);
                    $topicaffected = new Topic($topicid);
                    if ($topicaffected->valid_topic())
                    {
                        $error = $topicaffected->sticky_topic();
                    }
                    else
                        $error = "<div id='warning'>You cannot sticky a topic that does not exist!</div>";
                }
                else if(isset($_POST['topicunsticky']))
                {
                    $topicid = mysql_real_escape_string($_POST['topic']);
                    $topicaffected = new Topic($topicid);
                    if ($topicaffected->valid_topic())
                    {
                        $error = $topicaffected->unsticky_topic();
                    }
                    else
                        $error = "<div id='warning'>You cannot unsticky a topic that does not exist!</div>";
                }
                else if(isset($_POST['topicdelete']))
                {
                    $topicid = mysql_real_escape_string($_POST['topic']);
                    $topicaffected = new Topic($topicid);
                    if ($topicaffected->valid_topic())
                    {
                        $error = $topicaffected->delete_topic();
                    }
                    else
                        $error = "<div id='warning'>You cannot delete a topic that does not exist!</div>";
                }
                //Post Forms
                else if(isset($_POST['response']))
                {
                    $message = mysql_escape_string(substr($_POST['message'], 0, 30000));
                    $error = "<br>".$topic->submit_post($message);
                }
                else if (isset($_POST['reportpost']))
                {
                    $postid = mysql_real_escape_string($_POST['post']);
                    $postaffected = new Post($postid);
                    if ($postaffected->valid_post())
                    {
                        $error = $postaffected->report_post();
                    }
                    else
                        $error = "<div id='warning'>You cannot report a post that does not exist!</div>";
                }
                else if (isset($_POST['deletepost']))
                {
                    $postid = mysql_real_escape_string($_POST['post']);
                    $postaffected = new Post($postid);
                    if ($currentuser->userlevel <= 2)
                        $error = "<div id='warning'>You do not have permission to delete posts.</div>";
                    else if ($postaffected->valid_post())
                        $error = $postaffected->delete_post();
                    else
                        $error = "<div id='warning'>You cannot delete a post that does not exist!</div>";
                }
                else if (isset($_POST['modifypost']))
                {
                    $postid = mysql_real_escape_string($_POST['post']);
                    $postaffected = new Post($postid);
                    if ($currentuser->userlevel <= 2 && $currentuser->userid != $postaffected->post_author->userid)
                        $error = "<div id='warning'>You do not have permission to edit that post.</div>";
                    else if($postaffected->valid_post())
                        $error = $postaffected->modify_post($message);
                    else
                        $error = "<div id='warning'>You cannot edit a post that does not exist!</div>";
                }
                echo $error; ?>
                
                <h2 class='minortitle'><?php echo $topic->topic_name; ?></h2>
                <form action='' method='post' style='float:right;margin-right: 15px;'>
                    <input type='hidden' name='topic' value='<?php echo $topic->topic_id; ?>'></input>
                    <input type='submit' name='topicbump' value='Bump Topic' class='link'></input>
                    <?php if($currentuser->userlevel > 2)
                    {
                        if($topic->topic_sticky)
                        {?>
                    <input type='submit' name='topicunsticky' value='Unsticky Topic' class='link'></input>
                        <?php }
                        else { ?>
                    <input type='submit' name='topicsticky' value='Sticky Topic' class='link'></input>
                        <?php }
                        if ($topic->topic_lock)
                        {?>
                    <input type='submit' name='topicunlock' value='Unlock Topic' class='link'></input>
                        <?php }
                        else { ?>
                    <input type='submit' name='topiclock' value='Lock Topic' class='link'></input>
                    <?php }?>
                    <input type='submit' name='topicdelete' value='Delete Topic' class='link' onclick="return confirm('Are you sure you want to delete this topic?');"></input>
                    <?php } ?>
                </form><br>
                
                <?php
                // Display posts
                $i = 0;
                    echo "Page: ";
                for ($j=1; $j<=((sizeof($topic->topic_posts)/13)+1);$j++)
                        echo "<a href='".$site->baseurl."/community/forum/topic/$topicid/$j'>$j</a> || ";
                foreach ($topic->topic_posts as $post) // Loop for posts
                {
                    // Pagination, break from loop
                    if (($i != 0)&&(($i%13)==0)&&($i>(($pageid-1)*13)))
                            break;
                        
                    // Modulo for odd/even post color change
                    $mod = ($i%2 == 0) ? "evenpost" : "oddpost";
                    $divid = ($i==0) ? "initialpost" : $mod;
                    $posttext = ($post->post_reported == 1) ? "<p style='font-style:italic'>-This post was reported and is awaiting moderation.-</p>" : "<p>".$post->post_content."</p>";
                    $icon = (file_exists("../../images/avatars/".$post->post_author->userid.".png")) ?
                    "<img src='".$site->baseurl."/images/avatars/".$post->post_author->userid.".png'>" : "";
                    $status = ($post->post_author->online) ? "Online" : "Offline";
                    
                    // Loop through for pages
                    if (($i >= (($pageid-1)*13)) || ($pageid == 1))
                    {
                        if ($i%2 != 0)
                        { ?>
                            <div id='<?php echo $divid; ?>'><table class='posttable'>
                                    <tr>
                                        <td class='postdate'><p>Posted: <?php echo $post->post_datestyledday; ?> at <?php echo $post->post_datestyledtime; ?>
                                        <?php if ($currentuser->userlevel > 2 || $currentuser->userid == $post->post_author->userid)
                                        {?>
                                             || <a href='<?php echo $site->baseurl; ?>/community/forum/modify/<?php echo $post->post_id; ?>'>Modify Post</a>
                                        <?php } ?>
                                        </p></td>
                                        <td class='postauthorright' rowspan='2'>
                                            <p><a href='<?php echo $site->baseurl; ?>/user/index/<?php echo $post->post_author->userid; ?>'><?php echo $post->post_author->username; ?></a><br>
                                            <?php echo $post->post_author->userrank.", $status"; ?><br>
                                            <?php echo $icon; ?><br>
                                            Posts: <?php echo $post->post_author->postcount; ?><br>
                                            <form method='post' action=''>
                                                <input type='hidden' name='post' value='<?php echo $post->post_id; ?>'>
                                                <input type='submit'  name='reportpost' value='Report Post' class='link' onclick="return confirm('Are you sure you want to report this post?');"></input>
                                                <?php if ($currentuser->userlevel > 2)
                                                {?>
                                                <input type='submit' name='deletepost' value='Delete Post' class='link' onclick="return confirm('Are you sure you want to delete this post?');"></input>
                                                <?php } ?>
                                            </form>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class='postcontent'><?php echo $posttext; ?></div></td>
                                    </tr>
                                </table></div>
                        <?php }
                        else
                        { ?>
                            <div id='<?php echo $divid; ?>'><table class='posttable'>
                                    <tr>
                                        <td class='postauthorleft' rowspan='2'>
                                            <p><a href='<?php echo $site->baseurl; ?>/user/index/<?php echo $post->post_author->userid; ?>'><?php echo $post->post_author->username; ?></a><br>
                                            <?php echo $post->post_author->userrank.", $status"; ?><br>
                                            <?php echo $icon; ?><br>
                                            Posts: <?php echo $post->post_author->postcount; ?><br>
                                            <form method='post' action=''>
                                                <input type='hidden' name='post' value='<?php echo $post->post_id; ?>'>
                                                <input type='submit'  name='reportpost' value='Report Post' class='link' onclick="return confirm('Are you sure you want to report this post?');"></input>
                                                <?php if ($currentuser->userlevel > 2)
                                                {?>
                                                <input type='submit' name='deletepost' value='Delete Post' class='link' onclick="return confirm('Are you sure you want to delete this post?');"></input>
                                                <?php } ?>
                                            </form>
                                        </td>
                                        <td class='postdate'><p>Posted: <?php echo $post->post_datestyledday; ?> at <?php echo $post->post_datestyledtime; ?>
                                        <?php if ($currentuser->userlevel > 2 || $currentuser->userid == $post->post_author->userid)
                                        {?>
                                             || <a href='<?php echo $site->baseurl; ?>/community/forum/modify/<?php echo $post->post_id; ?>'>Modify Post</a>
                                        <?php } ?>
                                        </p></td>
                                    </tr>
                                    <tr>
                                        <td class='postcontent'><?php echo $posttext; ?></div></td>
                                    </tr>
                                </table></div>
                        <?php }
                    }
                    $i++;
                }
                echo "Page: ";
                for ($j=1; $j<=((sizeof($topic->topic_posts)/13)+1);$j++)
                    echo "<a href='".$site->baseurl."/community/forum/topic/$topicid/$j'>$j</a> || ";
                ?>
                
                <!-- Response Form-->
                <?php if (!$topic->topic_lock)
                { ?>
                <br><br>
                <form class="prettyform" action="" method="post">
                    <textarea id="bbcode" name="message"></textarea>
                    <input type="submit" Value="Post Response" name="response" ></input>
                </form>
                <?php } ?>

<?php   }
    }
include('../../footer.php');?>