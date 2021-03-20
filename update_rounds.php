<?php
session_start();
include_once('class.misc.php');
$misc = new misc();
$misc->updateRounds();
?>