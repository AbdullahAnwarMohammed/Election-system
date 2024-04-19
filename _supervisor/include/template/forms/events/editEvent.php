<?php 

    $supervisor = $db->getSingleInfo('events','id',$_GET['id']);

    ?>


<div class="d-flex align-items-center justify-content-between mb-4">
<h1 class="h3 mb-0 text-gray-800"> تعديل (<?=$supervisor['name']?>)</h1>
        
<a href="events.php">
      <img src="assets/img/back.png" />
  </a>
</div>
<h5 class="bg-dark text-white text-center py-4">
    قاعدة البيانات المرفوعة من قبل <?=$supervisor['nameFile']?>
</h5>

<div class="col-lg-12">

    <div class="card">
        <div class="card-body">

            <form id="update_event" method="POST"
                enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col-sm-12">
                    <input type="hidden" name="update_event">
                    <input type="hidden" name="id" value="<?=$_GET['id']?>">
        <input type="text" name="name" value="<?=$supervisor['name']?>" placeholder="الاسم"
                            class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-12">
                        <label for="">صور الحدث</label>
                        <input type="file" name="image" class="form-control">
                        <?php 
                    if($supervisor['image'])
                    {
                      ?>
                        <img class="imageEdit img-thumbnail my-4" src="<?=$supervisor['image']?>" width="200" alt="">
                        <?php
                    }
                    ?>
                    </div>
                </div>


                <div class="row mb-3">
                    <div class="col-sm-12">
                        <textarea name="desc" placeholder="وصف الحدث" class="form-control" id="" cols="30"
                            rows="10"><?=$supervisor['descElection']?></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6  text-white bg-success py-4">
                        <h5 class="my-2">قاعدة البيانات</h5>
                        <input type="file" name="database" class="form-control">
                    </div>
              
                    <div class="col-sm-6 text-white bg-info py-4">
                        <h5 class="my-2">رقع بيانات اضافية</h5>
                        <input type="file" name="databaseCon" class="form-control">
                    </div>

                
                </div>


                <div class="row my-4">
                    <div class="col-sm-6  border  py-4">
                        <h5 class="my-2"> قاعدة البيانات لجان جديدة</h5>
                        <input type="file" name="committees" class="form-control">
                    </div>
              
                    <div class="col-sm-6 border py-4">
                        <h5 class="my-2">رقع بيانات اضافية لقاعدة بيانات اللجان</h5>
                        <input type="file" name="addCommittees" class="form-control">
                    </div>

                
                </div>



                   

                <div class="row mb-3">
                    <div class="col-sm-12">
                        <label for="">تايخ البدء</label>
                        <input type="date" name="startingDate" value="<?=$supervisor['startingDate']?>" 
                            class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-12">
                        <label for="">تاريخ الانتهاء</label>
                        <input type="date" name="expireDate" value="<?=$supervisor['expireDate']?>" 
                            class="form-control">
                    </div>
                </div>






                <div class="row mb-3">
                    <div class="col-sm-12">
                        <button type="submit" name="update" id="update" class="btn btn-success">حفظ <i class="ri-check-double-line"></i></button>
                    </div>
                </div>

            </form><!-- End General Form Elements -->

        </div>
    </div>

</div>