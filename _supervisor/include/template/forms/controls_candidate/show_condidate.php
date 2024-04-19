<?php 
if(!$_SESSION['superVisor'])
{
    header("location:login.php");
}
if($_SESSION['idSuperVisor'] != $_GET['master'] OR empty($_GET['id']) OR !is_numeric($_GET['id']))
{
    header("location:index.php");
}

$dataSuper = $Supervisor->getSingleInfo('id',$_GET['id']);

$dataDamans = $Daman->getAll('idMusharif',$_GET['id'],'yes');
$countDamans = !empty($dataDamans) ? count($dataDamans) : '0';

$countVotesDaman = 0;
if($dataDamans){
foreach($dataDamans as $row)
{
    
    if($Vote->getAll('idParent',$row['id'],true)){
        foreach($Vote->getAll('idParent',$row['id'],true) as $x)
        {
           $countVotesDaman += 1;
        }
    }
    
}
}

$votecondidate = $Vote->getAll('idParent',$_GET['id'],true);
$votecondidate = !empty($votecondidate) ? count($votecondidate) : '0';
?>

<div class="d-flex align-items-center justify-content-between mb-4">

    <h1 class="h3 mb-0 text-gray-800"><?=$dataSuper['username']?></h1>

    <a href="show_condidate.php?master=<?=$_SESSION['idSuperVisor']?>" class="btn btn-md btn-dark "><i class="fas fa-chevron-circle-left"></i></a>
</div>

<table class="table">
    <tr>
        <th>الاسم : </th>
        <td><?=$dataSuper['username']?></td>
    </tr>
    <tr>
        <th>البريد : </th>
        <td><?=$dataSuper['email']?></td>
    </tr>
    <tr>
        <th>التاريخ : </th>
        <td><?=$dataSuper['dateReg']?></td>
    </tr>
    <tr>
        <th> الضامنين : </th>
        <td><?=$countDamans?></td>
    </tr>
    <tr>
        <th> اصوات التابعه للمُعاهد : </th>
        <td><?=$votecondidate?></td>
    </tr>
    <tr>
        <th> اصوات التابعه للضامنين : </th>
        <td><?=$countVotesDaman?></td>
    </tr>

    <tr>
        <th> الاصوات لديك : </th>
        <td><?=$countVotesDaman + $votecondidate?></td>
    </tr>


</table>