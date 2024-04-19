<?php
if(str_contains($_SERVER['REQUEST_URI'],"index.php"))
{
    ?>
<footer class="footer">
            <img src="_supervisor/<?=$infocandidate['footerImage']?>" alt="">
        </footer>
<div id="timer" class="down-footer d-flex flex-row">
            <small class="text-white d-textitme">باقي على الوقت</small>
            <div>
            <span class="day"></span>
            <span class="hour"></span>
            <span class="minute"></span>
            <span class="second"></span>
            </div>
        </div>
    <?php 
}
?>

        <?php 

        function active($parm)
        {

            return str_contains($_SERVER['REQUEST_URI'],$parm) ? "active" : "";    
        }
      

        ?>
        <div class="navbar-bottom">
        <div>
        
        <a  href="index.php?username=<?=$_GET['username']."&id=".$_GET['id']?>" class="home <?=active('index.php');?>"> <i class="ri-home-5-fill"></i> الرئيسية </a>
        <a href="search.php?username=<?=$_GET['username']."&id=".$_GET['id']?>" class="<?=active('search.php');?>"> <i class="ri-search-line"></i> البحث </a>
        <a href="index.php?username=<?=$_GET['username']."&id=".$_GET['id']?>" class="showMadmenButtonNavbar"> <i class="ri-team-line"></i> المضامين</a>
        <a href="alnaayib.php?username=<?=$_GET['username']."&id=".$_GET['id']?>"  class="<?=active('alnaayib.php');?>">  <i class="ri-user-2-fill"></i> النائب </a>
        <a href="tasks.php?username=<?=$_GET['username']."&id=".$_GET['id']?>" class="<?=active('tasks.php');?> "> <i class="ri-list-check-3"></i>المهام</a>
        <a href="transactions.php?username=<?=$_GET['username']."&id=".$_GET['id']?>" class="<?=active('transactions.php');?> "> <i class="ri-profile-line"></i> المعاملات</a>
        </div>
    </div>

    </div>

  
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/sweetalert2.min.js"></script>
    <script src="assets/js/html2pdf.bundle.js"></script>
    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/select2.min.js"></script>
    <script src="assets/js/ajax.js"></script>
    <script src="assets/js/script.js"></script>
    <script>
        $(function(){
            var currentUrl = window.location.href;

            if(localStorage.getItem("covertToMadmen"))
            {
                $(".getMadmen").click();
                localStorage.removeItem("covertToMadmen");

                $(".home").removeClass("active");
                $(".showMadmenButtonNavbar").addClass("active");
                    
            }
            
            $(".showMadmenButtonNavbar").click(function(e){
                if(currentUrl.includes("index.php"))
                {
                    e.preventDefault();
                    $(".getMadmen").click();
                    $(this).addClass("active");
                    $(".home").removeClass("active");
                    $(this).addClass("activeMadmen");
                }
                else{
                    localStorage.setItem("covertToMadmen", true);
                }
               
            })

            $(".home").on("click",function(e){
                if($(".showMadmenButtonNavbar").hasClass("activeMadmen"))
                {
                    e.preventDefault();
                    $(".getVoters").click();


                    $(this).addClass("active");
                    $(".showMadmenButtonNavbar").removeClass("active");
                }
            })
        })
    </script>
</body>

</html>