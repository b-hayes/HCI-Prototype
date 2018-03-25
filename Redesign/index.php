<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 12/09/2017
 * Time: 7:29 PM
 *
 * Images used in this prototype are sourced form
 * https://www.ytravelblog.com/19-best-places-to-visit-in-tasmania/
 */
include "enable_errors.php";
$projectTitle = "Tasmaniac";
$currentLocation = "Launceston.jpg";
$dir = "./Locations"; //path to location photos

if(!isset($_SESSION))      {          session_start();      }

//get locations list if it hasnt already been done
if (!isset($_SESSION["locations"])){
    $locations = scandir ( $dir, 0 ); //get list of images in the Locations folder
    //remove current location from the list
    if (($key = array_search($currentLocation, $locations)) !== false) {
        unset($locations[$key]);
    }
    //and then put it back at the top of the list
    array_unshift($locations , $currentLocation); //now current location will appear first before any others

    //and now by default there will be "." and ".." in the list as scandir includes these folders. So lets remove them...
    if (($key = array_search(".", $locations)) !== false) {
        unset($locations[$key]);
    }
    if (($key = array_search("..", $locations)) !== false) {
        unset($locations[$key]);
    }
    $_SESSION["locations"] = $locations;
    $_SESSION["currentLocation"] = $currentLocation;
}
$locations = $_SESSION["locations"];
//parameters...
$search = $voice = $filter = $filterParam = $sortBy = $sortParam = "";
if(isset($_GET["search"])){ goto NOFILTERS; } //skip all filtering if there is a search term
if(isset($_GET["voice"])){ $voice = "&voice=yes"; }
if(isset($_GET["filter"])){ $filter=$_GET["filter"]; $filterParam = "&filter=" . $filter; }
if(isset($_GET["sortBy"])){ $sortBy=$_GET["sortBy"]; $sortParam="&sortBy=" . $sortBy; }

//adjust whats in the list based on params received
if($sortBy=="distance"){
    //just randomizing the order to give the effect of change between order selections
    shuffle($locations);
    //and then puting current location back at the top of the list again if sorting by distance
    if($sortBy=="distance"){
        //remove current location from the list
        if (($key = array_search($currentLocation, $locations)) !== false) {
            unset($locations[$key]);
        }
        //and then put it back at the top of the list
        array_unshift($locations , $currentLocation);
    }
}
NOFILTERS:
function searchArray($var){
    if (strpos(strtolower($var),  strtolower($_GET["search"])) !== false) {
        return $var;
    }
}
if(isset($_GET["search"])){
    $locations =  array_filter($locations, "searchArray");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $projectTitle; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="overrides.css">
</head>

<body>

<!--                                              NAB BAR                                             -->

<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="">
                <?php echo $projectTitle; ?>
            </a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <li class=""><a href="?<?php echo $voice?>">All Locations</a></li>
                <li class=""><a href="?filter=bucketList<?php echo $voice . $sortParam ?>"><span class="glyphicon glyphicon-heart" style="color: red"></span> Bucket List</a></li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        Sort By
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="?sortBy=distance<?php echo $voice . $filterParam ?>">
                                Distance from me</a></li>
                        <li><a href="?sortBy=rating<?php echo $voice . $filterParam ?>">
                                Popularity (community rating)</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        Category Filter
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="?filter=Adventure<?php echo $voice . $sortParam ?>">
                                Adventure</a></li>
                        <li><a href="?filter=Hiking<?php echo $voice . $sortParam ?>">
                                Hiking</a></li>
                        <li><a href="?filter=Recreational<?php echo $voice . $sortParam ?>">
                                Recreational</a></li>
                    </ul>
                </li>
                <li>
                    <form class="navbar-form">
                        <input type="text" name="search" placeholder="Enter Search Term">
                        <input type="submit" value="Search" class="btn btn-default">
                    </form>
                </li>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Log Out</a></li></li>
                </ul>
            </ul>
        </div>

    </div>
</nav>

<!--                                              CONTENT                                           -->
<div class="container-fluid" style="margin-top: 55px">
    <div id="mainList" class="row" style="text-align: center">
        <?php
        foreach ($locations as $key => $photo):
            $category = "Adventure";                            //default category
            if($key % 2 == 0){ $category = "Hiking"; }          //every 2nd one is Hiking
            if($key % 3 == 0){ $category = "Recreational"; }    //every 3nd one is Recreational
            $visited = $bucket = "display: none !important;";   //default display for visited and favorite icons
            if($key % 4 == 0){ $visited=""; }                   //every 4th one has been visited
            if($key % 5 == 0){ $bucket=""; }                   //every 5th one is on the bucket list
            $filename = $dir . "/" . $photo;
            $displayName = pathinfo($filename, PATHINFO_FILENAME); // returns Just the name without the file extention

            //filtering by just skipping the html...
            if ($bucket!="" && $filter=="bucketList") { goto SKIP; } //bucketList filter
            if ($filter!="bucketList" && $filter != "" && $filter != $category){ goto SKIP; } //category Filter
        ?>
            <a href="#" class="btn btn-primary btn-photo" location="<?php echo $displayName ?>">
                <?php if ($photo == $currentLocation): ?>
                    <p class="map-pin" ><img src="map-Pin.png" class="map-pin"><br>This is your current location.</p>
                <?php endif; ?>
                <?php echo "<img src='$filename' class='btn-photo'>"; ?>
            <?php echo "<p>$displayName ( $category )</p>"; ?>
                <div style="margin-top: -55px; margin-left: 5px; height: 30px; text-align: center;">
                    <span class="icon glyphicon glyphicon-heart red" style="<?php echo $bucket ?>"></span>
                    <span class="icon glyphicon glyphicon-check green pull-left" style="<?php echo $visited ?>"></span>
                </div>
            </a>
            <?php SKIP: ?>
        <?php endforeach; ?>
    </div>
    <div id="details" class="row" hidden>
    </div>
</div>

<!--                                          Assistant for Voice Demo                                   -->
<?php if($voice!=""): ?>
    <a href="#" id="assistant" class="voice">
        <img class="assistant" src="TasTiger-with-shadow.png" style="height: 70px">
    </a>
    <div class="speech">
        <p class="speech">
            Hello! Im here to help.<br>
            Say "Hi Tasmaniac" to give me commands.</p>
    </div>
<?php endif; ?>

</body>
</html>

<script>
    $(function () {
        //user clicks a photo from main view
        $("a.btn-photo").click(function () {
            $("#mainList").fadeOut();
            var location = $(this).attr("location");
            $.ajax({
                url: "details.php",
                type: 'POST',
                data: { location: location },
                success: function(result){
                    $("#details").html(result);
                    $("#details").fadeIn();
                }
            });
        })
        var m = 0;
        var messages = [
            "", "Yes? <br> how can i help?",
            "", "No problem!"
        ];
        $("img.assistant").click(function () {
            $("div.speech").fadeToggle();
            $("p.speech").html(messages[m]);
            m++;
            if(m > messages.length-1){ m=0; }
        })
    })
</script>