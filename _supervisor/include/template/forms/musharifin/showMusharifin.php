<?php 
$id =  intval($_GET['id']);
$dataSuper = $Supervisor->getSingleInfo('id',$id);

$dataCan = $musharifin_candidate->getSingleInfo('idMusharifin',$id);
$dataCan =  $Supervisor->getSingleInfo('id',$dataCan['idCandidate']);
$datacandidate = $candidate->getSingleInfo('idCandidate',$dataCan['id']);

$countDamans = $Daman->getAll('idMusharif',$id,'yes');
?>
<div class="d-flex align-items-center justify-content-between mb-4">

<h1 class="h3 mb-0 text-gray-800"> المُتعهدون (عرض)</h1>

    <a href="musharifin.php" class="btn btn-md btn-dark "><i class="fas fa-chevron-circle-left"></i></a>


</div>
<div class="row">
    <div class="col-md-6 col-lg-4 col-12 mb-4 text-center">
        <div class="card bg-info text-white ">
            <div class="card-body">

                <h4> الاسم <i class="fas fa-id-card-alt"></i></h4>
                <span class="h6">
                    <?=$dataSuper['username']?>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4  col-12 mb-4 text-center">
        <div class="card bg-info text-white ">
            <div class="card-body">

                <h4> البريد <i class="fas fa-envelope-square"></i></h4>
                <span class="h6">
                    <?=$dataSuper['email']?>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4 col-12 mb-4 text-center">
        <div class="card bg-info text-white ">
            <div class="card-body">
                <h4> تاريخ التسجيل <i class="fas fa-envelope-square"></i></h4>
                <span class="h6">
                    <?=$dataSuper['dateReg']?>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4 col-12 mb-4 text-center">
        <div class="card bg-dark text-white ">
            <div class="card-body">
                <h4> المرشح <i class="fas fa-envelope-square"></i></h4>
                <span class="h6">
                <?=$dataCan['username']?>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4 col-12 mb-4 text-center">
        <div class="card bg-dark text-white ">
            <div class="card-body">
                <h4> الحدث <i class="fas fa-envelope-square"></i></h4>
                <span class="h6">
                <?=$datacandidate['nameEvent']?>
                </span>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4 col-12 mb-4 text-center">
        <div class="card bg-dark text-white ">
            <div class="card-body">
                <h4> الضامنين <i class="fas fa-envelope-square"></i></h4>
                <span class="h6">
                <?=empty($countDamans) ? '0' :     count($countDamans)?>
                </span>
            </div>
        </div>
    </div>

    
  
    
</div>