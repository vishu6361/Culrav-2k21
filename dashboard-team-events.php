<?php
session_start();
if(!isset($_SESSION['user']) and !isset($_SESSION['usertype'])){
  header('Location: ./login');
}
if(isset($_SESSION['usertype']) and ($_SESSION['usertype'] == 'admin' or $_SESSION['usertype'] == 'coordinator')){
    header('Location: ./dashboard');
}
define ("SECRETKEY", "bcd1669c0232a0bfda48a63ecbf16acd");
include_once('class.misc.php');
$misc = new misc();
$user = $_SESSION['user'];
$usertype = $_SESSION['usertype'];
$user = $misc->getUser($user);
if($user['usertype'] != 'students'){
  header('Location: ./dashboard');
}
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
                <p class="simple">You want to go back to dashboard to see your other progress?</p><br/>
                <button class="submit-button" onclick="window.location.href='dashboard'"><i class="fa fa-arrow-left" style="margin-right:10px"></i>Dashboard</button>
            </div>

            <div class="manage-teams">
                <h3>Manage Teams</h3>
                <p class="simple">Manage your teams by inviting more members to your crew and show off your skills infront of an amazing crowd.</p>
                <p class="simple">Select a team to manage</p>
                <div class="option-list">
                    <select name="manage-team-select">
                        <option selected disabled value>-- Select an option --</option>
        <?php
        $teams = $misc->getUserTeams($user['username']);
        foreach($teams as $team){
        ?>
                        <option value="<?php echo $team['teamID'];?>"><?php echo $misc->getTeamNameByTeamID($team['teamID']);?> - <?php echo $misc->getEventNameByEventID($team['eventID']);?></option>
        <?php
        }
        ?>
                    </select>
                    <p id="select-team-message" class="simple error" style="font-size:80%;">Please select a team</p>
                </div>
                <button id="show-team-button" class="submit-button">Check Team</button>
                <div id="team-manage-box" style="margin-top:10px;">
                    <div class="sent-invitations">
                    </div>
                </div>
                <p class="simple">Please enter an username of an user to invite below for your team <span style="font-size:90%;opacity:0.66">(Press enter to send request)</span></p>
                <div class="form-field">
                    <label>Username</label>
                    <input type="text" name="invite-username">
                    <p id="invite-username-message"></p>
                </div>
            </div>
            
            <div class="make-new-team-register form">
                <h3>Create new team for an event</h3>
                <p class="simple">Please select an event and then invite other users to join your team. If they accept your request they will be added to your team list.</p>

        <?php
        $events = $misc->getTeamNotParticipatedEvents($user['username']);
        if(count($events) == 0){
        ?>
                <br><p class="simple">We are really sorry for the inconvenience, there aren't any team events available.</p>
        <?php
        }
        else{
        ?>
                <div class="option-list">
                    <select name="team-event-select">
                        <option selected disabled value>-- Select an option --</option>
        <?php
            foreach($events as $event){
        ?>
                        <option value="<?php echo $event['eventID']?>"><?php echo $event['event_name'].' - '.$misc->getEventClassNameByEventClass($event['event_class']);?></option>
        <?php
            }
        ?>
                    </select>
                    <p class="simple error" id="create-team-event-message" style="font-size:80%;">Please Select an event</p>
                </div>
                <p class="simple">Please enter a team name below for your team</p>
                <div class="form-field">
                    <label>Team Name</label>
                    <input type="text" name="create-team-name">
                    <p id="team-name-message"></p>
                </div>
                <button id="create-new-team-button" class="submit-button" style="margin:15px 0 0;">Submit</button>
                <p id="create-new-team-message" class="simple">Created Successfully!</p>
        <?php
        }
        ?>
            </div>

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
                if($user['verified'] == 0){?>
                    <p class="simple">Your account isn't verified yet.</p>
                <?php
                }
                else{ ?>
                    <p class="simple">Welcome to Culrav 2020</p>
                    <p style="font-size:90%;"><?php echo 'FestID: '.$user['festID']; ?></p>
                <?php
                }?>
                </div>
                <div style="margin-top:15px"><a href="settings" class="button-square" style="color:var(--text-color);text-decoration:none;">Settings</a></div>
                <div style="margin-top:15px"><a href="logout" class="button-square" style="color:var(--text-color);text-decoration:none;">Log out</a></div>
            </div>
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
        </div>
    </div>
