<?php
session_start();
if(isset($_SESSION['user']) and isset($_SESSION['usertype'])){
  header("Location: ./dashboard");
}
$title = "Login | Culrav";
if(($_SERVER['REQUEST_METHOD'] === 'GET') or (isset($_POST['action']) and $_POST['action'] != "getContentPage")){
    include_once('components/header.php');
}
?>
<section style="margin-top:70px;">
    <div id="about_us" style="margin-bottom:0">
        <div data-aos="fade-down" data-aos-anchor-placement="top-center"><h1 style="animation:unset;">Sit Back and relax<br>We'll help you out</h1><p>It's okay we understand, people forget.<br>Please enter your details below for us to help you out.</p></div>
</section>
<?php
if(!isset($_GET['ID'])){
?>
<style>
.form{
  width:60%;margin:10% auto;
  border:1px solid var(--text-color);
  padding:20px 28px;
}
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
.form > p{font-size:13px;line-height:15px;margin:0 0 20px 0;padding:0;display:none;}
.form button{border:1px solid var(--text-color);padding:15px 35px;font-family:"Montserrat",sans-serif;font-weight:900;background:var(--background-color);color:var(--text-color);cursor:pointer;}
.form button:hover{opacity:0.7;}
@media only screen and (max-width:500px){
  .form-field{padding:15px 0;}
  .form-field > p{font-size:11px;line-height:13px;transform:translateY(6px);}
  .form-field > input{width:84%;padding: 8px 3%;margin: 0 5%;}
}
.error:before{content:'⚠';}
.success:before{content:'❕';}
.success:before,.error:before{padding-right:5px;}
</style>
<section class="form">
  <p id="forgot-password-response-message"></p>
  <div class="form-field">
    <label>Username</label>
    <input name="username" type="text" required>
    <p id="username-check-message" class="error"></p>
  </div>
  <div class="form-field">
    <label>Microsoft Teams Username</label>
    <input name="email" type="email" required>
  </div>
  <div class="form-field">
    <label>Email</label>
    <input name="tosendemail" type="email" required>
  </div>
  <p style="display:block;"><a href="register" style="color:var(--text-color);">New User? Register Now</a></p>
  <div style="width:100%;"><button id="forgot-password-button">Submit</button></div>
</section>

<script>
$('input[name="username"],input[name="email"]').on("keyup",function(e){
  if(e.which == 13) return;
  $('#username-check-message').removeClass(" error");
  $('#username-check-message').removeClass(" success");
  $('#username-check-message').css("display","none");
});
$('#forgot-password-button').on('click',function(){
  var username = $('input[name="username"]').val();
  var email = $('input[name="email"]').val();
  var tosendemail = $('input[name="tosendemail"]').val();
  var data = {'action':'forgot-password','username':username,'email':email,'tosendemail':tosendemail};
  $.ajax({
    url:'ajax',
    dataType:'json',
    data:data,
    method:"POST",
    async: false,
    cache: false,
    timeout: 30000,
    success:function(data){
      if(data.success == true){
        $('#username-check-message').addClass(" success");
        $('#username-check-message').html(data.msg);
        $('#username-check-message').css("display","block");
      }
      else{
        $('#username-check-message').addClass(" error");
        $('#username-check-message').html(data.msg);
        $('#username-check-message').css("display","block");
      }
    },
    error:function(error,status){
      $('#username-check-message').html(error);
      $('#username-check-message').css("display","block");
    }
  });
});
$('.form-field').keypress(function(e){
    if (e.which == 13){
      $("#forgot-password-button").click();
    }
});
</script>
<?php
}
else{
    $ID = $_GET['ID'];
?>
<section class="flex" style="justify-content:center">
    <center><h3 id="forgot-password-info">Please wait a few seconds...</h3></center>
</section>
<script>
function validateID(){
    var data = {'action':'validateGUIDForgotPassword','ID':'<?php echo $ID;?>'};
    $.ajax({
        url:'ajax',
        dataType:'json',
        data:data,
        method:"POST",
        cache: false,
        timeout: 30000,
        success:function(data){
            console.log(data);
            if(data.success == true){
                $('#forgot-password-info').addClass(" success");
                $('#forgot-password-info').html(data.msg);
                window.location.href="settings";
            }
            else{
                $('#forgot-password-info').addClass(" error");
                $('#forgot-password-info').html(data.msg);
            }
        },
        error:function(error,status){
            $('#forgot-password-info').html(error);
            $('#forgot-password-info').css("display","block");
        }
    });
}
$(window).on('load',function(){
    validateID();
});
</script>
<?php
}
if(($_SERVER['REQUEST_METHOD'] === 'GET') or (isset($_POST['action']) and $_POST['action'] != "getContentPage")){
    include_once('components/footer.php');
}
?>