<?php
session_start();
if(!isset($_SESSION['user']) and !isset($_SESSION['usertype'])){
  header('Location: ./login');
}
define ("SECRETKEY", "bcd1669c0232a0bfda48a63ecbf16acd");
include_once('class.misc.php');
$misc = new misc();
$user = $_SESSION['user'];
$usertype = $_SESSION['usertype'];
$user = $misc->getUser($user);
if($user['usertype'] != "coordinator"){
  header('Location: ./dashboard');
}
$event = $misc->getEventOfCoordinator($user['username']);
if(!isset($event['rounds']) or $event['rounds'] != 1){
    header('Location: ./dashboard');
}
$time = $misc->timeLeftForNextRound($event['eventID']); if($time == false){ header('Location: round-settings'); }

$title = "Round Settings | Culrav";
if(($_SERVER['REQUEST_METHOD'] === 'GET') or (isset($_POST['action']) and $_POST['action'] != "getContentPage")){
    include_once('components/header.php');
}
?>
<style>
.display-style-1 > .flex{flex-wrap:wrap;justify-content:center;}
.display-style-1{width:80%;margin:auto;}
.container-left{width:30%;padding:2.5%;}
.container-right{width:60%;padding:2.5%;}
.profile-image{height:50px;width:50px;margin:0 auto;padding:2%;border:2px solid var(--text-color);border-radius:50%;overflow:hidden;}
.profile-about,.events-in-action{padding:35px;margin:0 0 40px 0;text-align:center;border-radius:8px;box-shadow:0 0 5px 2px var(--highlight-color);}
.profile-image > img{width:100%;height:auto;border-radius:50%;}
.profile-details{margin:25px 0;}.profile-about a{border:1px solid var(--text-color);padding:8px 12px;line-height:40px}.profile-about a:hover{border-color:var(--highlight-color);}
.profile-details > h3{font-size:1.2rem;}.profile-details > p{margin:0;opacity:0.66;font-size:0.9rem;line-height:1rem;}
.events-in-action > div{position:relative;border-radius:15px;margin:25px 0;background:var(--highlight-color);}
.events-in-action{min-height:200px;}
.events-in-action p{font-size:1.0rem;line-height:1.0rem;margin:0;width:fit-content;margin:auto;}
p.simple{font-size:1.0rem;line-height:1.0rem;margin:0;}
input[type="text"]{background:var(--background-color);border:1px solid var(--text-color);padding:8px 1%;color:var(--text-color);font-family:"Muli",sans-serif;width:92%;margin:0 3%;}
select{background:var(--background-color);border:1px solid var(--text-color);padding:8px 1%;color:var(--text-color);font-family:"Muli",sans-serif;width:100%;margin:10px 0;}
.container-right > div{padding:20px;box-shadow:0 0 5px 2px var(--highlight-color);margin:40px 0;}
.container-right > div:nth-of-type(1){margin-top:0;}
.container-right a{text-decoration:underline;line-height:150%;}
[id$="message"]{font-size:80%;line-height:150%;display:none;}
.choice{margin-top:8px;}
.choice > button:first-of-type{margin-right:20px;border-color:green;}
.choice > button{display:inline-block;}
.choice > button > i{margin-right:10px}
a.button-square:hover{box-shadow:none;}
.profile-about a.button-square{margin:0 auto;}

table {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
  border: 1px solid var(--text-color);
}

th, td {
  text-align: left;
  padding: 8px;
  border:1px solid var(--text-color);
  text-align:left;
}

tr:nth-child(even){opacity:0.8;}

