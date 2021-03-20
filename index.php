<?php
$title = "Home | Culrav";
if(($_SERVER['REQUEST_METHOD'] === 'GET') or (isset($_POST['action']) and $_POST['action'] != "getContentPage")){
    include_once('components/header.php');
}
?>
<style>
.simple{font-size:1.3rem;line-height:2.0rem;letter-spacing:1px;}
header{mix-blend-mode:none !important;}
</style>
<section id="main-banner">
    <div id="neon-gridForm">
        <div id="neon-grid">
            <table id="gridTable"></table>
        </div>
    </div>
    <div id="sky"><img src="images/stars.png"><div id="sun"></div></div>
    <div id="silhouette"><img src="images/mountains.svg"><div id="separator-light"></div></div>
    <div class="fade"></div>
    
<div id="filter">
    <div class="scanlines"></div>

    <div class="intro-wrap">
        <div class="noise"></div>
    </div>
</div>

<div id="logo"><div id="logo-preserve-3d"><div class="triangle"><div class="triangle-inside"></div></div><div class="triangle" style="transform:translate(-50%,-50%) rotate(-10deg)"><div class="triangle-inside"></div></div><img src="images/logo.png"></div></div>
<div id="logo-compliment">
    <h3 class="sm-show" style="margin:0;font-size:4vw;display:none;">11th - 17th May, 2020</h3>
    <p class="sm-show" style="margin:0;margin-bottom:40px;line-height:normal;font-size:3.2vw;display:none;">Registrations are open<br/>till 11th May 11:59:59!</p>
    <a class="button-square" style="margin:auto;" onclick="javascript:getRegister()">Register</a>
</div>
<div class="sm-hide" style="position: absolute;left:4%;bottom:10%;z-index:100;">
    <h3>11th - 17th May, 2020</h3>
    <p style="margin:5px 0 0;font-size:15px;line-height:24px;">Registrations are open till 11th May 11:59:59!<br>Read about our events and register.</p>
</div>
<div id="more"><h5 style="width:50vh;margin:0"><span></span> there's more where that came from</h5></div>

</section>

<section style="margin:20vh 0 20vh 0;">
    <div id="about_us">
        <div data-aos="fade-down" data-aos-anchor-placement="top-center"><h1><span>About us</span></h1></div><br><br>
        <div class="flex">
            <div id="about_us_image"><img src="images/1.jpg" style="width:100%;height:auto"></div>
            <div id="about_us_content">
                <div data-aos="fade-left" data-aos-anchor-placement="top-center"><h3 style="text-transform:uppercase;font-size:2.8rem;">motilal nehru national institute of technology</h3></div>
                <div data-aos="fade-down" data-aos-anchor-placement="top-center"><p class="simple">Initiated in 1987, Culrav has emerged as the most resplendent college based fest in Northern India and as one of the most awaited cultural event of the country.</p></div>
                <div data-aos="fade-down" data-aos-anchor-placement="top-center"><p class="simple">And you’re right. If you want something that any team can build, then the basement is not your place. We go the extra mile, and then walk a couple of miles more, just for fun.</p></div>
                <div data-aos="fade-down" data-aos-anchor-placement="top-center"><p style="font-size:1.5rem;line-height:1.2rem;">Step in, don’t be shy!</p><br>
                <a class="button-square" onclick="javascript:getRegister()">Register</a></div>
            </div>
        </div>
    </div>
</section>

<section class="quote-section" data-aos="fade" data-aos-anchor-placement="top-center">
    <div class="big"><h1><span>"</span></h1></div>
    <div class="content"><h3>through the sands of time</h3><h4>- culrav</h4></div>
</section>


<section id="about-culrav">
    <center><h1 class="topic-heading"><span>About Culrav</span></h1></center>
    <div class="flex display-style-3" style="margin-bottom:0">
        <div class="count" style="transform:translateY(-20%)">
            <div class="heading" data-aos="fade-down" data-aos-anchor-placement="bottom-bottom">
                <h3>Events and Competitions</h3>
                <h5>We have exciting events in the domains of Dramatics, Dance, Fashion, Literary, Arts, Photography and Film Making.</h5>
            </div>
            <h1><span>01</span></h1>
        </div>
        <div class="count" style="transform:translateY(20%)">
            <div class="heading" data-aos="fade-down" data-aos-anchor-placement="bottom-bottom">
                <h3>Kavyasandhya</h3>
                <h5>The inaugural flagship poetry night of Culrav attracts connoisseurs of poetry, showcasing their talent which will be judged by eminent personalities.</h5>
            </div>
            <h1><span>02</span></h1>
        </div>
    </div>
    <div class="flex display-style-3" style="margin-top:0">
        <div class="count" style="transform:translateY(-20%)">
            <div class="heading" data-aos="fade-down" data-aos-anchor-placement="bottom-bottom">
                <h3>Celebrity and Pro-Nites</h3>
                <h5>The last two nights of Culrav witnesses thrilling performances from famous celebrities and artists in the fields of rock,hip-hop or EDM.</h5>
            </div>
            <h1><span>03</span></h1>
        </div>
        <div class="count" style="transform:translateY(20%)">
            <div class="heading" data-aos="fade-down" data-aos-anchor-placement="bottom-bottom">
                <h3>Informal Events</h3>
                <h5>From "Stand-up Comedy" to "Googly Cricket" we have a wide variety of events in our arena, to enthrall the audiences all the time wherein they stand a chance to win lots of goodies and merchandise.</h5>
            </div>
            <h1><span>04</span></h1>
        </div>
    </div>