</div>
</section>
<script>
$('select[name="team-event-select"]').on('click',function(){
    $('#create-team-event-message').css("display","none");
    $('#create-new-team-message').css('display','none');
});
$('input[name="create-team-name"]').on('keyup',function(){
    $('#team-name-message').css("display","none");
});
$('#create-new-team-button').on('click',function(){
    var eventID = $('select[name="team-event-select"]').val();
    if(eventID == null){
        $('#create-team-event-message').css("display","block");
        return;
    }
    var team_name = $('input[name="create-team-name"]').val();
    if(team_name == ""){
        $('#team-name-message').html("Please Enter a Team Name");
        $('#team-name-message').css("display","block");
        $('#team-name-message').css("color","#df4545");
    }
    var data = {"action":"createNewTeam","key":"<?php $time = time(); echo base64_encode(openssl_encrypt($user['username'].'@culrav'.$time.','.$_SESSION['password_change_count'],"AES-128-ECB",SECRETKEY));?>","eventID":eventID,"team_name":team_name};
    $.ajax({
        url:'ajax',
        data:data,
        dataType:'json',
        method:"POST",
        success:function(data){
            if(data.status == true){
                $('#create-new-team-message').html(data.msg);
                $('#create-new-team-message').css("color","green");
                $('#create-new-team-message').css("display","block");
                location.reload();
            }
            else{
                $('#create-new-team-message').html(data.msg);
                $('#create-new-team-message').css("color","#df4545");
                $('#create-new-team-message').css("display","block");
                if(data['logout']) loaction.reload(); 
            }
        },
        error:function(error){console.log(error);}
    });
});
$('select[name="manage-team-select"]').on('click',function(){
    $('#select-team-message').css("display","none");
});
function tag_view_team(data){
    var count = data.count;
    for(var i=0;i<count;i++){
        var key = i.toString();
        var value = data[key];
        key = "request"+i.toString();
        var status = data[key];
        if(status == "yes")
            $('.sent-invitations').append("<div class=\"success\"><p class=\"simple\">"+value+"</p></div>");
        else if(status == "no")
            $('.sent-invitations').append("<div class=\"error\"><p class=\"simple\">"+value+"</p></div>");
        else
            $('.sent-invitations').append("<div class=\"pending\"><p class=\"simple\">"+value+"</p></div>");
    }
}
$('#show-team-button').on('click',function(){
    var teamID = $('select[name="manage-team-select"]').val();
    if(teamID == null){
        $('#select-team-message').css("display","block");
        return;
    }
    var data = {"action":"checkTeamStatus","teamID":teamID};
    $.ajax({
        url:'ajax',
        data:data,
        dataType:'json',
        method:"POST",
        success:function(data){
            if(data.status == true){
                $('#team-manage-box > .sent-invitations').empty();
                tag_view_team(data);
                $('#team-manage-box').addClass("show-box");
            }
            else{
                $('#select-team-message').html(data.msg);
                $('#select-team-message').css("color","#df4545");
                $('#select-team-message').css("display","block");
            }
        },
        error:function(error){console.log(error);}
    });
});
$('input[name="invite-username"]').on('keyup',function(e){
    if(e.which == 13){
        var teamID = $('select[name="manage-team-select"]').val();
        if(teamID == null){
            $('#select-team-message').css("display","block");
            return;
        }
        var username = $(this).val();
        var data = {"action":"sendTeamRequest","key":"<?php $time = time(); echo base64_encode(openssl_encrypt($user['username'].'@culrav'.$time.','.$_SESSION['password_change_count'],"AES-128-ECB",SECRETKEY));?>","teamID":teamID,"username":username};
        $.ajax({
            url:'ajax',
            data:data,
            dataType:'json',
            method:"POST",
            success:function(data){console.log(data); 
                if(data.status == true){
                    $('#invite-username-message').html(data.msg);
                    $('#invite-username-message').css("color","green");
                    $('#invite-username-message').css("display","block");
                    location.reload();
                }
                else{
                    $('#invite-username-message').html(data.msg);
                    $('#invite-username-message').css("color","#df4545");
                    $('#invite-username-message').css("display","block");
                    if(data['logout']) loaction.reload(); 
                }
            },
            error:function(error){console.log(error);}
        });
    }
    else{
        $('#invite-username-message').css('display','none');
    }
});
</script>
<?php
if(($_SERVER['REQUEST_METHOD'] === 'GET') or (isset($_POST['action']) and $_POST['action'] != "getContentPage")){
    include_once('components/footer.php');
}
?>