<?php

include('lib/common.php');
// written by omaltese3

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}

$query = "SELECT first_name, last_name " .
"FROM `User` " .
"WHERE `User`.email = '{$_SESSION['email']}'";

$result = mysqli_query($db, $query);

if (!is_bool($result) && (mysqli_num_rows($result) > 0) ) {
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
} else {
	array_push($error_msg,  "SELECT ERROR: User profile... <br>".  __FILE__ ." line:". __LINE__ );
}

$query = "SELECT itemNumber, title, gameType, itemDescription, itemCondition, them.nickname, them.email, " .
    "f.city, f.state, f.postal_code, " .
    "CASE WHEN them.postal_code = me.postal_code THEN NULL " .
    "ELSE " .
    "ROUND(( " . 
	"6371 * 0.621371 * 2 * ATAN2" . 
	"( " .
	"SQRT " . 
	"( " .
	"POWER(SIN((RADIANS(f.latitude - g.latitude)) / 2), 2) " .
	"+ " .
	"COS(RADIANS(f.latitude)) * COS(RADIANS(g.latitude)) " .
	"* " .
	"POWER(SIN((RADIANS(f.Longitude - g.Longitude)) / 2), 2) " .
	") " . 
	", " . 
	"SQRT " . 
	"( 1 - ( " .
	"POWER(SIN((RADIANS(f.latitude - g.latitude)) / 2), 2) " .
	"+ " .
	"COS(RADIANS(f.latitude)) * COS(RADIANS(g.latitude)) " .
	"* " .
	"POWER(SIN((RADIANS(f.Longitude - g.Longitude)) / 2), 2) " .
	")) " . 
	") " .
	"), 1) END " .
	"AS 'Distance' " .
    "FROM Item, `User` them, Address f, `User` me, Address g " .
    "WHERE itemNumber = 1 " . // Replace item number
    "AND them.postal_code = f.postal_code " .
    "AND item.email = them.email " .
    "AND me.email = '{$_SESSION['email']}' " .
    "AND me.postal_code = g.postal_code " ;

$result1 = mysqli_query($db, $query);
include('lib/show_queries.php');
$row1 = mysqli_fetch_array($result1, MYSQLI_ASSOC);

$query5 = "SELECT postal_code, email " .
    "FROM `User` " .
    "WHERE email = '{$_SESSION['email']}' " ;

$result5 = mysqli_query($db, $query5);
$row5 = mysqli_fetch_array($result5, MYSQLI_ASSOC);
?>

<?php include("lib/header.php"); ?>

