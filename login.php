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
        <div data-aos="fade-down" data-aos-anchor-placement="top-center"><h1 style="animation:unset;">Beat your best<br>And keep doing that</h1><p>Don't hesistate to contact us, if you get stuck.<br>We're always there for you.</p></div>
</section>
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
  .form-field > p{font-size:11px !important;line-height:13px !important;transform:translateY(6px);}
  .form-field > input{width:84%;padding: 8px 3%;margin: 0 5%;}
}
.error:before{content:'⚠';}
.success:before{content:'❕';}
.success:before,.error:before{padding-right:5px;}
</style>
<section class="form" style="margin-top:60px">
  <p id="login-response-message"></p>
  <div class="form-field">
    <label>Username</label>
    <input name="username" type="text" required>
    <p id="username-check-message" class="error"></p>
  </div>
  <div class="form-field">
    <label>Password</label>
    <input name="password" type="password" required>
  </div>
  <p style="display:block;"><a href="forgot-password" style="color:var(--text-color);">Forgot Password?</a></p>
  <div style="width:100%;"><button id="login-button">Log In</button><div style="display:inline-block;padding-left:5%;margin:20px auto;"><a href="register" style="color:var(--text-color);">New User? Register Here</a></div></div>
</section>

<script>
$('input[name="username"]').on("keyup",function(e){
  if(e.which == 13) return;
  $('#username-check-message').removeClass(" error");
  $('#username-check-message').removeClass(" success");
  $('#username-check-message').css("display","none");
});
$('#login-button').on('click',function(){
  var username = $('input[name="username"]').val();
  var password = $('input[name="password"]').val();
  var data = {'action':'login','username':username,'password':password};
  $.ajax({
    url:'ajax',
    dataType:'json',
    data:data,
    method:"POST",
    success:function(data){
      $('#username-check-message').removeClass("error");
      $('#username-check-message').removeClass("success");
      if(data.success == true){
        $('#username-check-message').addClass(" success");
        $('#username-check-message').html(data.msg);
        $('#username-check-message').css("display","block");
        window.location.href = "./dashboard";
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
      $("#login-button").click();
    }
});
</script>
<?php
if(($_SERVER['REQUEST_METHOD'] === 'GET') or (isset($_POST['action']) and $_POST['action'] != "getContentPage")){
    include_once('components/footer.php');
}
?>