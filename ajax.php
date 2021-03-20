<?php
error_reporting(0);
define ("SECRETKEY", "bcd1669c0232a0bfda48a63ecbf16acd");
session_start();
header('Content-Type: application/json');
include_once('class.misc.php');
$misc = new misc();
$result = [];
if(isset($_POST['action'])){
	if($_POST['action'] == 'login'){
		$logged = $misc->checkLogin($_POST['username'], $_POST['password']);
		if($logged['status'] == 1){
			$result['success'] = true;
            $result['msg'] = "Welcome ".$logged['name'];
            $_SESSION['user'] = $logged['username'];
            $_SESSION['usertype'] = $logged['usertype'];
            
            //If password change count is changed logout
            $result['password_change_count'] = $logged['password_change_count'];
            $_SESSION['password_change_count'] = $logged['password_change_count'];

		}
		elseif($logged['status'] == 0){
			$result['success'] = false;
			$result['msg'] = "Username and Password do not Match";
		}
		elseif($logged['status'] == -1){
			$result['success'] = false;
			$result['msg'] = "User Id not Registered";
		}
		elseif($logged['status'] == -2){
			$result['success'] = false;
			$result['msg'] = "Username cannot be blank";
		}
		elseif($logged['status'] == -3){
			$result['success'] = false;
			$result['msg'] = "Username Invalid";
		}
		echo json_encode($result);
    }
    elseif($_POST['action'] == 'checkUsernameAvailability'){
        $check = $misc->checkUsernameAvailability($_POST['username']);
        if($check['status'] == 1){
            $result['success'] = true;
            $result['msg'] = 'Available';
        }
        elseif($check['status'] == 0){
            $result['success'] = false;
            $result['msg'] = "Username already exists";
        }
        elseif($check['status'] == -1){
            $result['success'] = false;
            $result['msg'] = "Username Cannot Be Blank";
        }
        elseif($check['status'] == -2){
            $result['success'] = false;
            $result['msg'] = "Username Invalid, Special Characters used";
        }
        echo json_encode($result);
    }
    elseif($_POST['action'] == 'registerParticipants'){
        $username = $_POST['username'];
        $check = $misc->checkUsernameAvailability($username);
        if($check['status'] != 1){
            $result['success'] = false;
            $result['msg'] = "Check for errors in your form";
        }
        $check = $misc->registerParticipants($username,$_POST['name'],$_POST['email'],$_POST['sex'],$_POST['aadhaar'],$_POST['contact'],'Motilal Nehru National Institute of Technology Allahabad',$_POST['password']);
        if($check['status'] == 1){
            $result['success'] = 1;
            if(!isset($check['msg'])){
                $result['msg'] = "Please verify your account";
            }
            else{
                $result['msg'] = $check['msg'];
            }
            $_SESSION['user'] = $check['username'];
            $_SESSION['usertype'] = $check['usertype'];
            $_SESSION['password_change_count'] = $logged['password_change_count'];
        }
        elseif($check['status'] == 0){
            $result['success'] = 0;
            $result['msg'] = "Some error caused, please contact team";
            $result['exception'] = $check['exception'];
        }
        elseif($check['status'] == -1){
            $result['success'] = -1;
            $result['msg'] = "Please fill the form properly";
            $result['errorField'] = $check['errorField'];
            $result['errorMsg'] = $check['errorMsg'];
        }
        echo json_encode($result);
    }
    elseif($_POST['action'] == 'verifyAccount'){
        $username = $_POST['username'];
        $verify = $_POST['verify'];
        if($misc->checkVerification($username,$verify) == true){
            $result['status'] = true;
            $result['msg'] = "Your account has been verified";
        }
        else{
            $result['status'] = false;
            $result['msg'] = "Wrong Key";
        }
        echo json_encode($result);
    }
    elseif($_POST['action'] == 'resendVerification'){
        $username = $_POST['username'];
        if($misc->resendVerification($username)){
            $result['status'] = true;
            $result['msg'] = "An email has been sent!";
        }
        else{
            $result['status'] = false;
            $result['msg'] = "Could not contact your email!";
        }
        echo json_encode($result);
    }
    elseif($_POST['action'] == 'registerIndividualEvent'){
        $username = openssl_decrypt(base64_decode($_POST['key']),"AES-128-ECB",SECRETKEY);
        $temp_key = $username;
        $token = strtok($temp_key,",");
        if($token != false){
            $username = substr($token,0,-17);
            $password_change_count = strtok(",");
            if($misc->checkIfUserValid($username)){
                if($password_change_count == $misc->getPasswordChangeCount($username)){
                    $eventID = $_POST['eventID'];
                    $check = $misc->registerIndividualEvent($username,$eventID);
                    if($check['status'] == 1){
                        $result['status'] = true;
                        $result['event_name'] = $check['event_name'];
                        $result['msg'] = "Registered Successfully!";
                    }
                    elseif($check['status'] == 0){
                        $result['status'] = false;
                        $result['msg'] = "Server Error, please contact team!";
                    }
                    else{
                        $result['status'] = false;
                        $result['msg'] = $check['msg'];
                    }
                }
                else{$result['status'] = false; $result['msg'] = "Password Changed!"; $result['logout'] = true; session_destroy(); }
            }
            else{$result['status'] = false; $result['msg'] = "User Invalid!";}
        }
        else{ $result['status'] = false; $result['msg'] = "Wrong Key!"; session_destroy(); }
        echo json_encode($result);
    }
    elseif($_POST['action'] == 'deregisterEvent'){
        $username = openssl_decrypt(base64_decode($_POST['key']),"AES-128-ECB",SECRETKEY);
        $temp_key = $username;
        $token = strtok($temp_key,",");
        if($token != false){
            $username = substr($token,0,-17);
            $password_change_count = strtok(",");
            if($misc->checkIfUserValid($username)){
                if($password_change_count == $misc->getPasswordChangeCount($username)){
                    $eventID = $_POST['eventID'];
                    $check = $misc->deregisterEvent($username,$eventID);
                    if($check['status'] == 1){
                        $result['status'] = true;
                        $result['eventid'] = $check['eventid'];
                        $result['eventname'] = $check['eventname'];
                        $result['eventtype'] = $check['eventtype'];
                        $result['msg'] = "Unregistered Successfully!";
                    }
                    elseif($check['status'] == 0){
                        $result['status'] = false;
                        $result['msg'] = "Server Error, please contact team!";
                    }
                    else{
                        $result['status'] = false;
                        $result['msg'] = $check['msg'];
                    }
                }
                else{$result['status'] = false; $result['msg'] = "Password Changed!"; $result['logout'] = true; session_destroy(); }
            }
            else{$result['status'] = false; $result['msg'] = "User Invalid!";}
        }
        else{ $result['status'] = false; $result['msg'] = "Wrong Key!"; session_destroy(); }
        echo json_encode($result);
    }
    elseif($_POST['action'] == 'createNewTeam'){
        $username = openssl_decrypt(base64_decode($_POST['key']),"AES-128-ECB",SECRETKEY);
        $temp_key = $username;
        $token = strtok($temp_key,",");
        if($token != false){
            $username = substr($token,0,-17);
            $password_change_count = strtok(",");
            if($misc->checkIfUserValid($username)){
                if($password_change_count == $misc->getPasswordChangeCount($username)){
                    $eventID = $_POST['eventID'];
                    $team_name = $_POST['team_name'];
                    $check = $misc->createNewTeam($username,$eventID,$team_name);
                    if($check['status'] == 1){
                        $result['status'] = true;
                        $result['msg'] = "Team Creation Successful!";
                    }
                    elseif($check['status'] == 0){
                        $result['status'] = false;
                        $result['msg'] = "Server Error, please contact team!";
                    }
                    else{
                        $result['status'] = false;
                        $result['msg'] = $check['msg'];
                    }
                }
                else{$result['status'] = false; $result['msg'] = "Password Changed!"; $result['logout'] = true; session_destroy(); }
            }
            else{$result['status'] = false; $result['msg'] = "User Invalid!";}
        }
        else{ $result['status'] = false; $result['msg'] = "Wrong Key!"; session_destroy(); }
        echo json_encode($result);
    }
    elseif($_POST['action'] == 'checkTeamStatus'){
        $teamID = $_POST['teamID'];
        $check = $misc->getTeamStatus($teamID);
        if($check['status'] == 1){
            $check['status'] = true;
        }
        else{
            $result['status'] = false;
            $result['msg'] = "Server Error, please contact team!";
            echo json_encode($result);
        }
        echo json_encode($check);
    }
    elseif($_POST['action'] == 'checkValidUsername'){
        $username = $_POST['username'];
        $check = $misc->checkIfUserValid($username);
        if($check['status'] == true){
            $result['status'] = true;
        }
        else{
            $result['status'] = false;
            $result['msg'] = "User does not exist!";
        }
        echo json_encode($result);
    }
    elseif($_POST['action'] == 'sendTeamRequest'){
        $sender = openssl_decrypt(base64_decode($_POST['key']),"AES-128-ECB",SECRETKEY);
        $temp_key = $sender;
        $token = strtok($temp_key,",");
        if($token != false){
            $sender = substr($token,0,-17);
            $password_change_count = strtok("");
            if($misc->checkIfUserValid($sender)){
                if($password_change_count == $misc->getPasswordChangeCount($sender)){
                    $teamID = $_POST['teamID'];
                    $username = $_POST['username'];
                    $check = $misc->sendTeamRequest($username,$teamID,$sender);
                    if($check['status'] == 1){
                        $result['status'] = true;
                        $result['msg'] = "Invite Request Sent!";
                    }
                    elseif($check['status'] == 0){
                        $result['status'] = false;
                        $result['msg'] = "Server Error, please contact team!";
                    }
                    else{
                        $result['status'] = false;
                        $result['msg'] = $check['msg'];
                    }
                }
                else{$result['status'] = false; $result['msg'] = "Password Changed!"; $result['logout'] = true; session_destroy(); }
            }
            else{$result['status'] = false; $result['msg'] = "User Invalid!";}
        }
        else{ $result['status'] = false; $result['msg'] = "Wrong Key!"; session_destroy(); }
        echo json_encode($result);
    }
    elseif($_POST['action'] == 'acceptTeamRequest'){
        $username = openssl_decrypt(base64_decode($_POST['key']),"AES-128-ECB",SECRETKEY);
        $temp_key = $username;
        $token = strtok($temp_key,",");
        if($token != false){
            $username = substr($token,0,-17);
            $password_change_count = strtok(",");
            if($misc->checkIfUserValid($username)){
                if($password_change_count == $misc->getPasswordChangeCount($username)){
                    $teamID = $_POST['teamID'];
                    $check = $misc->acceptTeamRequest($username,$teamID);
                    if($check['status'] == 1){
                        $result['status'] = true;
                        $result['msg'] = "Invite Request Accepted!";
                    }
                    elseif($check['status'] == 0){
                        $result['status'] = false;
                        $result['msg'] = "Server Error, please contact team!";
                    }
                    else{
                        $result['status'] = false;
                        $result['msg'] = $check['msg'];
                    }
                }
                else{$result['status'] = false; $result['msg'] = "Password Changed!"; $result['logout'] = true; session_destroy(); }
            }
            else{$result['status'] = false; $result['msg'] = "User Invalid!";}
        }
        else{ $result['status'] = false; $result['msg'] = "Wrong Key!"; session_destroy(); }
        echo json_encode($result);
    }
    elseif($_POST['action'] == 'rejectTeamRequest'){
        $username = openssl_decrypt(base64_decode($_POST['key']),"AES-128-ECB",SECRETKEY);
        $temp_key = $username;
        $token = strtok($temp_key,",");
        if($token != false){
            $username = substr($token,0,-17);
            $password_change_count = strtok(",");
            if($misc->checkIfUserValid($username)){
                if($password_change_count == $misc->getPasswordChangeCount($username)){
                    $teamID = $_POST['teamID'];
                    $check = $misc->rejectTeamRequest($username,$teamID);
                    if($check['status'] == 1){
                        $result['status'] = true;
                        $result['msg'] = "Invite Request Rejected!";
                    }
                    elseif($check['status'] == 0){
                        $result['status'] = false;
                        $result['msg'] = "Server Error, please contact team!";
                    }
                    else{
                        $result['status'] = false;
                        $result['msg'] = $check['msg'];
                    }
                }
                else{$result['status'] = false; $result['msg'] = "Password Changed!"; $result['logout'] = true; session_destroy(); }
            }
            else{$result['status'] = false; $result['msg'] = "User Invalid!";}
        }
        else{ $result['status'] = false; $result['msg'] = "Wrong Key!"; session_destroy(); }
        echo json_encode($result);
    }
    elseif($_POST['action'] == 'registerCoordinators'){
        $username = openssl_decrypt(base64_decode($_POST['key']),"AES-128-ECB",SECRETKEY);
        $temp_key = $username;
        $token = strtok($temp_key,",");
        if($token != false){
            $username = substr($token,0,-17);
            $password_change_count = strtok(",");
            if($misc->checkIfUserValid($username)){
                if($password_change_count == $misc->getPasswordChangeCount($username)){
                    $username = $_POST['username'];
                    $check = $misc->checkUsernameAvailability($username);
                    if($check['status'] != 1){
                        $result['success'] = false;
                        $result['msg'] = "Check for errors in your form";
                    }
                    $check = $misc->registerCoordinators($username,$_POST['name'],$_POST['sex'],$_POST['aadhaar'],$_POST['contact'],$_POST['password'],$_POST['eventID']);
                    if($check['status'] == 1){
                        $result['success'] = 1;
                        if(!isset($check['msg'])){
                            $result['msg'] = "Successfully registered a coordinator";
                        }
                        else{
                            $result['msg'] = $check['msg'];
                        }
                    }
                    elseif($check['status'] == 0){
                        $result['success'] = 0;
                        $result['msg'] = "Some error caused, please contact team";
                        $result['exception'] = $check['exception'];
                    }
                    elseif($check['status'] == -1){
                        $result['success'] = -1;
                        $result['msg'] = "Please fill the form properly";
                        $result['errorField'] = $check['errorField'];
                        $result['errorMsg'] = $check['errorMsg'];
                    }
                }
                else{
                    $result['success'] = -1;
                    $result['msg'] = "This is not your authorized space!";
                }
            }
            else{
                $result['success'] = -2;
                $result['msg'] = "How are you sure if you even exist?";
            }
        }
        else{
            $result['success'] = -2;
            $result['msg'] = "Don't fuck with the developer!";
        }
        echo json_encode($result);
    }
    elseif($_POST['action'] == 'printPaidList'){
        $result['status'] = true;
        $result['msg'] = $misc->printPaidList();
        echo json_encode($result);
    }
    elseif($_POST['action'] == 'printCoordinatorList'){
        $result['status'] = true;
        $result['msg'] = $misc->printCoordinatorList();
        echo json_encode($result);
    }
    elseif($_POST['action'] == 'forgot-password'){
		$logged = $misc->forgotPassword($_POST['username'], $_POST['email'],$_POST['tosendemail']);
		if($logged['status'] == 1){
			$result['success'] = true;
            $result['msg'] = "Please check your email";
		}
		elseif($logged['status'] == 0){
			$result['success'] = false;
			$result['msg'] = "Username and Microsoft Teams Username do not Match";
		}
		elseif($logged['status'] == -2){
			$result['success'] = false;
			$result['msg'] = "Username cannot be blank";
		}
		elseif($logged['status'] == -3){
			$result['success'] = false;
			$result['msg'] = "Username Invalid";
		}
		echo json_encode($result);
    }
    elseif($_POST['action'] == 'validateGUIDForgotPassword'){
        $logged = $misc->validateGUIDForgotPassword($_POST['ID']);
		if($logged['status'] == 1){
			$result['success'] = true;
			$result['msg'] = "Wait! We're redirecting you!";
            $_SESSION['user'] = $logged['username'];
            $_SESSION['usertype'] = $logged['usertype'];
            
            //If password change count is changed logout
            $result['password_change_count'] = $logged['password_change_count'];
            $_SESSION['password_change_count'] = $logged['password_change_count'];
		}
		elseif($logged['status'] == 0){
			$result['success'] = false;
			$result['msg'] = "You've used a wrong link!";
		}
		elseif($logged['status'] == -1){
			$result['success'] = false;
			$result['msg'] = "The Link has Expired.";
		}
		echo json_encode($result);
    }
    elseif($_POST['action'] == 'submissionEvent'){
        $username = openssl_decrypt(base64_decode($_POST['key']),"AES-128-ECB",SECRETKEY);
        $temp_key = $username;
        $token = strtok($temp_key,",");
        if($token != false){
            $username = substr($token,0,-17);
            $password_change_count = strtok(",");
            if($misc->checkIfUserValid($username)){
                if($password_change_count == $misc->getPasswordChangeCount($username)){
                    $eventID = $_POST['eventID'];
                    $link = $_POST['link'];
                    $check = $misc->submissionEvent($username,$eventID,$link);
                    if($check['status'] == 1){
                        $result['status'] = true;
                        $result['event_name'] = $check['event_name'];
                        $result['msg'] = "Submitted Successfully!";
                    }
                    elseif($check['status'] == 0){
                        $result['status'] = false;
                        $result['msg'] = "Server Error, please contact team!";
                    }
                    else{
                        $result['status'] = false;
                        $result['msg'] = $check['msg'];
                    }
                }
                else{$result['status'] = false; $result['msg'] = "Password Changed!"; $result['logout'] = true; session_destroy(); }
            }
            else{$result['status'] = false; $result['msg'] = "User Invalid!";}
        }
        else{ $result['status'] = false; $result['msg'] = "Wrong Key!"; session_destroy(); }
        echo json_encode($result);
    }
    elseif($_POST['action'] == 'addRound'){
        $username = openssl_decrypt(base64_decode($_POST['key']),"AES-128-ECB",SECRETKEY);
        $temp_key = $username;
        $token = strtok($temp_key,",");
        if($token != false){
            $username = substr($token,0,-17);
            $password_change_count = strtok(",");
            if($misc->checkIfUserValid($username)){
                if($password_change_count == $misc->getPasswordChangeCount($username)){
                    $result = $misc->addRound($username,$_POST['duration']);
                }
                else{$result['status'] = false; $result['msg'] = "Password Changed!"; $result['logout'] = true; session_destroy(); }
            }
            else{$result['status'] = false; $result['msg'] = "User Invalid!";}
        }
        else{ $result['status'] = false; $result['msg'] = "Wrong Key!"; session_destroy(); }
        echo json_encode($result);
    }
    elseif($_POST['action'] == 'removeRound'){
        $username = openssl_decrypt(base64_decode($_POST['key']),"AES-128-ECB",SECRETKEY);
        $temp_key = $username;
        $token = strtok($temp_key,",");
        if($token != false){
            $username = substr($token,0,-17);
            $password_change_count = strtok(",");
            if($misc->checkIfUserValid($username)){
                if($password_change_count == $misc->getPasswordChangeCount($username)){
                    $result = $misc->removeRound($username);
                }
                else{$result['status'] = false; $result['msg'] = "Password Changed!"; $result['logout'] = true; session_destroy(); }
            }
            else{$result['status'] = false; $result['msg'] = "User Invalid!";}
        }
        else{ $result['status'] = false; $result['msg'] = "Wrong Key!"; session_destroy(); }
        echo json_encode($result);
    }
    elseif($_POST['action'] == 'toggleQualify'){
        $username = openssl_decrypt(base64_decode($_POST['key']),"AES-128-ECB",SECRETKEY);
        $temp_key = $username;
        $token = strtok($temp_key,",");
        if($token != false){
            $username = substr($token,0,-17);
            $password_change_count = strtok(",");
            if($misc->checkIfUserValid($username)){
                if($password_change_count == $misc->getPasswordChangeCount($username)){
                    $result = $misc->toggleQualify($username,$_POST['participantID']);
                }
                else{$result['status'] = false; $result['msg'] = "Password Changed!"; $result['logout'] = true; session_destroy(); }
            }
            else{$result['status'] = false; $result['msg'] = "User Invalid!";}
        }
        else{ $result['status'] = false; $result['msg'] = "Wrong Key!"; session_destroy(); }
        echo json_encode($result);
    }
}
?>