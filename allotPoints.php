<?php
session_start();
include_once('class.misc.php');
$misc = new misc();
$user = $_SESSION['user'];
$usertype = $_SESSION['usertype'];
$user = $misc->getUser($user);
if(isset($_POST['eventID'])){
    $eventID = $_POST['eventID'];
    if($misc->checkIfCoordinator($user['username'],$eventID)){
        if(isset($_POST['individual-team']) and isset($_POST['eventType']) and isset($_POST['points'])){
            $eventType = $_POST['eventType'];
            $points = $_POST['points'];
            if($eventType == 'team'){
                $teamID = $_POST['individual-team'];
                $misc->allotPointsToTeam($teamID,$points,$eventID);
            }
            else if($eventType == 'individual'){
                $username = $_POST['individual-team'];
                $misc->allotPointsToIndividual($username,$points,$eventID);
            }
        }
    }
}
header('Location: dashboard');
?>