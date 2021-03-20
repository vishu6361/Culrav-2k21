<?php
session_start();
if(isset($_SESSION['user']) and isset($_SESSION['usertype']) and (($_SERVER['REQUEST_METHOD'] === 'GET') or (isset($_POST['action']) and $_POST['action'] != "getContentPage"))){
  header('Location: ./dashboard');
}
if(isset($_SESSION['user']) and isset($_SESSION['usertype'])){
  ob_start();
}
$title = "Register | Culrav";
if(($_SERVER['REQUEST_METHOD'] === 'GET') or (isset($_POST['action']) and $_POST['action'] != "getContentPage")){
    include_once('components/header.php');
}
?>
<section style="margin-top:70px;">
    <div id="about_us" style="margin-bottom:0">
        <div data-aos="fade-down" data-aos-anchor-placement="top-center"><h1 style="animation:unset;"><span>participate</span> and<br><span>connect</span> with others</h1><br><p style="margin-top:0;font-size:1.6rem;">Register yourself to the events and win amazing prizes alongside having an amazing time at culrav 2020.</p></div>
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
p{line-height:2.4rem;letter-spacing:2px;}
.form-field:last-of-type{margin:20px 0 0;}
.form-field > label{position:absolute;top:0;transform:translate(10px,-50%);background:var(--background-color);padding:0 6px;}
.form-field > input{background:var(--background-color);border:1px solid var(--text-color);padding:8px 2%;color:var(--text-color);font-family:"Muli",sans-serif;width:90%;margin:0 3%;}
.form-field > input:focus{outline-color:var(--text-color);}
.form-field > .radio > *{display:inline-block;margin:0 2%;}
.form-field > p{position:absolute;bottom:0;margin:0;font-size:13px;line-height:15px;width:94%;margin:0 3%;transform:translateY(2.5px);display:none;}
.form .success{color: green;}
.form .error{color:red;}
.form > p{font-size:13px;line-height:15px;margin:0 0 20px 0;padding:0;display:none;}
.form button{border:1px solid var(--text-color);padding:15px 35px;font-family:"Montserrat",sans-serif;font-weight:900;background:var(--background-color);color:var(--text-color);cursor:pointer;}
.form button:hover{opacity:0.7;}
@media only screen and (max-width:500px){
  .form-field{padding:15px 0;}
  .form-field > p{font-size:11px !important;line-height:13px !important;transform:translateY(6px);}
  .form-field > input{width:84%;padding: 8px 3%;margin: 0 5%;}
  p{line-height:2rem;letter-spacing:1.2px;}
}
.error:before{content:'⚠';}
.success:before{content:'❕';}
.success:before,.error:before{padding-right:5px;}
</style>
<section class="form" style="margin-bottom:5px;margin-top:50px;">
  <h3>Have an account?</h3>
  <p class="simple" style="display:block;margin-top:5px">I already have an account and I want to log in.</p>
  <button class="submit-button" onclick="window.location.href='login'">click here</button>
</section>
<section class="form" style="margin:25px auto">
  <div class="form-field">
    <label>Username</label>
    <input name="username" type="text" style="text-transform:lowercase" max-length="40" required>
    <p id="username-check-message" class="error"></p>
  </div>
  <div class="form-field">
    <label>Name</label>
    <input name="name" type="text" max-length="50" required>
    <p id="name-check-message" class="error"></p>
  </div>
  <div class="form-field">
    <label>Microsoft Teams Username</label>
    <input name="email" type="email" max-length="50" required>
    <p id="email-check-message" class="error"></p>
  </div>
  <div class="form-field">
    <label>Sex</label>
    <div class="radio">
        <div><input type="radio" id="male" name="sex" value="M" required><label for="male">Male</label></div>
        <div><input type="radio" id="female" name="sex" value="F"><label for="female">Female</label></div>
    </div>
    <p id="sex-check-message" class="error"></p>
  </div>
  <div class="form-field">
    <label>Aadhaar Number</label>
    <input name="aadhaar" type="text" maxlength="12" required>
    <p id="aadhaar-check-message" class="error"></p>
  </div>
  <div class="form-field">
    <label>Contact Number</label>
    <input name="contact" type="text" maxlength="10" required>
    <p id="contact-check-message" class="error"></p>
  </div>
  <div class="form-field">
    <label>College</label>
    <input name="college" type="text" max-length="60" value="Motilal Nehru National Institute of Technology Allahabad" readonly required>
    <p id="college-check-message" class="error"></p>
  </div>
  <div class="form-field">
    <label>Password</label>
    <input name="password" type="password" required>
    <p id="password-check-message" class="error"></p>
  </div>
  <div style="margin-bottom:20px;"><input type="checkbox" name="coc" required><label>I have read the <a href="documents/undertaking.pdf">undertaking</a> and agree to the conditions and will be responsible for my actions.</label></div>
  <p id="register-response-message" class="error"></p>
  <div style="width:100%;"><button id="register-button">Register</button><div style="display:inline-block;padding-left:5%;margin:20px auto;"><a href="login" style="color:var(--text-color);">Have an account?</a></div></div>
