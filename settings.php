<?php
session_start();
if(!isset($_SESSION['user']) and !isset($_SESSION['usertype'])){
  header('Location: ./login');
}
include_once('class.misc.php');
$misc = new misc();
$user = $_SESSION['user'];
$usertype = $_SESSION['usertype'];
$user = $misc->getUser($user);
$title = "Dashboard | Culrav";
if(($_SERVER['REQUEST_METHOD'] === 'GET') or ($_SERVER['REQUEST_METHOD'] === 'POST') or (isset($_POST['action']) and $_POST['action'] != "getContentPage")){
    include_once('components/header.php');
}
if(isset($_POST['oldpassword'])){
    if($_SESSION['password_change_count'] == $misc->getPasswordChangeCount($user['username'])){
        if($misc->checkPassword($user['username'],$_POST['oldpassword'])){
            if($_POST['newpassword'] == $_POST['confirmpassword']){
                if(!$misc->setPassword($user['username'],$_POST['newpassword'])){
                    $status = false;
                    $msg = "Try a better password!";
                }
                else{
                    $status = true;
                    $msg = "Password Changed!";
                    $value = $_SESSION['password_change_count'];
                    $_SESSION['password_change_count'] = $value + 1;
                }
            }
            else{
                $status = false;
                $msg = "Password Not Changed!";
            }
        }
        else{
            $status = false;
            $msg = "Wrong Password, Password Not Changed!";
        }
    }
    else{
        session_destroy();
        header('Location: ./login');
    }
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
            <div id="password-change-box">
                <form id="password-change-form" action="./settings" method="POST">
                    <h3>Change your password</h3>
                    <p class="simple">Sometimes you should keep changing your password to stay secure. Security comes first, that's what we follow here at Culrav 2020, having fun alongside all the entertainment.</p>
                    <br>
                    <div class="form-field">
                        <label>Old Password</label>
                        <input type="password" name="oldpassword" required>
                        <p id="old-password-message" class="simple"></p>
                    </div>
                    <div class="form-field">
                        <label>New Password</label>
                        <input type="password" name="newpassword" required>
                        <p id="new-password-message" class="simple"></p>
                    </div>
                    <div class="form-field">
                        <label>Confirm New Password</label>
                        <input type="password" name="confirmpassword" required>
                        <p id="confirm-new-password-message" class="simple"></p>
                    </div>
                    <button id="password-change-button" class="submit-button" style="margin-top:15px">submit</button>
                    <p class="simple" id="password-change-message" style="<?php if(isset($status)){ echo 'display:block;'; if($status == true){ echo 'color:green;';}else{ echo 'color:#df4545;';}} ?>"><?php if(isset($msg)) echo $msg;?></p>
                </form>
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
                else{?>
                    <p class="simple">Welcome to Culrav 2020</p>
                    <p style="font-size:90%;"><?php echo 'FestID: '.$user['festID'];?></p>
                <?php
                }?>
                </div>
                <div style="margin-top:15px"><a href="dashboard" class="button-square" style="color:var(--text-color);text-decoration:none;">Dashboard</a></div>
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
function checkPassword(){
    var confirmPassword = $('input[name="confirmpassword"]').val();
    var newPassword = $('input[name="newpassword"]').val();
    if(confirmPassword == "" && newPassword == ""){ $('#confirm-new-password-message').css('display','none'); return false;}
    if(newPassword != confirmPassword){
        $('#confirm-new-password-message').html("Password Doesn't Match");
        $('#confirm-new-password-message').css("color","#df4545");
        $('#confirm-new-password-message').css("display","block");
        return false;
    }
    else{
        $('#confirm-new-password-message').html("You're good to go!");
        $('#confirm-new-password-message').css("color","green");
        $('#confirm-new-password-message').css("display","block");
        return true;
    }
}
$('input[name="newpassword"]').on('keyup',checkPassword);
$('input[name="confirmpassword"]').on('keyup',checkPassword);
$('#password-change-button').on('click',function(){
    if(checkPassword() == true){
        if($('input[name="oldpassword"]').val() != ""){
            $('password-change-form').submit();
        }
    }
});
</script>
<?php
if(($_SERVER['REQUEST_METHOD'] === 'GET') or (isset($_POST['action']) and $_POST['action'] != "getContentPage")){
    include_once('components/footer.php');
}
?>