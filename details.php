<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 13/09/2017
 * Time: 11:47 AM
 */
include "enable_errors.php";
if(!isset($_SESSION))      {          session_start();      }

if (isset($_GET["location"])){
    $displayName=$_GET["location"];
} else if (isset($_POST["location"])){
    $displayName = $_POST["location"];
} else {
    echo "<BR><BR>ERROR : No location specified";
    return;
}

//print_r($_SESSION["locations"]);

//if (($key = array_search($currentLocation, $locations)) !== false) {
//    unset($locations[$key]);
//}
?>
<!--                                               MODALS                                       -->

<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Comment for <?php echo $displayName ?></h4>
            </div>
            <div class="modal-body">
                Enter your comment Fred :<br>
                <textarea id="newjobdetails" class="form-control" rows="10" cols="80"></textarea><br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Submit</button>
            </div>
        </div>

    </div>
</div>

<div id="camera" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add a Spot for <?php echo $displayName ?></h4>
            </div>
            <div class="modal-body">
                Click the button and then wave your device around in all directions untill you hear 3 beeps.
                <input name="Capture" type="file" accept="image/*;capture=camera">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Submit</button>
            </div>
        </div>

    </div>
</div>

<!--                                              DETAILS VIEW                                           -->

<div style="text-align: center">
    <h1><?php echo $displayName ?></h1>

    <div class="col-md-8" style="text-align: left; padding: 10px;">

        <div id="locationView">
<!--            <img src="Locations/--><?php //echo $displayName ?><!--.jpg" style="width: 100%;">-->
            <div class="frame-div">
                <iframe class="frame-child" src="https://storage.googleapis.com/vrview/examples/pano/index.html" height="500px" width="100%"></iframe>
            </div>
            <div style="margin-top: -50px; height: 50px; text-align: center;">
                <span id="fav-icon" class="icon glyphicon glyphicon-heart red" style="font-size: xx-large; display: none !important;"></span>
                <span id="vis-icon" class="icon glyphicon glyphicon-check green pull-left" style="margin-left: 5px; font-size: xx-large; display: none !important;"></span>
            </div>
            <div class="clearfix" style="margin-bottom: 10px">
                <h3 class="">Comments from other Tasmaniacs</h3>
                <button class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-target="#myModal">Add Comment...</button>
            </div>


            <div class="panel panel-default">
                <div class="panel-heading">
                    ManMan33 <p class="pull-right">26th December 2014</p>
                </div>
                <div class="panel-body">
                    Love this place went here last week. Definatly worth a visit!
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Jimmy502 <p class="pull-right">26th December 2014</p>
                </div>
                <div class="panel-body">
                    I hated it. Was raining and cold. Check the weather before you go!
                </div>
            </div>
            <button class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-target="#myModal">Add Comment...</button>
        </div>

        <div id="spotsView" style="text-align: center; display: none;">
            <h1>Great spots found by Tasmaniacs!</h1>
            <?php
            function randomName() {
                $names = array(
                    'Juan',
                    'Luis',
                    'Pedro',
                    'Wilson',
                    'Ted'
                );
                return $names[rand ( 0 , count($names) -1)];
            }

            function randomDate(){
                $timestamp = mt_rand(1, time());
                $randomDate = date("d M Y", $timestamp);
                return $randomDate;
            }

            $locations = $_SESSION["locations"];
            foreach ($locations as $key => $photo):
                $filename = "./Locations/" . $photo;
                $displayName = pathinfo($filename, PATHINFO_FILENAME); // returns Just the name without the file extention
                //details.php?location=<?php echo $displayName
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php echo "Uploade by " . randomName() . " on the " . randomDate(); ?>
                    </div>
                    <div class="panel-body">
                        <?php echo "<img src='$filename' class='spot-photo pull-left'>"; ?>
                        <p>kjh  sf lksjf gskjfshfkhjs fg sdlf gsdf g sdlkfg sdhf  lksjdfhg kljhsdfg klksdfgh sdkfg skldfhg ksdfhg lkshdfg lkhsdfgk lhlkhsdfg </p>
                        <a href="geo:124.028582,-29.201930" target="_blank" class="btn btn-small btn-primary">Get directions to this spot...</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="sidePanel" class="col-md-4" style="text-align: justify">
        <div>
            <p style="color: darkblue; font-size: large">6 hour round trip on main walking track</p>
            <p style="color: darkgreen; font-size: large">Category : Adventure</p>
            <p>
                Ruggedly beautiful with towering sea cliffs and deep sea caves, fur seals, fairy penguins, an abundance of bird life and if you’re visiting in the right season, the opportunity to see migrating whales.

                Bruny Island is an easy day trip from Hobart, and the best way to experience Bruny if you only have half a day is with local legend Rob Pennicott from Pennicott Wilderness Journeys.
            </p>
        </div>
        <div id="ratings">
            <label style="display: block">Average rating from all Tasmaniac's</label>
            <img src="communityrating.JPG" style="display: block">
            <label style="display: block">Your Rating (click the stars)</label>
            <a href="http://antenna.io/demo/jquery-bar-rating/examples/"><img src="communityrating.JPG" style="display: block"></a>
        </div>
        <button id="fav" class="btn btn-primary btn-block"><span class="fav glyphicon glyphicon-heart pull-left"></span> Add this Location to my Bucket List</button>
        <button id="vis" class="btn btn-primary btn-block"><span class="vis glyphicon glyphicon-unchecked pull-left"></span> Ive been here! Mark as Visited</button>
        <button id="spot" class="btn btn-primary btn-block" location="<?php echo $displayName ?>"><span class="glyphicon glyphicon-th pull-left"></span> See the community spots at this location</button>
        <button id="add" class="btn btn-primary btn-block" data-toggle="modal" data-target="#camera"><span class="glyphicon glyphicon-plus pull-left"></span> Add a spot & 360 photo...</button>
        <div class="mapouter" style="margin-top: 15px">
            <div class="gmap_canvas">
                <iframe width="auto" height="auto" id="gmap_canvas"
                        src="https://maps.google.com/maps?q=<?php echo $displayName ?>%2C%20Tasmania%2C%20Australia&t=&z=14&ie=UTF8&iwloc=&output=embed"
                        frameborder="0" scrolling="no" marginheight="0" marginwidth="0">
                </iframe>
            </div>
            <style>.mapouter{overflow:hidden;height:500px;width:100%;}.gmap_canvas {background:none!important;height:500px;width:100%;}</style>
        </div>
    </div>
</div>

<script>
    $(function () {
        $("#fav").click(function () {
            $("span.fav").toggleClass("red");
            $("#fav-icon").fadeToggle();
        })
        $("#vis").click(function () {
            $("span.vis").toggleClass("glyphicon-check");
            $("span.vis").toggleClass("glyphicon-unchecked");
            $("span.vis").toggleClass("green");
            $("#vis-icon").fadeToggle();
        })
        $("#spot").click(function () {
            $("#locationView").slideToggle("medium", function () {
                $("#spotsView").slideToggle();
            });
        })
    })
</script>
