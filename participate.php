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
$time = true;
if($user['usertype'] != 'students'){
    header('Location: dashboard');
}
$title = "Participate | Culrav";
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
.form-field{
  position:relative;
  border:1px solid var(--text-color);
  padding:25px 0;
  margin: 20px 0;
}
.form-field:nth-of-type(1){margin:0 0 20px;}
.form-field:last-of-type{margin:20px 0 0;}
.form-field > label{position:absolute;top:0;transform:translate(10px,-50%);background:var(--background-color);padding:0 6px;}
.form-field > input{background:var(--background-color);border:1px solid var(--text-color);padding:8px 1%;color:var(--text-color);font-family:"Muli",sans-serif;width:92%;margin:0 3%;}
.form-field > input:focus{outline-color:var(--text-color);}
.form-field > p{position:absolute;bottom:0;margin:0;font-size:13px;line-height:15px;width:94%;margin:0 3%;transform:translateY(2.5px);display:none;}
.form .success{color: green;}
.form .error{color: #df4545;}
.pending{color:var(--text-color);}
.sent-invitations > div{border-radius:8px;background:var(--highlight-color);width:fit-content;padding:2px 12px;margin:5px 6px 5px 0;display:inline-block;}
.sent-invitations > div > p{font-size:11px;}
.sent-invitations > div > p:before{content:"@";margin-right:2px;}
#team-manage-box{max-height:0;transition:max-height 0.5s ease-in-out all;overflow:hidden;transition-delay:0.5s;}
.show-box{max-height:100% !important;}
.profile-about a.button-square{margin:0 auto;}
@media only screen and (max-width:500px){
  .form-field{padding:15px 0;}
  .form-field > p{font-size:11px;line-height:13px;transform:translateY(6px);}
  .form-field > input{width:84%;padding: 8px 3%;margin: 0 5%;}
  .sent-invitations > div{padding:2px 6px;margin:3px 4px 3px 0;}
  .sent-invitations > div > p{font-size:9px;line-height:11px;}
}
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

            <div>
                <h3>General Procedure for submission</h3>
                <p class="simple">
                    <ul>
                        <li>Provide the link of the folder on their drive (Name of the folder should have the <em>Name of the Participant_(Registration No)_Culrav</em> mentioned in it. For example- <em>AkarshDubey_(201xxxxx)_Culrav</em>. This folder will contain all the files related to the event.</li> 
                        <li>Theme related to Covid-19 may be selected for making your act such as: Social activities/ support during pandemic covid-19 outbreak, problem faced by immigrant during covid-19, economic downfall during covid-19, Lockdown imposed on day to day activities and the nation… etc. </li>
                        <li>The results will be declared on the Culrav website as well as will be featured on the respective social media pages of the cultural clubs. </li>
                    </ul>
                </p>
            </div>

        <?php if($user['usertype'] == 'students'){ 
            if(isset($_GET['event'])){
                $eventID = $_GET['event'];
                $event = $misc->getEventByEventID($eventID);
                if(isset($event['eventID']) and $event['rounds'] == 1 and $event['current_round'] != NULL and $event['current_round'] >= 2){
                ?>
                <div>
                    <h3>Qualified Participants</h3>
                    <p class="simple">The following participants have been qualified for the next round. Please submit your responses before the end of round.</p>
                    <ul>
                    <?php
                        $participants = $misc->qualifiedParticipants($event['eventID']);
                        foreach($participants as $participant){ 
                            if($event['eventtype'] == 'individual'){ ?>
                            <li><?php $participant = $misc->getUser($participant['participantID']); echo $participant['name']; ?></li>
                    <?php
                            }
                            else if($event['eventtype'] == 'team'){ ?>
                            <li><?php $participant = $misc->getTeamNameByTeamID($participant['participantID']); echo $participant; ?></li>
                    <?php
                            }
                        }
                    ?>
                    </ul>
                </div>
            <?php }
            } ?>

            <div class="event-participation">
                <h3>Event Participation</h3>
                <p class="simple">To participate in an event, you need to register for that event. Please make sure to follow the guidelines provided by the event co-ordinator before submitting.</p>
            <?php
            $participatedEvents = $misc->getUserParticipatedEvents($user['username']);
            if(isset($_GET['event'])){ 
                $eventID = $_GET['event'];
                $event = $misc->getEventByEventID($eventID);
                if($misc->isUserParticipating($user['username'],$eventID)){ ?>
                    <p class="simple" style="padding-bottom:0"><strong>Event Name</strong>: <a style="color:white;opacity:0.8;" href="<?php echo 'events?event='.$event['eventID']; ?>"><?php echo $event['event_name']; ?></a></p>
                    <p class="simple" style="padding-bottom:0;padding-top:0"><strong>Event Category</strong>: <a style="color:white;opacity:0.8;" href="<?php echo 'events?event_class='.$event['event_class']; ?>"><?php echo $misc->getEventClassNameByEventClass($event['event_class']); ?></a></p>
                    <p class="simple" style="font-size:80%">*Please make a file/folder and save it on your google drive and share the link.</p>
                    <?php $link = $misc->getSubmissionLinkofEventForUser($user['username'],$event['eventID']); if($link != ""){ ?>
                    <p class="simple" style="padding-bottom:0;"><strong>Submitted Link</strong>:<br><a style="color:white;opacity:0.8;" href="<?php echo $link['submissionLink']; ?>"><?php echo $link['submissionLink']; ?></a></p>
                    <p class="simple" style="padding-bottom:0;ppadding-top:0;font-size:80%;"><?php $submittedBy = $misc->getUser($link['submittedBy']); echo $submittedBy['name']; ?> &nbsp;&middot;&nbsp; <?php echo $misc->processTimestamp($link['timestamp']); ?></p>
                    <?php } ?>
                    <p class="simple" style="margin-top:15px;">
                    <?php
                    if($event['rounds'] == 1){
                        if($event['current_round'] == NULL or $event['current_round'] == 1){$time = $misc->timeLeftForNextRound($event['eventID']); if($time == false){ echo "Processing..."; }else{ echo 'Time Left For Next Round: '.$time; }}
                        else if($misc->isQualified($user['username'],$eventID)){echo $misc->timeLeftForNextRound($eventID); $time = true;}
                        else{ echo "Sorry You didn't qualify!"; $time = true;}
                    ?>
                    </p>
                        <div class="form-field">
                            <label>Drive Shareable Link</label>
                            <input type="text" name="drive-link" placeholder="https://drive.google.com/open?id=asfsa564sdf242" value="<?php if($link != ""){ echo $link['submissionLink'];} ?>" />
                            <p id="drive-link-message"></p>
                        </div><br/>
                    <?php }
                    else{ ?>
                        <div class="form-field">
                            <label>Drive Shareable Link</label>
                            <input type="text" name="drive-link" placeholder="https://drive.google.com/open?id=asfsa564sdf242" value="<?php if($link != ""){ echo $link['submissionLink'];} ?>" />
                            <p id="drive-link-message"></p>
                        </div><br/>
                    <?php
                    } ?>
                    <button id="submission-event-button" class="submit-button">Submit</button><br/>
                    <p class="simple">Participate in another event, please select your event.</p>
                <?php 
                }
                else{ ?>
                    <p class="simple" style="padding-bottom:0">Event Name: <a style="color:white;opacity:0.8;" href="<?php echo (count($event) == 0)?'events?event='.$event[0]['eventID']:'#'; ?>"><?php echo (count($event) == 0)?$event[0]['event_name']:'No such Event'; ?></a></p>
                    <p id="did-not-register-error" class="simple error">You didn't register for this event!</p>
                    <p class="simple">Participate in an event, please select your event.</p>
                <?php }
                ?>
                <select name="select-registered-events">
                    <option value selected default>-- Select an event --</option>
            <?php
            foreach($participatedEvents as $event){
                $event = $misc->getEventByEventID($event['eventID']);
                ?>
                    <option value="participate?event=<?php echo $event['eventID']; ?>"><?php echo $event['event_name']; ?></option>
            <?php
            }
            ?>
                </select>
            <?php
            }
            else{ ?>
                <p class="simple">Participate in an event, please select your event.</p>
                <select name="select-registered-events" onchange="location = this.value">
                    <option value selected default>-- Select an event --</option>
            <?php
            foreach($participatedEvents as $event){
                $event = $misc->getEventByEventID($event['eventID']);
                ?>
                    <option value="participate?event=<?php echo $event['eventID']; ?>"><?php echo $event['event_name']; ?></option>
            <?php
            }
            ?>
                </select>
            <?php
            }
            ?>
            </div>
        <?php } ?>

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
                <?php
                }
            ?>
        </div>
    </div>
</div>
</section>
<script>
<?php
if($time == false){ ?>
window.location.reload();
<?php
}
?>
<?php
if($user['usertype'] == 'students'){
    if(isset($_GET['event'])){
?>
$('#submission-event-button').on('click',function(){
    if($('#did-not-register-error') == undefined) return;
    var eventID = "<?php echo $_GET['event']; ?>";
    var link = $('input[name="drive-link"]').val();
    var data = {'action':'submissionEvent','key':'<?php $time = time(); echo base64_encode(openssl_encrypt($user['username'].'@culrav'.$time.','.$_SESSION['password_change_count'],"AES-128-ECB",SECRETKEY));?>','eventID':eventID,'link':link};
    $.ajax({
        url:'ajax',
        method:"POST",
        data:data,
        dataType: "json",
        success:function(data){
            $('#drive-link-message').removeClass(" error");
            $('#drive-link-message').removeClass(" success");
            $('#drive-link-message').html(data.msg);
            if(data.status == true){ $('#drive-link-message').addClass(" success"); location.reload(); }
            else{ $('#drive-link-message').addClass(" error"); }
            $('#drive-link-message').css("display","block");
        },
        error:function(error,status){
            $('#drive-link-message').removeClass(" error");
            $('#drive-link-message').removeClass(" success");
            $('#drive-link-message').html(error.responseText);
            if(data.success == true) $('#drive-link-message').addClass(" success");
            else $('#drive-link-message').addClass(" error");
            $('drive-link-message').css("display","block");
        }
    });
});
<?php
    }
}
?>
</script>
<?php
if(($_SERVER['REQUEST_METHOD'] === 'GET') or (isset($_POST['action']) and $_POST['action'] != "getContentPage")){
    include_once('components/footer.php');
}
?>