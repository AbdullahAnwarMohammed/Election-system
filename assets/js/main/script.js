// Disable right-click
// document.addEventListener('contextmenu', (e) => e.preventDefault());

// function ctrlShiftKey(e, keyCode) {
//   return e.ctrlKey && e.shiftKey && e.keyCode === keyCode.charCodeAt(0);
// }

// document.onkeydown = (e) => {
//   // Disable F12, Ctrl + Shift + I, Ctrl + Shift + J, Ctrl + U
//   if (
//     event.keyCode === 123 ||
//     ctrlShiftKey(e, 'I') ||
//     ctrlShiftKey(e, 'J') ||
//     ctrlShiftKey(e, 'C') ||
//     (e.ctrlKey && e.keyCode === 'U'.charCodeAt(0))
//   )
//     return false;
// };

function exportTableToCSV() {
    var csvContent = "\uFEFF"; // BOM (Byte Order Mark) to support UTF-8
    var table = document.querySelector(".styleTable");
    
    // Get table headers
    var headers = [];
    for (var i = 0; i < table.rows[0].cells.length; i++) {
        headers.push('"' + table.rows[0].cells[i].innerText + '"');
    }
    csvContent += headers.join(',') + '\n';

    // Get table data
    for (var i = 1; i < table.rows.length; i++) {
        var row = [];
        for (var j = 0; j < table.rows[i].cells.length; j++) {
            row.push('"' + table.rows[i].cells[j].innerText + '"');
        }
        csvContent += row.join(',') + '\n';
    }

    // Create a Blob
    var blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });

    // Create a download link
    var link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'exported_data.csv';

    // Append the link to the document and trigger a click event
    document.body.appendChild(link);
    link.click();

    // Remove the link from the document
    document.body.removeChild(link);
}

function hideInPrintPdf()
{
    const td3 = document.querySelectorAll("table tr td:nth-child(1)")
    const td4 = document.querySelectorAll("table tr td:nth-child(4)")
    const td1 = document.querySelectorAll("table tr td:nth-child(3)")
    td1.forEach((e) => {
        e.style.visibility = "hidden"
    })
    td3.forEach((e) => {
        e.style.visibility = "hidden"
    })
    td4.forEach((e) => {
        e.style.visibility = "hidden"
    })
    const hidePdf = document.querySelectorAll(".hidePdf");

    hidePdf.forEach((e) => {
        e.style.visibility = "hidden"
    })

  
   

    if( document.querySelector("#changeChose"))
    {
        document.querySelector("#changeChose").style.display = "none";
    }
    document.querySelector("#table_search_paginate").style.display = "none";
    document.querySelector("#table_search_filter").style.display = "none";
    document.querySelector("#table_search_length").style.display = "none";
    document.querySelector(".appBtnPrint").style.display = "none";    
    document.querySelector("#table_search_info").style.display="none";
    document.querySelector(".insertMulti").style.visibility="hidden";
}

function showAfterPrintPdf()
{
    const td1 = document.querySelectorAll("table tr td:nth-child(1)")
    const td3 = document.querySelectorAll("table tr td:nth-child(3)")
    const td4 = document.querySelectorAll("table tr td:nth-child(4)")
    const hidePdf = document.querySelectorAll(".hidePdf");

    hidePdf.forEach((e) => {
        e.style.visibility = "visible"
    })

    td1.forEach((e) => {
        e.style.visibility = "visible"
    })

    td4.forEach((e) => {
        e.style.visibility = "visible"
    })

    td3.forEach((e) => {
        e.style.visibility = "visible"
    })
    if( document.querySelector("#changeChose"))
    {
        document.querySelector("#changeChose").style.display = "inline-block";

    }
    document.querySelector("#table_search_paginate").style.display = "inline-block";
    document.querySelector("#table_search_filter").style.display = "inline-block";
    document.querySelector("#table_search_length").style.display = "inline-block";
    document.querySelector(".appBtnPrint").style.display = "inline-block";    
    document.querySelector("#table_search_info").style.display="inline-block";
    document.querySelector(".insertMulti").style.visibility="visible";


}
function generatePDF() { 
    var element = document.querySelector(".parents");
    hideInPrintPdf();
    var pdfPromise = html2pdf(element, {
        margin: 0,
        filename: 'table.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
        },
        );

    pdfPromise.then(function (pdf) {
        showAfterPrintPdf();
    }).catch(function (error) {
        console.error('Error generating PDF:', error);
    });


  
      
  

}

