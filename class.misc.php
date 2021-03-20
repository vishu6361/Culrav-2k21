<?php
/**
 * This Contains all the Operations that are required for checking,
 * validation, security, rechecking and scanning and displaying and sending mails.
 * Basically this is the miscellaneous operations for the complete system.
 * Ýou can find all the operation based functions over here.
 * To search for a function, think of a basic name and then Search in this file
 * You'll find it, for team related operations search TeamName or TeamID
 * For Events related Operations search EventName or EventID
 * Author: Jyotirmoy Barman
 * Branch: Computer Science and Engineering
 * College: MNNIT Allahabad
 * Batch: 2016 - 2020
 */
class misc{
	protected $sql;
	public function __construct(){
		include_once('class.config.php');
		$config = new config();
		$base = $config->baseServer;
		require_once($_SERVER['DOCUMENT_ROOT'].$base.'class.sql.php');
		$this->sql = new sql();
    }
    /*################################################
     * Current User Details
     * Manipulation and Changes
     *################################################
     */
    public function getUser($username){
        //Gets the User Details
        $username = $this->sql->escape($username);
        return $this->sql->getDataOnlyOne('users','username',$username);
    }
    public function checkPassword($username,$password){
        //Checks the Password with the entered Password
        $username = $this->sql->escape($username);
        $password = $this->sql->escape($password);
        //Weak Hashing Technique, One Way Hashing, so it's still secure!
        $password = md5($password);
        if($password == $this->sql->getData('password','login','username',$username)){
            return true;
        }
        return false;
    }
    public function getPasswordChangeCount($username){
        //This is to check if the Password Count has changed or not
        //Application: If Password Count Changes then log out the user from the system with wrong Password change count
        $username = $this->sql->escape($username);
        if(!$this->checkIfUserValid($username)){
            return $this->sql->getData('password_change_count','login','username',$username);
        }
        return false;
    }
    public function setPassword($username,$newPassword){
        //Changing the Password
        $username = $this->sql->escape($username);
        $newPassword = $this->sql->escape($newPassword);
        if($newPassword == "" or strlen($newPassword) < 6){return false;}
        $newPassword = md5($newPassword);
        try{
            $this->sql->query = "UPDATE `login` SET `password` = '$newPassword',`password_change_count` = `password_change_count` + 1 where username = '$username'";
            $this->sql->process();
        }
        catch(Exception $e){
            return false;
        }
        return true;
    }
    public function checkLogin($username,$password){
        $username = $this->sql->escape($username);
        $password = $this->sql->escape($password);
        if($username == ""){
            $result['status'] = -2;
        }
        if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $username))
        {
            $result['status'] = -3;
            return $result;
        }
        elseif($this->sql->countData('users','username',$username) == 1){
            $password = md5($password);
            if($this->sql->countData('login','username',$username,'password',$password) == 1){
                $result = $this->sql->getDataOnlyOne('users','username',$username);
                $result['status'] = 1;
            }
            else{
                $result['status'] = 0;
            }
        }
        else{
            $result['status'] = -1;
        }
        return $result;
    }
    public function checkUsernameAvailability($username){
        if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $username))
        {
            $result['status'] = -2;
            return $result;
        }
        if($username == ''){
            $result['status'] = -1;
            return $result;
        }
        if(preg_match('/\s/',$username)){
            $result['status'] = -2;
            return $result;
        }
        $username = $this->sql->escape($username);
        if($this->sql->countData('users','username',$username) == 1){
            $result['status'] = 0;
        }
        else{
            $result['status'] = 1;
        }
        return $result;
    }
    public function is_natural_number($str)
    {
        return preg_match('/^[0-9]+$/', $str) ? true : false;
    }



    /**
     * To Register the Students for the portal
     * Registering the Students by all the values
     */
    public function registerParticipants($username,$name,$email,$sex,$aadhaar,$contact,$college,$password){
        $username = $this->sql->escape($username);
        $name = $this->sql->escape($name);
        $email = $this->sql->escape($email);
        $sex = $this->sql->escape($sex);
        $aadhaar = $this->sql->escape($aadhaar);
        $contact = $this->sql->escape($contact);
        $college = $this->sql->escape($college);
        $password = $this->sql->escape($password);
        $password = md5($password);
        $username = strtolower($username);
        if(!$this->is_natural_number($aadhaar)){$result['status'] = -1; $result['errorField'] = 'aadhaar';$result['errorMsg'] = 'Aadhaar invalid'; return $result;}
        if(!$this->is_natural_number($contact)){$result['status'] = -1; $result['errorField'] = 'contact';$result['errorMsg'] = 'Contact invalid'; return $result;}

        /*Handling Blank Errors */
        if($username == ''){$result['status'] = -1; $result['errorField'] = 'username';$result['errorMsg'] = 'Username cannot be blank'; return $result;}
        if($name == ''){$result['status'] = -1; $result['errorField'] = 'name';$result['errorMsg'] = 'Name cannot be blank'; return $result;}
        if($email == ''){$result['status'] = -1; $result['errorField'] = 'email';$result['errorMsg'] = 'Microsoft Teams Username cannot be blank'; return $result;}
        if($sex == ''){$result['status'] = -1; $result['errorField'] = 'sex';$result['errorMsg'] = 'Sex cannot be blank'; return $result;}
        if($aadhaar == ''){$result['status'] = -1; $result['errorField'] = 'aadhaar';$result['errorMsg'] = 'Aadhaar cannot be blank'; return $result;}
        if($contact == ''){$result['status'] = -1; $result['errorField'] = 'contact';$result['errorMsg'] = 'Contact cannot be blank'; return $result;}
        if($college == ''){$result['status'] = -1; $result['errorField'] = 'college';$result['errorMsg'] = 'College cannot be blank'; return $result;}
        if($password == ''){$result['status'] = -1; $result['errorField'] = 'password';$result['errorMsg'] = 'Password cannot be blank'; return $result;}

        /*Handling content issues */
        if($sex != 'M' and $sex !='F'){$result['status'] = -1; $result['errorField'] = 'sex';$result['errorMsg'] = 'Only Male or Female'; return $result;}
        if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $username)){$result['status'] = -1;$result['errorField'] = 'username';$result['errorMsg'] = 'Username Invalid'; return $result;}
        if(preg_match('/\s/',$username)){$result['status'] = -1;$result['errorField'] = 'username';$result['errorMsg'] = 'Username Invalid'; return $result;}
        if(strlen($username) < 6){$result['status'] = -1; $result['errorField'] = 'username';$result['errorMsg'] = 'Atleast 6 characters!'; return $result;}
        if (!preg_match("/^[a-zA-Z ]*$/",$name)) {$result['status'] = -1;$result['errorField'] = 'name';$result['errorMsg'] = 'Only letters and whitespace allowed'; return $result;}
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){$result['status'] = -1; $result['errorField'] = 'email';$result['errorMsg'] = 'Invalid Microsoft Teams Username'; return $result;}
        if(strlen($aadhaar) != 12){$result['status'] = -1; $result['errorField'] = 'aadhaar';$result['errorMsg'] = 'Aadhaar Number is invalid'; return $result;}
        if(strlen($contact) != 10){$result['status'] = -1; $result['errorField'] = 'contact';$result['errorMsg'] = 'Mobile Number is invalid'; return $result;}
        if(strlen($password) < 6){$result['status'] = -1; $result['errorField'] = 'password';$result['errorMsg'] = 'Must be atleast 6 characters'; return $result;}
        if(!preg_match('/@mnnit.ac.in$/', $email)){$result['status'] = -1; $result['errorField'] = 'email';$result['errorMsg'] = 'Invalid Microsoft Teams Username'; return $result;}

        try{
            $this->sql->query = "INSERT into `users` (username,name,email,sex,aadhaar,contact,college,usertype,PIN,verified,festID) values ('$username','$name','$email','$sex','$aadhaar','$contact','$college','students',NULL,'0',NULL)";
            $this->sql->process();
            $this->sql->query = "INSERT into `login` (username,password) values ('$username','$password')";
            $this->sql->process();
        }
        catch(Exception $e){
            $result['status'] = 0;
            $result['exception'] = $e;
            return $result;
        }
        $result['status'] = 1;
        $result['username'] = $username;
        $result['usertype'] = 'students';
        return $result;
    }



    /**
     * Generation of Random Values for Unique ID's
     */
    public function generatePIN(){
        $randomid = mt_rand(100000,999999);
        $PIN = md5($randomid);
        return $PIN;
    }
    public function checkIfFestIDExists($festID){
        $festID = $this->sql->escape($festID);
        if($this->sql->countData('users','festID',$festID) == 1){
            return false;
        }
        return true;
    }
    public function generateFestID(){
        $count = mt_rand(0,99999);
        $id = "CLRV";
        $id .= str_pad($count, 6, '0', STR_PAD_LEFT);
        while(!$this->checkIfFestIDExists($id)){
            $count = mt_rand(0,99999);
            $id = "CLRV";
            $id .= str_pad($count, 6, '0', STR_PAD_LEFT);
        }
        return $id;
    }


    /**
     * These are the operations that require accessing and sending the mails
     */
    public function forgotPassword($username,$email,$tosendemail){
        $username = $this->sql->escape($username);
        $email = $this->sql->escape($email);
        $tosendemail = $this->sql->escape($tosendemail);
        if($username == ""){
            $result['status'] = -2;
            return $result;
        }
        if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $username) or preg_match('/\s/', $username))
        {
            $result['status'] = -3;
            return $result;
        }
        elseif($this->sql->countData('users','username',$username,'email',$email) == 1){
            $GUID = $this->generatePIN();
            while($this->sql->countData('forgot_password_logs','GUID',$GUID) != 0){
                $GUID = $this->generatePIN();
            }
            $this->sql->query = "INSERT `forgot_password_logs` (`GUID`,`username`,`email`) values ('$GUID','$username','$tosendemail');";
            $this->sql->process();
            $this->sendEmailForVerification($username,$tosendemail,$GUID,1);
            $result['status'] = 1;
        }
        else{
            $result['status'] = 0;
        }
        return $result;
    }
    public function validateGUIDForgotPassword($GUID){
        $GUID = $this->sql->escape($GUID);
        if($this->sql->countData('forgot_password_logs','GUID',$GUID) == 1){
            $timestamp = $this->sql->getData('timestamp','forgot_password_logs','GUID',$GUID);
            $time_diff = strtotime("now") - strtotime($timestamp);
            if($time_diff < 1800){
                $user = $this->getUser($this->sql->getData('username','forgot_password_logs','GUID',$GUID));
                $result = $this->sql->getDataOnlyOne('login','username',$user['username']);
                $result['status'] = 1;
                return $result;
            }
            $result['status'] = 0;
            return $result;
        }
        $result['status'] = -1;
        return $result;
    }
    public function sendEmailForVerification($username,$email,$PIN,$type=0){
        $to = $email;
        $from = "culrav.webteam@gmail.com";
        $name = "Culrav WebTeam";
        $header  = 'MIME-Version: 1.0' . "\r\n";
        $header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $header .= "from: ".$name."<".$from.">";
        if($type == 0){
            $subject = "Verification of Culrav Account";
            $message = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Muli|Montserrat&display=swap">
                <style>
                </style>
            </head>
            <body style="font-family:\'Montserrat\',sans-serif;margin:0;padding:0;background:whitesmoke;height:100%;width:100%;">
                <table style="width:50%;margin:auto;background:white;padding:10%;position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);">
                    <img src="https://culrav.mnnit.ac.in/images/logo.png" style="max-width:400px;width:50%;min-width:100px;height:auto">
                    <h1 style="font-family:\'Muli\',sans-serif;">
                        Just one more step away...
                    </h1>
                    <p>Below is your PIN, it generates every time you request for a verification email. So make sure to enter the latest PIN.</p>
                    <h3>PIN: '.$PIN.'</h3>
                    <p>We are here to help if you need it. Visit the <a href="culrav.mnnit.ac.in">Culrav Website</a> for more info or <a href="culrav.mnnit.ac.in/contacts">Contact Us</a>.</p>
                    <p>- Culrav Webteam</p>
                    <br><br>
                    <p>Please don\'t reply back to this thread. This is only an email for developers.</p>
                </table>
            </body>
            </html>
            ';
        }
        else{
            $subject = "Password Change Link";
            $message = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Muli|Montserrat&display=swap">
                <style>
                </style>
            </head>
            <body style="font-family:\'Montserrat\',sans-serif;margin:0;padding:0;background:whitesmoke;height:100%;width:100%;">
                <table style="width:50%;margin:auto;background:white;padding:10%;position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);">
                    <img src="https://culrav.mnnit.ac.in/images/logo.png" style="max-width:400px;width:50%;min-width:100px;height:auto">
                    <h1 style="font-family:\'Muli\',sans-serif;">
                        Just one more step away...
                    </h1>
                    <p>Below is the link, it generates every time you request for a forgot password email. So make sure to use the latest link.</p>
                    <h3>Change Password Link: <a href="https://culrav.mnnit.ac.in/forgot-password?ID='.$PIN.'">https://culrav.mnnit.ac.in/forgot-password?ID='.$PIN.'</a></h3>
                    <p>If you didn\'t opt for a password change <a href="culrav.mnnit.ac.in/contacts">let us know</a>.</p>
                    <p>We are here to help if you need it. Visit the <a href="culrav.mnnit.ac.in">Culrav Website</a> for more info or <a href="culrav.mnnit.ac.in/contacts">Contact Us</a>.</p>
                    <p>- Culrav Webteam</p>
                    <br><br>
                    <p>Please don\'t reply back to this thread. This is only an email for developers.</p>
                </table>
            </body>
            </html>
            ';
        }
        try{
            if(mail($to,$subject,$message,$header) == false){
                return -1;
            }
        }
        catch(Exception $e){
            return -1;
        }
        return;
    }


    /**
     * This is the User Validation of details for security checks
     */
    public function checkIfUserValid($username){
        $username = $this->sql->escape($username);
        if($this->sql->countData('users','username',$username) == 1){
            return true;
        }
        return false;
    }
    public function checkIfUserPaid($username){
        if($this->checkIfUserValid($username)){
            $username = $this->sql->escape($username);
            if($this->sql->countData('transaction','paidBy',$username) > 0){
                return true;
            }
        }
        return false;
    }
    public function checkVerification($username,$PIN){
        $username = $this->sql->escape($username);
        $PIN = $this->sql->escape($PIN);
        if(strlen($PIN) != 8 and !preg_match('/^[0-9]{4}/',$PIN)){return false;}
        else if($this->sql->countData('users','username',$username) == 1){
            $festID = $this->generateFestID();
            $this->sql->query = "UPDATE `users` set `PIN` = '$PIN',`verified` = '1',`festID` = '$festID' where `username` = '$username';";
            $this->sql->process();
            return true;
        }
        return false;
    }
    public function resendVerification($username){
        $username = $this->sql->escape($username);
        $PIN = $this->generatePIN();
        if($this->sql->countData('users','username',$username) == 1){
            $this->sql->query = "UPDATE users set PIN = '$PIN' where username = '$username'";
            $this->sql->process();
            $user = $this->getUser($username);
            $send_mail_check = $this->sendEmailForVerification($username,$user['email'],$PIN);
            if($send_mail_check == -1){
                return false;
            }
            return true;
        }
        return false;
    }


    /**
     * Getting Users Registered Events
     */
    public function getUserRegisteredEvents($username){
        $username = $this->sql->escape($username);
        if($this->checkIfUserValid($username)){
            return $this->sql->getDatas('scores','username',$username);
        }
        return false;
    }
    public function onCompleteTransaction($transactionID,$username,$paidAmount){
        $username = $this->sql->escape($username);
        $transactionID = $this->sql->escape($transactionID);
        $paidAmount = $this->sql->escape($paidAmount);
        if($this->checkIfUserValid($username)){
            $this->sql->query = "INSERT into `transaction` (transactionID,paidBy,paidTo,paidAmount) values ('$transactionID','$username','Culrav 2020','$paidAmount')";
            $this->sql->process();
            return true;
        }
        return false;
    }

    public function getTeamEventRequest($username){
        $username = $this->sql->escape($username);
        if($this->checkIfUserValid($username)){
            return $this->sql->getDatas('team_members','username',$username,'request','pending');
        }
        return false;
    }
    public function getTeamNameByTeamID($teamID){
        $teamID = $this->sql->escape($teamID);
        if($this->sql->countData('team_creation_logs','teamID',$teamID) == 1){
            return $this->sql->getData('team_name','team_creation_logs','teamID',$teamID);
        }
        return false;
    }
    public function getTeamDetailsByTeamID($teamID){
        $teamID = $this->sql->escape($teamID);
        if($this->sql->countData('team_creation_logs','teamID',$teamID) == 1){
            return $this->sql->getDataOnlyOne('team_name','team_creation_logs','teamID',$teamID);
        }
        return false;
    }
    public function getEventNameByEventID($eventID){
        $eventID = $this->sql->escape($eventID);
        if($this->sql->countData('events','eventID',$eventID) == 1){
            return $this->sql->getData('event_name','events','eventID',$eventID);
        }
        return false;
    }
    public function getEventClassNameByEventClass($eventClass){
        $eventClass = $this->sql->escape($eventClass);
        if($this->sql->countData('event_classes','event_class',$eventClass) == 1){
            return $this->sql->getData('event_class_name','event_classes','event_class',$eventClass);
        }
        return false;
    }
    public function getEventClassNameByEventID($eventID){
        $eventID = $this->sql->escape($eventID);
        if($this->sql->countData('events','eventID',$eventID) == 1){
            $eventClass = $this->sql->getData('event_class','events','eventID',$eventID);
        }
        else{
            return false;
        }
        if($this->sql->countData('event_classes','event_class',$eventClass) == 1){
            return $this->sql->getData('event_class_name','event_classes','event_class',$eventClass);
        }
        return false;
    }
    public function getIndividualEvents(){
        return $this->sql->getDatas('events','eventtype','individual');
    }
    public function getTeamEvents(){
        return $this->sql->getDatas('events','eventtype','team');
    }
    public function getEventByEventID($eventID){
        $eventID = $this->sql->escape($eventID);
        return $this->sql->getDataOnlyOne('events','eventID',$eventID);
    }
    public function getIndividualEventByEventID($eventID){
        $eventID = $this->sql->escape($eventID);
        if($this->sql->countData('events','eventID',$eventID,'eventtype','individual') == 1)
        return $this->sql->getDataOnlyOne('events','eventID',$eventID,'eventtype','individual');
        return false;
    }
    public function getTeamEventByEventID($eventID){
        $eventID = $this->sql->escape($eventID);
        if($this->sql->countData('events','eventID',$eventID,'eventtype','team') == 1)
        return $this->sql->getDataOnlyOne('events','eventID',$eventID,'eventtype','team');
        return false;
    }
    private function eventListComparator($a,$b){
        if($a['eventID'] === $b['eventID']) return 0;
        return -1;
    }
    public function getIndividualNotParticipatedEvents($username){
        $all_events = $this->sql->getDatas('events','eventtype','individual');
        $username = $this->sql->escape($username);
        $get_participated_events = $this->sql->getDatas('scores','username',$username);
        $get_registered_individual_events = [];
        foreach($get_participated_events as $event){
            $e = $this->getIndividualEventByEventID($event['eventID']);
            if($e != false){
                array_push($get_registered_individual_events,$e);
            }
        }
        $result = array_udiff($all_events,$get_registered_individual_events,"misc::eventListComparator");
        return $result;
    }
    public function getTeamNotParticipatedEvents($username){
        $all_events = $this->sql->getDatas('events','eventtype','team');
        $username = $this->sql->escape($username);
        $get_participated_events = $this->sql->getDatas('scores','username',$username);
        $get_registered_team_events = [];
        foreach($get_participated_events as $event){
            $e = $this->getTeamEventByEventID($event['eventID']);
            if($e != false){
                array_push($get_registered_team_events,$e);
            }
        }
        $result = array_udiff($all_events,$get_registered_team_events,"misc::eventListComparator");
        return $result;
    }
    public function registerIndividualEvent($username,$eventID){
        $username = $this->sql->escape($username);
        $eventID = $this->sql->escape($eventID);
        if($this->checkIfUserValid($username)){
            if($this->sql->countData('users','username',$username,'verified','1') > 0){
                if($this->sql->getData('status','events','eventID',$eventID) == '1'){
                    if($this->sql->getData('registrationStatus','events','eventID',$eventID) == '1'){
                        try{
                            $this->sql->query = "INSERT INTO `scores` values ('$username','$eventID','0')";
                            $this->sql->process();
                        }
                        catch(Exception $e){
                            $result['status'] = -1;
                            $result['msg'] = "Already registered!";
                            return $result;
                        }
                        $result['status'] = 1;
                        $result['event_name'] = $this->getEventNameByEventID($eventID);
                        return $result;
                    }
                    else{
                        $result['status'] = -1;
                        $result['msg'] = "Registrations are closed for the event!";
                        return $result;
                    }
                }
                else{
                    $result['status'] = -1;
                    $result['msg'] = "Event has ended or been cancelled!";
                    return $result;
                }
            }
            else{
                $result['status'] = -1;
                $result['msg'] = "You haven't paid your fees yet!";
                return $result;
            }
        }
        $result['status'] = -1;
        $result['msg'] = "User does not exists";
        return $result;
    }
    public function isUserParticipating($username,$eventID){
        $username = $this->sql->escape($username);
        $eventID = $this->sql->escape($eventID);
        if($this->sql->countData('scores','username',$username,'eventID',$eventID) > 0){ return true;}
        return false;
    }
    public function getUserParticipatedEvents($username){
        $username = $this->sql->escape($username);
        return $this->sql->getDatas('scores','username',$username);
    }
    public function deregisterEvent($username,$eventID){
        $username = $this->sql->escape($username);
        $eventID = $this->sql->escape($eventID);
        if($this->sql->countData('scores','username',$username,'eventID',$eventID) > 0){
            //Removing the events for User only if scores are not allotted
            //For Team Events
            if($this->sql->countData('team_members','username',$username,'eventID',$eventID,'request','yes') == 1){
                $result['eventid'] = $eventID;
                $result['eventtype'] = 'team';
                $result['eventname'] = $this->getEventNameByEventID($eventID);
                $teamID = $this->sql->getData('teamID','team_members','username',$username,'eventID',$eventID,'request','yes');
                $this->sql->query = "UPDATE `team_members` set request = 'no' where username = '$username' and eventID = '$eventID' and request = 'yes'";
                $this->sql->process();
                $rows = $this->sql->getDataOnlyOne('scores','username',$username,'eventID',$eventID,'score',0);
                //Incase if an entry already exists in event logs
                if($this->sql->countData('delete_event_logs','username',$username,'eventID',$eventID,'score','0') == 0){
                    $this->sql->query = "INSERT into `delete_event_logs` values ('$username','$eventID','0','$teamID')";
                    $this->sql->process();
                }
            }
            //For individual events
            elseif($this->sql->countData('scores','username',$username,'eventID',$eventID)){
                $result['eventid'] = $eventID;
                $result['eventtype'] = 'individual';
                $result['eventname'] = $this->getEventNameByEventID($eventID);
                $rows = $this->sql->getDataOnlyOne('scores','username',$username,'eventID',$eventID,'score',0);
                //Incase if an entry already exists in event logs
                if($this->sql->countData('delete_event_logs','username',$username,'eventID',$eventID,'score','0') == 0){
                    $this->sql->query = "INSERT into `delete_event_logs` (username,eventID,score) values ('$username','$eventID','0')";
                    $this->sql->process();
                }
            }
            $this->sql->query = "DELETE from `scores` where username = '$username' and eventID = '$eventID'";
            $this->sql->process();
            $this->sql->query = "DELETE from `submissionlinks` where username = '$username' and eventID = '$eventID'";
            $this->sql->process();
            $this->sql->query = "DELETE from `round_submissions` where username = '$username' and eventID = '$eventID'";
            $this->sql->process();
            $result['status'] = 1;
            return $result;
        }
        $result['status'] = -1;
        $result['msg'] = 'Haven\'t registered for this event yet';
        return $result;
    }
    public function getUserTeams($username){
        $username = $this->sql->escape($username);
        if($this->checkIfUserValid($username)){
            $teams = $this->sql->getDatas('team_members','username',$username,'request','yes');
        }
        return $teams;
    }
    public function checkIfUserCanBeInvited($username,$eventID){
        $username = $this->sql->escape($username);
        $eventID = $this->sql->escape($eventID);
        if($this->checkIfUserValid($username)){
            $user = $this->getUser($username);
                if($user['verified'] == 1){
                    if($this->sql->countData('scores','username',$username,'eventID',$eventID) == 0){
                        $result['status'] = true;
                        return $result;
                    }
                    $result['status'] = false;
                    $result['msg'] = "User is already in another team!";
                    return $result;
                }
            $result['status'] = false;
            $result['msg'] = "User hasn't verified his/her account yet!";
            return $result;
        }
        $result['status'] = false;
        $result['msg'] = "User does not exists!";
        return $result;
    }
    public function createNewTeam($username,$eventID,$team_name){
        $username = $this->sql->escape($username);
        $eventID = $this->sql->escape($eventID);
        $team_name = $this->sql->escape($team_name);

        if(strlen($team_name) == 0){
            $result['status'] = -1;
            $result['msg'] = "Team Name Cannot Be Empty!";
            return $result;
        }
        if($this->checkIfUserValid($username)){
            if($this->sql->countData('users','username',$username,'verified','1') > 0){
                if($this->sql->countData('events','eventID',$eventID) == 1){
                    if($this->sql->getData('status','events','eventID',$eventID) == '1'){
                        if($this->sql->getData('registrationStatus','events','eventID',$eventID) == '1'){
                            if($this->sql->countData('team_members','username',$username,'eventID',$eventID,'request','yes') == 0){
                                $this->sql->query = "INSERT into `team_creation_logs` (team_name,eventID,createdBy) values ('$team_name','$eventID','$username')";
                                $this->sql->process();
                                $this->sql->query = "INSERT into `scores` (username,eventID,score) values ('$username','$eventID','0')";
                                $this->sql->process();
                                $teamID = $this->sql->getData('teamID','team_creation_logs','team_name',$team_name,'eventID',$eventID,'createdBy',$username);
                                $this->sql->query = "INSERT into `team_members` (teamID,eventID,username,request) values ('$teamID','$eventID','$username','yes')";
                                $this->sql->process();
                                $this->sql->query = "UPDATE `team_members` SET request = 'no' where eventID = '$eventID' and username = '$username' and teamID != '$teamID'";
                                $this->sql->process();
                                $result['status'] = 1;
                                $result['msg'] = "Team successfully created!";
                                return $result;
                            }
                            $result['status'] = -1;
                            $result['msg'] = "You already have a team!";
                            return $result;
                        }
                        $result['status'] = -1;
                        $result['msg'] = "Registrations are closed for this event!";
                        return $result;
                    }
                    $result['status'] = -1;
                    $result['msg'] = "Event has already ended or been cancelled!";
                    return $result;
                }
                $result['status'] = -1;
                $result['msg'] = "Event does not exist!";
                return $result;
            }
            $result['status'] = -1;
            $result['msg'] = "You haven't verified your account yet!";
            return $result;
        }
        $result['status'] = -1;
        $result['msg'] = "User doesnot exist!";
        return $result;
    }
    public function getTeamStatus($teamID){
        $teamID = $this->sql->escape($teamID);
        $team_requests = $this->sql->getDatas('team_members','teamID',$teamID);
        $result['count'] = count($team_requests);
        $count = 0;
        foreach($team_requests as $req){
            $result[strval($count)] = $req['username'];
            $result['request'.strval($count)] = $req['request'];
            $count++;
        }
        $result['status'] = 1;
        return $result;
    }
    public function getTeamSizeByTeamID($teamID){
        $teamID = $this->sql->escape($teamID);
        return $this->sql->countData('team_members','teamID',$teamID,'request','yes');
    }
    public function sendTeamRequest($username,$teamID,$sender){
        $username = $this->sql->escape($username);
        $teamID = $this->sql->escape($teamID);
        $sender = $this->sql->escape($sender);
        if($username == $sender){ $result['status'] = -1; $result['msg'] = "You can't send invite to yourself!"; return $result;}
        $eventID = $this->sql->getData('eventID','team_creation_logs','teamID',$teamID);
        $events = $this->sql->getDatas('events','eventID',$eventID);
        $event = $events[0];
        if($this->getTeamSizeByTeamID($teamID) >= $event['people_count']){ $result['status'] = -1; $result['msg'] = "Team size limit exceeded!"; return $result;}
        if($this->checkIfUserValid($username)){
            $user = $this->getUser($username);
            if($user['usertype'] != 'students'){ $result['status'] = -1; $result['msg'] = "User is not a student!"; return $result; }
            if($user['verified'] == 1){
                if($this->sql->countData('scores','username',$username,'eventID',$eventID) == 0){
                    //case where an entry already exists
                    if($this->sql->countData('team_members','username',$username,'teamID',$teamID,'eventID',$eventID) == 1){
                        $this->sql->query = "UPDATE `team_members` SET request = 'pending', requestBy = '$sender' where username = '$username' and eventID = '$eventID'";
                    }
                    else{
                        $this->sql->query = "INSERT into `team_members` (teamID,eventID,username,request,requestBy) values ('$teamID','$eventID','$username','pending','$sender')";
                    }
                    $this->sql->process();
                    $result['status'] = 1;
                    return $result;
                }
                $result['status'] = -1;
                $result['msg'] = "User is already in another team!";
                return $result;
            }
            $result['status'] = -1;
            $result['msg'] = "User hasn't verified his/her account yet!";
            return $result;
        }
        $result['status'] = -1;
        $result['msg'] = "User does not exists!";
        return $result;
    }
    public function acceptTeamRequest($username,$teamID){
        $username = $this->sql->escape($username);
        $teamID = $this->sql->escape($teamID);
        $eventID = $this->sql->getData('eventID','team_creation_logs','teamID',$teamID);
        $event = $this->sql->getDatas('events','eventID',$eventID);
        if($this->getTeamSizeByTeamID($teamID) >= $event['people_count']){ $result['status'] = -1; $result['msg'] = "Team size limit exceeded!"; return $result;}
        if($this->checkIfUserValid($username)){
            if($this->sql->countData('users','username',$username,'verified','1') > 0){
                if($this->sql->countData('scores','eventID',$eventID,'username',$username) == 0){
                    $this->sql->query = "INSERT into `scores` values ('$username','$eventID','0')";
                    $this->sql->process();
                    $this->sql->query = "UPDATE `team_members` SET request = 'yes' WHERE username = '$username' AND teamID = '$teamID'";
                    $this->sql->process();
                    $this->sql->query = "UPDATE `team_members` SET request = 'no' WHERE username = '$username' AND teamID != '$teamID' AND eventID = '$eventID'";
                    $this->sql->process();
                    $result['status'] = 1;
                    $result['msg'] = "Successfully joined the team!";
                    return $result;
                }
                $result['status'] = -1;
                $result['msg'] = "You are already in another team!";
                return $result;
            }
            $result['status'] = -1;
            $result['msg'] = "You haven't verified yet!";
            return $result;
        }
        $result['status'] = -1;
        $result['msg'] = "Your account does not exists!";
        return $result;
    }
    public function rejectTeamRequest($username,$teamID){
        $username = $this->sql->escape($username);
        $teamID = $this->sql->escape($teamID);
        $eventID = $this->sql->getData('eventID','team_creation_logs','teamID',$teamID);
        if($this->checkIfUserValid($username)){
            $this->sql->query = "UPDATE `team_members` SET request = 'no' WHERE username = '$username' AND teamID = '$teamID'";
            $this->sql->process();
            $result['status'] = 1;
            $result['msg'] = "Successfully rejected the request!";
            return $result;
        }
        $result['status'] = -1;
        $result['msg'] = "Your account does not exists!";
        return $result;
    }
    public function getAllEvents(){
        return $this->sql->getDatas('events');
    }
    public function getEventClass($eventClass){
        $eventClass = $this->sql->escape($eventClass);
        return $this->sql->getDataOnlyOne('event_classes','event_class',$eventClass);
    }
    public function getEventsByEventClass($eventClass){
        $event = $this->sql->escape($eventClass);
        return $this->sql->getDatas('events','event_class',$eventClass);
    }
    public function getTotalEventsCount(){
        return $this->sql->countData('events');
    }
    public function getTotalStudentsRegisteredCount(){
        return $this->sql->countData('users','usertype','students');
    }
    public function getTotalStudentsPaidFeesCount(){
        $this->sql->query = "SELECT * from transaction where paidAmount != 0";
        $result = $this->sql->process();
        return mysqli_num_rows($result);
    }
    public function getTopTenListStudents(){
        $this->sql->query = "SELECT username,SUM(score) as score FROM scores GROUP BY username ORDER BY SUM(score) LIMIT 10;";
        $result = $this->sql->process();
        $rows = [];
        while($row = mysqli_fetch_assoc($result)){
            array_push($rows,$row);
        }
        return $rows;
    }
    public function registerCoordinators($username,$name,$sex,$aadhaar,$contact,$password,$eventID){
        $username = $this->sql->escape($username);
        $name = $this->sql->escape($name);
        $password = $this->sql->escape($password);
        $eventID = $this->sql->escape($eventID);
        $sex = $this->sql->escape($sex);
        $aadhaar = $this->sql->escape($aadhaar);
        $contact = $this->sql->escape($contact);
        $PIN = $this->generatePIN();
        $username = strtolower($username);
        $password = md5($password);
        if(!$this->is_natural_number($aadhaar)){$result['status'] = -1; $result['errorField'] = 'aadhaar';$result['errorMsg'] = 'Aadhaar invalid'; return $result;}
        if(!$this->is_natural_number($contact)){$result['status'] = -1; $result['errorField'] = 'contact';$result['errorMsg'] = 'Contact invalid'; return $result;}

        /*Handling Blank Errors */
        if($username == ''){$result['status'] = -1; $result['errorField'] = 'username';$result['errorMsg'] = 'Username cannot be blank'; return $result;}
        if($name == ''){$result['status'] = -1; $result['errorField'] = 'name';$result['errorMsg'] = 'Name cannot be blank'; return $result;}
        if($sex == ''){$result['status'] = -1; $result['errorField'] = 'sex';$result['errorMsg'] = 'Sex cannot be blank'; return $result;}
        if($aadhaar == ''){$result['status'] = -1; $result['errorField'] = 'aadhaar';$result['errorMsg'] = 'Aadhaar cannot be blank'; return $result;}
        if($contact == ''){$result['status'] = -1; $result['errorField'] = 'contact';$result['errorMsg'] = 'Contact cannot be blank'; return $result;}
        if($password == ''){$result['status'] = -1; $result['errorField'] = 'password';$result['errorMsg'] = 'Password cannot be blank'; return $result;}
        
        /*Handling content issues */
        if($sex != 'M' and $sex !='F'){$result['status'] = -1; $result['errorField'] = 'sex';$result['errorMsg'] = 'Only M or F'; return $result;}
        if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $username)){$result['status'] = -1;$result['errorField'] = 'username';$result['errorMsg'] = 'Username Invalid'; return $result;}
        if (!preg_match("/^[a-zA-Z ]*$/",$name)) {$result['status'] = -1;$result['errorField'] = 'name';$result['errorMsg'] = 'Only letters and whitespace allowed'; return $result;}
        if(strlen($aadhaar) != 12){$result['status'] = -1; $result['errorField'] = 'aadhaar';$result['errorMsg'] = 'Aadhaar Number is invalid'; return $result;}
        if(strlen($contact) != 10){$result['status'] = -1; $result['errorField'] = 'contact';$result['errorMsg'] = 'Mobile Number is invalid'; return $result;}
        if(strlen($password) < 6){$result['status'] = -1; $result['errorField'] = 'password';$result['errorMsg'] = 'Must be atleast 6 characters'; return $result;}

        $email = $username.'-culrav-coordi@mnnit.ac.in';

        $this->sql->query = "INSERT users (username,name,email,sex,aadhaar,contact,college,usertype,PIN,verified) values ('$username','$name','$email','$sex','$aadhaar','$contact','Motilal Nehru National Institute of Technology Allahabad','coordinator','$PIN','1')";
        $this->sql->process();
        $this->sql->query = "INSERT coordinators values ('$username','$eventID')";
        $this->sql->process();
        $this->sql->query = "INSERT login values ('$username','$password','0')";
        $this->sql->process();
        $result['status'] = true;
        return $result;
    }
    public function printPaidList(){
        $this->sql->query = "SELECT * from transaction t,users u where paidAmount != 0 and t.paidBy = u.username";
        $result = $this->sql->process();
        $rows = [];
        $msg = '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><style>td,th{border:1px solid black;color:black;}</style></head><body style="font-family:sans-serif;margin:0;padding:0;height:100%;width:100%;"><table style="width:100%;border:1px solid black;color:black"><tr><th>Name</th><th>College</th><th>Transaction ID</th><th>Timestamp</th></tr>';
        while($row = mysqli_fetch_assoc($result)){
            $msg .= '<tr><td>'.$row['name'].'</td><td>'.$row['college'].'</td><td>'.$row['transactionID'].'</td><td>'.$row['timestamp'].'</td></tr>';
            array_push($rows,$row);
        }
        $msg .='</table></body></html>';
        return $msg;
    }
    public function printCoordinatorList(){
        $this->sql->query = "SELECT u.name as name,u.aadhaar as aadhaar,u.contact as contact,e.event_name as event_name from users u,coordinators c,events e where u.usertype = 'coordinator' and c.username = u.username and c.eventID = e.eventID order by c.eventID asc;";
        $result = $this->sql->process();
        $rows = [];
        $msg = '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><style>td,th{border:1px solid black;color:black;}</style></head><body style="font-family:sans-serif;margin:0;padding:0;height:100%;width:100%;"><table style="width:100%;border:1px solid black;color:black"><tr><th>Name</th><th>Aadhaar</th><th>Contact</th><th>Event Name</th></tr>';
        while($row = mysqli_fetch_assoc($result)){
            $msg .= '<tr><td>'.$row['name'].'</td><td>'.$row['aadhaar'].'</td><td>'.$row['contact'].'</td><td>'.$row['event_name'].'</td></tr>';
            array_push($rows,$row);
        }
        $msg .='</table></body></html>';
        return $msg;
    }
    public function getEventOfCoordinator($username){
        $username = $this->sql->escape($username);
        $eventID = $this->sql->getData('eventID','coordinators','username',$username);
        return $this->sql->getDataOnlyOne('events','eventID',$eventID);
    }
    public function getTeamsByEventID($eventID){
        $eventID = $this->sql->escape($eventID);
        $result = $this->sql->getDatas('team_creation_logs','eventID',$eventID);
        $rows = [];
        foreach($result as $r){
            if($this->getTeamSizeByTeamID($r['teamID']) > 0){
                array_push($rows,$r);
            }
        }
        return $rows;
    }
    public function getIndividualsByEventID($eventID){
        $eventID = $this->sql->escape($eventID);
        $result = $this->sql->getDatas('scores','eventID',$eventID);
        $rows = [];
        foreach($result as $r){
            array_push($rows,$this->getUser($r['username']));
        }
        return $rows;
    }
    public function setTransaction($registrationNumber,$username,$paidAmount = 0){
        $registrationNumber = $this->sql->escape($registrationNumber);
        $username = $this->sql->escape($username);
        $paidAmount = $this->sql->escape($paidAmount);
        if($this->checkIfUserValid($username)){
            $this->sql->query = "INSERT into transaction (transactionID,paidBy,paidTo,paidAmount) values('$registrationNumber','$username','Culrav Account','$paidAmount')";
            $this->sql->process();
            return true;
        }
        return false;
    }
    public function getParticipantOrTeamCountInEvent($eventID,$eventtype){
        $eventID = $this->sql->escape($eventID);
        if($eventtype == "team"){
            $total_teams = $this->getTeamsByEventID($eventID);
            return count($total_teams);
        }
        else if($eventtype == "individual"){
            return $this->sql->countData('scores','eventID',$eventID);
        }
        return 0;
    }
    public function checkIfCoordinator($username,$eventID){
        $eventID = $this->sql->escape($eventID);
        $username =  $this->sql->escape($username);
        if(($this->sql->countData('coordinators','username',$username,'eventID',$eventID) == 1)){
            return true;
        }
        return false;
    }
    public function toggleRegistrations($username,$eventID){
        $eventID = $this->sql->escape($eventID);
        if($this->checkIfCoordinator($username,$eventID)){
            if($this->sql->getData('registrationStatus','events','eventID',$eventID) == '0'){
                $this->sql->query = "UPDATE events set registrationStatus = '1' where eventID = '$eventID'";
            }
            else{
                $this->sql->query = "INSERT INTO `event_commence_time` values ('$eventID',CURRENT_TIMESTAMP)";
                $this->sql->process();
                $this->sql->query = "UPDATE events set registrationStatus = '0' where eventID = '$eventID'";
            }
            $this->sql->process();
        }
    }
    public function endEvent($username,$eventID){
        $eventID = $this->sql->escape($eventID);
        if($this->checkIfCoordinator($username,$eventID)){
            $this->sql->query = "UPDATE events set status = '0' where eventID = '$eventID'";
            $this->sql->process();
        }
    }
    public function setRoundSystem($username,$eventID){
        $eventID = $this->sql->escape($eventID);
        if($this->checkIfCoordinator($username,$eventID)){
            $this->sql->query = "UPDATE events set rounds = '1' where eventID = '$eventID'";
            $this->sql->process();
        }
    }
    public function removeRoundSystem($username,$eventID){
        $eventID = $this->sql->escape($eventID);
        if($this->checkIfCoordinator($username,$eventID)){
            $this->sql->query = "UPDATE events set rounds = '0' where eventID = '$eventID'";
            $this->sql->process();
        }
    }
    public function getScoreByUsernameEventID($username,$eventID){
        $username = $this->sql->escape($username);
        $eventID = $this->sql->escape($eventID);
        $result = $this->sql->getData('score','scores','username',$username,'eventID',$eventID);
        if($result == NULL) return -1;
        else return $result;
    }
    public function getTeamMembersByTeamID($teamID){
        $teamID = $this->sql->escape($teamID);
        return $this->sql->getDatas('team_members','teamID',$teamID,'request','yes');
    }
    public function allotPointsToTeam($teamID,$points,$eventID){
        $teamID = $this->sql->escape($teamID);
        $points = $this->sql->escape($points);
        $eventID = $this->sql->escape($eventID);
        $this->sql->query = "UPDATE team_creation_logs set score = '$points' where teamID = '$teamID' and eventID = '$eventID'";
        $this->sql->process();
        $members = $this->getTeamMembersByTeamID($teamID);
        foreach($members as $member){
            $username = $member['username'];
            $this->sql->query = "UPDATE scores set score = '$points' where username = '$username' and eventID = '$eventID'";
            $this->sql->process();
        }
    }
    public function allotPointsToIndividual($username,$points,$eventID){
        $username = $this->sql->escape($username);
        $points = $this->sql->escape($points);
        $eventID = $this->sql->escape($eventID);
        $this->sql->query = "UPDATE scores set score = '$points' where username = '$username' and eventID = '$eventID'";
        $this->sql->process();
    }
    public function getAllEventClasses(){
        return $this->sql->getDatas('event_classes');
    }

    public function VHSStatus(){
        return false;
    }
    public function submissionEvent($username, $eventID, $link){
        $username = $this->sql->escape($username);
        $eventID = $this->sql->escape($eventID);
        $event = $this->getEventByEventID($eventID);
        if(count($event) == 0){
            $result['status'] = -1;
            $result['msg'] = "The event doesnot exist!";
            return $result;
        }
        $link = $this->sql->escape($link);
        if($link == ""){
            $result['status'] = -1;
            $result['msg'] = "The link cannot be blank!";
            return $result;
        }
        if($this->checkIfUserValid($username)){
            $user = $this->getUser($username);
            if($user['verified'] == 1){
                if($this->sql->getData('status','events','eventID',$eventID) == '1'){
                    if($this->sql->getData('registrationStatus','events','eventID',$eventID) == '0'){
                        if($event['eventtype'] == 'individual'){
                            if($event['rounds'] == 1){
                                $cur_round = $event['current_round'];
                                if($cur_round == NULL){
                                    $cur_round = 1;
                                    if($this->sql->countData('round_submissions','participantID',$username,'eventID',$eventID) == 0)
                                        $this->sql->query = "INSERT into `round_submissions` (participantID,qualify,eventID,submissionLink) values ('$username','0','$eventID','$link')";
                                    else
                                        $this->sql->query = "UPDATE `round_submissions` SET submissionLink = '$link' where participantID = '$username' and eventID = '$eventID'";
                                    $this->sql->process();
                                }
                                else if($this->sql->countData('round_submissions','participantID',$username,'eventID',$eventID) != 0 and $cur_round > 1){
                                    $this->sql->query = "UPDATE `round_submissions` SET submissionLink = '$link' where participantID = '$username' and eventID = '$eventID'";
                                    $this->sql->process();
                                }
                                else{
                                    $result['status'] = -1;
                                    $result['msg'] = "Sorry, you didn't qualify for the next round!";
                                    return $result;
                                }
                            }
                            if($this->sql->countData('submissionlinks','username',$username,'eventID',$eventID) > 0)
                                $this->sql->query = "UPDATE `submissionlinks` SET submissionLink = '$link' where username = '$username' and eventID = '$eventID'";
                            else
                                $this->sql->query = "INSERT into `submissionlinks` (username,eventID,submissionLink,submittedBy) values ('$username','$eventID','$link','$username')";
                            $data = $this->sql->process();
                        }
                        else if($event['eventtype'] == 'team'){
                            $teamID = $this->getTeamIDByUsernameEventID($username,$eventID);
                            if($event['rounds'] == 1){
                                $cur_round = $event['current_round'];
                                if($cur_round == NULL){
                                    $cur_round = 1;
                                    if($this->sql->countData('round_submissions','participantID',$teamID,'eventID',$eventID) == 0)
                                        $this->sql->query = "INSERT into `round_submissions` (participantID,qualify,eventID,submissionLink) values ('$teamID','0','$eventID','$link')";
                                    else
                                        $this->sql->query = "UPDATE `round_submissions` SET submissionLink = '$link' where participantID = '$teamID' and eventID = '$eventID'";
                                    $this->sql->process();
                                }
                                else if($this->sql->countData('round_submissions','participantID',$teamID,'eventID',$eventID) != 0 and $cur_round > 1){
                                    $this->sql->query = "UPDATE `round_submissions` SET submissionLink = '$link' where participantID = '$teamID' and eventID = '$eventID'";
                                    $this->sql->process();
                                }
                                else{
                                    $result['status'] = -1;
                                    $result['msg'] = "Sorry, you didn't qualify for the next round!";
                                    return $result;
                                }
                            }
                            if($this->sql->countData('submissionlinks','username',$username,'eventID',$eventID) > 0)
                                $this->sql->query = "UPDATE `submissionlinks` SET submissionLink = '$link' where username = '$teamID' and eventID = '$eventID'";
                            else
                                $this->sql->query = "INSERT into `submissionlinks` (username,eventID,submissionLink,submittedBy) values ('$teamID','$eventID','$link','$username')";
                            $data = $this->sql->process();
                        }
                        else{
                            $result['status'] = -1;
                            $result['msg'] = "Something went wrong!";
                            return $result;
                        }
                        $result['status'] = 1;
                        $result['event_name'] = $event['$event_name'];
                        $result['msg'] = "Submitted Successfully!";
                        return $result;
                    }
                    $result['status'] = -1;
                    $result['msg'] = "Submissions for this event has not yet started!";
                    return $result;
                }
                $result['status'] = -1;
                $result['msg'] = "Event has ended or has been cancelled!";
                return $result;
            }
            $result['status'] = -1;
            $result['msg'] = "Your account is not yet verified!";
            return $result;
        }
        $result['status'] = -1;
        $result['msg'] = "Your account does not exists!";
        return $result;
    }
    public function getTeamIDByUsernameEventID($username,$eventID){
        $username = $this->sql->escape($username);
        $eventID = $this->sql->escape($eventID);
        return $this->sql->getData('teamID','team_members','username',$username,'eventID',$eventID,'request','yes');
    }
    public function getSubmissionLinkOfEventForUser($username,$eventID){
        $username = $this->sql->escape($username);
        $eventID = $this->sql->escape($eventID);
        $event = $this->getEventByEventID($eventID);
        if(count($event) == 0){
            return "";
        }
        if($this->checkIfUserValid($username)){
            $user = $this->getUser($username);
            if($user['verified'] == 1){
                if($this->sql->getData('status','events','eventID',$eventID) == '1'){
                    if($this->sql->getData('registrationStatus','events','eventID',$eventID) == '0'){
                        $data = "";
                        if($event['eventtype'] == 'individual')
                            if($this->sql->countData('submissionlinks','username',$username,'eventID',$eventID) > 0)
                                $data = $this->sql->getDataOnlyOne('submissionlinks','username',$username,'eventID',$eventID);
                            else
                                return "";
                        else if($event['eventtype'] == 'team'){
                            $teamID = $this->getTeamIDByUsernameEventID($username,$eventID);
                            if($this->sql->countData('submissionlinks','username',$teamID,'eventID',$eventID) > 0)
                                $data = $this->sql->getDataOnlyOne('submissionlinks','username',$teamID,'eventID',$eventID);
                            else
                                return "";
                        }
                        else
                            return "";
                        return $data;
                    }return "";
                }return "";
            }return "";
        }return "";
    }
    public function processTimestamp($time){
        $time = strtotime($time);
        return date("M d, H:i",$time);
    }
    public function getSubmissionLinksForCoordinatorsByEventID($username,$eventID){
        $username = $this->sql->escape($username);
        $eventID = $this->sql->escape($eventID);
        if($this->checkIfUserValid($username)){
            $user = $this->getUser($username);
            if($user['usertype'] == "coordinator"){
                $result['data'] = $this->sql->getDatas('submissionlinks','eventID',$eventID);
                $result['status'] = 1;
                $result['msg'] = "Here you go, Mr. Coordinator";
                return $result;
            }
            $result['data'] = [];
            $result['status'] = -1;
            $result['msg'] = "Sorry you're not authorized Mr. Unknown";
            return $result;
        }
        $result['data'] = [];
        $result['status'] = -1;
        $result['msg'] = "Who are you again??";
        return $result;
    }
    public function addRound($username,$duration){
        $username = $this->sql->escape($username);
        $event = $this->getEventOfCoordinator($username);
        $duration = $this->sql->escape($duration);
        if(!is_numeric($duration)){ //print type error
            $result['status'] = false;
            $result['msg'] = "Wrong Type, Only Enter the Value i.e. 2.5";
            return $result;
        }
        $duration = floatval($duration);
        if($duration <= 0 or $duration > 7){
            $result['status'] = false;
            $result['msg'] = "Duration is off limit!";
            return $result;
        }
        if(isset($event['eventID'])){
            $eventID = $event['eventID'];
            $roundIndex = $this->getTotalRoundsForEvent($eventID) + 1;
            $this->sql->query = "INSERT INTO `event_round_settings` values ('$eventID','$roundIndex','$duration')";
            $this->sql->process();
            $result['status'] = true;
            $result['msg'] = "Successfully Added";
            $result['data'] = $this->getUpdatedRoundListForEvent($eventID);
            return $result;
        }
        $result['status'] = false;
        $result['msg'] = "You're probably not a coordinator!";
        return $result;
    }
    public function getUpdatedRoundListForEvent($eventID){
        $eventID = $this->sql->escape($eventID);
        $rounds = $this->sql->getDistinctDatas('round_timings','event_round_settings','eventID',$eventID);
        return $rounds;
    }
    public function getTotalRoundsForEvent($eventID){
        $eventID = $this->sql->escape($eventID);
        return $this->sql->countData('event_round_settings','eventID',$eventID);
    }
    public function removeRound($username){
        $username = $this->sql->escape($username);
        $event = $this->getEventOfCoordinator($username);
        if(isset($event['eventID'])){
            $eventID = $event['eventID'];
            $roundIndex = $this->getTotalRoundsForEvent($eventID);
            $this->sql->query = "DELETE FROM `event_round_settings` where eventID = '$eventID' and round_index = '$roundIndex'";
            $this->sql->process();
            $result['status'] = true;
            $result['msg'] = "Successfully Removed";
            $result['data'] = $this->getUpdatedRoundListForEvent($eventID);
            return $result;
        }
        $result['status'] = false;
        $result['msg'] = "You're probably not a coordinator!";
        return $result;
    }
    public function getSubmissionLinksOfRounds($eventID){
        $eventID = $this->sql->escape($eventID);
        $this->sql->query = "SELECT * from `round_submissions` WHERE eventID = '$eventID' and submissionLink != 'NULL'";
        $result = $this->sql->process();
        $rows = [];
        while($row = mysqli_fetch_assoc($result)){
            array_push($rows,$row);
        }
        return $rows;
    }
    public function toggleQualify($username,$participantID){
        $username = $this->sql->escape($username);
        $event = $this->getEventOfCoordinator($username);
        $participantID = $this->sql->escape($participantID);
        if(isset($event['eventID'])){
            $eventID = $event['eventID'];
            if($this->sql->countData('round_submissions','participantID',$participantID,'eventID',$eventID,'qualify',0) == 1){
                $this->sql->query = "UPDATE `round_submissions` SET qualify = '1' where participantID = '$participantID' and eventID = '$eventID'";
                $this->sql->process();
                $result['status'] = true;
                $result['msg'] = "Successfully Qualified";
                return $result;
            }
            else{
                $this->sql->query = "UPDATE `round_submissions` SET qualify = '0' where participantID = '$participantID' and eventID = '$eventID'";
                $this->sql->process();
                $result['status'] = true;
                $result['msg'] = "Successfully Disqualified";
                return $result;
            }
        }
        $result['status'] = false;
        $result['msg'] = "You're probably not a coordinator!";
        return $result;
    }
    public function timeLeftForNextRound($eventID){
        $eventID = $this->sql->escape($eventID);
        $event = $this->getEventByEventID($eventID);
        if(!isset($event['eventID'])){
            return "Event Does Not Exists!";
        }
        if($event['status'] == 0){ return "Wait for the results to be declared!"; }
        $cur_round = $event['current_round'];
        if($cur_round == NULL){ $this->updateRounds(); $cur_round = 1;}
        $total_rounds = $this->getTotalRoundsForEvent($event['eventID']);
        if($total_rounds < $cur_round){ return false; }
        $fromTime = $this->sql->getData('timestamp','event_commence_time','eventID',$eventID);
        $time = 0;
        for($a=1;$a<=$cur_round;$a++){
            $time += $this->sql->getData('round_timings','event_round_settings','round_index',$cur_round,'eventID',$eventID);
        }
        $days = (int)$time;
        $hours = (int)((24*$time)-(24*$days));
        $query = "+".$days." days ".$hours." hours";
        $time = strtotime($query,strtotime($fromTime));
        $currentTime = strtotime("now");
        $difference = $time - $currentTime;
        if($difference < 0){ $this->updateRounds(); return false;}
        return date("d",$difference)." days ".date("h",$difference)." hours ".date("m",$difference)." minutes left!";
    }
    public function isQualified($username,$eventID){
        $username = $this->sql->escape($username);
        $eventID = $this->sql->escape($eventID);
        if($this->sql->countData('round_submissions','participantID',$username,'eventID',$eventID) == 1)
            return true;
        return false;
    }
    public function timeLeftCheckForEvent($eventID){
        $eventID = $this->sql->escape($eventID);
        $event = $this->getEventByEventID($eventID);
        if(!isset($event['eventID'])){
            return true;
        }
        $cur_round = $event['current_round'];
        if($cur_round == NULL){ $cur_round = 1;}
        $total_rounds = $this->getTotalRoundsForEvent($event['eventID']);
        if($total_rounds < $cur_round){ return false; }
        $fromTime = $this->sql->getData('timestamp','event_commence_time','eventID',$eventID);
        $time = 0;
        for($a=1;$a<=$cur_round;$a++){
            $time += $this->sql->getData('round_timings','event_round_settings','round_index',$cur_round,'eventID',$eventID);
        }
        $days = (int)$time;
        $hours = (int)((24*$time)-(24*$days));
        $query = "+".$days." days ".$hours." hours";
        $time = strtotime($query,strtotime($fromTime));
        $currentTime = strtotime("now");
        $difference = $time - $currentTime;
        if($difference < 0){ return false;}
        return true;
    }
    public function qualifiedParticipants($eventID){
        $event = $this->sql->escape($eventID);
        return $this->sql->getDatas('round_submissions','eventID',$event);
    }
    public function updateRounds(){
        $events = $this->getAllEvents();
        foreach($events as $event){
            $eventID = $event['eventID'];
            if($event['rounds'] == 1 and $event['status'] == 1){
                $cur_round = $event['current_round'];
                if($cur_round == NULL){$cur_round = 1;}
                $total_rounds = $this->getTotalRoundsForEvent($eventID);
                if(!$this->timeLeftCheckForEvent($eventID)){
                    //increase current round and check total rounds
                    if($cur_round < $total_rounds){
                        $cur_round = $cur_round + 1;
                        $this->sql->query = "UPDATE `events` SET current_round = '$cur_round' where eventID = '$eventID'";
                        $this->sql->process();
                        //remove unqualified participants
                        if($this->sql->countData('round_submissions','qualify',1,'eventID',$eventID) > 0){
                            $this->sql->query = "DELETE FROM `round_submissions` where eventID = '$eventID' and qualify = '0'";
                            $this->sql->process();
                        }
                        $this->sql->query = "UPDATE `round_submissions` SET submissionLink = 'NULL' where eventID = '$eventID'";
                        $this->sql->process();
                    }
                    else{   //end Event if rounds have ended
                        $this->sql->query = "UPDATE `events` SET status = '0' where eventID = '$eventID'";
                        $this->sql->process();
                    }
                }
            }
        }
    }
}
?>