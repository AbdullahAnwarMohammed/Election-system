$(function(){
    //Add New Event 

 $('#import_excel_form').on('submit', function(event){
  event.preventDefault()



    $.ajax({
        url:"ajax.php",
        method:"POST",
        data:new FormData(this),
        dataType:"json",
        contentType:false,
        cache:false,
        processData:false,
        beforeSend : function(){
          $("#import").attr("disabled", true);
          $(".waiting").css("display","flex");
        },
        
        success:function(data)
        {
          $("#import").attr("disabled", false);
          $(".waiting").css("display","none");
          $("#import_excel_form")[0].reset();
          Swal.fire(data.message)

        }
      })



      
    })


    $('#update_event').on('submit', function(event){
      event.preventDefault();
     
       $.ajax({
          url:"ajax.php",
          method:"POST",
          data:new FormData(this),
          contentType:false,
          cache:false,
          processData:false,
          beforeSend:function(){
            $('#update').attr('disabled', 'disabled');
            $(".waiting").css("display","flex");

          },
          success:function(data)
          { 
            
            data = jQuery.trim(data);
            if(data == '2database')
            {
              $('#update').attr('disabled', false);
              $(".waiting").css("display","none");
              Swal.fire(
                'خطأ',
                'من فضلك ارفع قاعدة بيانات واحده',
                'error'
              )
              $("#update_event")[0].reset();

            }else{
            $('#update').attr('disabled', false);
            $(".waiting").css("display","none");
             location.reload()
            }
          }
        })
      
      })




      // معرفة الضامنين 
      $(document).on("click",".Smadmen",function(){
        let idUser = $(this).data("id");
        $.ajax({
          url : "ajax_backend.php",
          method : "POST",
          data:{
              action:'showMadmen',
              idUser : idUser
          },
          beforeSend:function () { 
            $("#getdata").empty();
           },
          success:function(data){
              $("#getdata").html(data);
          }
        })
      })


      // اختيار حسب اللجنة

      

      function getChangeValue(val,action,className,attend)
      {
       
          $.ajax({
            url : "ajax_backend.php",
            method : "POST",
            data:{
                action:action,
                val : val,
                attend:attend
            },
            beforeSend:function () { 
              $(className).empty();
             },
            success:function(data){
              $(className).html(data);
            }
          })
      

      }

      $('.selectByljna').on("change",()=>{
        let val = $('.selectByljna').find(":selected").val();
        getChangeValue(val,'selectByljna','.madmenLjna','');
      })
      
      $('.selectByljnaAttend').on("change",()=>{
        let attendOrNot =  $(".appAttend a.active").data("action");
        let val = $('.selectByljnaAttend').find(":selected").val();
        getChangeValue(val,'selectByljnaAttend','.madmenLjnaAttend',attendOrNot);
      })
    


})