function printTable(namePage)
{
     // Create a new window for printing
     var printWindow = window.open('', '_blank');

     // Get the table content by id
     var tableContent = document.querySelector(".styleTable").outerHTML;

     // Apply additional styles for the printout
     var printStyles = `
     <style>
     @import url('https://fonts.googleapis.com/css2?family=El+Messiri&display=swap');

     body { 
        font-family: 'El Messiri', sans-serif;

        margin: 20px;
        direction:rtl;
    }
      table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
       th, td { border: 1px solid #dddddd; text-align: right; font-size:9px }
        th { background-color: #f2f2f2; }
        table input[type='checkbox']{
            display:none;
        }
        a{
            text-decoration:none;
        }
        table tr td:nth-child(1),
        table tr th:nth-child(1),
        table tr td:nth-child(3),
        table tr th:nth-child(3),
        table tr td:nth-child(4),
        table tr th:nth-child(4)
        {
            display:none;
        }
     </style>`;
     var title = '<title>Printable Table</title>';
     var combinedContent = `<html> ${title}<head>${printStyles}</head><body>
     صفحة ${namePage}
     ${tableContent}
     </body></html>`;

     // Set the content of the print window
     printWindow.document.body.innerHTML = combinedContent;

     // Print the contents of the new window
     printWindow.print();


      // Check if the user clicked "Cancel" in the print dialog
        if (!window.matchMedia('print').matches) {
            // Code to execute if the user clicked "Cancel"
            // For example, you can close the page
            printWindow.close();
        }

}



$(function(){

    
function TableMadmen(gender = '',committee = '')
{
var dataTable = $('.getTableMadmen').DataTable({
    bAutoWidth: false, 
    // "bInfo" : false,

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
        $(".getNumberMadmen2").html('');

        $(".getNumberMadmen2").html(  $(".getNumber").text());
      },
    
    "language": 
                        {
                            "info":"_TOTAL_",

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
        idUserNow: $(".idUser").val(),
        rank:$(".level").val(),
        gender:gender,
        committee:committee
        }
    }
    ,
    "drawCallback": function (settings) { 
        // Here the response
        var response = settings.json;
        $(".getNumberAttend").html(response.attend)
    }
    })
}


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


$(document).on("change","#GenderHome",function(){
    let GenderHome = $("#GenderHome").find(":selected").val();
    let committee = $("#committee").find(":selected").val();

    TableMadmen(GenderHome,committee);


})
$(document).on("change","#committee",function(){
    let GenderHome = $("#GenderHome").find(":selected").val();
    let committee = $("#committee").find(":selected").val();
    TableMadmen(GenderHome,committee);

})
   
    $(".getMadmen").on("click",function(){
        $(".namePage").html('صفحة  المضامين')
        $(".mainMaleOrFemaleHomePage").hide();
        TableMadmen();

       $("#showMamen").removeClass("d-none");
       $("#showVoters").addClass("d-none");
       $(this).addClass("d-none");
       $(".getVoters").removeClass("d-none");
       $(".areaName").addClass("d-none");
       
    })

    $(".getVoters").on("click",function(){
        $(".areaName").removeClass("d-none");


        $(".namePage").html('الصفحة الرئيسية')

        $(".mainMaleOrFemaleHomePage").show();
        mainPage();
        $("#showMamen").addClass("d-none");
        $("#showVoters").removeClass("d-none");
        $(this).addClass("d-none");
        $(".getMadmen").removeClass("d-none");
     })

    $(document).on('show.bs.modal', '.modal', function() {
        const zIndex = 1040 + 10 * $('.modal:visible').length;
        $(this).css('z-index', zIndex);
        setTimeout(() => $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack'));
      });

    $(document).on("click",".btn-print-csv",()=>{
        exportTableToCSV();
    })
    $(document).on("click",".btn-print-pdf",()=>{
        generatePDF();
    })
    $(document).on("click",".print",()=>{
        let pageName = $(".print").data("pagename");
        printTable(pageName);
    })
    
    $('.family_value_search ').select2();


    $(".openDropdown").on("click",function(){

        $(this).siblings(".dropdown").slideToggle();
        
    })
   

    

    $(".colors span").on("click",function(){
        let backgroundSpan = $(this).css("background-color");
        $(".search h2").css("background",backgroundSpan);
        $(".createListNames h3").css("background",backgroundSpan);
        $("#timer span ").css("color",backgroundSpan);
       
    })

function mainPage(mainMaleOrFemale = '',areaName = '')
{
var dataTable = $('.myTable').DataTable({
    bAutoWidth: false, 
    // "bInfo" : false,
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
                            "info":"_TOTAL_",
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
        url:"load_data.php",
        type:"POST",
        data : {
        nameEvent : $(".nameEvent").val(),
        idEVENT : $(".idEvent").val(),
        idParent:$(".idParent").val(),
        idSupervisor:$(".idSupervisor").val(),
        rank:$(".level").val(),
        idUserNow: $(".idUser").val(),
        mainMaleOrFemale:mainMaleOrFemale,
        areaName : areaName
        }
    }  ,
    "drawCallback": function (settings) { 
        // Here the response
        var response = settings.json;
        console.log(response.attend);
        $(".getNumberAttend").html(response.attend)

    }
    
});


}

