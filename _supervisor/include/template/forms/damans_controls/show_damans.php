<?php 

$damanInfo = $Daman->getSingleInfo('id',$_GET['id']);
$superInfo = $Supervisor->getSingleInfo('id',$damanInfo['idMusharif']);

?>
<div class="d-flex align-items-center justify-content-between mb-4">
      
    <h1 class="h3 mb-0 text-gray-800">(تعديل) الاحداث</h1>
    <a href="show_damans.php?master=<?=$_GET['master']?>" class="btn btn-md btn-dark "><i class="fas fa-chevron-circle-left"></i></a>

</div>

<div class="row">
    <div class="col-md-6 col-lg-4 col-12 mb-4 text-center">
        <div class="card bg-info text-white ">
            <div class="card-body">

                <h4> الاسم <i class="fas fa-id-card-alt"></i></h4>
                <span class="h6">
                    <?=$damanInfo['username']?>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4 col-12 mb-4 text-center">
        <div class="card bg-info text-white ">
            <div class="card-body">

                <h4> البريد <i class="fas fa-envelope-open"></i></h4>
                <span class="h6">
                    <?=$damanInfo['email']?>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4 col-12 mb-4 text-center">
        <div class="card bg-info text-white ">
            <div class="card-body">

                <h4> الهاتف <i class="fas fa-phone"></i></h4>
                <span class="h6">
                    <?=$damanInfo['phone']?>
                </span>
            </div>
        </div>
    </div>
    <table class="table">
        <tr>
            <th>المُتعاهد : </th> 
            <td><?=$superInfo['username']?></td>
        </tr>
        <tr>
            <th>الاصوات : </th> 
            <td><?= !empty($Vote->getAll('idParent',$_GET['id'],'yes')) ?   count($Vote->getAll('idParent',$_GET['id'],'yes')) : '0'?></td>
        </tr>
      
    </table>
</div>