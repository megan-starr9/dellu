<?php include('../header.php');
    if (($id == -1) || ($currentuser->userlevel < 3))
    {
        header('Location:'.$site->baseurl.'/');
    }
    else
    {
        $error = "";
        if (isset($_POST['catadd']) && $currentuser->userlevel >= 4)
        {
            $forum = new Forum();
            $catname = mysql_real_escape_string($_POST['catname']);
            $catlevel = mysql_real_escape_string($_POST['cataccess']);
            $error = $forum->add_category($catname,$catlevel);
        }
        else if(isset($_POST['boardadd']) && ($currentuser->userlevel >= 4))
        {
            $boardname = mysql_real_escape_string($_POST['boardname']);
            $boardcat = mysql_real_escape_string($_POST['boardcat']);
            $boardparent = mysql_real_escape_string($_POST['boardparent']);
            $boarddesc = mysql_real_escape_string($_POST['boarddesc']);
            $boardlevel = mysql_real_escape_string($_POST['boardaccess']);
            
            $parent = new Board($boardparent);
            if ($parent->valid_board())
            {
                $error = $parent->add_subboard($boardname,$boarddesc,$boardlevel);
            }
            else
            {
                $category = new Category($boardcat);
                $error = $category->add_board($boardname,$boarddesc,$boardlevel);
            }
        }
        else if(isset($_POST['catmodify']))
        {
            
        }
        else if(isset($_POST['boardmodify']))
        {
            
        }
        else if(isset($_POST['excuse']))
        {
            $postid = mysql_real_escape_string($_POST['post']);
            $post = new Post($postid);
            
            $error = $post->excuse_post();
        }
        else if(isset($_POST['delete']))
        {
            $postid = mysql_real_escape_string($_POST['post']);
            $post = new Post($postid);
            
            $error = $post->delete_post();
        }
        echo $error;
    ?>
                <h3>Admin CP: Forums</h3>
                
                <?php if($currentuser->userlevel >= 4)
                { ?>
                <table width='100%'>
                    <tr>
                        <td>
                            <h4>Add a Category</h4>
                            <form class="prettyform" method="post">
                                <p>Category Name: <input type="text" name="catname"></p>
                                <p>Category Access Level: <select name="cataccess">
                                    <option value='5'>Owner/Coder/Head Admin</option>
                                    <option value='4'>Administrators</option>
                                    <option value='3'>Moderators</option>
                                    <option value='2'>Staff Artists</option>
                                    <option value='0'>Members</option>
                                </select></p>
                                <input type="submit" name="catadd" value="Add Category">
                            </form>
                        </td>
                        <td>
                            <h4>Add a Board</h4>
                            <form class="prettyform" method="post">
                                <p>Board Name: <input type="text" name="boardname"></p>
                                <p>Board Category: <select name="boardcat">
                                <?php
                                    $query = mysql_query("SELECT * FROM forum__category");
                                    while ($cat = mysql_fetch_array($query))
                                    {
                                        echo "<option value='".$cat['catid']."'>".$cat['catname']."</option>";
                                    }
                                ?>
                                </select></p>
                                <p>Board Parent: <select name="boardparent">
                                    <option value="0">None</option>
                                <?php
                                    $query = mysql_query("SELECT * FROM forum__board");
                                    while ($board = mysql_fetch_array($query))
                                    {
                                        echo "<option value='".$board['boardid']."'>".$board['boardname']."</option>";
                                    }
                                ?>
                                </select></p>
                                <p>Board Description: <input type="text" name="boarddesc"></p>
                                <p>Board Access Level: <select name="boardaccess">
                                    <option value='5'>Owner/Coder/Head Admin</option>
                                    <option value='4'>Administrators</option>
                                    <option value='3'>Moderators</option>
                                    <option value='2'>Staff Artists</option>
                                    <option value='0'>Members</option>
                                </select></p>
                                <input type="submit" name="boardadd" value="Add Board">
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h4>Modify a Category</h4>
                            <p>Coming...</p>
                        </td>
                        <td>
                            <h4>Modify a Board</h4>
                            <p>Coming...</p>
                        </td>
                    </tr>
                </table>
                <?php } ?>
                
                <h4>Reported Posts</h4>
                <?php
                    $reportedquery=mysql_query("SELECT * FROM forum__post WHERE postreported = 1");
                    
                    while ($reported = mysql_fetch_array($reportedquery))
                    {
                        $postid = $reported['postid'];
                        echo $reported['posttopic'].": ".$reported['posttext']."
                        <form class='prettyform' method='post' action=''>
                            <input type='hidden' name='post' value='$postid'></input>
                            <input method='post' type='submit' action='excuse' name = 'excuse' value='Excuse'>
                             || <input method='post' type='submit' name='delete' value='Delete'><br>
                        </form>";
                    }
                ?>

<?php
    }
include('../footer.php');
    
?>