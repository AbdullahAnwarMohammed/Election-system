
$(function(){

    $(".openDropdown").on("click",function(){

        $(this).siblings(".dropdown").slideToggle();
        
    })


function mainPage()
{
var dataTable = $('.myTable').DataTable({
    bAutoWidth: false, 

    columnDefs: [
        { orderable: false, targets: 0 }
      ],
      "order":[[1,'asc']],
    "bDestroy": true,
    "responsive": true,
    "pageLength": 100,
    
    "initComplete": function(settings, json) {
        $(".getNumber").html('');
        $(".dataTables_info").appendTo(".getNumber");
      },
    
    "language": 
                        {
                            "info":"",
                            "sProcessing": "جارٍ التحميل...",
                            "sLengthMenu": " _MENU_ ",
                            "sZeroRecords": "لم يعثر على أية سجلات",
                            "sInfo":"_TOTAL_ ",
                            "sInfoEmpty": "0",
                            "sInfoFiltered": "",
                            "sInfoPostFix": "",
                            "sSearch": "",
                            "searchPlaceholder": "ابحث من هنا",
                            "sUrl": "",
                            "oPaginate": {
                                "sFirst": "الأول",
                                "sPrevious": "السابق",
                                "sNext": "التالي",
                                "sLast": "الأخير"
                            }
                        },
    "processing" : true,
    "serverSide" : true,    

    "ajax" : {
        url:"load_data_madmen.php",
        type:"POST",
        data : {
        nameEvent : $(".nameEvent").val(),
        idEVENT : $(".idEvent").val(),
        idParent:$(".idParent").val(),
        idSupervisor:$(".idSupervisor").val(),
        rank:$(".level").val(),
 
        }
    },
    "drawCallback": function (settings) { 
        // Here the response
        var response = settings.json;
        console.log(response);
    },

});

}



mainPage();



})

