<?php

include('lib/common.php');
// written by omaltese3

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

    $query = "SELECT first_name, last_name, `User`.email " .
         "FROM `User` " .
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
                            <?php  
                                $query1 = "SELECT ROUND(AVG(rating),2) AS 'My Rating' " .
                                    "FROM Rate, Swap " .
                                    "WHERE (Rate.email = Swap.counterpartyEmail " .
                                    "AND Swap.proposerEmail = '{$_SESSION['email']}' " .
                                    "AND Rate.rating IS NOT NULL) " .
                                    "OR (Rate.email = Swap.proposerEmail " .
                                    "AND Swap.counterpartyEmail = '{$_SESSION['email']}' " .
                                    "AND Rate.rating IS NOT NULL)";

                                $result1 = mysqli_query($db, $query1);
                                include('lib/show_queries.php');
                                        
                                if (is_bool($result1) && (mysqli_num_rows($result1) == 0) ) {
                                    print "<td>" . "None" . "</td>";
                                        }
                                else {
                                    $row1 = mysqli_fetch_array($result1, MYSQLI_ASSOC);
                                    print "<td>" . $row1['My Rating'] . "</td>";
                                    }

								?>                      
                        </tr>

                        <tr>
                            <td class="item_label">Unaccepted Swaps</td>
                            <?php  
                                $query2 = "SELECT COUNT(swapID) as 'Unaccepted Swaps' " .
                                    "FROM Swap " .
                                    "WHERE counterpartyEmail ='{$_SESSION['email']}'" .
                                    "AND swapStatus IS NULL ";

                                $result2 = mysqli_query($db, $query2);
                                include('lib/show_queries.php');
                                
                                $row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);
                                print "<td>" . $row2['Unaccepted Swaps'] . "</td>";

								?>                                 
                        </tr>

                        <tr>
                            <td class="item_label">Accepted Swaps</td>
                            <?php  
                                $query3 = "SELECT COUNT(swapID) as 'Accepted Swaps' " .
                                    "FROM Swap " .
                                    "WHERE counterpartyEmail ='{$_SESSION['email']}'" .
                                    "AND swapStatus IS NOT NULL ";

                                $result3 = mysqli_query($db, $query3);
                                include('lib/show_queries.php');
                                
                                $row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC);
                                print "<td>" . $row3['Accepted Swaps'] . "</td>";

								?>                                 
                        </tr>

                        <tr>
                            <td class="item_label">Unrated Swaps</td>
                            <?php  
                                $query4 = "SELECT COUNT(rating) AS 'Unrated Swaps' " .
                                    "FROM Rate " .
                                    "WHERE email='{$_SESSION['email']}'" .
                                    "AND rating IS NULL ";
                                $result4 = mysqli_query($db, $query4);
                                include('lib/show_queries.php');
                                
                                $row4 = mysqli_fetch_array($result4, MYSQLI_ASSOC);
                                print "<td>" . $row4['Unrated Swaps'] . "</td>";

								?>          
                        </tr>
                    </table>                        
                </div>  
            </div>          
        </div> 

                <?php include("lib/error.php"); ?>
                    
                <div class="clear"></div>       
            </div>                  
        </div>
    </body>
</html>
