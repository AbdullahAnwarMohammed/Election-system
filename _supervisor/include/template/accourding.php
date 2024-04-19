<?php


// المتعاهدون 
$mota3down = $db->db->prepare("
SELECT * FROM musharifin_candidate WHERE idCandidate = ?
");
$mota3down->execute([$_SESSION['idSuperVisor']]);
$countMota3down = $mota3down->rowCount();
$datas = [];
$datas2 = [];

foreach ($mota3down->fetchAll() as $row) {
    $mota3downSuperVisor = $db->db->prepare("
   SELECT * FROM supervisor WHERE id = ?
   ");;
    $mota3downSuperVisor->execute([$row['idMusharifin']]);
    $datas[] = $mota3downSuperVisor->fetch();

    // الضامنين
    $damans = $db->db->prepare("
    SELECT * FROM supervisor INNER JOIN daman ON daman.idMusharif = ?
    ");
    $damans->execute([$row['idMusharifin']]);

    $datas2[] = $damans->fetch();
}
$datas2 = array_filter($datas2);



// الضامنين 
$madmen = $db->db->prepare("
SELECT * FROM daman  WHERE idSuperviosr = ? 
");
$madmen->execute([$_SESSION['idSuperVisor']]);
$fetchMadmen = $madmen->fetchAll();
$countMadmen = $madmen->rowCount();



// المضامين 
$stmt = $db->db->prepare("SELECT * FROM vote INNER JOIN voters  WHERE vote.idUser = voters.id AND  idSupervisor = ?");
$stmt->execute([$_SESSION['idSuperVisor']]);
$fetchlAll = $stmt->fetchAll();
$temp = array_unique(array_column($fetchlAll, 'username'));
$elMadmen = array_intersect_key($fetchlAll, $temp);



// الحضور 
$al7dour = $db->db->prepare("
SELECT  * FROM voters INNER JOIN vote ON
 voters.id = vote.idUser AND 
 vote.idSupervisor = ?  AND voters.attend = '1'
");
$al7dour->execute([$_SESSION['idSuperVisor']]);
$fetchAl7dour = $al7dour->fetchAll();
 $countAl7dour = $al7dour->rowCount();

?>


<div class="row gutters">
    <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="accordion" id="defaultAccordion">
            <div class="accordion-container">
                <div class="accordion-header d-flex justify-content-between" id="defaultAccordionOne">
                    <a href="" class="" data-toggle="collapse" data-target="#defaultAccordionCollapseOne" aria-expanded="true" aria-controls="defaultAccordionCollapseOne">
                        المُتعهدون (<?= $countMota3down ?>)
                    </a>

                    <div class="buttons">
                    <button class="btn-print  btn-print-csv" data-id="printMota">CSV</button>
                    <button class="btn-print btn-print-pdf" data-id="printMota">PDF</button>
                    <button class="btn-print print" data-pagename="المُتعهدون" data-id="printMota">PRINT</button>
                    </div>


                </div>
                <div id="defaultAccordionCollapseOne" class="collapse " aria-labelledby="defaultAccordionOne" data-parent="#defaultAccordion" style="">
                    <div class="accordion-body">
                        <div>
                            <div class="float-end mb-1">

                                <a href="show_condidate.php?action=add&master=<?= $_SESSION['idSuperVisor'] ?>" class="btn btn-sm btn-success">اضافة</a>
                                <a href="show_condidate.php?master=<?= $_SESSION['idSuperVisor'] ?>" class="btn btn-sm btn-dark">مشاهدة</i></a>
                            </div>
                        </div>
                        <table class="table table-warning table-striped" id="printMota">
                            <tr>
                                <th>الاسم</th>
                                <th>البريد</th>
                            </tr>
                            <?php
                            foreach ($datas as $row) {
                            ?>
                                <tr>
                                    <td><a href="show_condidate.php?master=<?= $_SESSION['idSuperVisor'] ?>&action=show&id=<?= $row['id'] ?>" class="text-primary"><?= $row['username'] ?></a></td>
                                    <td><?= $row['email'] ?></td>
                                </tr>
                            <?php
                            }
                            ?>

                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="accordion" id="defaultAccordion">
            <div class="accordion-container">
                <div class="accordion-header  d-flex justify-content-between" id="defaultAccordionOne">
                    <a href="" class="" data-toggle="collapse" data-target="#defaultAccordionCollapseTwo" aria-expanded="true" aria-controls="defaultAccordionCollapseOne">
                        الضامنين  (<?= !empty($fetchMadmen) ? $countMadmen : '0' ?>)
                    </a>
                    <div class="buttons">
                    <button class="btn-print  btn-print-csv" data-id="printDamnen">CSV</button>
                    <button class="btn-print btn-print-pdf" data-id="printDamnen">PDF</button>
                    <button class="btn-print print" data-pagename="الضمامين" data-id="printDamnen">PRINT</button>
                    </div>
                </div>
                <div id="defaultAccordionCollapseTwo" class="collapse " aria-labelledby="defaultAccordionOne" data-parent="#defaultAccordion" style="">
                    <div class="accordion-body">
                        <div>
                            <div class="float-end mb-1">
                                <a href="show_damans.php?action=add&master=<?= $_SESSION['idSuperVisor'] ?>" class="btn btn-sm btn-success">اضافة</a>
                                <a href="show_damans.php?master=<?= $_SESSION['idSuperVisor'] ?>" class="btn btn-sm btn-dark">
                                    مشاهدة
                                </a>
                            </div>
                            <table class="table table-success table-striped " id="printDamnen">
                                <tr>
                                    <th>المرشح</th>
                                    <th>البريد</th>
                                    <th>بواسطة</th>
                                </tr>
                                <?php
                                foreach ($fetchMadmen as $row) {
                                ?>
                                    <tr>
                                        <td><a href="show_damans.php?action=show&master=<?= $_SESSION['idSuperVisor'] ?>&id=<?= $row['id'] ?>" class="text-primary"><?= $row['username'] ?></a></td>
                                        <td><?= $row['phone'] ?></td>
                                        <td>
                                    <!-- $Supervisor->getSingleInfo('id', $row['idMusharif'])['username']  -->
                                        <?php 
                                        if($row['idMusharif'] == NULL)
                                        {
                                            echo 'تابع لك';
                                        }else{
                                            $q = "SELECT username FROM supervisor WHERE id = ?";
                                            $stmt = $db->db->prepare($q);
                                            $stmt->execute([$row['idMusharif']]);
                                            echo $stmt->fetch()['username'];
                                        }
                                        ?>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>

                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="accordion" id="defaultAccordion">
            <div class="accordion-container">
                <div class="accordion-header d-flex justify-content-between" id="defaultAccordion">
                    <a href="" class="" data-toggle="collapse" data-target="#defaultAccordionThree" aria-expanded="true" aria-controls="defaultAccordionCollapseOne">
                        المضامين (<?=count($elMadmen)?>)
                    </a>

                    <div class="buttons">
                        <button class="btn-print  btn-print-csv" data-id="printMadmen">CSV</button>
                        <button class="btn-print btn-print-pdf" data-id="printMadmen">PDF</button>
                        <button class="btn-print print" data-pagename="المضامين الخاصة صفحة التحكم" data-id="printMadmen">PRINT</button>
                    </div>


                    
                </div>
                <div id="defaultAccordionThree" class="collapse " aria-labelledby="defaultAccordionOne" data-parent="#defaultAccordion" style="">
                    <div class="accordion-body">
                    <table class="table" id="printMadmen">
                            <tr>
                            <th>#</th>
                            <th>الاسم</th>
                             </tr>
                        <?php 
                        $i = 0;
                        foreach($elMadmen as $row)
                        {
                            $i++;
                            $changeColor = $row['gender'] == 1 ? "#062bb1" : "#c51334";

                           ?>
                         <tr>
                         <td><?=$i?></td>
                         <td class="fw-bold" style="color:<?=$changeColor?>"><?=$row['username']?></td>
                         </tr>
                            
                           <?php 
                        }
                        ?>
                           </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="accordion" id="defaultAccordion">
            <div class="accordion-container">
                <div class="accordion-header d-flex justify-content-between" id="defaultAccordion">
                    <a href="" class="" data-toggle="collapse" data-target="#defaultAccordionFour" aria-expanded="true" aria-controls="defaultAccordionCollapseOne">
                        الحضور (<?=$countAl7dour?>)
                    </a>
                    
                <div class="buttons">
                    <button class="btn-print  btn-print-csv" data-id="printAttend">CSV</button>
                    <button class="btn-print btn-print-pdf" data-id="printAttend">PDF</button>
                    <button class="btn-print print" data-pagename="الضمامين الخاصة صفحة التحكم" data-id="printAttend">PRINT</button>
                </div>


                </div>
                <div id="defaultAccordionFour" class="collapse " aria-labelledby="defaultAccordionOne" data-parent="#defaultAccordion" style="">
                    <div class="accordion-body">
                    <table class="table" id="printAttend">
                            <tr>
                            <th>#</th>
                            <th>الاسم</th>
                             </tr>
                        <?php 
                                                $i = 0;

                        foreach($fetchAl7dour as $row)
                        {
                            $i++;

                            $changeColor = $row['gender'] == 1 ? "#062bb1" : "#c51334";

                           ?>
                           <tr>
                           <td><?=$i?></td>
                           <td  class="fw-bold" style="color:<?=$changeColor?>"><?=$row['username']?></td>
                           </tr>
                           <?php
                        }
                        ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





<!-- اظهار المضامين -->
<div class="modal fade" id="madmen" tabindex="-1" aria-labelledby="madmen" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body" id="getdata" style="padding: 2px;">

            </div>
            <div class="modal-footer" style="padding:2px">
                <button type="button" class="btn btn-sm btn-danger text-end" data-bs-dismiss="modal">اغلاق</button>
            </div>
        </div>
    </div>
</div>