<title>View Items</title>
</head>
	<body>
        <div id="main_container">
		    <?php include("lib/menu.php"); ?>
            
			<div class="center_content">
				<div class="center_left">
					<div class="title_name"> Item Details  </div>          
					
					<div class="features">   	
                        <div style="float:left;">
                            <table style="width: 150%;">
                                <tr>
                                    <td class="heading"> </td>
                                </tr>

                                <tr>
                                    <td class="heading">Item #  </td>
                                    <?php print "<td>" . $row1['itemNumber'] . "</td>" ; ?>
                                </tr>

                                <tr>
                                    <td class="heading">Title </td>
                                    <?php print "<td>" . $row1['title'] . "</td>" ; ?>
                                </tr>

                                <tr>
                                    <td class="heading">Game type: </td>
                                    <?php print "<td>" . $row1['gameType'] . "</td>" ; ?>
                                </tr>

                                <tr>
                                    <td class="heading">Condition: </td>
                                    <?php print "<td>" . $row1['itemCondition'] . "</td>" ; ?>
                                </tr>

                                <! -- HTML Comment --> 

                                <?php
                                if ($row1['gameType'] == "Video game"){

                                    $query2 = "SELECT itemNumber, platformType, media " .
                                        "FROM videogame " .
                                        "WHERE itemNumber = 1 " ; // Replace item number

                                    $result2 = mysqli_query($db, $query2);
                                    $row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);

                                    print "<tr> <td class=" . "heading" . ">Platform </td>" . " <td>" . $row2['platformType'] . "</td> </tr>" ;
                                    print "<tr> <td class=" . "heading" . ">Media </td>" . " <td>" . $row2['media'] . "</td> </tr>" ;
                                }

                                else if ($row1['gameType'] == "Computer game") {
                                    $query3 = "SELECT itemNumber, platform " .
                                        "FROM computergame " . 
                                        "WHERE itemNumber = 1 " ; // Replace item number

                                    $result3 = mysqli_query($db, $query3);
                                    $row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC);

                                    print "<tr> <td class=" . "heading" . ">Platform </td>" . " <td>" . $row3['platform'] . "</td> </tr>" ;

                                }

                                else if ($row1['gameType'] == "Jigsaw puzzle") {
                                    $query4 = "SELECT itemNumber, pieceCount " .
                                        "FROM jigsawpuzzle " . 
                                        "WHERE itemNumber = 1 " ; // Replace item number

                                    $result4 = mysqli_query($db, $query4);
                                    $row4 = mysqli_fetch_array($result4, MYSQLI_ASSOC);

                                    print "<tr> <td class=" . "heading" . ">Piece Count </td>" . " <td>" . $row4['pieceCount'] . "</td> </tr>" ;

                                }

                                if (is_null($row1['itemDescription'])){
                                    print "<tr> <td>" . $row1['itemDescription'] . "</td> </tr>" ;
                                }

                                else{
                                    print "<tr> <td class=" . "heading" . ">Description </td>" . " <td>" . $row1['itemDescription'] . "</td> </tr>" ;
                                }

                                ?>

                            </table>
                        </div>
                        

                        <div style="float:right;">
                            <table>
                                <tr>
                                    <?php 
                                        if ($row1['email'] == $row5['email']) {
                                            print "<td> " . "  " . "</td>" ;
                                        }
                                        
                                        else {
                                            print "<td class=" . "heading" . ">" . "Offered by " . $row1['nickname'] . "</td>" ; 
                                        }    
                                    ?>
                                </tr>

                                <tr>
                                    <?php 
                                        if ($row1['email'] == $row5['email']) {
                                            print "<td> " . "  " . "</td>" ;
                                        }

                                        else{
                                            print "<td>" . "Location: " . $row1['city'] . ", " . $row1['state'] . " " . $row1['postal_code'] . "</td>" ; 
                                        }
                                        
                                        ?>
                                </tr>

                                <tr>
                                    <?php 

                                        if ($row1['email'] == $row5['email']) {
                                            print "<td> " . "  " . "</td>" ;
                                        }

                                        else{
                                            $query = "SELECT ROUND(AVG(rate.rating),2) as 'AverageRating' FROM Rate, Swap " .
                                                "WHERE (rate.email = swap.counterpartyEmail " .
                                                "AND swap.proposerEmail = '{$row1['email']}' " .
                                                "AND rate.rating IS NOT NULL) " .
                                                "OR (rate.email = swap.proposerEmail " .
                                                "AND swap.counterpartyEmail = '{$row1['email']}' " .
                                                "AND rate.rating IS NOT NULL) " ;
                                            
                                            $result = mysqli_query($db, $query);
                                            include('lib/show_queries.php');
                                            $row4 = mysqli_fetch_array($result, MYSQLI_ASSOC);

                                            if ( is_null($row4['AverageRating'])){

                                                print "<td> " . "Rating: " . "None " . "</td>" ; 
                                            }
                                            else {

                                                print "<td> " . "Rating: " . $row4['AverageRating'] . "</td>" ;
                                            } 
                                        }
                                    ?>

                                </tr>

                                <tr>

                                    <?php 

                                        $int_val_result1 = intval($row1['Distance']);

                                        if ( $row5['postal_code'] == $row1['postal_code'] ) {
                                            print "<td> " . "  " . "</td>" ;
                                        }

                                        else if ($int_val_result1 >= 0 AND $int_val_result1 <= 25 ) {
                                            print "<td bgcolor=" . "green".">" . "Distance: " . $int_val_result1 . "</td>" ; 
                                        }

                                        else if ($int_val_result1 > 25 AND $int_val_result1 <= 50 ) {
                                            print "<td bgcolor=" . "yellow".">" . "Distance: " . $int_val_result1 . "</td>" ; 
                                        }

                                        else if ($int_val_result1 > 50 AND $int_val_result1 <= 100 ) {
                                            print "<td bgcolor=" . "orange".">" . "Distance: " . $int_val_result1 . "</td>" ; 
                                        }

                                        else if ($int_val_result1 > 100 ) {
                                            print "<td bgcolor=" . "red".">" . "Distance: " . $int_val_result1 . "</td>" ; 
                                        }

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