@media only screen and (max-width:990px){
    .container-left,.container-right{width:100%;}
}
@media only screen and (max-width:400px){
    section{margin-top:30px;}
}
</style>
<section>
<div class="display-style-1">
    <div class="flex">
        <div class="container-right">
            <div>
                <h3>Go to dashboard</h3>
                <p class="simple">You want to go back to dashboard to register for more events?</p><br/>
                <button class="submit-button" onclick="window.location.href='dashboard'"><i class="fa fa-arrow-left" style="margin-right:10px"></i>Dashboard</button>
            </div>
        <?php
        if($user['usertype'] == 'coordinator'){
        ?>
        <?php if($event['registrationStatus'] == 1){ //If registrations are open ?>
                <div>
                    <h3>Registrations are open.</h3>
                    <ul>
                        <li style="margin-bottom:10px">You can make any changes you want right now, after you close the registrations, rounds will begin automatically and you won't be able to make any changes.</li>
                        <li>If you do not mark qualify to atleast one participant before the start of another round, every participant from that round will be eligible for the next round.</li>
                    </ul>
                </div>
                <div>
                    <h3>Round Timings</h3>
                    <p class="simple">Here you can add rounds and have custom duration for each rounds.</p>
                    <p class="simple">Total Rounds: <span id="round-count"><?php echo $misc->getTotalRoundsForEvent($event['eventID']); ?></span></p>
                    <ul id="rounds" style="margin:20px 0;">
                    <?php
                    $rounds = $misc->getUpdatedRoundListForEvent($event['eventID']);
                    foreach($rounds as $key => $round){ ?>
                        <li>Round <?php echo ($key+1).': '.$round.' days';?></li>
                    <?php
                    }
                    ?>
                    </ul>
                    <div style="margin:0 0 15px" >
                        <label>Round Duration: (in days)</label>
                        <input id="round-duration" type="text" name="duration" style="margin:5px 0 0" placeholder="2 days"/>
                        <p id="duration-message" class="simple"></p>
                    </div>
                    <button id="add-round" class="submit-button" style="display:inline-block">Add Round</button>
                    <button id="remove-round" class="submit-button" style="display:inline-block;margin-left:20px;">Remove Round</button>
                </div>
        <?php }
              else{ ?>
                <div>
                    <h3>Registrations are closed</h3>
                    <p class="simple">Registrations are closed, so you won't be able to change any of the settings now. You can only qualify the participants for the rounds.</p>
                    <p class="simple">Time Left For Next Round: <?php echo $time; ?></p>
                </div>

                <div>
                    <h3>Qualify Participants</h3>
                    <p class="simple">You'll be shown a list of students and their submission links and if you select the checkbox to qualify them, they will be qualified for the next round. Please refresh the page once again to cross check if your changes have been made.</p>
                    <?php $submissions = $misc->getSubmissionLinksOfRounds($event['eventID']); ?>
                    <table style="margin-top:10px;">
                    <?php foreach($submissions as $submission){ 
                        if($event['eventtype'] == 'individual'){?>
                        <tr><td><?php $participant = $misc->getUser($submission['participantID']); echo $participant['name']; ?></td><td><a style="color:var(--text-color)" href="<?php echo $submission['submissionLink']; ?>">Click Here</a></td><td><input class="qualify-participant" data-participantID="<?php echo $submission['participantID']; ?>" type="checkbox" <?php echo ($submission['qualify'] == 1)?'checked':''; ?>/></td></tr>
                    <?php
                        }
                        else if($event['eventtype'] == 'team'){?>
                        <tr><td><?php echo $misc->getTeamNameByTeamID($submission['participantID']); ?></td><td><a style="color:var(--text-color)" href="<?php echo $submission['submissionLink']; ?>">Click Here</a></td><td><input class="qualify-participant" data-participantID="<?php echo $submission['participantID']; ?>" type="checkbox" <?php echo ($submission['qualify'] == 1)?'checked':''; ?>/></td></tr>
                    <?php
                        }
                    }?>
                    </table>
                    <?php if(count($submissions) == 0){ ?><p class="simple">No submissions have been made yet!</p><?php }?>
                    <p id="qualify-message" class="simple"></p>
                </div>
                <?php
                if($event['current_round'] >= 2 && $event['current_round'] != NULL){ ?>
                <div>
                    <h3>Qualified Participants</h3>
                    <p class="simple">The qualified participants for this round are as follows:</p>
                    <table style="margin-top:10px;">
                    <?php $submissions = $misc->qualifiedParticipants($event['eventID']);foreach($submissions as $submission){ 
                        if($event['eventtype'] == 'individual'){?>
                        <tr><td><?php $participant = $misc->getUser($submission['participantID']); echo $participant['name']; ?></td><td><?php echo $participant['contact']; ?></td></tr>
                    <?php
                        }
                        else if($event['eventtype'] == 'team'){?>
                        <tr><td><?php echo $misc->getTeamNameByTeamID($submission['participantID']); ?></td></tr>
                    <?php
                        }
                    }?>
                    </table>
                </div>
                <?php
                }
                ?>
        <?php }
        }
        ?>
        </div>
        <div class="container-left">
            <div class="profile-about">
                <div class="profile-image">
                    <img src="images/profile-blank.png">
                </div>
                <div class="profile-details">
                    <h3><?php echo $user['name'];?></h3>
                    <p><?php echo $user['college'];?></p>
                <?php 
                if($user['usertype'] == 'students'){
                    if($user['verified'] == 0){?>
                        <p class="simple">Your account isn't verified yet.</p>
                    <?php
                    }
                    else{ ?>
                        <p class="simple">Welcome to Culrav 2020</p>
                        <p style="font-size:90%;"><?php echo 'FestID: '.$user['festID'];?></p>
                    <?php
                    }
                }
                else if($user['usertype'] == 'coordinator'){
                ?>
                    <p class="simple">You're a Coordinator</p>
                    <p class="simple">Your Event: <?php echo $event['event_name']; ?></p>
                <?php
                }
                else if($user['usertype'] == 'admin'){
                ?>
                    <p class="simple">You're the superior authority.</p>
                    <p class="simple">Have a great day!</p>
                <?php
                }?>
                </div>
                <div style="margin-top:15px"><a href="settings" class="button-square" style="color:var(--text-color);text-decoration:none;">Settings</a></div>
                <div style="margin-top:15px"><a href="logout" class="button-square" style="color:var(--text-color);text-decoration:none;">Log out</a></div>
            </div>
            <?php
            if($user['usertype'] == 'students'){
            ?>
                <div class="events-in-action">
                    <h3>Events Registered</h3>
                <?php
                $registered_events = $misc->getUserRegisteredEvents($user['username']);
                if(count($registered_events) == 0){?>
                        <div><p>Not yet</p></div>
                <?php
                }
                foreach($registered_events as $a){?>
                        <div><p><?php echo $misc->getEventNameByEventID($a['eventID']);?></p></div>
                <?php
                }
                ?>
                </div>
            <?php
            }
            else if($user['usertype'] == 'admin'){
            ?>
                <div class="profile-about">
                    <div class="profile-details" style="margin-top:0;">
                        <h3>Total Events Conducting</h3>
                    </div>
                        <h3><?php echo $misc->getTotalEventsCount();?></h3>
                </div>
                <div class="profile-about">
                    <div class="profile-details" style="margin-top:0;">
                        <h3>Total Students Registered</h3>
                    </div>
                        <h3><?php echo $misc->getTotalStudentsRegisteredCount();?></h3>
                </div>
                <div class="profile-about">
                    <div class="profile-details" style="margin-top:0;">
                        <h3>Total Students Paid Fees</h3>
                    </div>
                        <h3><?php echo $misc->getTotalStudentsPaidFeesCount();?></h3>
                </div>
            <?php
            }
            else if($user['usertype'] == 'coordinator'){
                ?>
                    <div class="profile-about">
                        <div class="profile-details" style="margin-top:0;">
                            <h3>Total <?php echo $event['eventtype'].'s'; ?></h3>
                        </div>
                            <h3><?php echo $misc->getParticipantOrTeamCountInEvent($event['eventID'],$event['eventtype']);?></h3>
                    </div>
                    <div class="profile-about">
                        <div class="profile-details" style="margin-top:0;">
                            <h3>Total Submissions</h3>
                        </div>
                            <h3><?php $data = $misc->getSubmissionLinksForCoordinatorsByEventID($user['username'],$event['eventID']); echo count($data['data']); ?></h3>
                    </div>
                <?php
                }
            ?>
        </div>
    </div>
