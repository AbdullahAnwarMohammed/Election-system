

$(function(){


    function deleteVote(e,typeAction,page)
    {
        let idParent = $(".idParent").val();
        let idUser = $(e).data('iduser');
        $.ajax({
            url : "_supervisor/ajax_frontend.php",
            method : "POST",
            data:{
                action:'deleteVote',
                idParent:idParent,
                idUser : idUser,
            },
            success : function(data)
            {
                data = jQuery.trim(data);
                if(page === "name_only")
                {
                    getAjax('name_only');
                }else if(page === "aqarbNameOnly"){
                    let username = $(".usernameSA").val();
                    ajaxAqarb(username);
                }
                else if(page == "refreshopenlist")
                {
                    let id = $(e).data("id");
                    ajaxOpenlist(id)
                }
              
                else if(page == "mychose"){
                    getYourChose();
                }
                else if(page == 'details')
                {
                    details(select_value,$(e).data("search"))
                }
                else{
                    getAjax('name_family');
                }
                
            }
        })
    }



    function updateVote(e,typeAction,page)
    {
        let idParent = $(".idParent").val();
        let idSupervisor = $(".idSupervisor").val();
        let rank = $(".level").val();
        let idUser = $(e).data('iduser');
        $.ajax({
            url : "_supervisor/ajax_frontend.php",
            method : "POST",
            data:{
                action:'updateVoter',
                idParent:idParent,
                idUser : idUser,
                idSupervisor:idSupervisor,
                rank:rank
            },
            success : function(data)
            {

                data = jQuery.trim(data);

                if(data == 'done')
                {
                    if(page === "name_only")
                    {
                        getAjax('name_only');
                    }else if(page === "aqarbNameOnly"){
                        let username = $(".usernameSA").val();
                        ajaxAqarb(username);
                    }
                    else if(page == "refreshopenlist")
                    {
                        let id = $(e).data("id");
                        ajaxOpenlist(id)
                    }else if(page == "mychose"){
                        let changeChose = $("#changeChose").find(":selected").val();
                        let maleOrFemaleChose = $("#maleOrFemaleChose").find(":selected").val();
                        if(changeChose != 'empty')
                        {
                        getYourChose(changeChose,maleOrFemaleChose);
                        }else{
                        getYourChose();
                        }
                    }
                    else if(page == "mandub"){
                        mandubFunction();
                    }
                    else if(page == 'details')
                    {
                        details($(e).data("search"))
                    }
                    else{
                        getAjax('name_family');
                    }
                  
                }/*else{
                    Swal.fire({
                        position: 'top-center',
                        icon: 'error',
                        title: 'حدث خطأ',
                        showConfirmButton: false,
                        timer: 1500
                      })
                
                }*/
                
            }
        })
    }

    function getAjax(type = '')
    {   
        let input_value_search = $(".input_value_search").val();
        
        let family_value_search = $(".family_value_search").find(":selected").val();
        let idParent = $(".idParent").val();
        let idUser = $(".idUser").val();
        let idEvent = $(".idEvent").val();
        let idSupervisor = $(".idSupervisor").val();
        let rank = $(".level").val();
        $.ajax({        
            url : "_supervisor/ajax_frontend.php",
            method : "POST",
            data:{
                action:'load_data_search',
                input : input_value_search,
                selected : family_value_search,
                idParent: idParent,
                idEvent:idEvent,
                idSupervisor:idSupervisor,
                idUser:idUser,
                type : type,
                rank : rank
            },
            beforeSend:function(){
              $(".loading").css("display","flex");
            },
            success : function(data)
            {
                console.log(data);
                $(".loading").css("display","none");
                $(".load_data_search").html(data);  


                var table = $('#table_search').dataTable( {
                    "ordering": false,
                    "pageLength": 100,
                    "language": 
                    {
                        "sProcessing": "جارٍ التحميل...",
                        "sLengthMenu": " _MENU_ ",
                        "sZeroRecords": "لم يعثر على أية سجلات",
                        "sInfo": "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
                        "sInfoEmpty": "يعرض 0 إلى 0 من أصل 0 سجل",
                        "sInfoFiltered": "(منتقاة من مجموع _MAX_ مُدخل)",
                        "sInfoPostFix": "",
                        "sSearch": "ابحث:",
                        "sUrl": "",
                        "oPaginate": {
                            "sFirst": "الأول",
                            "sPrevious": "السابق",
                            "sNext": "التالي",
                            "sLast": "الأخير"
                        }
                    }
                    
                });
                

                let idUser = $(".idUser").val();
                let idSupervisor = $(".idSupervisor").val();

                if(idUser != idSupervisor)
                {
                    $(".dt-buttons").remove();
                }
            

            }
        })
    }


$(document).on("click",".button_search",function(){
    let input_value_search = $(".input_value_search").val();
    let family_value_search = $(".family_value_search").find(":selected").val();

    if(family_value_search == "none")
    {
        getAjax('name_only');
    }
    else{
    if(input_value_search.length > 0)
    {
        getAjax('name_family'); 
    }
    else 
    {
        
        getAjax('family_only'); 

    }
    
    }
})



$(document).on("click",".votemandub",function(){
let page = $(this).data("page");
    updateVote(this,'votemandub',page);
  
  
    if($("#showVoters").hasClass("d-none"))
    {
    var table = $('.getTableMadmen').DataTable();
    table.ajax.reload();
    const num2 = parseInt($(".paginate_button.current").data("dt-idx"));
    table.page(num2).draw(false);
    }else{
    var table = $('.myTable').DataTable();
    table.ajax.reload();
    const num = parseInt($(".paginate_button.current").data("dt-idx"));
    table.page(num).draw(false);
    } 
})

$(document).on("click",".vote",function(){
    let page = $(this).data("page");
    
    updateVote(this,'name_only',page);
  
  
    var table = $('.myTable').DataTable();
    table.ajax.reload();
    const num = parseInt($(".paginate_button.current").data("dt-idx"));
    table.page(num).draw(false);
})

$(document).on("click",".resetVote",function(){
    let page = $(this).data("page");
    
    deleteVote(this,"name_only",page);
    var table = $('.myTable').DataTable();
    table.ajax.reload();
    const num = parseInt($(".paginate_button.current").data("dt-idx"));
    table.page(num).draw(false);
})

function multiVote(selected,typeAction,e,page)
{
        let idFrontend =$(".idFrontend").val();
        let idSupervisor = $(".idSupervisor").val();
        let rank = $(".level").val();
        let names = new Array();
        let idUsers = new Array();
        let idParent = new Array();
        $(selected).each(function(i){
            names.push($(this).data("username"));
            idUsers.push($(this).data("userid"));
            idParent = $(this).data("idparent");
        })

    $.ajax({
        url : "_supervisor/ajax_frontend.php",
        method : "POST",
        data:{
            action:'multiVote',
            type : typeAction,
            names:names,
            idUsers:idUsers,
            idParent:idParent,
            idFrontend:idFrontend,
            idSupervisor:idSupervisor,
            rank : rank
        },
        beforeSend:function(){
            $(".loading").css("display","flex");
          },
        success : function(data)
        {
           data = jQuery.trim(data);
           console.log(data);

           $(".loading").css("display","none");
           $(".checkboxMulti ").prop('checked', false)

           if(data == 'done')
           {
            if(page === "name_only")
            {
                getAjax('name_only');
            }
            else if(page == "aqarbNameOnly"){

                let username = $(".usernameSA").val();
                ajaxAqarb(username);
            }else if(page == "mychose"){
                let changeChose = $("#changeChose").find(":selected").val();
                let maleOrFemaleChose = $("#maleOrFemaleChose").find(":selected").val();
                if(changeChose == "all")
                {
                    getYourChose();

                }else{
                    getYourChose(changeChose,maleOrFemaleChose);
                }
            }else if(page == "mandub"){
                
                let changeChose = $("#changeChosemandub").find(":selected").val();
                if(changeChose == "empty")
                {
                    mandubFunction();
                }else{
                    mandubFunction(changeChose);

                }

            }
            else if(page == "refreshopenlist"){
                let id = $(e).data('id');
                ajaxOpenlist(id);
            }
            else if(page == 'details')
            {
                details(select_value,$(e).data("name"))
            }
            else{
                getAjax('name_family');
            }
          
           }
            

        }
    })
}
$(document).on("click",".btnMultiVote",function(){

    let valueSelected = $('.checkboxMulti:checkbox:checked');
    let  insertMulti = $(".insertMulti").find(":selected").val();
    var  idList = $(".insertMulti").find(":selected").data("value");
    let page = $(this).data("page");
    let i = 0;
    if(valueSelected.length > 0)
    {

            if(insertMulti == "insert")
            {   

                multiVote(valueSelected,'insert',this,page)  
                var table = $('.myTable').DataTable();
                table.ajax.reload();
                const num = parseInt($(".paginate_button.current").data("dt-idx"));
                table.page(num).draw(false);
            }else if(insertMulti == "insertVote"){
                multiVote(valueSelected,'insertVote',this,page)  
                var table = $('.myTable').DataTable();
                table.ajax.reload();
                const num = parseInt($(".paginate_button.current").data("dt-idx"));
                table.page(num).draw(false);
            }
    
            else if(insertMulti == "insertlist"){
                    // اضافة للقائمة

                        var idParent = $("#userNow").val();
                        var idFrontend = $(".idFrontend").val();
                        var idSupervisor = $(".idSupervisor").val();
                        // var idList = $('.lists').find(":selected").val();;
                        var idUsers = new Array();

                        $(valueSelected).each(function(i){
                            idUsers.push($(this).data("userid"));
                        })



                        $.ajax({
                        url:"_supervisor/ajax_frontend.php",  
                        method:"POST",  
                        data:{
                            action:'insertSearchToList',
                            idUser : idUsers,
                            idParent : idParent,
                            idList:idList,
                            idFrontend:idFrontend,
                            idSupervisor:idSupervisor
                             },
                             beforeSend:function(){
                                $(".loading").css("display","flex");
                              },
                        success:function(data){ 
                            data = jQuery.trim(data);
                            $(".loading").css("display","none");
                            $(".checkboxMulti ").prop('checked', false)
                            if(data == 'done')
                            {
                                Swal.fire({
                                    title: "القائمة",
                                    text: "تم الاضافة بنجاح",
                                    icon: "success"
                                  });
                            }
                        }
                        })

                     
               
            }
            else if(insertMulti == "insert2")
            {

                multiVote(valueSelected,"insertLevel2",this,page);
                 var table = $('.myTable').DataTable();
                table.ajax.reload();
                 const num = parseInt($(".paginate_button.current").data("dt-idx"));
                 table.page(num).draw(false);
            }
          
            else if(insertMulti == "deleteFromList"){
                multiVote(valueSelected,"deleteFromList",this,page);
                var table = $('.myTable').DataTable();
                table.ajax.reload();
                const num = parseInt($(".paginate_button.current").data("dt-idx"));
                table.page(num).draw(false);
            }else if(insertMulti == "NotPresent"){

              multiVote(valueSelected,"NotPresent",this,page);
                var table = $('.myTable').DataTable();
              table.ajax.reload();
                 const num = parseInt($(".paginate_button.current").data("dt-idx"));
                table.page(num).draw(false);
            }
            else 
            {
                multiVote(valueSelected,"delete",this,page);
                var table = $('.myTable').DataTable();
                table.ajax.reload();
                const num = parseInt($(".paginate_button.current").data("dt-idx"));
                table.page(num).draw(false);
            }
      
       
    }
    else 
    {
        Swal.fire({
            position: 'top-center',
            icon: 'error',
            title: 'من فضلك حدد الاشخاص',
            showConfirmButton: false,
            timer: 1500
          })
    }
   
})







// البحث عن العائلة

$(document).on("click",".searchForAqarb",function(){
    let username = $(this).data("username");
    ajaxAqarb(username)
})
// العائلة 
function ajaxAqarb(username)
{
    
    
    let idParent = $(".idParent").val();
    let idEvent = $(".idEvent").val();
    let idUser = $(".idUser").val();
    let idSupervisor = $(".idSupervisor").val();
    let rank = $(".level").val();
    $.ajax({
        url : "_supervisor/ajax_frontend.php",
        method : "POST",
        data:{
            action:'searchForAqarb',
            username:username,
            idParent:idParent,
            idEvent : idEvent,
            idUser:idUser,
            idSupervisor : idSupervisor,
            rank : rank,
        },
        success : function(data)
        {
           data = jQuery.trim(data);
           if(data != 'notfound')
           {
            $(".load_data_search").html(data); 
             
            $('#table_search').dataTable( {
                
                "ordering": false,
               
                "sProcessing": "جارٍ التحميل...",
                "sLengthMenu": "_MENU_",
                "sZeroRecords": "لم يعثر على أية سجلات",
                "sInfo": "",
                "sInfoEmpty": "يعرض 0 إلى 0 من أصل 0 سجل",
                "sInfoFiltered": "",
                "sInfoPostFix": "",
                "sSearch": "",
                "searchPlaceholder": "ابحث من هنا",

                "sUrl": "",
                "pageLength": 100,
                "language" : {
                    "lengthMenu":     "_MENU_",
                    "search":         "",
                    "info":           "",
                    "searchPlaceholder": "ابحث من هنا",

                    "paginate": {
                        "next": "التالي",
                        "previous": "السابق",
                        "first": "First page"
                        }
                },
            });
            }else{
            $(".load_data_search").html(
                `
                <div class="alert alert-danger">لا يوجد بيانات</div>
                `
            );  
           }
           
    let idUser = $(".idUser").val();
    let idSupervisor = $(".idSupervisor").val();

    if(idUser != idSupervisor)
    {
        $(".dt-buttons").remove();
    }
            

        }
    })
}

//////// الصفحة الرئيسية 
$(".voteButtonMain").on("click",function(){
    if($("#showVoters").hasClass("d-none"))
    {

        var idList = $('#valueList2').find(":selected").val();

    }else{
        var idList = $('#valueList').find(":selected").val();

    }

    var idParent = $("#userNow").val();
    var idFrontend = $(".idFrontend").val();
    var idSupervisor = $(".idSupervisor").val();
   
    var idUsers = new Array();
    var names = new Array();

    if( $('.getVote:checkbox:checked').length > 0){
          if(idList == 'insertnameoflist'){
            $('.getVote:checkbox:checked').each(function(i){            
                idUsers.push($(this).data('id'))
                names.push($(this).next().val())
            })
              $.ajax({        
                    url:"_supervisor/ajax_frontend.php",  
                    method:"POST",  
                    data:{
                        action:'insertVoteMulti', 
                        idUsers : idUsers,
                        idParent:idParent,
                        names:names,
                        level : 1,
                        idFrontend:idFrontend,
                        idSupervisor:idSupervisor
                         },
                        beforeSend:function(){
                        $(".loading").css("display","flex");
                        },
                
                  
                    success:function(data){
                        data = jQuery.trim(data);
                        if(data == 'done')
                        {

                            if($("#showVoters").hasClass("d-none"))
                            {
                            var table = $('.getTableMadmen').DataTable();
                            table.ajax.reload();
                            const num2 = parseInt($(".paginate_button.current").data("dt-idx"));
                            table.page(num2).draw(false);
                            }else{
                            var table = $('.myTable').DataTable();
                            table.ajax.reload();
                            const num = parseInt($(".paginate_button.current").data("dt-idx"));
                            table.page(num).draw(false);
                            }

                            
                            $(".loading").css("display","none");
                            Swal.fire({
                                title: "بنجاح",
                                text: "تم بنجاح",
                                icon: "success"
                              });

                            
                              getNumberAttend('madmen','.getNumberMadmen');                                
                        }

                    }                    
            })
    }
       

 

    else if(idList == "presentmain")
    {
        if( $('.getVote:checkbox:checked').length > 0){
            var idUsers = new Array();
            var idParent = $(".idParent").val();
            var rank = $(".level").val();
            var idSupervisor = $(".idSupervisor").val();
            
            $('.getVote:checkbox:checked').each(function(i){
                idUsers.push($(this).data('id'))
            })
                $.ajax({
                    url:"_supervisor/ajax_frontend.php",  
                    method:"POST",  
                    data:{
                        action:'presetnmain', 
                        idUsers : idUsers,
                        idParent:idParent,
                        rank:rank,
                        idSupervisor:idSupervisor
                        },
                        beforeSend:function(){
                            $(".loading").css("display","flex");
                            },
                    
                  
                    success:function(data){  
                        data = jQuery.trim(data);
                        $(".loading").css("display","none");
                        console.log(data)
                        if(data == 'done')
                        {
                            if(data =='done')
                            {
                                Swal.fire({
                                    title: "بنجاح",
                                    text: "تم بنجاح",
                                    icon: "success"
                                  });
                            }
                            var table = $('.myTable').DataTable();
                            table.ajax.reload();
                            const num = parseInt($(".paginate_button.current").data("dt-idx"));
                            table.page(num).draw(false);
                            getNumberAttend('attend','.getNumberAttend');

                        }
                    }
                    })
           
        }

    }
    else if(idList == 'notpresentmain'){
        
            var idUsers = new Array();
            var idParent = $(".idParent").val();
            var rank = $(".level").val();
            var idSupervisor = $(".idSupervisor").val();
            
            $('.getVote:checkbox:checked').each(function(i){
                idUsers.push($(this).data('id'))
         
            })

            $.ajax({
                url:"_supervisor/ajax_frontend.php",  
                method:"POST",  
                data:{
                    action:'notpresetnmain', 
                    idUser : idUsers,
                    idParent:idParent,
                    rank:rank,
                    idSupervisor:idSupervisor
                    },
                    beforeSend:function(){
                        $(".loading").css("display","flex");
                        },
                
              
                success:function(data){  
                    data = jQuery.trim(data);
                    $(".loading").css("display","none");
                    console.log(data);
                    if(data == 'done')
                    {
                        $(".getVote").prop('checked', false)    

                    

                        if($("#showVoters").hasClass("d-none"))
                        {
                            var table = $('.getTableMadmen').DataTable();
                            table.ajax.reload();
                            const num2 = parseInt($(".paginate_button.current").data("dt-idx"));
                            table.page(num2).draw(false);
                        }else{
                        var table = $('.myTable').DataTable();
                        table.ajax.reload();
                        const num = parseInt($(".paginate_button.current").data("dt-idx"));
                        table.page(num).draw(false);
                        }

                        getNumberAttend('attend','.getNumberAttend');

                        // var table = $('.getTableMadmen').DataTable();
                        // table.ajax.reload();
                        // const num2 = parseInt($(".paginate_button.current").data("dt-idx"));
                        // table.page(num2).draw(false);

                        if(data =='done')
                        {
                            Swal.fire({
                                title: "بنجاح",
                                text: "تم بنجاح",
                                icon: "success"
                              });
                        }
                       
                    }
                }
                })

  

    }
    else if(idList == 'insert2')
    {
            {

                var idParent = $("#userNow").val();
                var idFrontend = $(".idFrontend").val();
                var idSupervisor = $(".idSupervisor").val();
                var idList = $('.lists').data('value');
                var rank  = $(".level").val();
                var idUsers = new Array();
                var names = new Array();

                $('.getVote:checkbox:checked').each(function(i){
                    idUsers.push($(this).data("id"));
                    names.push($(this).data("name"));
                })
                

    
            $.ajax({
                url : "_supervisor/ajax_frontend.php",
                method : "POST",
                data:{
                    action:'insertLevel2',
                    names:names,
                    idUsers:idUsers,
                    idParent:idParent,
                    idFrontend:idFrontend,
                    idSupervisor:idSupervisor,
                    rank : rank
                },
                beforeSend:function(){
                    $(".loading").css("display","flex");
                  },
                success : function(data)
                {
                    data = jQuery.trim(data);
                    console.log(data);
                    $(".loading").css("display","none");

                    if(data == 'done'){
              
                    if($("#showVoters").hasClass("d-none"))
                    {
                        var table = $('.getTableMadmen').DataTable();
                        table.ajax.reload();
                        const num2 = parseInt($(".paginate_button.current").data("dt-idx"));
                        table.page(num2).draw(false);
                    }else{
                    var table = $('.myTable').DataTable();
                    table.ajax.reload();
                    const num = parseInt($(".paginate_button.current").data("dt-idx"));
                    table.page(num).draw(false);
                    }
                 


                    getNumberAttend('attend','.getNumberAttend');
                    getNumberAttend('madmen','.getNumberMadmen');

                    }
                }
            })
              
             
            }
    }
    else if(idList == 'resetdata')
    {
        // حذف من مضمون  لشخص غير مضمون
            var idUsers = new Array();
            var idParent = $("#userNow").val();
            var idSupervisor = $(".idSupervisor").val();

            $('.getVote:checkbox:checked').each(function(i){
                idUsers.push($(this).data('id'))
            })


            $.ajax({
                url:"_supervisor/ajax_frontend.php",  
                method:"POST",  
                data:{
                action:'deleteVoteMulti', 
                idUser : idUsers,
                idParent:idParent,
                idSupervisor:idSupervisor
                },
                beforeSend:function(){
                    $(".loading").css("display","flex");
                },
                
                success:function(data){  
                    data = jQuery.trim(data);
                    $(".loading").css("display","none");
                    $(".getVote").prop('checked', false)    
                    console.log(data);
                    if(data == 'done')
                    {
                        Swal.fire({
                            title: "حذف",
                            text: "تح الحذف بنجاح",
                            icon: "success"
                          });

                          if($("#showVoters").hasClass("d-none"))
                            {
                                var table = $('.getTableMadmen').DataTable();
                                table.ajax.reload();
                                const num2 = parseInt($(".paginate_button.current").data("dt-idx"));
                                table.page(num2).draw(false);
                            }else{
                            var table = $('.myTable').DataTable();
                            table.ajax.reload();
                            const num = parseInt($(".paginate_button.current").data("dt-idx"));
                            table.page(num).draw(false);
                            }
                          getNumberAttend('madmen','.getNumberMadmen');
                    }
                }
                })
      
       
    }
    else{
    // اضافة اشخاص للقوائم
    var idParent = $("#userNow").val();
    var idFrontend = $(".idFrontend").val();
    var idSupervisor = $(".idSupervisor").val();
    var idList = $('#valueList').find(":selected").val();
    var idUsers = new Array();
    if( $('.getVote:checkbox:checked').length > 0){
    $('.getVote:checkbox:checked').each(function(i){
        idUsers.push($(this).data('id'))
      });
     
      $.ajax({
        url:"_supervisor/ajax_frontend.php",  
        method:"POST",  
        data:{
            action:'insertVoteList',
            idUser : idUsers,
            idParent : idParent,
            idList:idList,
            idFrontend:idFrontend,
            idSupervisor:idSupervisor
             },
             beforeSend:function(){
                $(".loading").css("display","flex");
                },
        
        success:function(data){ 
            data = jQuery.trim(data);
            
            console.log(data);
            $(".loading").css("display","none"); 
            $(".getVote").prop('checked', false)        
            if(data == 'done')
            {
                Swal.fire({
                    title: "اضافة للقائمة",
                    text: "تح  بنجاح",
                    icon: "success"
                  });
            }   
        }
        })

    }
    }
}
else{
    Swal.fire({
        position: 'top-center',
        icon: 'error',
        title: 'من فضلك حدد الاشخاص',
        showConfirmButton: false,
        timer: 1500
      })
}
   
})


// RestVote Mandoum 

$(document).on("click",".resVoteBackMandob",function(){

let idUser = $(this).data('iduser');
let idParent = $(".idParent").val();
let idSupervisor = $(".idSupervisor").val();
$.ajax({
    url:"_supervisor/ajax_frontend.php",  
    method:"POST",  
    data:{
        action:'resVoteBackMandob',
        idUser:idUser,
        idParent:idParent,
        idSupervisor:idSupervisor

    }, 
    success:function(data){ 
                let changeChose = $("#changeChosemandub").find(":selected").val();
                if(changeChose != 'empty')
                {
                    mandubFunction(changeChose);
                }else{
                    mandubFunction();
                }
                
if($("#showVoters").hasClass("d-none"))
                {
                var table = $('.getTableMadmen').DataTable();
                table.ajax.reload();
                const num2 = parseInt($(".paginate_button.current").data("dt-idx"));
                table.page(num2).draw(false);
                }else{
                var table = $('.myTable').DataTable();
                table.ajax.reload();
                const num = parseInt($(".paginate_button.current").data("dt-idx"));
                table.page(num).draw(false);
                }
                
            // getNumberAttend('attend','.getNumberAttend');

    }
   
})
})
// Upback vote 1 level


$(document).on("click",".resVoteBack",function(){
let idUser = $(this).data('iduser');
let idParent = $(".idParent").val();
let idSupervisor = $(".idSupervisor").val();
$.ajax({
    url:"_supervisor/ajax_frontend.php",  
    method:"POST",  
    data:{
        action:'resVoteBack',
        idUser:idUser,
        idParent:idParent,
        idSupervisor : idSupervisor
    }, 
    success:function(data){ 
                let changeChose = $("#changeChose").find(":selected").val();
                if(changeChose != 'empty')
                {
                getYourChose(changeChose);
                }else{
                getYourChose();
                }

            var table = $('.myTable').DataTable();
            table.ajax.reload();
            const num = parseInt($(".paginate_button.current").data("dt-idx"));
            table.page(num).draw(false);
            getNumberAttend('attend','.getNumberMadmen');
    }
   
})
})

// Delete One User 
$(document).on("click",".resetVoteMainMandob",function(){
let idUser = $(this).data('iduser');
let idParent = $(".idParent").val();
let idSupervisor = $(".idSupervisor").val();

$.ajax({
    url:"_supervisor/ajax_frontend.php",  
    method:"POST",  
    data:{
        action:'resetVoteMandob',
        idUser:idUser,
        idParent:idParent,
        idSupervisor:idSupervisor,
        
    }, 
    beforeSend:function(){
        $(".loading").css("display","flex");
      },


    success:function(data){ 
        $(".loading").css("display","none");

            var table = $('.myTable').DataTable();
            table.ajax.reload();
            const num = parseInt($(".paginate_button.current").data("dt-idx"));
            table.page(num).draw(false);
    }
   
})
})


// Delete One User 
$(document).on("click",".resetVoteMain",function(){
let idUser = $(this).data('iduser');
let idParent = $(".idParent").val();
$.ajax({
    url:"_supervisor/ajax_frontend.php",  
    method:"POST",  
    data:{
        action:'resetVote',
        idUser:idUser,
        idParent:idParent
    }, 
    beforeSend:function(){
        $(".loading").css("display","flex");
      },


    success:function(data){ 
        $(".loading").css("display","none");

            var table = $('.myTable').DataTable();
            table.ajax.reload();
            const num = parseInt($(".paginate_button.current").data("dt-idx"));
            table.page(num).draw(false);
            getNumberAttend('madmen','.getNumberMadmen');
              
if($("#showVoters").hasClass("d-none"))
                {
                var table = $('.getTableMadmen').DataTable();
                table.ajax.reload();
                const num2 = parseInt($(".paginate_button.current").data("dt-idx"));
                table.page(num2).draw(false);
                }else{
                var table = $('.myTable').DataTable();
                table.ajax.reload();
                const num = parseInt($(".paginate_button.current").data("dt-idx"));
                table.page(num).draw(false);
                }

    }
   
})
})
// Insert One Vote
$(document).on("click",".voteLevel2MainPage",function(){
let idUser = $(this).data('iduser');
let idParent = $(this).data('idparent');
let level = $(this).data('level');

$.ajax({
    url:"_supervisor/ajax_frontend.php",  
    method:"POST",  
    data:{
        action:'updateVote',
        idUser:idUser,
        idParent:idParent,
        level:level,
        
    },
    success:function(data){ 
        data = jQuery.trim(data);
        if(data == 'done')
        {
            var table = $('.myTable').DataTable();
            table.ajax.reload();
            const num = parseInt($(".paginate_button.current").data("dt-idx"));
            table.page(num).draw(false); 
        } 
    }
   })

})

// انشاء قائمة جديد
$(document).on("submit","#createList",function (e) {
e.preventDefault();
if($("#nameList").val() != ''){

$.ajax({
    url:"_supervisor/ajax_frontend.php",  
    method:"POST",  
    data:$(this).serialize(),
    beforeSend:function(){  
    $('#insert').val("Inserting");  
    }, 
    success:function(data){ 
        data = jQuery.trim(data);
        console.log(data);
        if(data == 'done'){
            Swal.fire({
                position: 'top-center',
                icon: 'success',
                title: 'تم انشاء القائمة بنجاح',
                showConfirmButton: false,
                timer: 1500
              })
              setTimeout(function(){
                window.location.reload();
             }, 1000);
        }else{
            Swal.fire({
                position: 'top-center',
                icon: 'error',
                title: 'اسم القائمة موجود من قبل',
                showConfirmButton: false,
                timer: 1500
              }) 
        }
    }
    })
}else{
    Swal.fire({
        position: 'top-center',
        icon: 'error',
        title: 'من فضلك ادخل اسم القائمة',
        showConfirmButton: false,
        timer: 1500
      })
}

})

// القوائم
$(".itemlist").on("click",function(){
let id = $(this).data('id');
ajaxOpenlist(id);
})

$(document).on("click",".updateLevelList",function(){
let idList = $(this).data("idlist");

let idUser = $(this).data("id");
let username = $(this).data("name");
var idParent = $("#userNow").val();
var idFrontend = $(".idFrontend").val();
var idSupervisor = $(".idSupervisor").val();

$.ajax({        
  url:"_supervisor/ajax_frontend.php",  
  method:"POST",  
  data:{
      action:'updateVoteLevelOneByCircle', 
      idUser : idUser,
      username:username,
      idParent:idParent,
      idFrontend:idFrontend,
      idSupervisor:idSupervisor
       },
      beforeSend:function(){
      $(".loading").css("display","flex");
      },


  success:function(data){
      $(".loading").css("display","none");

      data = jQuery.trim(data);
    
      if(data == 'done')
      {
         ajaxOpenlist(idList);

          var table = $('.myTable').DataTable();
          table.ajax.reload();
          const num = parseInt($(".paginate_button.current").data("dt-idx"));
          table.page(num).draw(false);
          getNumberAttend('madmen','.getNumberMadmen');

          
      }

  }                    
})

})

function ajaxOpenlist(idlist){
 // let nameList = $(this).data("name");
/*    $(".value-id-list").val($(this).data("id"));
$(".get-name-list").val(nameList);*/
var idFrontend = $(".idFrontend").val();
var idParent = $(".idParent").val();
var idEvent = $(".idEvent").val();
var rank = $(".level").val();
var idSupervisor = $(".idSupervisor").val();    
$.ajax({
    url:"_supervisor/ajax_frontend.php",  
    method:"POST",  
    data:{
        action:'openlist',
        idList : idlist,
        idFrontend:idFrontend,
        idParent:idParent,
        idEvent:idEvent,
        idSupervisor : idSupervisor,
        rank : rank
         },
  
    success:function(data){ 
        data = jQuery.trim(data);
        if(data == 'zero')
        {
            $(".load_data_search").html(
                `<div class='alert alert-warning py-4 my-4'>
                <h4> <i class="ri-error-warning-fill"></i> فارغ </h4>
                </div>`
                );
        }
        else {
            $(".load_data_search").html(data);
            
            $('#table_search').dataTable( {
                "ordering": false,
                "pageLength": 100,
                "sProcessing": "جارٍ التحميل...",
                "sLengthMenu": "_MENU_",
                "sZeroRecords": "لم يعثر على أية سجلات",
                "sInfo": "",
                "sInfoEmpty": "يعرض 0 إلى 0 من أصل 0 سجل",
                "sInfoFiltered": "",
                "sInfoPostFix": "",
                "sSearch": "",
                "searchPlaceholder": "ابحث من هنا",

                "sUrl": "",
                "language" : {
                    "lengthMenu":     "_MENU_",
                    "search":         "",
                    "info":           "",
                    "searchPlaceholder": "ابحث من هنا",

                    "paginate": {
                        "next": "التالي",
                        "previous": "السابق",
                        "first": "First page"
                        }
                },
            });

            let idUser = $(".idUser").val();
            let idSupervisor = $(".idSupervisor").val();

            if(idUser != idSupervisor)
            {
                $(".dt-buttons").remove();
            }
            
        }
}
})
}

// mandub المندب 
$(document).on("click",".mandub",function(){
mandubFunction();
})

$(document).on("change","#changeChosemandub",function(){
let changeChose = $("#changeChosemandub").find(":selected").val();
mandubFunction(changeChose);
})




// mychose 
$(document).on("click",".mychose",function(){
getYourChose();
})

$(document).on("change","#changeChose",function(){
let changeChose = $("#changeChose").find(":selected").val();
let maleOrFemaleChose = $("#maleOrFemaleChose").find(":selected").val();
getYourChose(changeChose,maleOrFemaleChose);
})
$(document).on("change","#maleOrFemaleChose",function(){
let changeChose = $("#changeChose").find(":selected").val();
let maleOrFemaleChose = $("#maleOrFemaleChose").find(":selected").val();
getYourChose(changeChose,maleOrFemaleChose);
})



// المضامين
function getYourChose(change = '',maleOrFemale = ''){
var idParent = $(".idParent").val();
var idUser = $(".idUser").val();
var idEvent = $(".idEvent").val();
var idSupervisor = $(".idSupervisor").val();
var rank = $(".level").val();

$.ajax({
    url:"_supervisor/ajax_frontend.php",  
    method:"POST",  
    data : {
        action : "mychose",
        idParent : idParent,
        idEvent : idEvent,
        idSupervisor : idSupervisor,
        idUser:idUser,
        change : change,
        maleOrFemale : maleOrFemale,
        rank:rank
    },
    beforeSend:function(){
        $(".loading").css("display","flex");
      },
    success : function(data)
    {
        $(".loading").css("display","none");
        data = $.trim(data);
        if(data == 'zero')
        {
        $(".load_data_search").html(
            `
            <div class="alert alert-warning py4">لا يوجد بيانات</div>
            `
        )
        }
        else
        {
            $(".load_data_search").html(data)


              
            $('#table_search').DataTable({

                "ordering": false,
                "pageLength": 100,

            
                "sProcessing": "جارٍ التحميل...",
                "sLengthMenu": "_MENU_",
                "sZeroRecords": "لم يعثر على أية سجلات",
                "sInfo": "",
                "sInfoEmpty": "يعرض 0 إلى 0 من أصل 0 سجل",
                "sInfoFiltered": "",
                "sInfoPostFix": "",
                "sSearch": "",

                "sUrl": "",
                "language" : {
                    "lengthMenu":     "_MENU_",
                    "search":         "",
                    "info":           "",
                    "searchPlaceholder": "ابحث من هنا",

                    "paginate": {
                        "next": "التالي",
                        "previous": "السابق",
                        "first": "First page"
                        }
                },
              });
        
        let idUser = $(".idUser").val();
        let idSupervisor = $(".idSupervisor").val();

        if(idUser != idSupervisor)
        {
            $(".dt-buttons").remove();
        }
        }
    }
})
}


// المندوب
function mandubFunction(change = ''){
var idParent = $(".idParent").val();
var idUser = $(".idUser").val();
var idEvent = $(".idEvent").val();
var idSupervisor = $(".idSupervisor").val();

$.ajax({
    url:"_supervisor/ajax_frontend.php",  
    method:"POST",  
    data : {
        action : "mandub",
        idParent : idParent,
        idEvent : idEvent,
        idSupervisor : idSupervisor,
        idUser:idUser,
        change : change,
    },
    beforeSend:function(){
        $(".loading").css("display","flex");
      },
    success : function(data)
    {
        $(".loading").css("display","none");
        data = $.trim(data);
        if(data == 'zero')
        {
        $(".load_data_search").html(
            `
            <div class="alert alert-warning py4">لا يوجد بيانات</div>
            `
        )
        }
        else
        {
            $(".load_data_search").html(data)
            $('#table_search').DataTable({

                "ordering": false,
                "pageLength": 100,

            
                "sProcessing": "جارٍ التحميل...",
                "sLengthMenu": "_MENU_",
                "sZeroRecords": "لم يعثر على أية سجلات",
                "sInfo": "",
                "sInfoEmpty": "يعرض 0 إلى 0 من أصل 0 سجل",
                "sInfoFiltered": "",
                "sInfoPostFix": "",
                "sSearch": "",

                "sUrl": "",
                "language" : {
                    "lengthMenu":     "_MENU_",
                    "search":         "",
                    "info":           "",
                    "searchPlaceholder": "ابحث من هنا",

                    "paginate": {
                        "next": "التالي",
                        "previous": "السابق",
                        "first": "First page"
                        }
                },
              });
        let idUser = $(".idUser").val();
        let idSupervisor = $(".idSupervisor").val();

        if(idUser != idSupervisor)
        {
            $(".dt-buttons").remove();
        }
        }
    }
})
}


//التفاصيل 
$(document).on("click",".details",function(){
let iduser = $(this).data("id");
let idUserNow = $(".idUser").val();
let idSupervisor = $(".idSupervisor").val();
let rank = $(".level").val();
let idEvent = $(".idEvent").val();

$.ajax({
    url:"_supervisor/ajax_frontend.php",  
    method:"POST",  
    data : {
        action : "details",
        idUser : iduser,
        idUserNow : idUserNow,
        idParent : $(".idParent").val(),
        idSupervisor:idSupervisor,
        rank:rank,
        idEvent:idEvent
    },
    success : function(data)
    {
       
        data = $.trim(data);
        $(".load_deta_details").html(data)
       
      
    }
})
})


let select_value = 1;

$(document).on("change",".select_degree",function(){
select_value = $(this).val();
})

// اقارب
$(document).on("click",".search_degree",function(){
details(select_value,$(this).data("name"));
})



function details(select_value = '',usernameSearch){
let idParent = $(".idParent").val();
let idEvent = $(".idEvent").val();
let idSupervisor = $(".idSupervisor").val();
let idUser = $(".idUser").val();
$.ajax({
    url:"_supervisor/ajax_frontend.php",  
    method:"POST",  
    data : {
        action : "search_degree",
        select_value : select_value,
        username:usernameSearch,
        idParent:idParent,
        idEvent:idEvent,
        idSupervisor:idSupervisor,
        idUser : idUser,
    },
    beforeSend : function(){
        $(".load_data_degree").html(`
        <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
        `);

    },
    success : function(data)
    {
     $(".load_data_degree").html(data);
     $('#table_search_degree').dataTable( {

        "ordering": false,
        "pageLength": 100,

                "language" : {
            "lengthMenu":     "_MENU_",
            "search":         "",
            "info":           "",
            "searchPlaceholder": "ابحث من هنا",

            "paginate": {
                "next": "التالي",
                "previous": "السابق",
                "first": "First page"
                }
        },
    });
    }
})
}

// Remove List 
$(document).on("click",".btndeletelist",function(e){
e.stopPropagation();
$('.checkboxlist:checked').each(function() {
    let idList = $(this).data("id");
    $.ajax({
        url:"_supervisor/ajax_frontend.php",  
        method:"POST",  
        data:{
            action:'deletelist',
            idList:idList,
        },
        success:function(data){ 
           
            location.reload();
        }
       })
});

})


let valOne = '';
let valTwo = '';
$(document).on("keyup",".newValeLjna",function(){
valOne = $(this).val();
})
$(document).on("keyup",".newValuePhone",function(){
valTwo = $(this).val();
})

$(document).on("click",".changeDataDetails",function(){
InputnewValeLjna = valOne;
InputnewValuePhone =valTwo;
idUser = $(this).data("id");
   $.ajax({
    url:"_supervisor/ajax_frontend.php",  
    method:"POST",  
    data:{
        action:'changeDataDetails',
        newValeLjna:InputnewValeLjna,
        newValuePhone:InputnewValuePhone,
        idUser : idUser
    },
    success:function(data){ 
        data = $.trim(data);
        if(data == 'empty')
        {
            Swal.fire({
                title: "خطأ",
                text: "من فضلك ادخل قيمة",
                icon: "error"
              });
        }else{

            let idUserNow = $(".idUser").val();
            let idSupervisor = $(".idSupervisor").val();
            let rank = $(".level").val();
        
            $.ajax({
                url:"_supervisor/ajax_frontend.php",  
                method:"POST",  
                data : {
                    action : "details",
                    idUser : idUser,
                    idUserNow : idUserNow,
                    idParent : $(".idParent").val(),
                    idSupervisor:idSupervisor,
                    rank:rank
                },
                success : function(data)
                {
                   
                    data = $.trim(data);
                    $(".load_deta_details").html(data)
                   
                    valOne = '';
                    valTwo = '';
                }
            })

        }
    }
   })
})  


function getNumberAttend(type,id)
{
    let idSupervisor  = $(".idSupervisor").val();
    let idParent  = $(".idParent").val();
    let rank  = $(".level").val();
    $.ajax({
        url:"_supervisor/ajax_frontend.php",  
        method:"POST",  
        data : {
            action : "getNumberMadmen",    
            type : type, 
            idSupervisor : idSupervisor,
            idParent:idParent,
            rank : rank
        },
        success : function(data)
        {
            data = $.trim(data);
            $(id).html(data);
        }
    })
}

getNumberAttend('attend','.getNumberAttend');
getNumberAttend('madmen','.getNumberMadmen');



// Update Vote Level One 
$(document).on("click",".updateVoteLevelOne",function(){

    let idUser = $(this).data("id");
    let username = $(this).data("name");
    var idParent = $("#userNow").val();
    var idFrontend = $(".idFrontend").val();
    var idSupervisor = $(".idSupervisor").val();

     $.ajax({        
        url:"_supervisor/ajax_frontend.php",  
        method:"POST",  
        data:{
            action:'updateVoteLevelOneByCircle', 
            idUser : idUser,
            username:username,
            idParent:idParent,
            idFrontend:idFrontend,
            idSupervisor:idSupervisor
             },
            beforeSend:function(){
            $(".loading").css("display","flex");
            },
    
      
        success:function(data){
            $(".loading").css("display","none");

            data = jQuery.trim(data);
          
            if(data == 'done')
            {

                var table = $('.myTable').DataTable();
                table.ajax.reload();
                const num = parseInt($(".paginate_button.current").data("dt-idx"));
                table.page(num).draw(false);
                getNumberAttend('madmen','.getNumberMadmen');

                
            }

        }                    
})

})

})

