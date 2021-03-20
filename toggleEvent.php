<?php
session_start();
include_once('class.misc.php');
$misc = new misc();
$user = $_SESSION['user'];
$usertype = $_SESSION['usertype'];
$user = $misc->getUser($user);
if(isset($_GET['eventID'])){
    $eventID = $_GET['eventID'];
    if($misc->checkIfCoordinator($user['username'],$eventID)){
        if(isset($_GET['action'])){
            if($_GET['action'] == "toggleRegistrations"){
                    $misc->toggleRegistrations($user['username'],$eventID);
            }
            else if($_GET['action'] == "endEvent"){
                $eventID = $_GET['eventID'];
                $misc->endEvent($user['username'],$eventID);
            }
            else if($_GET['action'] == "setRoundSystem"){
                $eventID = $_GET['eventID'];
                $misc->setRoundSystem($user['username'],$eventID);
            }
            else if($_GET['action'] == "removeRoundSystem"){
                $eventID = $_GET['eventID'];
                $misc->removeRoundSystem($user['username'],$eventID);
            }
        }
    }
}
header('Location: dashboard');
?>