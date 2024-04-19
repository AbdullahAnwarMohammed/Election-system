<?php 
ob_start();
if(!isset($_SESSION))
{
    session_start(); 
}

require_once("include/classes/DB.php");
require_once("include/classes/Upload.php");
// require_once("include/classes/Component.php");
require_once("include/classes/Login.php");


$db = new DB();

// $Events = new Component('events');;
// $Voters = new Component('voters');
// $Daman = new Component('daman');
// $Supervisor = new Component('supervisor');
// $frontend = new Component('frontend');
// $candidate = new Component('infocandidate');
// $musharifin_candidate = new Component('musharifin_candidate');
// $powers = new Component('powers');
// $passwords = new Component('passwords');


// $Vote = new Component('vote');
// $List = new Component('list');
// $listcontent = new Component('listcontent');
// $showVoters = new Component('show_voters');
// $powermandob = new Component('powermandob');
// $relationship_mandob = new Component('relationship_mandob');


?>