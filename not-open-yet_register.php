<?php
$title = "Register | Culrav";
if(($_SERVER['REQUEST_METHOD'] === 'GET') or (isset($_POST['action']) and $_POST['action'] != "getContentPage")){
    include_once('components/header.php');
}
?>
<style>
@media only screen and (max-width:500px){
    h1{line-height:3.5rem !important;}
}
</style>
<section style="width:70%;text-align:center;margin:30vh auto;">
    <h1 style="line-height:5rem;"><span style="word-wrap: break-word">Registrations</span> are not yet open.</h1>
    <p>Please come back later in a few days.</p>
</section>

<?php
if(($_SERVER['REQUEST_METHOD'] === 'GET') or (isset($_POST['action']) and $_POST['action'] != "getContentPage")){
    include_once('components/footer.php');
}
if(isset($_SESSION['user']) and isset($_SESSION['usertype'])){
  ob_end_clean();
}
?>