</div>
</section>
<script>
<?php if($event['registrationStatus'] == 1){ ?>
$('#round-duration').on('keyup',function(event){
    $('#duration-message').removeClass("error");
    $('#duration-message').removeClass("success");
    $('#duration-message').css("display","none");
    if(event.which == 13){$('#add-round').trigger("click");}
});
$('#add-round').on("click",function(){
    var duration = $('#round-duration').val();
    var data = {'action':'addRound','key':'<?php $time = time(); echo base64_encode(openssl_encrypt($user['username'].'@culrav'.$time.','.$_SESSION['password_change_count'],"AES-128-ECB",SECRETKEY));?>','duration':duration};
    $.ajax({
        url:'ajax',
        data:data,
        method:"POST",
        dataType:'json',
        success:function(data){
            $('#duration-message').removeClass("error");
            $('#duration-message').removeClass("success");
            $('#duration-message').html(data.msg);
            if(data.status == true){
                $('#duration-message').addClass("success");
                $('#duration-message').css("display","block");
                $('#duration-message').html(data.msg);
                $('#round-count').html(data.data.length);
                $('ul#rounds').html("");
                $('#round-duration').val('');
                var a = data.data;
                for(var i=0;i<a.length;i++)
                    $('ul#rounds').append("<li>Round "+(i+1)+": "+a[i]+" days</li>");
            }
            else{
                $('#duration-message').addClass("error");$('#duration-message').css("display","block");
            }
        },
        error:function(error,status){
            $('#duration-message').removeClass("error");
            $('#duration-message').removeClass("success");
            $('#duration-message').html(error.responseText);
            $('#duration-message').addClass("error");$('#duration-message').css("display","block");
        }
    });
});
$('#remove-round').on("click",function(){
    var data = {'action':'removeRound','key':'<?php $time = time(); echo base64_encode(openssl_encrypt($user['username'].'@culrav'.$time.','.$_SESSION['password_change_count'],"AES-128-ECB",SECRETKEY));?>'};
    $.ajax({
        url:'ajax',
        data:data,
        method:"POST",
        dataType:'json',
        success:function(data){
            $('#duration-message').removeClass("error");
            $('#duration-message').removeClass("success");
            if(data.status == true){
                $('#duration-message').addClass("success");
                $('#duration-message').css("display","block");
                $('#duration-message').html(data.msg);
                $('#round-count').html(data.data.length);
                $('ul#rounds').html("");
                var a = data.data;
                for(var i=0;i<a.length;i++)
                    $('ul#rounds').append("<li>Round "+(i+1)+": "+a[i]+" days</li>");
            }
            else{
                $('#duration-message').addClass("error");$('#duration-message').css("display","block");
            }
        },
        error:function(error,status){
            $('#duration-message').removeClass("error");
            $('#duration-message').removeClass("success");
            $('#duration-message').html(error.responseText);
            $('#duration-message').addClass("error");$('#duration-message').css("display","block");
        }
    });
});
<?php } 
else{ ?>
$('.qualify-participant').on("click",function(){
    var participantID = $(this).attr("data-participantID");
    var data = {'action':'toggleQualify','key':'<?php $time = time(); echo base64_encode(openssl_encrypt($user['username'].'@culrav'.$time.','.$_SESSION['password_change_count'],"AES-128-ECB",SECRETKEY));?>','participantID':participantID};
    $.ajax({
        url:'ajax',
        data:data,
        method:"POST",
        dataType:'json',
        success:function(data){
            $('#qualify-message').removeClass("error");
            $('#qualify-message').removeClass("success");
            $('#qualify-message').html(data.msg);
            if(data.status == true){
                $('#qualify-message').addClass("success");
                $('#qualify-message').css("display","block");
            }
            else{
                $('#qualify-message').addClass("error");$('#qualify-message').css("display","block");
            }
        },
        error:function(error,status){
            $('#qualify-message').removeClass("error");
            $('#qualify-message').removeClass("success");
            $('#qualify-message').html(error.responseText);
            $('#qualify-message').addClass("error");$('#qualify-message').css("display","block");
        }
    });
});
<?php } ?>
</script>
<?php
if(($_SERVER['REQUEST_METHOD'] === 'GET') or (isset($_POST['action']) and $_POST['action'] != "getContentPage")){
    include_once('components/footer.php');
}
?>