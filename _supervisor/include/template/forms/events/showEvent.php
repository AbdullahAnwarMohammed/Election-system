<?php 
$singleEvent = $db->getSingleInfo('events','id',$_GET['id']);
$getCandidate = $db->getAll('infocandidate','idEvent',$_GET['id'],'yes');


if($getCandidate){
foreach($getCandidate as $single)
{
    $musharifinCandidate = $musharifin_candidate->getAll('idCandidate',$single['idCandidate'],'yes');
}
}
?>
<div class="d-flex align-items-center justify-content-between mb-4">
<h1 class="h3 mb-0 text-gray-800"><?=$singleEvent['name']?></h1>

<a href="events.php">
      <img src="assets/img/back.png" />
  </a>
</div>

<div class="row text-center">
<div class="col-md-6 col-12 mb-4 text-center">
    
        <div class="card bg-success text-white shadow">
            <div class="card-body">

                <h4> الناخبين <i class="fas fa-user-check"></i></h4>
                <span class="h6">
                    <?=$singleEvent['numberVoters']?>
                </span>
            </div>
        </div>

    </div>
    
<div class="col-md-6 col-12 mb-4 text-center">
    <div class="row">
    <div class="col-6">
         <div class="card bg-secondary text-white shadow">
            <div class="card-body">
                <h4>المُتعهدون <i class="fas fa-handshake"></i></h4>
                <span class="h6">
                    <?=empty($getCandidate) ? '0': count($getCandidate)?>
                </span>
            </div>
        </div>
        </div>
        
        <div class="col-6">
         <div class="card bg-primary text-white shadow">
            <div class="card-body">
                <h4>المرشحين <i class="fas fa-user-check"></i></h4>
                <span class="h6">
                    <?=empty($getCandidate) ? '0': count($getCandidate)?>
                </span>
            </div>
        </div>
        </div>
        
    </div>
    

    </div>

    <div class="col-lg-6 mb-4 text-center">
        <div class="card bg-dark text-white shadow">
            <div class="card-body">
                <h4>تاريخ البدء <i class="fas fa-hourglass-end"></i></h4>
                <span class="h6">
                    <?=empty($singleEvent['startingDate']) ? 'لا يوجد': $singleEvent['startingDate']?>
                </span>
            </div>
        </div>

    </div>
    
<div class="col-lg-6 mb-4 text-center">
        <div class="card bg-dark text-white shadow">
            <div class="card-body">
                <h4>تاريخ الانتهاء <i class="fas fa-hourglass"></i></h4>
                <span class="h6">
                    <?=empty($singleEvent['startingDate']) ? 'لا يوجد': $singleEvent['startingDate']?>
                </span>
            </div>
        </div>

    </div>
   
    
 

</div>