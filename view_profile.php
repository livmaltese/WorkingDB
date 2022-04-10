<?php

include('lib/common.php');
// written by omaltese3

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

    $query = "SELECT first_name, last_name, `User`.email, ROUND(AVG(rating),2) AS 'My Rating' " .
         "FROM `User` INNER JOIN Rate on `User`.email = Rate.email " .
         "WHERE `User`.email='{$_SESSION['email']}'";

    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
 
    if ( !is_bool($result) && (mysqli_num_rows($result) > 0) ) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    } else {
        array_push($error_msg,  "Query ERROR: Failed to get User profile...<br>" . __FILE__ ." line:". __LINE__ );
    }
?>

<?php include("lib/header.php"); ?>
<title>GameSwap Profile</title>
</head>

<body>
        <div id="main_container">
    <?php include("lib/menu.php"); ?>

    <div class="center_content">
        <div class="center_left">
            <div class="title_name">GameSwap</div>          
            <div class="features">   
            
            <div class="profile_section">
                    <div class="subtitle">
                    <?php print "Welcome, ".$row['first_name'] . ' ' . $row['last_name']."!"; ?>
                </div>   
                    <table>
                        <tr>
                            <td class="item_label">My Rating</td>                        
                        </tr>
                        <?php  
                                $query = "SELECT email, ROUND(AVG(rating),2) AS 'My Rating' " .
                                    "FROM Rate " .
                                    "WHERE email='{$_SESSION['email']}'";
                                $result = mysqli_query($db, $query);
                                include('lib/show_queries.php');
                                        
                                if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                                        array_push($error_msg,  "Query ERROR: Failed to get rating information...<br>" . __FILE__ ." line:". __LINE__ );
                                        } 
                                             
									while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
										print "<td>" . $row['My Rating'] . "</td>";
									}
								?>

                        <tr>
                            <td class="item_label">Unaccepted Swaps</td>
                        </tr>
                        <tr>
                            <td class="item_label">Unrated Swaps</td>
                        </tr>
                    </table>                        
                </div>  
            </div>          
        </div> 

                <?php include("lib/error.php"); ?>
                    
                <div class="clear"></div>       
            </div>    

               <?php include("lib/footer.php"); ?>
                 
        </div>
    </body>
</html>