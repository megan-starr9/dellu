<?php
    /* FORUM CLASS */
    class Forum extends Site
    {
        // ====================================== Properties ====================
        public $forum_categories; // array of Categories within Forum
        
        //======================================== Methods =========================
        /* Constructor */
        public function __construct()
        {
            // Set Categories
            $catquery = mysql_query("SELECT * FROM forum__category");
            while ($cat = mysql_fetch_array($catquery))
            {
                $this->forum_categories[] = new Category($cat['catid']);
            }
        }
        
        /*******************/
        /* Admin Functions */
        public function add_category($catname,$catlevel)
        {
            $query = "INSERT INTO forum__category (catname,cataccesslevel) VALUES ('$catname','$catlevel')";
            $error = (!mysql_query($query)) ? "<div id='warning'>There has been an error: ".mysql_error()."</div>" : "<div id='alert'>Category successfully added!</div>";
            return $error;
        }
        public function reorder_forum()
        {
            // COMING
        }
    }
    
    /* FORUM CATEGORY CLASS */
    class Category extends Site
    {
        // ====================================== Properties ====================
        public $category_boards; // array of Boards within Category (that are not sub-boards)
        public $category_id;
        public $category_name;
        public $category_accesslevel;
        
        //======================================== Methods =========================
        /* Constructor */
        public function __construct($id)
        {
            // Set Boards
            $boardquery = mysql_query("SELECT * FROM forum__board WHERE boardcat = $id AND boardparent = 0");
            while ($board = mysql_fetch_array($boardquery))
            {
                $this->category_boards[] = new Board($board['boardid']);
            }
            // Set Category Information
            $catarray = mysql_fetch_array(mysql_query("SELECT * FROM forum__category WHERE catid = $id"));
            $this->category_id = $id;
            $this->category_name = $catarray['catname'];
            $this->category_accesslevel = $catarray['cataccesslevel'];
        }
        
        /*******************/
        /* Admin Functions */
        public function add_board($boardname,$boarddesc,$boardlevel)
        {
            $query = "INSERT INTO forum__board (boardname,boardcat,boarddesc,boardaccesslevel) VALUES ('$boardname','".$this->category_id."','$boarddesc','$boardlevel')";
            $error = (!mysql_query($query)) ? "<div id='warning'>There has been an error: ".mysql_error()."</div>" : "<div id='alert'>Board successfully added!</div>"; 
            return $error;
        }
        public function modify_category()
        {
            // COMING
        }
        public function delete_category()
        {
            // COMING
        }
    }
    
    /* FORUM BOARD CLASS */
    class Board extends Site
    {
        // ====================================== Properties ====================
        public $board_normaltopics; // array of normal Topics within Board
        public $board_stickytopics; // array of important, stickied Topics within Board
        public $board_subboards; // array of sub-Boards within Board
        public $board_id;
        public $board_category; // Category Class
        public $board_accesslevel;
        public $board_parent; // Board ID or NULL
        public $board_description;
        public $board_recenttopic; // Topic Class
        
        //======================================== Methods =========================
        /* Constructor */
        public function __construct($id)
        {
            parent::__construct();
            // Set Normal Topics
            $topicquery = mysql_query("SELECT * FROM forum__topic WHERE topicboard = $id AND topicsticky = 0 ORDER BY topicdate DESC");
            while ($topic = mysql_fetch_array($topicquery))
            {
                $this->board_normaltopics[] = new Topic($topic['topicid']);
            }
            // Set Sticky Topics
            $topicquery = mysql_query("SELECT * FROM forum__topic WHERE topicboard = $id AND topicsticky = 1 ORDER BY topicdate DESC");
            while ($topic = mysql_fetch_array($topicquery))
            {
                $this->board_stickytopics[] = new Topic($topic['topicid']);
            }
            // Set Sub-boards
            $subboardquery = mysql_query("SELECT * FROM forum__board WHERE boardparent = $id");
            while ($subboard = mysql_fetch_array($subboardquery))
            {
                $this->board_subboards[] = new Board($subboard['boardid']);
            }
            // Set Board Information
            $boardarray = mysql_fetch_array(mysql_query("SELECT * FROM forum__board WHERE boardid = $id"));
            $this->board_id = $id;
            $this->board_name = $boardarray['boardname'];
            //$this->board_category = new Category ($boardarray['boardcat']);
            $this->board_accesslevel = $boardarray['boardaccesslevel'];
            $this->board_parent = ($boardarray['boardparent'] == 0) ? NULL : $boardarray['boardparent'];
            $this->board_description = $boardarray['boarddesc'];
            $newestquery = mysql_query("SELECT * FROM forum__topic WHERE topicboard = $id ORDER BY topicdate DESC LIMIT 1");
            while ($newestarray = mysql_fetch_array($newestquery))
            {
                $this->board_recenttopic = new Topic($newestarray['topicid']);
            }
        }
        
        /*******************/
        /* Operation Functions */
        public function breadcrumbs()
        {
            $query = mysql_query("SELECT * FROM forum__board WHERE boardid = ".$this->board_id);
            $result = mysql_fetch_array($query);
            
            if($this->board_parent != NULL)
            {
                $parentboard = new Board($this->board_parent);
                echo $parentboard->breadcrumbs()." >><a href='".$this->baseurl."/community/forum/board/".$this->board_id."'>".$this->board_name."</a>";
            }
            else
            {
                echo "<a href='".$this->baseurl."/community/forum'>Forum</a> >> <a href='".$this->baseurl."/community/forum/board/".$this->board_id."'>".$this->board_name."</a>";
            }
        }
        public function valid_board()
        {
            $query=mysql_query("SELECT * FROM forum__board WHERE boardid = $this->board_id");
            $num= mysql_num_rows($query);
            if ($num != 0)
            {                
                return (($this->board_accesslevel) <= ($this->loggeduser->userlevel));
            }
            else
            {
                return false;
            }
        }
        
        /*******************/
        /* User Functions */
        public function submit_topic($title,$message)
        {
            if($title == "")
            {
                return "You must enter a subject.";
            }
            else if ($message == "")
            {
                return "You don't want to leave an empty post, do you?";
            }
            else
            {
                $topicquery = "INSERT INTO forum__topic (topicname,topicboard,topicuser) VALUES ('$title','".$this->board_id."','".$this->loggeduser->userid."')";
                if (!mysql_query($topicquery))
                {
                    return "Topic failed to post";
                }
                $newtopicid = mysql_insert_id();
                $postquery = "INSERT INTO forum__post (posttopic, postuser, posttext) VALUES ($newtopicid, ".$this->loggeduser->userid.", '$message')";
                if (!mysql_query($postquery))
                {
                    return "Post failed to post";
                }
                else
                {
                    return $newtopicid;
                }
            }
        }
        
        /*******************/
        /* Admin Functions */
        public function modify_board()
        {
            // COMING
        }
        public function delete_board()
        {
            // COMING
        }
        public function add_subboard($boardname,$boarddesc,$boardlevel)
        {
            $query = "INSERT INTO forum__board (boardname,boardcat,boardparent,boarddesc,boardaccesslevel) VALUES ('$boardname','".$this->board_category->category_id."','".$this->board_id."','$boarddesc','$boardlevel')";
            $error = (!mysql_query($query)) ? "<div id='warning'>There has been an error: ".mysql_error()."</div>" : "<div id='alert'>Sub-board successfully added!</div>"; 
            return $error;
        }
    }
    
    /* FORUM TOPIC CLASS */
    class Topic extends Site
    {
        // ====================================== Properties ====================
        public $topic_posts; // array of Posts within Topic
        public $topic_id;
        public $topic_name;
        public $topic_recentresponse;
        public $topic_starter; // User Class
        public $topic_board; // Board Class
        public $topic_sticky;
        public $topic_lock;
        
        //======================================== Methods =========================
        /* Constructor */
        public function __construct($id)
        {
            parent::__construct();
            // Set Posts
            $postquery = mysql_query("SELECT * FROM forum__post WHERE posttopic = $id ORDER BY postdate ASC");
            while ($post = mysql_fetch_array($postquery))
            {
                $this->topic_posts[] = new Post($post['postid']);
            }
            // Set Topic Information
            $topicarray = mysql_fetch_array(mysql_query("SELECT * FROM forum__topic WHERE topicid = $id"));
            $topic_recentarray = mysql_fetch_array(mysql_query("SELECT * FROM forum__post WHERE posttopic = $id ORDER BY postdate DESC LIMIT 1"));
            $this->topic_id = $id;
            $this->topic_name = $topicarray['topicname'];
            $this->topic_recentresponse = new Post ($topic_recentarray['postid']);
            $this->topic_starter = new User($topicarray['topicuser']);
            $this->topic_board = $topicarray['topicboard'];
            $this->topic_sticky = $topicarray['topicsticky'];
            $this->topic_lock = $topicarray['topiclock'];
        }
        
        /*******************/
        /* Operation Functions */
        public function valid_topic()
        {
            $query=mysql_query("SELECT * FROM forum__topic WHERE topicid = ".$this->topic_id);
            $num= mysql_num_rows($query);
            if ($num!=0)
            {
                $result = mysql_fetch_array($query);
                $boardid = $result['topicboard'];
                $boardquery = mysql_query("SELECT * FROM forum__board WHERE boardid = $boardid");
                $boardresult = mysql_fetch_array($boardquery);
                return ($boardresult['boardaccesslevel'] <= $this->loggeduser->userrank);
            }
            else
            {
                return false;
            }
        }
        
        /*******************/
        /* User Functions */
        public function submit_post($message)
        {
            $validatequery = mysql_query("SELECT * FROM forum__post WHERE postuser = ".$this->loggeduser->userid." AND posttopic = ".$this->topic_id." AND posttext = '$message'");
            $valid = mysql_num_rows($validatequery) == 0;
            $query = "INSERT INTO forum__post (postuser, posttopic, posttext) VALUES ('".$this->loggeduser->userid."', '".$this->topic_id."', '$message')";
            if(!$valid)
            {
                $error = "<div id='warning'>That would be a double post!</div>";
            }
            else if ($message == "")
            {
                $error = "<div id='warning'>You don't want to post an empty response, do you?</div>";
            }
            else
            {
                if (!mysql_query($query))
                    $error = "<div id='warning'>There has been an error with your response.  ".mysql_error()."</div>";
                else
                {
                    $error = "<div id='alert'>Response successfully posted.</div>";
                    mysql_query("UPDATE forum__topic SET topicdate = CURRENT_TIMESTAMP WHERE topicid = ".$this->topic_id);
                }
            }
            return $error;
        }
        public function modify_topic()
        {
            // COMING
        }
        public function bump_topic()
        {
            $query = "UPDATE forum__topic SET topicdate = CURRENT_TIMESTAMP WHERE topicid = ".$this->topic_id;
            $error = (!mysql_query($query)) ? "<div id='warning'>There has been an error: ".mysql_error()."</div>" : "<div id='alert'>This topic has been bumped!</div>";
            return $error;
        }
        
        /*******************/
        /* Mod Functions */
        public function sticky_topic()
        {
            $query = "UPDATE forum__topic SET topicsticky = '1' WHERE topicid = ".$this->topic_id;
            $error = (!mysql_query($query)) ? "<div id='warning'>There has been an error: ".mysql_error()."</div>" : "<div id='alert'>This topic has been stickied.</div>";
            return $error;
        }
        public function unsticky_topic()
        {
            $query = "UPDATE forum__topic SET topicsticky = '0' WHERE topicid = ".$this->topic_id;
            $error = (!mysql_query($query)) ? "<div id='warning'>There has been an error: ".mysql_error()."</div>" : "<div id='alert'>This topic has been unstickied.</div>";
            return $error;
        }
        public function lock_topic()
        {
            $query = "UPDATE forum__topic SET topiclock = '1' WHERE topicid = ".$this->topic_id;
            $error = (!mysql_query($query)) ? "<div id='warning'>There has been an error: ".mysql_error()."</div>" : "<div id='alert'>This topic has been locked.</div>";
            return $error;
        }
        public function unlock_topic()
        {
            $query = "UPDATE forum__topic SET topiclock = '0' WHERE topicid = ".$this->topic_id;
            $error = (!mysql_query($query)) ? "<div id='warning'>There has been an error: ".mysql_error()."</div>" : "<div id='alert'>This topic has been unlocked.</div>";
            return $error;
        }
        public function move_topic($newboard)
        {
            $query = "UPDATE forum__topic SET topicboard = $newboard WHERE topicid = ".$this->topic_id;
            $error = (!mysql_query($query)) ? "<div id='warning'>There has been an error: ".mysql_error()."</div>" : "<div id='alert'>This topic has been moved.</div>";
            return $error;
        }
        public function delete_topic()
        {
            $this->log("Topic deletion by User ".$this->loggeduser->userid.".  Topic '".$this->topic_name."' started by User ".$this->topic_starter->userid
                       ." containing ".sizeof($this->topic_posts)." posts", "forum_mod");
            $query1 = "DELETE FROM forum__topic WHERE topicid = ".$this->topic_id." LIMIT 1";
            foreach ($this->topic_posts as $post)
            {
                $post->delete_post();
            }
            $error = (!mysql_query($query1)) ? "<div id='warning'>There has been an error: ".mysql_error()."</div>" : "<div id='alert'>Topic has been deleted!</div>";
            return $error;
        }
    }
    
    /* FORUM POST CLASS */
    class Post extends Site
    {
        // ====================================== Properties ====================
        public $post_id;
        public $post_content;
        public $post_author; // User Class
        public $post_topic; // Topic Class
        public $post_date;
        public $post_editdate;
        public $post_reported;
        
        //======================================== Methods =========================
        /* Constructor */
        public function __construct($id)
        {
            parent::__construct();
            // Set Post Information
            $postarray = mysql_fetch_array(mysql_query("SELECT * FROM forum__post WHERE postid = $id"));
            $this->post_id = $id;
            $this->post_content = $postarray['posttext'];
            $this->post_author = new User($postarray['postuser']);
            $this->post_topic = $postarray['posttopic'];
            $this->post_date = $postarray['postdate'];
            $this->post_datestyledday = date("n/j/y ", strtotime($postarray['postdate']));
            $this->post_datestyledtime = date("g:i a ", strtotime($postarray['postdate']));
            $this->post_editdate = $postarray['posteditdate'];
            $this->post_reported = $postarray['postreported'];
        }
        
        /*******************/
        /* Operation Functions */
        public function valid_post()
        {
            $query = mysql_query("SELECT * FROM forum__post WHERE postid = ".$this->post_id);
            $num = mysql_num_rows($query);
            return ($num != 0);
        }
        
        /*******************/
        /* User Functions */
        public function modify_post($newmessage)
        {
            $query = "UPDATE forum__post SET posttext = '$newmessage', postdate = '".$this->post_date."', posteditdate = CURRENT_TIMESTAMP WHERE postid = ".$this->post_id;
            $this->log("Post #".$this->post_id." modified by user ".$this->loggeduser->userid, "forum_mod");
            $error = (!mysql_query($query)) ? "<div id='warning'>There has been an error: ".mysql_error()."</div>" : $this->post_topic;
            return $error;
        }
        public function report_post()
        {
            $query = "UPDATE forum__post SET postreported = 1, postdate = '".$this->post_date."' WHERE postid = ".$this->post_id;
            $error = (!mysql_query($query)) ? "<div id='warning'>There has been an error: ".mysql_error()."</div>" : "<div id='alert'>This post was reported!</div>";
            $this->log("User ".$this->loggeduser->userid." reported post #".$this->post_id, "forum_mod");
            return $error;
        }
        public function delete_post()
        {
            $query = "DELETE FROM forum__post WHERE postid = ".$this->post_id." LIMIT 1";
            $this->log("Post deleted by User ".$this->loggeduser->userid."\n
                       Author: User ".$this->post_author->userid." Date: ".$this->post_date."\r\n".
                       $this->post_content, "forum_mod");
            $error = (!mysql_query($query)) ? "<div id='warning'>There has been an error: ".mysql_error()."</div>" : "<div id='alert'>Post successfully deleted.</div>";
            return $error;
        }
        
        /*******************/
        /* Mod Functions */
        public function excuse_post()
        {
            $query = "UPDATE forum__post SET postreported = '0', postdate = '".$this->post_date."' WHERE postid = ".$this->post_id;
            $error = (!mysql_query($query)) ? "<div id='warning'>There has been an error: ".mysql_error()."</div>" : "<div id='alert'>Post was excused!</div>";
            return $error;
        }
    }
?>