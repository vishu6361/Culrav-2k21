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
$title = "Dashboard | Culrav";
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
        <?php
        if($user['usertype'] == 'students'){
        if($user['verified'] == 0){?>
            <div class="verification-box" style="margin-top:0">
                <h3>Verify your account now</h3>
                <p class="simple">You need to verify your account to be able to participate in Culrav 2020.<br/><br/>Please enter your &nbsp;<strong><u>registration number</u></strong>&nbsp; to verify.</p>
                <p class="simple"><span id="verification-message">Verification Code Sent</span></p>
                <input type="text" name="verify" style="margin:15px 0" required><button id="verification-button" class="submit-button">submit</button>
            </div>
        <?php 
        }
        ?>
            <div class="participate">
                <h3>Participate</h3>
                <p class="simple">You need to register for your events before participating in them. Here you can choose your event and participate in them along with the given time constraint.</p><br/>
                <button class="submit-button" onclick="window.location.href='participate'">Participate</button>
            </div>
        <?php 
        /*
        if(!$misc->checkIfUserPaid($user['username'])){?>
            <div class="payment-box">
                <h3>Pay your fees</h3>
                <p class="simple">You will be charged a fee of ₹1500 for participating in Culrav.</p>
                <p class="simple">This fees are for participating in Culrav and includes accomodation and pronite charges.</p>
                <button class="submit-button" style="margin-top:10px" onclick="window.location.href = 'pay_fees';">Pay Fees</button>
            </div>
        <?php
        }*/
        $requests = $misc->getTeamEventRequest($user['username']);
        foreach($requests as $request){
        ?>
            <div id="team-request-<?php echo $request['teamID']?>" class="team-request">
                <h3>Team Event Request</h3>
                <p class="simple"><span class="user"><?php $requestBy = $misc->getUser($request['requestBy']);echo $requestBy['name']?></span> requests you to join their team <a class="team-name"><?php echo $misc->getTeamNameByTeamID($request['teamID']);?></a> for the event <a><?php echo $misc->getEventNameByEventID($request['eventID']);?></a></p>
                <div class="choice" data-requestID="<?php echo $request['requestID'];?>"><button data-team-id="<?php echo $request['teamID'];?>" class="submit-button accept-button" style="border-color:green;color:green"><i class="fa fa-check"></i>Yes</button><button data-team-id="<?php echo $request['teamID'];?>"  class="submit-button reject-button" style="border-color:#df4545;color:#df4545"><i class="fa fa-times"></i>No</button></div>
                <p id="team-request-<?php echo $request['teamID']?>-message" class="simple">Team request accepted!</p>
            </div>
        <?php
        }
        ?>
            <div class="select-event-to-register">
                <h3>Select individual events to register</h3>
                <p class="simple">These events carry points and will be directly alloted and affect your overall performance score.</p>
                <div class="option-list">
                    <select name="event-to-register">
                        <option selected disabled value>--Select an option--</option>
        <?php
        $events = $misc->getIndividualNotParticipatedEvents($user['username']);
        foreach($events as $event){
        ?>
                        <option value="<?php echo $event['eventID']?>"><?php echo $event['event_name'].' - '.$misc->getEventClassNameByEventClass($event['event_class']);?></option>
        <?php
        }
        ?>
                    </select>
                    <p id="individual-event-to-register-message" style="margin:0 0 5px;"></p>
                    <button id="individual-event-register" class="submit-button">Register</button>
                </div>
            </div>

            <div class="select-team-event-to-register">
                <h3>Register for team events</h3>
                <p class="simple">Create a team of members and participate in the most enticing events of Culrav 2020. Make new connections and compete against other teams.</p>
                <button onclick="javascript:window.location.href = 'dashboard-team-events';" class="submit-button" style="margin:15px 0;">Manage Teams<i class="fa fa-arrow-right" style="margin-left:10px"></i></a>
            </div>

            <div class="select-event-to-deregister">
                <h3 style="color:#df4545">Deregister from events</h3>
                <p class="simple" style="color:#df4545;opacity:0.66;">It does not matter how slowly you go as long as you do not stop.</p>
                <div class="option-list">
                    <select name="event-to-deregister" style="border-color:#df4545;color:#df4545;">
                        <option selected disabled value>--Select an option--</option>
        <?php
        $events = $misc->getUserParticipatedEvents($user['username']);
        foreach($events as $event){
        ?>
                        <option value="<?php echo $event['eventID']?>"><?php echo $misc->getEventNameByEventID($event['eventID']).' - '.$misc->getEventClassNameByEventID($event['eventID']);?></option>
        <?php
        }
        ?>
                    </select>
                <p id="individual-event-to-deregister-message" style="margin:0 0 5px;"></p>
                <button id="individual-event-deregister" class="submit-button" style="border-color:#df4545;color:#df4545;">Deregister</button>
                </div>
            </div>
        <?php
        }
        else if($user['usertype'] == 'coordinator'){
            $event = $misc->getEventOfCoordinator($user['username']);
        ?>
        <?php if($event['registrationStatus'] == 1){ ?>
        <div>
            <h3>End Registration Period</h3>
            <p class="simple">End the registration period and hence no student can register for this event any more. It will also trigger the start of the rounds so be careful when you choose to end your registration period.</p><br>
            <button class="submit-button" onclick="javascript:window.location.href= 'toggleEvent?action=toggleRegistrations&eventID=<?php echo $event['eventID'];?>';">End</button>
        </div>
        <?php if($event['rounds'] == NULL){?>
        <div id="choose-round-system">
            <h3>Select Round System</h3>
            <p class="simple">If you want to apply a rounds system for your event.</p><br>
            <button class="submit-button" style="color:green;border-color:green;display:inline-block" onclick="javascript:window.location.href='toggleEvent?action=setRoundSystem&eventID=<?php echo $event['eventID'];?>';"><i class="fa fa-check" style="margin-right:8px"></i>Yes</button>
            <button class="submit-button" style="color:#df4545;border-color:#df4545;display:inline-block;margin-left:20px;" onclick="javascript:window.location.href='toggleEvent?action=removeRoundSystem&eventID=<?php echo $event['eventID'];?>';"><i class="fa fa-times" style="margin-right:8px"></i>No</button>
        </div>
        <?php }
        }
        else{ ?>
        <div>
            <h3>Resume Registration Period</h3>
            <p class="simple">Resume the registration period and hence students can register for this event.</p><br>
            <button class="submit-button" onclick="javascript:window.location.href= 'toggleEvent?action=toggleRegistrations&eventID=<?php echo $event['eventID'];?>';">Resume</button>
        </div>
        <?php } ?>
        <?php if($event['status'] == 1){ ?>
        <div>
            <h3>Delcare the end of event</h3>
            <p class="simple">Delcare that the events have ended and the results will be announced.</p><br>
            <button class="submit-button" onclick="javascript:window.location.href= 'toggleEvent?action=endEvent&eventID=<?php echo $event['eventID'];?>';">End</button>
        </div>
        <?php 
            if($event['rounds'] == 1){ ?>
                <div id="round-system">
                    <h3>Round System</h3>
                    <p class="simple">Make changes to your number of rounds and it's timings, </p><br>
                    <button class="submit-button" onclick="javascript:window.location.href='round-settings';">Round Settings</button>
                </div>
            <?php
            }
        }
        else if($event['status'] == 0){ 
            if($event['rounds'] == 1){ ?>
                <div id="round-system">
                    <h3>Round System</h3>
                    <p class="simple">Make changes to your number of rounds and it's timings, </p><br>
                    <button class="submit-button" onclick="javascript:window.location.href='round-settings';">Round Settings</button>
                </div>
            <?php
            }?>
        <div>
            <h3>The Event Has Ended</h3>
            <p class="simple">Congratulations! The event has ended and please wait for the coordinators to update the results.</p>
        </div>
        <div>
            <h3>Allot Points</h3>
            <p class="simple">The points that each participant/team got on this event, they will all be put into consideration for all final deciding score.</p>
            <form action="allotPoints" method="POST">
            <select name="individual-team" required>
                <option selected disabled value>-- Select an option --</option>
            <?php
            if($event['eventtype'] == 'team'){
                $teams = $misc->getTeamsByEventID($event['eventID']);
                foreach($teams as $team){
                ?>
                    <option value="<?php echo $team['teamID'];?>"><?php echo $team['team_name']; ?></option>
                <?php
                }
            }
            else if($event['eventtype'] == 'individual'){
                $participants = $misc->getIndividualsByEventID($event['eventID']);
                foreach($participants as $participant){
                ?>
                    <option value="<?php echo $participant['username'];?>"><?php echo $participant['name']; ?></option>
                <?php
                }
            }
            ?>
            </select>
            <input type="text" name="points" placeholder="10 Points" required style="margin:0 0 10px 0;width:97.5%;">
            <input name="eventID" value="<?php echo $event['eventID'];?>" hidden>
            <input name="eventType" value="<?php echo $event['eventtype'];?>" hidden>
            <button class="submit-button" type="submit">Allot Points</button>
            </form>
        </div>
        <?php } 
        else if($event['status'] == -1){ ?>
        <div>
            <h3>The Event Has Been Cancelled</h3>
            <p class="simple">Due to some reasons, the team decided to drop the event. Sorry for the inconvinience.</p>
        </div>
        <?php }
        if($event['registrationStatus'] == 0 and $event['rounds'] != 1){ ?>
            <div>
                <h3>Submissions</h3>
                <p class="simple">You can check out all the submission links for your event over here. The submissions are sorted according to the time of submission.</p><br/>
                <?php
                $data = $misc->getSubmissionLinksForCoordinatorsByEventID($user['username'],$event['eventID']);
                $links = $data['data']; ?>
                    <table>
                        <?php foreach($links as $link){ 
                                if($event['eventtype'] == 'team'){
                                    $team = $misc->getTeamByTeamID($link['username']); ?>
                                        <tr><td><?php echo $team['team_name']; ?></td><td><?php $teamCreator = $misc->getUser($team['createdBy']); echo $teamCreator['name']; ?></td><td><?php echo $teamCreator['contact'];?></td><td><?php echo $misc->getTeamSizeByTeamID($team['teamID']).' mbr';?></td><td><?php echo $link['submissionLink'];?></td></tr>
                            <?php }
                                else if($event['eventtype'] == 'individual'){ 
                                    $participant = $misc->getUser($link['username']); ?>
                                        <tr><td><?php echo $participant['name']; ?></td><td><?php echo $participant['contact']; ?></td><td><?php echo $link['submissionLink'];?></td><td><?php echo $misc->getScoreByUsernameEventID($participant['username'],$event['eventID']);?></td></tr>
                            <?php } 
                            } ?>
                    </table>
                <?php
                if(count($links) == 0){
                ?>
                    <p class="simple">There are no submissions yet!</p>
                <?php
                }
                ?>
            </div>

        <?php } ?>
            <div>
                <h3>Get the <?php echo $event['eventtype'].'s'; ?> list</h3>
                <p class="simple">Get the list of teams/individuals participating in the event that you're responsible for.</p><br>
                <?php
                if($event['eventtype'] == 'team'){
                    $teams = $misc->getTeamsByEventID($event['eventID']);
                ?>
                    <table>
                        <?php foreach($teams as $team){ ?>
                        <tr><td><?php echo $team['team_name']; ?></td><td><?php $teamCreator = $misc->getUser($team['createdBy']); echo $teamCreator['name']; ?></td><td><?php echo $teamCreator['contact'];?></td><td><?php echo $misc->getTeamSizeByTeamID($team['teamID']).' mbr';?></td><td><?php echo $team['score'];?></td></tr>
                        <?php } ?>
                    </table>
                <?php
                    if(count($teams) == 0){ ?>
                        <p class="simple">Please wait for the students to register for your event!</p>
                <?php
                    }
                }
                else if($event['eventtype'] == 'individual'){
                    $participants = $misc->getIndividualsByEventID($event['eventID']);
                ?>
                    <table>
                        <?php foreach($participants as $participant){ ?>
                        <tr><td><?php echo $participant['name']; ?></td><td><?php echo $participant['contact']; ?></td><td><?php echo $misc->getScoreByUsernameEventID($participant['username'],$event['eventID']);?></td></tr>
                        <?php } ?>
                    </table>
                <?php
                    if(count($participants) == 0){ ?>
                        <p class="simple">Sorry, no one registered for your event!</p>
                <?php
                    }
                }
                ?>
            </div>
        <?php
        }
        else if($user['usertype'] == 'admin'){
        ?>
            <div style="overflow-x:auto;">
                <h3>Top Point List</h3>
                <p class="simple">The list of top 10 students competing.</p>
                <table style="border:1px solid white;padding:8px;width:100%;margin-top:20px;">
                    <tr><th style="width:75%;">Name</th><th style="width:25%;">Points</th></tr>
                <?php
                $students = $misc->getTopTenListStudents();
                foreach($students as $student){
                ?>
                    <tr><td><?php $participant = $misc->getUser($student['username']);echo $participant['name'];?></td><td><?php echo $student['score'];?></td></tr>
                <?php
                }
                ?>
                </table>
            </div>
            <div>
                <h3>Get Paid Students List</h3>
                <p class="simple">Print the list of all the registered students who paid.</p>
                <button class="submit-button" onclick="javascript:printPaidList()" style="margin-top:10px">Print</button>
            </div>
            <div>
                <h3>Get Co-ordinator List</h3>
                <p class="simple">Print the list of all the co-ordinators.</p>
                <button class="submit-button" onclick="javascript:printCoordinatorList()" style="margin-top:10px">Print</button>
            </div>
            <div>
                <h3>Register Co-ordinator</h3>
                <p class="simple">Register a co-ordinator only after verifying the details about them.</p>
                <input type="text" name="username" required style="margin:15px 0 0;text-transform:lowercase" placeholder="Username">
                <p id="username-message" class="simple">Username Empty</p>
                <input type="text" name="name" required style="margin:10px 0 0;" placeholder="Name">
                <input type="text" name="sex" required style="margin:10px 0 0;" placeholder="M or F" maxlength="1">
                <input type="text" name="contact" required style="margin:10px 0 0;" placeholder="+91-1234567890">
                <input type="text" name="aadhaar" required style="margin:10px 0 0;" placeholder="Aadhaar" maxlength="12">
                <input type="text" name="password" required style="margin:10px 0 0;" placeholder="Password">
                <select name="select-coordinator-event" style="width:94.5%;">
                    <option disabled selected value>-- Select Event --</option>
                <?php
                $events = $misc->getAllEvents();
                foreach($events as $event){
                ?>
                    <option value="<?php echo $event['eventID'];?>"><?php echo $event['event_name'];?></option>
                <?php
                }
                ?>
                </select>
                <p id="register-message" class="simple error"></p>
                <button id="register-co-ordinator-button" class="submit-button">Register</button>
            </div>

            <div>
                <h3 style="color:#df4545">Disqualify Student</h3>
                <p class="simple" style="color:#df4545;opacity:0.66;">Disqualify a student due to some specific reason.</p>
                <select name="student-to-disqualify" style="border-color:#df4545;color:#df4545;">
                    <option selected disabled value>--Select an option--</option>
                </select>
                <button id="student-to-disqualify-button" class="submit-button" style="border-color:#df4545;color:#df4545;">Disqualify</button>
            </div>
        <?php
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
<?php
if($user['usertype'] == 'students'){
?>
$('#verification-button').on('click',function(){
    var verify = $('input[name="verify"]').val();
    var data = {'action':'verifyAccount','username':'<?php echo $user['username'];?>','verify':verify}
    $.ajax({
        url:'ajax',
        dataType:'json',
        data: data,
        method: 'POST',
        success:function(data){
            $('#verification-message').html(data.msg);
            $('#verification-message').css('display','block');
            if(data.status == true){$('#verification-message').css("color","green");location.reload(true);}
            else{$('#verification-message').css("color","#df4545");}
        },
        error:function(error){
            $('#verification-message').html("Not Connected to the Internet");
            $('#verification-message').css('display','block');
            $('#verification-message').css("color","#df4545");
        }
    });
});
$('#individual-event-register').on('click',function(){
    var eventID = $('select[name="event-to-register"]').val();
    if(eventID == null){return;}
    var data = {'action':'registerIndividualEvent','key':'<?php $time = time(); echo base64_encode(openssl_encrypt($user['username'].'@culrav'.$time.','.$_SESSION['password_change_count'],"AES-128-ECB",SECRETKEY));?>','eventID':eventID};
    $.ajax({
        url:'ajax',
        dataType:'json',
        data: data,
        method: 'POST',
        success:function(data){
            $('#individual-event-to-register-message').html(data.msg);
            $('#individual-event-to-register-message').css('display','block');
            if(data.status == true){
                $('#individual-event-to-register-message').css("color","green");
                $('select[name="event-to-deregister"]').append($('select[name="event-to-register"] option[value="'+eventID+'"]'));
                $('.events-in-action div:contains(Not yet)').remove();
                $('.events-in-action').append('<div><p>'+data.event_name+'</p></div>');
                $('select[name="event-to-register"] option[value="'+eventID+'"]').remove();
                document.querySelector('select[name="event-to-register"] option').selected = true;
            }
            else{$('#individual-event-to-register-message').css("color","#df4545"); if(data['logout']) loaction.reload(); }
        },
        error:function(error){
            $('#individual-event-to-register-message').html("Not Connected to the Internet");
            $('#individual-event-to-register-message').css('display','block');
            $('#individual-event-to-register-message').css("color","#df4545");
        }
    });
});
$('#individual-event-deregister').on('click',function(){
    var eventID = $('select[name="event-to-deregister"]').val();
    if(eventID == null){return;}
    var data = {'action':'deregisterEvent','key':'<?php $time = time(); echo base64_encode(openssl_encrypt($user['username'].'@culrav'.$time.','.$_SESSION['password_change_count'],"AES-128-ECB",SECRETKEY));?>','eventID':eventID};
    $.ajax({
        url:'ajax',
        dataType:'json',
        data: data,
        method: 'POST',
        success:function(data){
            $('#individual-event-to-deregister-message').html(data.msg);
            $('#individual-event-to-deregister-message').css('display','block');
            if(data.status == true){
                $('#individual-event-to-deregister-message').css("color","green");
                if(data.eventtype == "individual"){
                    $('select[name="event-to-register"]').append($('select[name="event-to-deregister"] option[value="'+data.eventid+'"]'));
                    $('.events-in-action div:contains('+data.eventname+')').remove();
                    if($('.events-in-action div').length == 0)
                        $('.events-in-action').append('<div><p>Not yet</p></div>');
                }
                else{
                    //things to do after unregistering from team event
                    $('.events-in-action div:contains('+data.eventname+')').remove();
                    if($('.events-in-action div').length == 0)
                        $('.events-in-action').append('<div><p>Not yet</p></div>');
                }
                $('select[name="event-to-deregister"] option[value="'+data.eventid+'"]').remove();
                document.querySelector('select[name="event-to-deregister"] option').selected = true;
            }
            else{$('#individual-event-to-deregister-message').css("color","#df4545"); if(data['logout']) loaction.reload(); }
        },
        error:function(error){
            $('#individual-event-to-deregister-message').html("Not Connected to the Internet");
            $('#individual-event-to-deregister-message').css('display','block');
            $('#individual-event-to-deregister-message').css("color","#df4545");
        }
    });
});
$('.accept-button').on('click',function(){
    var teamID = $(this).attr('data-team-id');
    var data = {'action':'acceptTeamRequest','key':'<?php $time = time(); echo base64_encode(openssl_encrypt($user['username'].'@culrav'.$time.','.$_SESSION['password_change_count'],"AES-128-ECB",SECRETKEY));?>','teamID':teamID};
    $.ajax({
        url:'ajax',
        dataType:'json',
        data: data,
        method: 'POST',
        success:function(data){
            $('#team-request-'+teamID+'-message').html(data.msg);
            $('#team-request-'+teamID+'-message').css('display','block');
            if(data.status == true){
                $('#team-request-'+teamID+'-message').css("color","green");
                $('#team-request-'+teamID).css("display","none");
                $('#team-request-'+teamID).next().css('margin-top',"0");
            }
            else{$('#team-request-'+teamID+'-message').css("color","#df4545"); if(data['logout']) loaction.reload(); }
        },
        error:function(error){
            console.log(error);
        }
    });
});
$('.reject-button').on('click',function(){
    var teamID = $(this).attr('data-team-id');
    var data = {'action':'rejectTeamRequest','key':'<?php $time = time(); echo base64_encode(openssl_encrypt($user['username'].'@culrav'.$time.','.$_SESSION['password_change_count'],"AES-128-ECB",SECRETKEY));?>','teamID':teamID};
    $.ajax({
        url:'ajax',
        dataType:'json',
        data: data,
        method: 'POST',
        success:function(data){
            $('#team-request-'+teamID+'-message').html(data.msg);
            $('#team-request-'+teamID+'-message').css('display','block');
            if(data.status == true){
                $('#team-request-'+teamID+'-message').css("color","green");
                $('#team-request-'+teamID).css("display","none");
                $('#team-request-'+teamID).next().css('margin-top',"0");
            }
            else{$('#team-request-'+teamID+'-message').css("color","#df4545"); if(data['logout']) loaction.reload(); }
        },
        error:function(error){
            console.log(error);
        }
    });
});
<?php
}
else if($user['usertype'] == 'admin'){
?>
$('input[name="username"]').on('keyup',function(){
    var username = $('input[name="username"]').val();
    var data = {'action':'checkUsernameAvailability','username':username};
    $.ajax({
        url:'ajax',
        method:"POST",
        data:data,
        dataType: "json",
        success:function(data){
            $('#username-message').removeClass(" error");
            $('#username-message').removeClass(" success");
            $('#username-message').html(data.msg);
            if(data.success == true) $('#username-message').addClass(" success");
            else $('#username-message').addClass(" error");
            $('#username-message').css("display","block");
        },
        error:function(error,status){
            $('#username-message').removeClass(" error");
            $('#username-message').removeClass(" success");
            $('#username-message').html(error.responseText);
            if(data.success == true) $('#username-message').addClass(" success");
            else $('#username-message').addClass(" error");
            $('#username-message').css("display","block");
        }
    });
});
$('#register-co-ordinator-button').on('click',function(){
    var username = $('input[name="username"]').val();
    if(username == "" || username == undefined){
        $('#username-message').html("Username Empty");
        $('#username-message').css('display','block');
        return;
    }
    var name = $('input[name="name"]').val();
    var password = $('input[name="password"]').val();
    var sex = $('input[name="sex"]').val();
    var aadhaar = $('input[name="aadhaar"]').val();
    var contact = $('input[name="contact"]').val();
    var eventID = $('select[name="select-coordinator-event"]').val();
    var data = {'action':'registerCoordinators','key':'<?php $time = time(); echo base64_encode(openssl_encrypt($user['username'].'@culrav'.$time.','.$_SESSION['password_change_count'],"AES-128-ECB",SECRETKEY));?>','username':username,'name':name,'sex':sex,'aadhaar':aadhaar,'contact':contact,'password':password,'eventID':eventID};
    $.ajax({
        url:'ajax',
        method:"POST",
        data:data,
        dataType: "json",
        success:function(data){
            if(data.success == true){
                $('input[name="username"]').val('');
                $('input[name="name"]').val('');
                $('input[name="sex"]').val('');
                $('input[name="aadhaar"]').val('');
                $('input[name="contact"]').val('');
                $('select[name="select-coordinator-event"]').val('');
                $('input[name="password"]').val('');
            }
            $('#register-message').removeClass(" error");
            $('#register-message').removeClass(" success");
            $('#register-message').html(data.msg);
            if(data.success == true) $('#register-message').addClass(" success");
            else $('#register-message').addClass(" error");
            $('#register-message').css("display","block");
        },
        error:function(error,status){
            $('#register-message').removeClass(" error");
            $('#register-message').removeClass(" success");
            $('#register-message').html(error.responseText);
            $('#register-message').addClass(" error");
            $('#register-message').css("display","block");
        }
    });
});
function printPaidList(){
   var data = {'action':'printPaidList'};
   $.ajax({
        url:'ajax',
        method:"POST",
        data:data,
        dataType: "json",
        success:function(data){
            if(data.status == true){
                newWin= window.open("");
                newWin.document.write(data.msg);
                newWin.print();
                newWin.close();
            }
        },
        error:function(error,status){
            console.log(error.responseText);
        }
    });
}
function printCoordinatorList(){
   var data = {'action':'printCoordinatorList'};
   $.ajax({
        url:'ajax',
        method:"POST",
        data:data,
        dataType: "json",
        success:function(data){
            if(data.status == true){
                newWin= window.open("");
                newWin.document.write(data.msg);
                newWin.print();
                newWin.close();
            }
        },
        error:function(error,status){
            console.log(error.responseText);
        }
    });
}
<?php
}
?>
</script>
<?php
if(($_SERVER['REQUEST_METHOD'] === 'GET') or (isset($_POST['action']) and $_POST['action'] != "getContentPage")){
    include_once('components/footer.php');
}
?>