</section>

<script>
$('input[name="username"]').on("keyup",function(){
    var username = $('input[name="username"]').val();
    var data = {'action':'checkUsernameAvailability','username':username};
    $.ajax({
        url:'ajax',
        method:"POST",
        data:data,
        dataType: "json",
        success:function(data){
            $('#username-check-message').removeClass(" error");
            $('#username-check-message').removeClass(" success");
            $('#username-check-message').html(data.msg);
            if(data.success == true) $('#username-check-message').addClass(" success");
            else $('#username-check-message').addClass(" error");
            $('#username-check-message').css("display","block");
        },
        error:function(error,status){
            $('#username-check-message').removeClass(" error");
            $('#username-check-message').removeClass(" success");
            $('#username-check-message').html(error.responseText);
            if(data.success == true) $('#username-check-message').addClass(" success");
            else $('#username-check-message').addClass(" error");
            $('#username-check-message').css("display","block");
        }
    });
});
$('.form-field > input').on('keyup',function(e){
    if(e.which == 13) return;
    $(this).next().removeClass(" error");
    $(this).next().css("display","none");
});
$('.form-field input[type="radio"]').on('click',function(e){
    $('#sex-check-message').removeClass(" error");
    $('#sex-check-message').css("display","none");
});
function checkFieldsIfBlank(){
    $.each($('.form-field'),function(i,val){
        var content = val.querySelector('input').value;
        if(content == 'M'){ content = $("input[name='sex']:checked").val(); if(content == undefined) content = ""; }
        if(content == ''){
            var a = val.querySelector('p');
            a.classList.add("error");
            a.style.display = "block";
            a.innerHTML = val.querySelector("label").innerHTML+" cannot be blank";
        }
    });
}
$('input[name="sex"]').on('click',function(){
    $('#sex-check-message').removeClass("error");
    $('#sex-check-message').css("display","none");
});
$('input[name="coc"]').on('click',function(){
    $('#register-response-message').css("display","none");
});
function checkIfCOCAgreed(){
    if(!$('input[name="coc"]')[0].checked){
        $('#register-response-message').html("Please read and agree Code of Conduct to continue.");
        $('#register-response-message').css("display","block");
        return false;
    }
    return true;
}
$('#register-button').on('click',function(){
    checkFieldsIfBlank();
    var a = $('.form-field > p.error');
    if(a.length != 0){$('#register-response-message').html("Please fill the form completely.");$('#register-response-message').css("display","block"); return;}
    if(checkIfCOCAgreed()){
        var username = $('input[name="username"]').val();
        var name = $('input[name="name"]').val();
        var email = $('input[name="email"]').val();
        var sex = $("input[name='sex']:checked").val();
        var aadhaar = $('input[name="aadhaar"]').val();
        var contact = $('input[name="contact"]').val();
        var college = $('input[name="college"]').val();
        college = "Motilal Nehru National Institute of Technology";
        var password = $('input[name="password"]').val();
        var data = {'action':'registerParticipants','username':username,'name':name,'email':email,'sex':sex,'aadhaar':aadhaar,'contact':contact,'college':college,'password':password};
        $.ajax({
            url:'ajax',
            dataType:'json',
            data:data,
            method:'POST',
            success:function(data){
                console.log(data);
                $('#register-response-message').html(data.msg);
                if(data.success == 1){ $('#register-response-message').removeClass("error"); $('#register-response-message').addClass("success"); $('#register-response-message').html(data.msg); $('#register-response-message').css("display","block"); window.location.href = './dashboard'; }
                else if(data.success == -1){$('#register-response-message').css("display","block");$('input[name="'+data.errorField+'"] ~ p').html(data.errorMsg);$('input[name="'+data.errorField+'"] ~ p').css("display","block");$('input[name="'+data.errorField+'"] ~ p').addClass("error");}
                else{$('#register-response-message').css("display","block");}
            },
            error:function(error){$('#register-response-message').html(error.responseText);$('#register-response-message').css("display","block");}
        });
    }
    return;
});
</script>
<?php
if(($_SERVER['REQUEST_METHOD'] === 'GET') or (isset($_POST['action']) and $_POST['action'] != "getContentPage")){
    include_once('components/footer.php');
}
if(isset($_SESSION['user']) and isset($_SESSION['usertype'])){
  ob_end_clean();
}
?>