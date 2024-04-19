$(function(){
    let table = $('#tableBackend').DataTable( {
        responsive: true,
        "searching": false,
        "bPaginate": false,

        "bLengthChange" : false, //thought this line could hide the LengthMenu
        "language" : {
            "lengthMenu":     "اظهار _MENU_ من القائمة",
            "search":         "البحث:",
            "info":           "",
           

        },
    } );

  

    // deleteMot 
    $(document).on("click",".deleteEvent",function(){

        let id = $(this).data("id")
        deleteBackend(id,"deleteEvent");
    })



    $(document).on("click",".deleteSuperVisor",function(){
        let id = $(this).data("id")
        deleteBackend(id,"deleteSuperVisor");

    })
    
    $(document).on("click",".deleteCandidate",function(){
        let id = $(this).data("id")
        deleteBackend(id,"deleteCandidate");
    })
    $(document).on("click",".deleteMusharifin",function(){
        let id = $(this).data("id")
        deleteBackend(id,"deleteMusharifin");
    })
    
    function deleteBackend(iD,nameEvent)
    {   
       

        Swal.fire({
            title: "هل تريد الحذف",
            showCancelButton: true,
            confirmButtonText: "حذف",
            cancelButtonText : 'الغاء',
          }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url : "ajax_backend.php",
                    method : "POST",
                    data:{
                        action: nameEvent,
                        id:iD
                    },
                    success:function(data){
                        location.reload()
                    }
                  })

            } 
          });
    }

    // ?action=delete&id=<?=$row['id']?>


    // Modal Copy Link

    var $temp = $("<input>");

    $(document).on('click',".copylink", function() {
      $("body").append($temp);
      $temp.val($(this).data("link")).select();
       document.execCommand("copy");
       $temp.remove();
       Swal.fire(
        `<i class="fas fa-copy"></i>`,
        'تم',
        'success'
      )
       })


       
    let id = $("#nameEvent option:selected").attr('data-id');
    $("#nameEvent").on("change",function(){
     id = $("#nameEvent option:selected").attr('data-id');
     $(".idEvent").val(id);
    })
    $(".idEvent").val(id);

    

    new DataTable('#data-table');
  
  
})


  

  // attend and non-attend 
  $(".appAttend a").on("click",function(){
    $(this).addClass("active").siblings().removeClass("active");
    let action = $(this).data("action");
    $.ajax({
        url : "ajax_backend.php",
        method : "POST",
        data:{
            action:'attendAction',
            type:action
        },
        success:function(data){
            $(".showDataAttend").empty();
            $(".showDataAttend").html(data);
        }
      })
  })


  function exportTableToCSV(id) {
    var csvContent = "\uFEFF"; // BOM (Byte Order Mark) to support UTF-8
    var table = document.getElementById(id);

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

function generatePDF(id) { 
    var element = document.getElementById(id);
    
html2pdf(element, {
    margin: 10,
    filename: 'table.pdf',
    image: { type: 'jpeg', quality: 0.98 },
    html2canvas: { scale: 2 },
    jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },

    });
    
}

function printTable(namePage,id)
{
     // Create a new window for printing
     var printWindow = window.open('', '_blank');

     // Get the table content by id
     var tableContent = document.getElementById(id).outerHTML;

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
       th, td { border: 1px solid #dddddd; text-align: right; padding: 8px; }
        th { background-color: #f2f2f2; }
        table input[type='checkbox']{
            display:none;
        }
        a{
            text-decoration:none;
        }
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
    $(".print").on("click",function(){
        let id = $(this).data("id");
        printTable($(this).data("pagename"),id);
    })
    $(".btn-print-csv").on("click",function(){
        let id = $(this).data("id");
        exportTableToCSV(id);
    })
    $(".btn-print-pdf").on("click",function(){
        let id = $(this).data("id");
        generatePDF(id);
    })
})