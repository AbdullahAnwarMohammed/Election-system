<header class="header">
<div class="toggle-btns">
<a id="toggle-sidebar" href="#">
<i class="icon-list"></i>
</a>
<a id="pin-sidebar" href="#">
<i class="icon-list"></i>
</a>
</div>
<div class="header-items">


<!-- Header actions start -->
<ul class="header-actions">
<!-- Start --->

<?php 
if($_SESSION['rankSuperVisor'] == 2){
?>
<li class="dropdown">
<a href="#" id="notifications" data-toggle="dropdown" aria-haspopup="true">
<i class="icon-box"></i>
</a>
<div class="dropdown-menu dropdown-menu-right lrg" aria-labelledby="notifications">
<?php 


$att = "
SELECT * FROM voters INNER JOIN vote WHERE
vote.idSupervisor = ?
AND 
vote.level = ?
AND 
vote.idUser = voters.id
";
$get = $db->db->prepare($att);
$get->execute([$_SESSION['idSuperVisor'],'2']);
$fetchAll = $get->fetchAll();
$temp = array_unique(array_column($fetchAll, 'username'));
$fetchAllAttend = array_intersect_key($fetchAll, $temp);
$AttendMale = 0;
$AttendFemale = 0;
foreach($fetchAllAttend as $row)
{	
if($row['gender'] == 1)
{
$AttendMale++;
}else{
$AttendFemale++;
}
}

$mad = "
SELECT * FROM voters INNER JOIN vote WHERE
vote.idSupervisor = ?
AND 
(vote.level = ?
OR 
vote.level = ?)
AND 
vote.idUser = voters.id
";
$Madmen = $db->db->prepare($mad);
$Madmen->execute([$_SESSION['idSuperVisor'],'1','2']);
$fetchAll = $Madmen->fetchAll();
$temp = array_unique(array_column($fetchAll, 'username'));
$fetchAllMadmen = array_intersect_key($fetchAll, $temp);
$MadmenMale = 0;
$MadmenFemale = 0;
foreach($fetchAllMadmen as $row)
{	
if($row['gender'] == 1)
{
$MadmenMale++;
}else{
$MadmenFemale++;
}
}
if(count($fetchAllMadmen) != 0)
{
	
	$PerchMale = $AttendMale * 100 / count($fetchAllMadmen);
	$PerchFemale = $AttendFemale * 100 / count($fetchAllMadmen);
	$all = count($fetchAllAttend) * 100 / count($fetchAllMadmen);
}else{
	$PerchMale = 0;
	$PerchFemale = 0;
	$all = 0;
}



?>
			
			
	<div class="dropdown-menu-header">
نسب الحضور
</div>	
<ul class="header-tasks">
<li>
	<p>الكل<span><?= round($all) ?>%</span></p>
	<div class="progress">
		<div class="progress-bar bg-primary" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: <?=round($all)?>%">
			<span class="sr-only">90% Complete (success)</span>
		</div>
	</div>
</li>
<li>
	<p>ذكور<span><?=round($PerchMale)?>%</span></p>
	<div class="progress">
		<div class="progress-bar bg-info" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?=round($PerchMale)?>%">
			<span class="sr-only">60% Complete (success)</span>
		</div>
	</div>
</li>
<li>
	<p>اناث<span><?=round($PerchFemale)?>%</span></p>
	<div class="progress">
		<div class="progress-bar bg-secondary" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?=round($PerchFemale)?>%">
			<span class="sr-only">40% Complete (success)</span>
		</div>
	</div>
</li>
</ul>
</div>
</li>

<?php 
}
?>
<!-- END -->

<li class="dropdown">
<a href="#" id="userSettings" class="user-settings" data-toggle="dropdown" aria-haspopup="true">
<span class="user-name"><?=$_SESSION['superVisor']?></span>
<span class="avatar">=<span class="status busy"></span></span>
</a>
<div class="dropdown-menu dropdown-menu-right" aria-labelledby="userSettings">
<div class="header-profile-actions">
<div class="header-user-profile">
	<!-- <div class="header-user">
		<img src="img/user.png" alt="Admin Template">
	</div> -->

	<h5><?=$_SESSION['superVisor']?></h5>
	<p><?=rank()?></p>
</div>
<a href="changepassword.php"><i class="icon-user1"></i> تغير كلمة المرور</a>
<a href="logout.php"><i class="icon-log-out1"></i> تسجيل خروج</a>
</div>
</div>
</li>
</ul>						
<!-- Header actions end -->
</div>
</header>