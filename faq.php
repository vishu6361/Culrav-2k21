<?php
$title = "FAQ | Culrav";
if(($_SERVER['REQUEST_METHOD'] === 'GET') or (isset($_POST['action']) and $_POST['action'] != "getContentPage")){
    include_once('components/header.php');
}
?>
<section style="margin-top:70px;">
    <div id="about_us" style="margin-bottom:0">
        <div data-aos="fade-down" data-aos-anchor-placement="top-center"><h1 style="animation:unset;">Got stuck?</h1><br><p>Incase you're stuck feel free to go ahead and contact us or leave us a mail regarding your issues and problems</p></div>
</section>

<section class="faqs">
    <div class="question">How many events can we register for?<div class="answer">You can register for as many events as you like, there is no limitation as such.</div></div>
    <div class="question">I am unable to create a team. What do I do?<div class="answer">You can only create only a single team for an event.<br>If you unregistered yourself from that event, you'll have to ask someone else to create a team and ask them to send a request to join.<br>If some other error, contact <a href="mailto:jewelbarman998@gmail.com">web team</a>.</div></div>
    <div class="question">I can't invite anymore people in my team. Why is it so?<div class="answer">A team can consist of certain number of people depending on the event, read more about the event.<br>You can only send as many invites at a time as the total number of participants allowed in a team.<br>If your invitee is not responding and your invites is stuck, please contact the <a href="mailto:jewelbarman998@gmail.com">web admin</a> to cancel the invite.</div></div>
    <div class="question">How many people can we invite for an event?<div class="answer">Each event has a certain limitation to the number of participants in a single team. So, you can only invite those many invites.</div></div>
    <div class="question">How can I be Mr. or Miss. Culrav?<div class="answer">You need to participate in events and win and score more points than anyone else.<br>Winning an event will fetch you points and then at the end of it all, we'll be evalutaing the scores and declaring the winner(s).<br>Just watch the movie, 'Student of the Year' if you haven't. It'll be easier to explain then.</div></div>
    <div class="question">What Is The Procedure Of Forming A Team?<div class="answer">After registration, participants are required to form their team by creating a team for a certain team event only.<br>P.S. - Teams can only have a certain size depending on the event.</div></div>
    <div class="question">I don't have google drive. Can I use something else to share?<div class="answer">You surely can use any other link, but if that's not a verified site. We might not consider it, due to security risks and malwares.</div></div>
    <div class="question">I missed my submission, can I submit it now?<div class="answer">We're very sorry, but according to the rules, it's not acceptable. And we won't accept your submission now.<br>But if it's a special case, rather try contacting the co-ordinators.</div></div>
    <div class="question">How can I contact a co-ordinator for a query?<div class="answer">You'll probably find the contact number of the co-ordinator in the <a herf="events">events</a> section under your desired events.</div></div>
</section>

<?php
if(($_SERVER['REQUEST_METHOD'] === 'GET') or (isset($_POST['action']) and $_POST['action'] != "getContentPage")){
    include_once('components/footer.php');
}
?>