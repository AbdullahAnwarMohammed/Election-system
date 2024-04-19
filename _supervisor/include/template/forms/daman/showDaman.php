<?php 

$infoDaman = $Daman->getSingleInfo('id',$_GET['id']);
$vote = $Vote->getAll('idParent',$_GET['id'],'yes');
$countVote =  $vote ? count($vote) : '0';
?>
<div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?=$infoDaman['username']?></h1>
    <a href="?master=<?=$_SESSION['idSuperVisor']?>" class="btn btn-md btn-dark shadow-sm"><i
            class="fas fa-chevron-left"></i></a>
</div>

<div class="row text-white text-center">
    <div class="col-12 mb-4">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title  fw-bold">عدد الاصوات</h5>
                <p class="h5 text-info  fw-bold card-text"><?=$countVote?></p>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title  fw-bold">الاسم</h5>
                <p class="h5 text-info  fw-bold card-text"><?=$infoDaman['username']?></p>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title  fw-bold">البريد</h5>
                <p class="h5 text-info text-success  fw-bold card-text"><?=$infoDaman['email']?></p>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4  mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-bold">الهاتف</h5>
                <p class="h5  text-info text-success  fw-bold card-text"><?=$infoDaman['phone']?></p>
            </div>
        </div>
    </div>
</div>