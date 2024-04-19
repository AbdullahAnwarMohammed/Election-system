<?php 
include("include/template/_header.php");
?>
    
    <!-- Sidebar wrapper start -->
    <?php  include("include/template/_sidebar.php"); ?>

    <!-- Sidebar wrapper end -->

    <!-- Page content start  -->
    <div class="page-content">

        <!-- Header start -->
        <?php 
        include("include/template/_navbar.php");
        ?>
        <!-- Header end -->

        <!-- Page header start -->
        <div class="page-header">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">المحضرون</li>
                <!-- <li class="breadcrumb-item active">Admin Dashboard</li> -->
            </ol>

            <ul class="app-actions">
                <li>
                    <a href="#" id="reportrange">
                        <span class="range-text"></span>
                        <i class="icon-chevron-down"></i>	
                    </a>
                </li>
                <li>
                    <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Print">
                        <i class="icon-print"></i>
                    </a>
                </li>
                <li>
                    <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Download CSV">
                        <i class="icon-cloud_download"></i>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Page header end -->
        
        <div class="main-container">

        <div class="row gutters">
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
							<div class="documents-section">
								<!-- Row start -->
								<div class="row no-gutters">
									<div class="col-xl-2 col-lg-2 col-md-3 col-sm-3 col-4">
										<div class="docs-type-container">
											<div class="mt-5"></div>
											<div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 100%;"><div class="docTypeContainerScroll" style="overflow: hidden; width: auto; height: 100%;">
												<div class="docs-block">
													<h5>المضلة</h5>
													<div class="doc-labels">
														<a href="#" class="active">
															<i class="icon-receipt"></i> ملفاتي
														</a>
													
													</div>
												</div>
											</div><div class="slimScrollBar" style="background: rgb(230, 236, 243); width: 4px; position: absolute; top: 0px; opacity: 0.8; display: none; border-radius: 0px; z-index: 99; left: 1px; height: 433px;"></div><div class="slimScrollRail" style="width: 4px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 0px; background: rgb(230, 236, 243); opacity: 0.2; z-index: 90; left: 1px;"></div></div>
										</div>
									</div>
									<div class="col-xl-10 col-lg-10 col-md-9 col-sm-9 col-8">
										<div class="documents-container">
											<div class="modal fade" id="addNewDocument" tabindex="-1" role="dialog" aria-labelledby="addNewDocumentLabel" aria-hidden="true">
												<div class="modal-dialog" role="document">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="addNewDocumentLabel">اضافة</h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">×</span>
															</button>
														</div>
														<div class="modal-body">
															<form class="row gutters">
																<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
																	<div class="form-group">
																		<label for="docTitle">Document Title</label>
																		<input type="text" class="form-control" id="docTitle" placeholder="Task Title">
																	</div>
																</div>
																<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
																	<div class="form-group">
																		<label for="dovType">Document Type</label>
																		<input type="text" class="form-control" id="dovType" placeholder="Task Title">
																	</div>
																</div>
																<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
																	<div class="form-group">
																		<label for="addedDate">Created On</label>
																		<div class="custom-date-input">
																			<input type="text" name="" class="form-control datepicker picker__input" id="addedDate" placeholder="10/10/2019" readonly="" aria-haspopup="true" aria-expanded="false" aria-readonly="false" aria-owns="addedDate_root"><div class="picker" id="addedDate_root" aria-hidden="true"><div class="picker__holder" tabindex="-1"><div class="picker__frame"><div class="picker__wrap"><div class="picker__box"><div class="picker__header"><div class="picker__month">February</div><div class="picker__year">2024</div><div class="picker__nav--prev" data-nav="-1" role="button" aria-controls="addedDate_table" title="Previous month"> </div><div class="picker__nav--next" data-nav="1" role="button" aria-controls="addedDate_table" title="Next month"> </div></div><table class="picker__table" id="addedDate_table" role="grid" aria-controls="addedDate" aria-readonly="true"><thead><tr><th class="picker__weekday" scope="col" title="Sunday">Sun</th><th class="picker__weekday" scope="col" title="Monday">Mon</th><th class="picker__weekday" scope="col" title="Tuesday">Tue</th><th class="picker__weekday" scope="col" title="Wednesday">Wed</th><th class="picker__weekday" scope="col" title="Thursday">Thu</th><th class="picker__weekday" scope="col" title="Friday">Fri</th><th class="picker__weekday" scope="col" title="Saturday">Sat</th></tr></thead><tbody><tr><td role="presentation"><div class="picker__day picker__day--outfocus" data-pick="1706392800000" role="gridcell" aria-label="28 January, 2024">28</div></td><td role="presentation"><div class="picker__day picker__day--outfocus" data-pick="1706479200000" role="gridcell" aria-label="29 January, 2024">29</div></td><td role="presentation"><div class="picker__day picker__day--outfocus" data-pick="1706565600000" role="gridcell" aria-label="30 January, 2024">30</div></td><td role="presentation"><div class="picker__day picker__day--outfocus" data-pick="1706652000000" role="gridcell" aria-label="31 January, 2024">31</div></td><td role="presentation"><div class="picker__day picker__day--infocus" data-pick="1706738400000" role="gridcell" aria-label="1 February, 2024">1</div></td><td role="presentation"><div class="picker__day picker__day--infocus" data-pick="1706824800000" role="gridcell" aria-label="2 February, 2024">2</div></td><td role="presentation"><div class="picker__day picker__day--infocus" data-pick="1706911200000" role="gridcell" aria-label="3 February, 2024">3</div></td></tr><tr><td role="presentation"><div class="picker__day picker__day--infocus" data-pick="1706997600000" role="gridcell" aria-label="4 February, 2024">4</div></td><td role="presentation"><div class="picker__day picker__day--infocus" data-pick="1707084000000" role="gridcell" aria-label="5 February, 2024">5</div></td><td role="presentation"><div class="picker__day picker__day--infocus" data-pick="1707170400000" role="gridcell" aria-label="6 February, 2024">6</div></td><td role="presentation"><div class="picker__day picker__day--infocus picker__day--today picker__day--highlighted" data-pick="1707256800000" role="gridcell" aria-label="7 February, 2024" aria-activedescendant="true">7</div></td><td role="presentation"><div class="picker__day picker__day--infocus" data-pick="1707343200000" role="gridcell" aria-label="8 February, 2024">8</div></td><td role="presentation"><div class="picker__day picker__day--infocus" data-pick="1707429600000" role="gridcell" aria-label="9 February, 2024">9</div></td><td role="presentation"><div class="picker__day picker__day--infocus" data-pick="1707516000000" role="gridcell" aria-label="10 February, 2024">10</div></td></tr><tr><td role="presentation"><div class="picker__day picker__day--infocus" data-pick="1707602400000" role="gridcell" aria-label="11 February, 2024">11</div></td><td role="presentation"><div class="picker__day picker__day--infocus" data-pick="1707688800000" role="gridcell" aria-label="12 February, 2024">12</div></td><td role="presentation"><div class="picker__day picker__day--infocus" data-pick="1707775200000" role="gridcell" aria-label="13 February, 2024">13</div></td><td role="presentation"><div class="picker__day picker__day--infocus" data-pick="1707861600000" role="gridcell" aria-label="14 February, 2024">14</div></td><td role="presentation"><div class="picker__day picker__day--infocus" data-pick="1707948000000" role="gridcell" aria-label="15 February, 2024">15</div></td><td role="presentation"><div class="picker__day picker__day--infocus" data-pick="1708034400000" role="gridcell" aria-label="16 February, 2024">16</div></td><td role="presentation"><div class="picker__day picker__day--infocus" data-pick="1708120800000" role="gridcell" aria-label="17 February, 2024">17</div></td></tr><tr><td role="presentation"><div class="picker__day picker__day--infocus" data-pick="1708207200000" role="gridcell" aria-label="18 February, 2024">18</div></td><td role="presentation"><div class="picker__day picker__day--infocus" data-pick="1708293600000" role="gridcell" aria-label="19 February, 2024">19</div></td><td role="presentation"><div class="picker__day picker__day--infocus" data-pick="1708380000000" role="gridcell" aria-label="20 February, 2024">20</div></td><td role="presentation"><div class="picker__day picker__day--infocus" data-pick="1708466400000" role="gridcell" aria-label="21 February, 2024">21</div></td><td role="presentation"><div class="picker__day picker__day--infocus" data-pick="1708552800000" role="gridcell" aria-label="22 February, 2024">22</div></td><td role="presentation"><div class="picker__day picker__day--infocus" data-pick="1708639200000" role="gridcell" aria-label="23 February, 2024">23</div></td><td role="presentation"><div class="picker__day picker__day--infocus" data-pick="1708725600000" role="gridcell" aria-label="24 February, 2024">24</div></td></tr><tr><td role="presentation"><div class="picker__day picker__day--infocus" data-pick="1708812000000" role="gridcell" aria-label="25 February, 2024">25</div></td><td role="presentation"><div class="picker__day picker__day--infocus" data-pick="1708898400000" role="gridcell" aria-label="26 February, 2024">26</div></td><td role="presentation"><div class="picker__day picker__day--infocus" data-pick="1708984800000" role="gridcell" aria-label="27 February, 2024">27</div></td><td role="presentation"><div class="picker__day picker__day--infocus" data-pick="1709071200000" role="gridcell" aria-label="28 February, 2024">28</div></td><td role="presentation"><div class="picker__day picker__day--infocus" data-pick="1709157600000" role="gridcell" aria-label="29 February, 2024">29</div></td><td role="presentation"><div class="picker__day picker__day--outfocus" data-pick="1709244000000" role="gridcell" aria-label="1 March, 2024">1</div></td><td role="presentation"><div class="picker__day picker__day--outfocus" data-pick="1709330400000" role="gridcell" aria-label="2 March, 2024">2</div></td></tr><tr><td role="presentation"><div class="picker__day picker__day--outfocus" data-pick="1709416800000" role="gridcell" aria-label="3 March, 2024">3</div></td><td role="presentation"><div class="picker__day picker__day--outfocus" data-pick="1709503200000" role="gridcell" aria-label="4 March, 2024">4</div></td><td role="presentation"><div class="picker__day picker__day--outfocus" data-pick="1709589600000" role="gridcell" aria-label="5 March, 2024">5</div></td><td role="presentation"><div class="picker__day picker__day--outfocus" data-pick="1709676000000" role="gridcell" aria-label="6 March, 2024">6</div></td><td role="presentation"><div class="picker__day picker__day--outfocus" data-pick="1709762400000" role="gridcell" aria-label="7 March, 2024">7</div></td><td role="presentation"><div class="picker__day picker__day--outfocus" data-pick="1709848800000" role="gridcell" aria-label="8 March, 2024">8</div></td><td role="presentation"><div class="picker__day picker__day--outfocus" data-pick="1709935200000" role="gridcell" aria-label="9 March, 2024">9</div></td></tr></tbody></table><div class="picker__footer"><button class="picker__button--today" type="button" data-pick="1707256800000" disabled="" aria-controls="addedDate">Today</button><button class="picker__button--clear" type="button" data-clear="1" disabled="" aria-controls="addedDate">Clear</button><button class="picker__button--close" type="button" data-close="true" disabled="" aria-controls="addedDate">Close</button></div></div></div></div></div></div>
																		</div>
																	</div>
																</div>
																<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
																	<div class="form-group mb-0">
																		<label for="docDetails">Document Details</label>
																		<textarea class="form-control" id="docDetails"></textarea>
																	</div>
																</div>
															</form>
														</div>
														<div class="modal-footer custom">
															<div class="left-side">
																<button type="button" class="btn btn-link danger btn-block" data-dismiss="modal">Cancel</button>
															</div>
															<div class="divider"></div>
															<div class="right-side">
																<button type="button" class="btn btn-link success btn-block">Add</button>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="documents-header">
												<h3>2024-1-1 </h3>
												<button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#addNewDocument">اضافة</button>
											</div>
											<div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 100%;"><div class="documentsContainerScroll" style="overflow: hidden; width: auto; height: 100%;">
												<div class="documents-body">
													<!-- Row start -->
													<div class="row gutters">
														<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
															<div class="doc-block">
																<div class="doc-icon">
																	<img src="assets/img/docs/zip.svg" alt="Doc Icon">
																</div>
																<div class="doc-title">App Workflow</div>
																<button class="btn btn-primary btn-lg">Download</button>
															</div>
														</div>
														<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
															<div class="doc-block">
																<div class="doc-icon">
																	<img src="assets/img/docs/pdf.svg" alt="Doc Icon">
																</div>
																<div class="doc-title">Design Document</div>
																<button class="btn btn-primary btn-lg">Download</button>
															</div>
														</div>
														<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
															<div class="doc-block">
																<div class="doc-icon">
																	<img src="assets/img/docs/doc.svg" alt="Doc Icon">
																</div>
																<div class="doc-title">Monthly Income</div>
																<button class="btn btn-primary btn-lg">Download</button>
															</div>
														</div>
														<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
															<div class="doc-block">
																<div class="doc-icon">
																	<img src="assets/img/docs/xls.svg" alt="Doc Icon">
																</div>
																<div class="doc-title">Project Budget</div>
																<button class="btn btn-primary btn-lg">Download</button>
															</div>
														</div>
														<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
															<div class="doc-block">
																<div class="doc-icon">
																	<img src="assets/img/docs/ppt.svg" alt="Doc Icon">
																</div>
																<div class="doc-title">Presentation</div>
																<button class="btn btn-primary btn-lg">Download</button>
															</div>
														</div>
														<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
															<div class="doc-block">
																<div class="doc-icon">
																	<img src="assets/img/docs/zip.svg" alt="Doc Icon">
																</div>
																<div class="doc-title">Application</div>
																<button class="btn btn-primary btn-lg">Download</button>
															</div>
														</div>
														
													</div>
													<!-- Row end -->
												</div>
											</div><div class="slimScrollBar" style="background: rgb(230, 236, 243); width: 4px; position: absolute; top: 0px; opacity: 0.8; display: none; border-radius: 0px; z-index: 99; right: 1px; height: 178.678px;"></div><div class="slimScrollRail" style="width: 4px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 0px; background: rgb(230, 236, 243); opacity: 0.2; z-index: 90; right: 1px;"></div></div>
										</div>
									</div>
								</div>
								<!-- Row end -->
							</div>
						</div>
					</div>
        </div>

    </div>
    <!-- Page content end -->
    <?php 
        include("include/template/_footer.php");
        ?>