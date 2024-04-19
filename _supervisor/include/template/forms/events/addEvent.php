


<div class="d-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"> الاحداث (اضافة)</h1>        

  <a href="index.php">
      <img src="assets/img/back.png" />
  </a>
</div>

<div class="col-lg-12">
   
       
       <div class="card">
            <div class="card-body">

              <form class="needs-validation"   id="import_excel_form" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                  <div class="col-sm-12  ">
                    <input type="hidden" name="addEvent" />
                    <input type="text" required  name="username" placeholder="اسم الحدث" class="form-control">
                 
                    <div class="invalid-feedback">
                    هذا الحقل اجباري
                    </div>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-sm-12">
                    <label for="">الصورة</label>
                    <input type="file"  accept="image/*" name="image"  class="form-control">
                  </div>
                </div>
             
                <div class="row mb-3">
                  <div class="col-sm-12">
                    <textarea name="desc" placeholder="وصف الحدث" class="form-control" id="" cols="30" rows="10"></textarea>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-sm-12">
                    <label for="">قاعدة البيانات</label>
                    <input type="file" accept=".csv"  required name="database"  class="form-control">
                    <div class="invalid-feedback">
                     هذا الحقل اجباري
                    </div>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-sm-12">
                    <label for="">قاعدة بيانات اللجان</label>
                    <input type="file" accept=".csv"   name="databaseAllijan"  class="form-control">

                  </div>
                </div>
              
             
                <div class="row mb-3">
                  <div class="col-sm-12">
                    <label for="">تايخ البدء</label>
                    <input type="date"   name="startingDate"  class="form-control">
                  </div>
                </div>


                <div class="row mb-3">
                  <div class="col-sm-12">
                    <label for="">تاريخ الانتهاء</label>
                    <input type="date"   name="expireDate"   class="form-control">
                  </div>
                </div>

                

                <div class="row mb-3">
                  <div class="col-sm-12">
                    <button type="submit"  id="import" name="add" class="btn btn-primary ">اضافة <i class="ri-file-add-line"></i></button>
                  </div>
                </div>

              </form><!-- End General Form Elements -->

            </div>
          </div>

        </div>
