<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="images/icon.png">
    <title><?php echo $title;?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="styles/index.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="scripts/jquery-min.js"></script>
</head>
<style id="gridstyle"></style>
<style id="gridExtra"></style>
<style id="new-style"></style>
<style>
#mouse{position:fixed;transform:translate(-50%,-50%);height:20px;width:20px;top:0;left:0;pointer-events:none;border-radius:50%;display:none;z-index:1001;transition:ease 0.5s all;transition-property:height,width;mix-blend-mode:difference;}
#clickable-target{position:absolute;top:100%;left:100%;opacity:0;transition:0.5s all ease;z-index:1000;}
#mouse.clickable-target > #clickable-target{opacity:1;}
</style>
<body>

<div id="mouse">
  <div id="clickable-target"></div>
</div>

<!--Navigation Bar -->
<header>
  <div id="menu">
    <div class="menu-left"><!--<img id="image-logo" src="images/logo.png">--><a href="./" style="color:black;text-decoration:unset;"><h3><span class="text-stroke"  style="text-shadow: 3px 3px black,5px 5px lightblue,7px 7px black,9px 9px violet !important;-webkit-text-stroke: 2px yellow;color:black;">culrav</span></h3></a></div>
    <div class="menu-right">
      <div style="display:inline-block;margin-right:20px"><button class="submit-button color-difference" onclick="javascript:invertColors();">Change Theme</button></div>
      <div class="menu-marker" style="display:inline-block">
        <input type="checkbox">
        <ul>
          <li class="first-bar"></li>
          <li class="second-bar"></li>
          <li class="third-bar"></li>
        </ul>
      </div>
    </div>
  </div>
</header>
<div id="modal-menu">
  <div id="menu-options">
    <div class="flex" style="justify-content:center;align-items:center;margin:0;">
      <div id="options-menu">
        <div><h1><span><a class="menu-links" onclick="javascript:getHome();">Home</a></span></h1></div>
        <div><h1><span><a class="menu-links" onclick="javascript:getAbout();">About Us</a></span></h1></div>
        <div><h1><span><a class="menu-links" onclick="javascript:getEvents();">Events</a></span></h1></div>
        <div><h1><span><a class="menu-links" onclick="javascript:getCelebs();">Celebs</a></span></h1></div>
        <div><h1><span><a class="menu-links" onclick="javascript:getSponsors();">Sponsors</a></span></h1></div>
        <div><h1><span><a class="menu-links" onclick="javascript:getContacts();">Contacts</a></span></h1></div>
        <div><h1><span><a class="menu-links" onclick="javascript:getTeam();">Team</a></span></h1></div>
      </div>
      <div id="quick-contact-info">
        <h3>Need Help?</h3>
        <br>
        <h5>Email: culrav2k20@gmail.com</h5>
        <h5>Phone: +91-456456465</h5>
        <!-- <a style="text-decoration:underline;line-height:200%;cursor:pointer;" onclick="javascript:invertColors()">Change Theme</a> -->
      </div>
    </div>
  </div>
  <div id="menu-footer">
      <div>&copy; Culrav 2020, MNNIT all rights reserved.</div>
      <div><a href="https://facebook.com/mnnit.culrav"><i class="fa fa-facebook"></i></a><a href="mailto:culrav2k20@gmail.com"><i class="fa fa-envelope"></i></a><a href="https://instagram.com/culrav"><i class="fa fa-instagram"></i></a></div>
  </div>
</div>
<!--Navigation Bar Done -->

<section id="main-page-content">