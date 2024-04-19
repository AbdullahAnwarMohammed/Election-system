<nav id="sidebar" class="sidebar-wrapper">
				<!-- Sidebar brand start  -->
				<div class="sidebar-brand">
                    <a href="index.php">
                    <h3 class="text-white text-center py-4">
                        منصة مجلس <span class="icon-layers2"></span>
                    </h3>
                    </a>
					<!-- <a href="index.html" class="logo">
					</a> -->
					<!-- <a href="index.html" class="logo-sm">
						<img src="img/logo-sm.png" alt="Wafi Admin Dashboard" />
					</a> -->
					<div class="text-center text-white">
					<p class="bg-dark"><span class="btn btn-danger">ال<?=rank()?></span> <?=$_SESSION['superVisor']?></p>
					</div> 
				</div>
				<!-- Sidebar brand end  -->
				<?php 
				   function rank(){
					if($_SESSION['rankSuperVisor'] == 1)
					{
						return 'مدير';
					}
					else if($_SESSION['rankSuperVisor'] == 2)
					{
						return 'مرشح';
					}
					else
					{
						return 'مفتاح';
					}
				}
				?>
				

				<!-- Sidebar content start -->
				<div class="sidebar-content">

					<!-- sidebar menu start -->
					<div class="sidebar-menu">
						<ul>
                            
						 <li class="header-menu ">
                         <?php 


                            function activeUrl($file){
                                $url = $_SERVER['REQUEST_URI'];
                               
                                if(strpos($url,$file))
                                {
                                  return true;
                                }
                                return false;
                              }
                        

                         
                            ?>

                            </li> 

                           
							

                            <?php 
                               if($_SESSION['rankSuperVisor'] == 3)
							   {
								?>
								 <li class="<?= activeUrl('index.php') ? 'active-page-link': ''?>">
								<a href="index.php" >
									<i class="icon-home"></i>
									<span class="menu-text">الرئيسية</span>
								</a>
							    </li>

  <li class="nav-item  <?= activeUrl('daman.php') ? 'active-page-link': ''?>  ">
            <a class="nav-link" href="daman.php?master=<?=$_SESSION['idSuperVisor']?>">
			<i class="icon-home"></i>
                <span>المتعهدون</span>
            </a>
        </li>
      
      	<li class="<?= activeUrl('statisticskey.php') ? 'active-page-link': ''?>">
								<a href="statisticskey.php" >
									<i >
									<img src="assets/img/immigration.png" width="30" alt="">

									</i>
									<span class="menu-text">الاحصائيات</span>
								</a>
							</li>
							
								<?php 
								}
                               if($_SESSION['rankSuperVisor'] == 2)
                               {
                                ?>
                                  <li class="<?= activeUrl('index.php') ? 'active-page-link': ''?>">
								<a href="index.php" >
									<i class="icon-home"></i>
									<span class="menu-text">الرئيسية</span>
								</a>
							    </li>

								

								
                                <li class="<?= activeUrl('show_condidate.php') ? 'active-page-link': ''?>">
								<a href="show_condidate.php?master=<?=$_SESSION['idSuperVisor']?>" >
									<i class="icon-vpn_key"></i>
									<span class="menu-text">المفاتيح</span>
								</a>
							</li>

						
                            <li class="<?= activeUrl('show_damans.php') ? 'active-page-link': ''?>">
								<a href="show_damans.php?master=<?=$_SESSION['idSuperVisor']?>" >
									<i >
										<img src="assets/img/support.png"  width="30"  alt="">
									</i>
									<span class="menu-text">المتعهدون</span>
								</a>
							</li>

							
						


                            <li class="<?= activeUrl('mandub.php') ? 'active-page-link': ''?>">
								<a href="mandub.php" >
									<i>
									<img src="assets/img/attend.png" width="30" alt="">

									</i>
									<span class="menu-text">المحضرون</span>
								</a>
							</li>

							
                            <li class="<?= activeUrl('madmen.php') ? 'active-page-link': ''?>">
								<a href="madmen.php" >
									<i>
									<img src="assets/img/peoples.png" width="30" alt="">

									</i>
									<span class="menu-text">المضامين</span>
								</a>
							</li>
							
                            <li class="<?= activeUrl('attend.php') ? 'active-page-link': ''?>">
								<a href="attend.php" >
									<i >
									<img src="assets/img/immigration.png" width="30" alt="">

									</i>
									<span class="menu-text">الحضور</span>
								</a>
							</li>

									<li class="<?= activeUrl('statistics.php') ? 'active-page-link': ''?>">
								<a href="statistics.php" >
									<i >
									<img src="assets/img/statistics.png" width="35" alt="">

									</i>
									<span class="menu-text">الاحصائيات</span>
								</a>
							</li>

							
						
							

                                <?php 
                               }
                             if($_SESSION['rankSuperVisor'] == 1)
                             {
                             ?>

                             



                            <li class="<?= activeUrl('index.php') ? 'active-page-link': ''?>">
								<a href="index.php" >
									<i class="icon-home"></i>
									<span class="menu-text">الرئيسية</span>
								</a>
							</li>

							<li class="<?= activeUrl('setting.php') ? 'active-page-link': ''?>">
								<a href="setting.php" >
									<i class="icon-settings1"></i>
									<span class="menu-text">الاعدادت</span>
								</a>
							</li>
							

							
							
                          

                          

                           

							<li class="<?= activeUrl('events.php') ? 'active-page-link': ''?>">
								<a href="events.php" >
								<i >
										<img src="assets/img/events.png"  width="35"  alt="">
									</i>
									<span class="menu-text">الاحداث</span>
								</a>
							</li>
							

                            <li class="<?= activeUrl('candidate.php') ? 'active-page-link': ''?>">
								<a href="candidate.php" > 
									<i class="icon-people"></i>
									<span class="menu-text">المرشحون</span>
								</a>
							</li>

                            <li class="<?= activeUrl('user_guide.php') ? 'active-page-link': ''?>">
								<a href="user_guide.php" >
									<i class="icon-question_answer"></i>
									<span class="menu-text">دليل المستخدم</span>
								</a>
							</li>

							<li class="<?= activeUrl('direct_to.php') ? 'active-page-link': ''?>">
								<a href="direct_to.php" >
									<i class="icon-question_answer"></i>
									<span class="menu-text">موجه الي</span>
								</a>
							</li>


                             <?php 
                             } 
                             ?>
                       


							
						
						</ul>
					</div>
					<!-- sidebar menu end -->

				</div>
				<!-- Sidebar content end -->
			</nav>