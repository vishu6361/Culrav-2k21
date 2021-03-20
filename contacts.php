<?php
$title = "Contacts | Culrav";
if(($_SERVER['REQUEST_METHOD'] === 'GET') or (isset($_POST['action']) and $_POST['action'] != "getContentPage")){
    include_once('components/header.php');
}
?>
<section style="margin-top:70px;">
    <div id="about_us" style="margin-bottom:0">
        <div data-aos="fade-down" data-aos-anchor-placement="top-center"><h1 style="animation:unset;line-height:110%;">We're always there <span>for you</span><br>to help and clear <span>your queries</span></h1><br><p style="max-width:85%;line-height:125%;">Incase you're stuck feel free to go ahead and contact us or leave us a mail regarding your issues and problems</p></div>
</section>

<section class="event">
  <div class="flex" style="align-items:center;margin:0;">
    <div class="event-details">
      <h3 class="heading">where we are?</h3>
      <p class="tag" style="margin-bottom:0">Location:</p>
      <p class="details" style="margin-top:0">MNNIT Allahabad, Tellyarganj, Allahabad - 211004</p>
      <p class="tag" style="margin-bottom:0">Email:</p>
      <p class="details" style="margin-top:0">culrav2k20@gmail.com</p>
      <p class="tag" style="margin-bottom:0;text-transform:unset">Get in touch using the form below, shoot us an email, reach out on social media, heck – send a carrier pigeon. Regardless of how, we’d love to hear from you.</p>
      <br><br>
      <a class="button-square" onclick="javascript:getFAQ()">Read FAQ</a>
    </div>
    <div class="event-image"><div class="glitch-image"><img src="images/talk-on-phone.png" class="light-image"></div></div>
  </div>
</section>

<?php
if(($_SERVER['REQUEST_METHOD'] === 'GET') or (isset($_POST['action']) and $_POST['action'] != "getContentPage")){
    include_once('components/footer.php');
}
?>