</section>
<center><hr width="30%" style="margin-top:0"><br></center>
<section>
    <div class="flex display-style-1">
        <div class="count">
            <div class="heading" data-aos="fade-down" data-aos-anchor-placement="bottom-bottom">
                <h3>Corona Virus Disease</h3>
                <h5>COVID-19 Alert</h5>
            </div>
            <h1><span class="text-stroke">01</span></h1>
        </div>
        <div class="content" data-aos="fade-down" data-aos-anchor-placement="center-bottom">
            <p class="simple">Protect yourself and others around you by knowing the facts and taking appropriate precautions. Follow advice provided by your local public health agency.<br/><br/>Avoiding unneeded visits to medical facilities allows healthcare systems to operate more effectively, therefore protecting you and others.</p>
            <a class="button-square" onclick="window.location.href='https://www.who.int/emergencies/diseases/novel-coronavirus-2019'">Read More</a>
        </div>
    </div>
</section>
<section>
    <div class="flex display-style-2">
        <div class="count">
            <div class="heading" data-aos="fade-down" data-aos-anchor-placement="top-center">
                <h3>Participate in our events</h3>
                <h5>involve yourself while staying at home</h5>
            </div>
            <h1><span class="text-stroke">02</span></h1>
        </div>
        <div class="image" data-aos="fade-in" data-aos-anchor-placement="top-center"><div class="glitch-image"><img src="images/121.png"></div></div>
        <div class="content" data-aos="fade-down" data-aos-anchor-placement="bottom-bottom">
            <p class="tag">Participate in our events</p>
            <h3 class="heading">NOTHING STOPS US</h3>
            <p class="details">Now participate in our events and keep yourself busy, while also having fun at our events. You can choose your type of events and register for them. All you have to do is register your account on this portal and you're good to go!</p>
            <br>
            <a class="button-square" onclick="javascript:getRegister()">Let's Go <i class="fa fa-arrow-right"></i></a>
        </div>
    </div>
</section>

<section class="sponsors">
    <h1 data-aos="fade-down" data-aos-anchor-placement="top-center">Past Sponsors</h1>
    <div class="flex" data-aos="fade-down" data-aos-anchor-placement="top-center">
        <div class="sponsor"><a href="https://www.heromotocorp.com"><img src="images/sponsors/hero.svg"></a></div>
        <div class="sponsor"><a href="https://www.thesouledstore.com"><img src="images/sponsors/the_souled_store.png"></a></div>
        <div class="sponsor"><a><img src="images/sponsors/octave.png"></a></div>
        <div class="sponsor"><a href="https://www.grabon.in"><img src="images/sponsors/grab_on.svg"></a></div>
        <div class="sponsor"><a href="https://www.onlinesbi.com"><img src="images/sponsors/sbi.svg"></a></div>
        <div class="sponsor"><a href="https://redfmindia.in"><img src="images/sponsors/redfm.svg"></a></div>
        <div class="sponsor"><a href="https://www.vlccwellness.com/"><img src="images/sponsors/vlcc.svg"></a></div>
        <div class="sponsor"><a href="https://www.iocl.com"><img src="images/sponsors/iocl.svg"></a></div>
        <div class="sponsor"><a href="https://www.lakmeindia.com"><img src="images/sponsors/lakme.svg"></a></div>
        <div class="sponsor"><a><img src="images/sponsors/blue_world.png"></a></div>
        <div class="sponsor"><a href="https://www.zebronics.com"><img src="images/sponsors/zebronics.png"></a></div>
        <div class="sponsor"><a href="http://www.kwalitywalls.in/"><img src="images/sponsors/kwalitywalls.svg"></a></div>
        <div class="sponsor"><a href="https://www.zomato.com/lucknow/al-baik-xpress-1-indira-nagar"><img src="images/sponsors/albaik.png"></a></div>
        <div class="sponsor"><a><img src="images/sponsors/ies.png"></a></div>
        <div class="sponsor"><a><img src="images/sponsors/made_easy.png"></a></div>
        <div class="sponsor"><a><img src="images/sponsors/semitone_studios.png"></a></div>
        <div class="sponsor"><a><img src="images/sponsors/goli.png"></a></div>
        <div class="sponsor"><a><img src="images/sponsors/rhythm_exports.png"></a></div>
    </div>
    <div class="fade"></div>
</section>
<script>
var win = $(window);
var allMods = $(".sponsor");
$.fn.visible = function(partial) {
    
    var $t            = $(this),
        $w            = $(window),
        viewTop       = $w.scrollTop(),
        viewBottom    = viewTop + $w.height(),
        _top          = $t.offset().top,
        _bottom       = _top + $t.height(),
        compareTop    = partial === true ? _bottom : _top,
        compareBottom = partial === true ? _top : _bottom;
  
  return ((compareBottom <= viewBottom) && (compareTop >= viewTop));

};
// Already visible modules
allMods.each(function(i, el) {
  var el = $(el);
  if (el.visible(true)) {
      el.addClass("glitch-blink"); 
    } 
  else{
      el.removeClass("glitch-blink");
    }
});

win.scroll(function(event) {
  
  allMods.each(function(i, el) {
    var el = $(el);
    if (el.visible(true)) {
      el.addClass("glitch-blink"); 
    }
    else{
      el.removeClass("glitch-blink");
    }
  });
});
var a = document.querySelectorAll('.sponsor');
a.forEach(function(index){
    var delay = Math.random();
    index.style.animationDelay = delay+"s";
});
</script>
<?php
if(($_SERVER['REQUEST_METHOD'] === 'GET') or (isset($_POST['action']) and $_POST['action'] != "getContentPage")){
    include_once('components/footer.php');
}
?>