mainPage();

$(document).on("change","#mainMaleOrFemale",function(){
    let changeChose = $("#mainMaleOrFemale").find(":selected").val();
    let areaName = $(".areaName").find(":selected").val();
    mainPage(changeChose,areaName);
})

$(document).on("change",".areaName",function(){
    let changeChose = $("#mainMaleOrFemale").find(":selected").val();
    let areaName = $(".areaName").find(":selected").val();
    mainPage(changeChose,areaName);
})


$(document).on("change",".committees",function(){
    let changeChose = $("#mainMaleOrFemale").find(":selected").val();
    let areaName = $(".areaName").find(":selected").val();
    mainPage(changeChose,areaName);
})

$(document).on("change",".headquarters",function(){
    alert('w')
    let changeChose = $("#mainMaleOrFemale").find(":selected").val();
    let areaName = $(".areaName").find(":selected").val();

    let committees =  $(".committees").find(":selected").val();
    let headquarters  =  $(".headquarters").find(":selected").val();
    alert(headquarters);
    // mainPage(changeChose,areaName);
})


// TIMER 

var countDownDate = new Date( $(".expireDate").val()).getTime();

var x = setInterval(function() {
    $("#timer").css("display","flex");
  // Get today's date and time
  var now = new Date().getTime();

  // Find the distance between now and the count down date
  var distance = countDownDate - now;

  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

  $("#timer .day").html(days + "يوم");
  $("#timer .hour").html(hours + "ساعة");
  $("#timer .minute").html(minutes + "دقيقة");
  $("#timer .second").html(seconds + "ثانية");


  // If the count down is finished, write some text
  if (distance < 0) {
    clearInterval(x);
    $(".d-textitme").hide();
    $("#timer .day").html("صفر");
    $("#timer .hour").html("صفر");
    $("#timer .minute").html("صفر");
    $("#timer .second").html("صفر");
  }
}, 1000);




$(document).on("click",".details_name_of_item",function(){
   $(".btn-close").click();
})

$(document).on("click",".btn-search-qarib",function(){
    $(".btn-close-searchuser").click();
 })


/* 
اضافة كلاس الاكتف وحذفة فى  جميع الحالات
*/

$(".family_value_search").on("change",function(){
    if($(this).find(":selected").val() != "none")
    {
        $(".button_search").removeClass("disabled");
    }else{
        $(".button_search").addClass("disabled");
    }
})
$(".input_value_search").on("keyup",function(){
    if($(this).val().length != 0)
    {
        $(".button_search").removeClass("disabled");
    }else{
        if($(".family_value_search").find(":selected").val() == "none")
        {
            $(".button_search").addClass("disabled");
        }

    }
})



// DataTable For Search 
$(document).on("click",".btn_load_data",function(){
    $("#itemList").hide();
    $("#itemList").find(".btn-close").click();
})






$(document).on("click",".checkboxlist",function (e) {
    //btndeletelist
    if($('.checkboxlist:checkbox:checked').length > 0){
       $(".btndeletelist").fadeIn();
    }else{
        $(".btndeletelist").fadeOut();
    }
  

})





$(document).on("click",".checkall",function(){
    let target = $(this).data("target");
    $(target).prop("checked", $(this).prop("checked"));
})



function get_all_counts()
{
        let idUser = $(".idUser").val();
        let idSupervisor = $(".idSupervisor").val();

            $.ajax({
            url:"_supervisor/ajax_frontend.php",  
            method:"POST",  
            data:{
                action:'get_all_counts',    
                idUser:idUser,
                idSupervisor:idSupervisor          
            },
            success:function(data){ 
                $(".get_counts").html(data)
            }
        })
}
get_all_counts();


$(".get_counts").on("click",function(){
    get_all_counts();

})






})

