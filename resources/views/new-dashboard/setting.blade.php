@extends('new-dashboard.main')

@section('content')
<script>
$(document).ready(function() {
	$(".demo-tagsinput-area").each(function(){
		$(this).resizable({
		alsoResize: $(this).find('.form-line')
		});
	});
	activateNouislide();
});	
</script>
<div class="row">
	<div class="col-lg-12 col-md-12">
		<div class="container-fluid">
			<div class="block-header">
				<h2><i class="fa fa-cog"></i>&nbsp;User Settings</h2>
			</div>
			<div class="clearfix"></div><br>
			<div class="row">
			<div class="col-md-5 col-sm-12 col-xs-12">
				<h5>Profile</h5>
				<div class="card h-l-300">
					<div class=" h-l-300 body bg-lightGrey">
						<div class="row h-l-300">
							<div class="col-md-8 col-sm-12 col-xs-12 ">
								<div class="row">
									<div class="col-md-3 col-sm-3 col-xs-3">
										<img src="../../celebGramme/images/user.png" class="m-t-20 img-circle">
									</div>
									<div class="col-md-9 col-sm-9 col-xs-9 padding-0 startStopArea">
										<p style="white-space: nowrap;" class="padding-0">
										<h5 class="text-primary">&nbsp;Instagram Name</h5>
											<small>Status Activity &nbsp;: &nbsp;<span class="text-success col-teal">Started</span><br>
												Total waktu berlangganan<br/>
												<b class="text-primary">30 Hari 20:31</b>
											</small>
										</p>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6 col-sm-6 col-xs-12">
										<button class="btn bg-red btn-block text-center waves-effect btnStop br-6">
											<i class="fa fa-stop"></i>&nbsp;<span>Stop</span>
										</button>
									</div>
									<div class="col-md-6 col-sm-6 col-xs-6">
										<button class="btn btnAutoHide bg-cyan btn-block text-center text-white waves-effect br-6"><i class="fa fa-save"></i>&nbsp;Save</button>
									</div>
								</div>
							</div>
							<div class="col-md-4 col-sm-12 col-xs-12 bl-blue">
								<div class="row padding-0">
									<div class="col-md-9 col-sm-9 col-xs-9 padding-0">
										<small style="font-size:11px;white-space:nowrap;">Followers Saat Join&nbsp;:&nbsp;</small>
									</div>
									<div class="col-md-3 col-sm-3 col-xs-3 padding-0 text-center">
										0
									</div>
								</div>
								<div class="row padding-0">
									<div class="col-md-9 col-sm-9 col-xs-9 padding-0">
										<small style="font-size:11px;white-space:nowrap;">Following Saat Join&nbsp;:&nbsp;</small>
									</div>
									<div class="col-md-3 col-sm-3 col-xs-3 padding-0 text-center">
										12
									</div>
								</div>
								<div class="row padding-0">
									<div class="col-md-9 col-sm-9 col-xs-9 padding-0">
										<small style="font-size:11px;white-space:nowrap;">Followers Hari ini&nbsp;:&nbsp;</small>
									</div>
									<div class="col-md-3 col-sm-3 col-xs-3 padding-0 text-center">
										5131
									</div>
								</div>
								<div class="row padding-0">
									<div class="col-md-9 col-sm-9 col-xs-9 padding-0">
										<small style="font-size:11px;white-space:nowrap;">Following Hari ini&nbsp;:&nbsp;</small>
									</div>
									<div class="col-md-3 col-sm-3 col-xs-3 padding-0 text-center">
										212
									</div>
								</div>
							</div>
						</div>
					</div>            
				</div>
			</div>
			<div class="col-md-7 col-sm-12 col-xs-12">
				<h5>&nbsp;</h5>
				<div class="card h-l-300">
					<div class="h-l-300 body bg-lightGrey padding-0">
						<div class="row">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<b>Tambah waktu langganan </b>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-6 ">
								<a class="btn btn-link btn-block bg-cyan br-6 text-center" data-toggle="tab" href="#normal">Normal</a>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-6 text-white">
								<a class="btn btn-link btn-block bgBlueGreen text-white br-6 text-center" data-toggle="tab" href="#extra">Extra</a>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="tab-content">
								  <div id="normal" class="tab-pane fade in active">
									  <div class="row">
										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="card resposiveText br-6">
												<div class="body bgBlueGreen text-center br-6">
													<h3 class="text-white">30<br><small class="text-white">Days</small></h3>
													<h4 class="text-white" style="white-space:nowrap;">Rp. 195.000,-</h4>
													<button class="btn btn-sm bgGreenLight text-white br-6">Buy Now</button>
												</div>
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="card resposiveText br-6">
												<div class="body bgBlueGreen text-center br-6">
													<h3 class="text-white">60<br><small class="text-white">Days</small></h3>
													<h4 class="text-white" style="white-space:nowrap;">Rp. 295.000,-</h4>
													<button class="btn btn-sm bgGreenLight text-white br-6">Buy Now</button>
												</div>
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="card resposiveText br-6">
												<div class="body bgBlueGreen text-center br-6">
													<h3 class="text-white">90<br><small class="text-white">Days</small></h3>
													<h4 class="text-white" style="white-space:nowrap;">Rp. 395.000,-</h4>
													<button class="btn btn-sm bgGreenLight text-white br-6">Buy Now</button>
												</div>
											</div>
										</div>
									  </div>
								  </div>
								  <div id="extra" class="tab-pane">
									<div class="row">
										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="card resposiveText br-6">
												<div class="body bgBlueGreen text-center br-6">
													<h3 class="text-white">180<br><small class="text-white">Days</small></h3>
													<h4 class="text-white" style="white-space:nowrap;">Rp. 695.000,-</h4>
													<button class="btn btn-sm bgGreenLight text-white br-6">Buy Now</button>
												</div>
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="card resposiveText br-6">
												<div class="body bgBlueGreen text-center br-6">
													<h3 class="text-white">270<br><small class="text-white">Days</small></h3>
													<h4 class="text-white" style="white-space:nowrap;">Rp. 995.000,-</h4>
													<button class="btn btn-sm bgGreenLight text-white br-6">Buy Now</button>
												</div>
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="card resposiveText br-6">
												<div class="body bgBlueGreen text-center br-6">
													<h3 class="text-white">360<br><small class="text-white">Days</small></h3>
													<h4 class="text-white" style="white-space:nowrap;">Rp. 1285.000,-</h4>
													<button class="btn btn-sm bgGreenLight text-white br-6">Buy Now</button>
												</div>
											</div>
										</div>
									</div>
								  </div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			</div>
			<div class="clearfix"></div><br>
			<div class="row tabButton">
				<div class="col-md-4 col-sm-8 col-xs-12 padding-0">
					<div class="col-md-6 col-sm-6 col-xs-6 padding-0">
						<button class="btn btn-sm bg-cyan btn-block btnGeneral br-6" data-toggle="tab" href="#general"><i class="fa fa-cog"></i>&nbsp;General</button>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-6 padding-0">
						<button class="btn btn-sm bg-grey btn-block btnMessage br-6"  style="font-size:inherit;"data-toggle="tab" href="#message"><i class="fa fa-envelope text-white"></i>&nbsp;Direct Message</button>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="tab-content">
					<div id="general" class="tab-pane fade in active">
						<div class="clearfix"></div><br>
						<div class="row">
							<div class="col-md-12">
								<div class="card m-b-0">
									<div class="body bg-lightGrey margin-0 padding-0">
										<div class="row">
											<div class="col-md-6 col-sm-6 col-xs-12">
												<div class="card m-b-0" style="background:transparent;box-shadow:none;">
													<div class="header">
														<h2>
															Global Settings &nbsp;<img class="cursorActive" src="../../celebGramme/images/questionIcon.png">
														</h2>
													</div>
													<div class="body" style="background:transparent;box-shadow:none;">
														<div class="row btnGroupOO">
															<div class="col-md-6 col-sm-4 col-xs-4">
																<b>Choose Settings</b>
															</div>
															<div class="col-md-3 col-sm-4 col-xs-4 padding-0">
																<button class="btn btn-block bg-grey btnOff">Full Auto</button>
															</div>
															<div class="col-md-3 col-sm-4 col-xs-4 padding-0">
																<button class="btn btn-block bg-cyan btnOn">Manual</button>
															</div>
														</div>
														<div class="row">
															<div class="col-md-6 col-sm-6 col-xs-6">
																<b>Activity Speed</b>
															</div>
															<div class="col-md-6 col-sm-6 col-xs-6 padding-0">
																<div class="cursorActive" id="rating_slider">
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<div class="card m-b-0" style="background:transparent;box-shadow:none;">
													<div class="header">
														<h2>
															Auto Like Settings
														</h2>
													</div>
													<div class="body" style="background:transparent;box-shadow:none;">
														<div class="row btnGroupOO">
															<div class="col-md-6 col-sm-6 col-xs-6">
																<b>Auto Like My Post</b>
															</div>
															<div class="col-md-3 col-sm-3 col-xs-3 padding-0">
																<button class="btn btn-block bg-cyan btnOn">ON</button>
															</div>
															<div class="col-md-3 col-sm-3 col-xs-3 padding-0">
																<button class="btn btn-block bg-grey btnOff">OF</button>
															</div>
														</div>
														<div class="row btnGroupOO">
															<div class="col-md-6 col-sm-6 col-xs-6">
																<b>Auto Like My Follower</b>
															</div>
															<div class="col-md-3 col-sm-3 col-xs-3 padding-0">
																<button class="btn btn-block bg-cyan btnOn">ON</button>
															</div>
															<div class="col-md-3 col-sm-3 col-xs-3 padding-0">
																<button class="btn btn-block bg-grey btnOff">OF</button>
															</div>
														</div>
														<div class="row">
															<div class="col-md-offset-6 col-sm-offset-6 col-xs-offset-6 col-sm-6 col-xs-6">
																<div class="row">
																	<div class="col-md-6 col-sm-6 col-xs-6">
																		<input name="group1" type="radio" class="with-gap radio-col-light-blue" id="radio_3">
																		<label for="radio_3">25%</label>
																	</div>
																	<div class="col-md-6 col-sm-6 col-xs-6">
																		<input name="group1" type="radio" id="radio_4" class="with-gap radio-col-light-blue">
																		<label for="radio_4">50%</label>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12 col-sm-12 col-xs-12">
												<div class="card m-b-0" style="background:transparent;box-shadow:none;">
													<div class="header">
														<h2>
															Black List & White List &nbsp;<img class="cursorActive" src="../../celebGramme/images/questionIcon.png">
														</h2>
													</div>
													<div class="body" style="background:transparent;box-shadow:none;">
														<div class="row">
															<div class="col-md-3 col-sm-3 col-xs-3">
																<b>Black List</b>
															</div>
															<div class="col-md-4 col-sm-9 col-xs-9">
																<div class="row btnGroupOO">
																	<div class="col-md-3 col-sm-3 col-xs-3 padding-0">
																		<button class="btn btn-block bg-cyan btnOn">ON</button>
																	</div>
																	<div class="col-md-3 col-sm-3 col-xs-3 padding-0">
																		<button class="btn btn-block bg-grey btnOff">OF</button>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-md-12 col-sm-12 col-xs-12">
																<div class="form-group demo-tagsinput-area br-6">
																	<div class="form-line">
																		<input type="text" class="form-control" data-role="tagsinput" value="Amsterdam,Washington,Sydney,Beijing,Cairo">
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-md-3 col-sm-3 col-xs-3">
																<b>White List</b>
															</div>
															<div class="col-md-4 col-sm-9 col-xs-9">
																<div class="row btnGroupOO">
																	<div class="col-md-3 col-sm-3 col-xs-3 padding-0">
																		<button class="btn btn-block bg-cyan btnOn">ON</button>
																	</div>
																	<div class="col-md-3 col-sm-3 col-xs-3 padding-0">
																		<button class="btn btn-block bg-grey btnOff">OF</button>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-md-12 col-sm-12 col-xs-12">
																<div class="form-group demo-tagsinput-area">
																	<div class="form-line">
																		<input type="text" class="form-control" data-role="tagsinput" value="Amsterdam,Washington,Sydney,Beijing,Cairo">
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										
										<div class="row">
											<div class="col-md-12 col-sm-12 col-xs-12">
												<div class="card m-b-0 m-t--50" style="background:transparent;box-shadow:none;">
													<div class="header">
														<h2>
															Follow &nbsp;<img class="cursorActive" src="../../celebGramme/images/questionIcon.png">
														</h2>
													</div>
													<div class="body" style="background:transparent;box-shadow:none;">
														<div class="row">
															<div class="col-md-4 col-sm-4 col-xs-4">
																<b>Status</b>
															</div>
															<div class="col-md-3 col-sm-8 col-xs-8">
																<div class="row btnGroupOO">
																	<div class="col-md-6 col-sm-6 col-xs-6 padding-0">
																		<button class="btn btn-block bg-cyan btnOn">ON</button>
																	</div>
																	<div class="col-md-6 col-sm-6 col-xs-6 padding-0">
																		<button class="btn btn-block bg-grey btnOff">OF</button>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-md-4 col-sm-4 col-xs-4">
																<b>Activity</b>
															</div>
															<div class="col-md-3 col-sm-8 col-xs-8">
																<div class="row btnGroupOO">
																	<div class="col-md-6 col-sm-6 col-xs-6 padding-0">
																		<button class="btn btn-block bg-cyan btnOn">Follow</button>
																	</div>
																	<div class="col-md-6 col-sm-6 col-xs-6 padding-0">
																		<button class="btn btn-block bg-grey btnOff">Unfollow</button>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-md-4 col-sm-4 col-xs-4">
																<b>Follow Source</b>
															</div>
															<div class="col-md-3 col-sm-8 col-xs-8">
																<select class="form-control">
																	<option value=""> Please select </option>
																	<option value="10">#Hashtags</option>
																	<option value="20">20</option>
																</select>
															</div>
														</div>
														<div class="row">
															<div class="col-md-6 col-sm-6 col-xs-12">
																<div class="row">
																	<div class="col-md-4 col-sm-3 col-xs-12">
																		<b>Don't Follow Private User</b>
																	</div>
																	<div class="col-md-3 col-sm-9 col-xs-12">
																		<div class="row btnGroupOO">
																			<div class="col-md-6 col-sm-6 col-xs-6 padding-0">
																				<button class="btn btn-block bg-cyan btnOn">ON</button>
																			</div>
																			<div class="col-md-6 col-sm-6 col-xs-6 padding-0">
																				<button class="btn btn-block bg-grey btnOff">Off</button>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="col-md-6 col-sm-6 col-xs-12">
																<div class="row">
																	<div class="col-md-4 col-sm-4 col-xs-12">
																		<b>Don't Follow Same User</b>
																	</div>
																	<div class="col-md-3 col-sm-8 col-xs-12">
																		<div class="row btnGroupOO">
																			<div class="col-md-6 col-sm-6 col-xs-6 padding-0">
																				<button class="btn btn-block bg-cyan btnOn">ON</button>
																			</div>
																			<div class="col-md-6 col-sm-6 col-xs-6 padding-0">
																				<button class="btn btn-block bg-grey btnOff">Of</button>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										
										<div class="row">
											<div class="col-md-12 col-sm-12 col-xs-12">
												<div class="card m-b-0 m-t--50" style="background:transparent;box-shadow:none;">
													<div class="header">
														<h2>
															Media Source &nbsp;: &nbsp; #Hashtags &nbsp;<img class="cursorActive" src="../../celebGramme/images/questionIcon.png">
														</h2>
													</div>
													<div class="body" style="background:transparent;box-shadow:none;">
														<div class="row">
															<div class="col-md-12 col-sm-12 col-xs-12">
																<div class="form-group demo-tagsinput-area">
																	<div class="form-line">
																		<input type="text" class="form-control" data-role="tagsinput" value="Amsterdam,Washington,Sydney,Beijing,Cairo">
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										
										<div class="row">
											<div class="col-md-12 col-sm-12 col-xs-12">
												<div class="card m-b-0 m-t--50" style="background:transparent;box-shadow:none;">
													<div class="header">
														<h2>
															Like &nbsp; & &nbsp; Comment &nbsp;<img class="cursorActive" src="../../celebGramme/images/questionIcon.png">
														</h2>
													</div>
													<div class="body" style="background:transparent;box-shadow:none;">
														<div class="row">
															<div class="col-md-4 col-sm-4 col-xs-4">
																<b>Like</b>
															</div>
															<div class="col-md-3 col-sm-8 col-xs-8">
																<div class="row btnGroupOO">
																	<div class="col-md-6 col-sm-6 col-xs-6 padding-0">
																		<button class="btn btn-block bg-cyan btnOn">ON</button>
																	</div>
																	<div class="col-md-6 col-sm-6 col-xs-6 padding-0">
																		<button class="btn btn-block bg-grey btnOff">OF</button>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-md-4 col-sm-4 col-xs-5">
																<b>Comment</b>
															</div>
															<div class="col-md-3 col-sm-8 col-xs-7">
																<div class="row btnGroupOO">
																	<div class="col-md-6 col-sm-6 col-xs-6 padding-0">
																		<button class="btn btn-block bg-cyan btnOn">ON</button>
																	</div>
																	<div class="col-md-6 col-sm-6 col-xs-6 padding-0">
																		<button class="btn btn-block bg-grey btnOff">OF</button>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-md-12 col-sm-12 col-xs-12">
																<div class="row">
																	<div class="col-md-6 col-sm-6 col-xs-6">
																		<label>Comments</label><br>
																		<label>Penjelasan fitur spin comment</label>
																	</div>
																	<div class="col-md-6 col-sm-6 col-xs-6">
																		<label>Copy contoh spin comment</label><br>
																		<label>Petunjuk tanda baca spin comment</label>
																	</div>
																</div>
																<textarea class="form-control"></textarea>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										
										<div class="row">
											<div class="col-md-4 col-sm-6 col-xs-12">
												<div class="row">
													<div class="col-md-6 col-sm-6 col-xs-6">
														<button class="btn btn-block bg-cyan"><i class="fa fa-save"></i>&nbsp;Save</button>
													</div>
													<div class="col-md-6 col-sm-6 col-xs-6">
														<button class="btn btn-block bgGreenLight "><i class="fa fa-play"></i>&nbsp;Start</button>
													</div>
												</div>
											</div>
										</div>
										
										
									</div>
								</div>
							</div>
						</div>
					</div>
					<div id="message" class="tab-pane fade">
						<div class="clearfix"></div><br>
						<div class="row">
							<div class="col-md-12">
								<div class="card m-b-0">
									<div class="body bg-lightGrey margin-0 padding-0">
										<div class="row">
											<div class="col-md-12 col-sm-12 col-xs-12">	
												<div class="btnTab">
													<div class="col-md-6 col-sm-12 col-xs-12 padding-0">
														<div class="col-md-4 col-sm-4 col-xs-4 padding-0">
															<button class="btn btn-sm bg-cyan btn-block br-6 btnDmIn" data-toggle="tab" href="#DMInbox"><i class="fa fa-envelope"></i>&nbsp;<small class="text-white">DM Inbox</small></button>
														</div>
														<div class="col-md-4 col-sm-4 col-xs-4 padding-0">
															<button class="btn btn-sm bg-grey btn-block br-6 btnDmRe"  style="font-size:inherit;"data-toggle="tab" href="#DMRequest"><i class="fa fa-envelope text-white"></i>&nbsp;<small class="text-white">DM Request</small></button>
														</div>
														<div class="col-md-4 col-sm-4 col-xs-4 padding-0">
															<button class="btn btn-sm bg-grey btn-block br-6 btnDmAu"  style="font-size:inherit;"data-toggle="tab" href="#DMAuto"><i class="fa fa-envelope text-white"></i>&nbsp;<small class="text-white">DM Auto Reply</small></button>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12 col-sm-12 col-xs-12">
												<div class="tab-content">
													<div id="DMInbox" class="tab-pane fade in active">
														<div class="col-md-12 col-sm-12 col-xs-12">
															<div class="clearfix"></div><br/>
															<div class="row">
																<div style="min-height:100px;" class="col-md-5 col-sm-12 col-xs-12 bg-white br-6">
																	<h2 style="color:#333;font-weight:200;">
																		<button type="button" style="min-width:80px;height:80px;"class="pull-left m-t-25 iconInstaAccount btn bgBlueGreen btn-circle-lg waves-effect waves-circle waves-float">
																			<i style="font-size:24px;" class="fa fa-user text-white"></i>
																		</button>
																		&nbsp;Ig_account name
																	</h2>
																	<small style="color:#333;">Hi text here</small>
																</div>
																<div class="col-md-7 col-sm-12 col-xs-12">
																	<div class="row">
																		<div class="col-md-4 col-sm-4 col-xs-4">
																			<div class="br-6">
																				<div style="min-height:100px;" class="body bg-white br-6 text-center">
																					<b class="text-primary">Saturday<br/>21/4/2016</b>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-4 col-sm-4 col-xs-4">
																			<div class="br-6">
																				<div style="min-height:100px;" class="body bg-cyan br-6 text-center">
																					<i class="fa fa-mail-reply fa-2x"></i><br/>
																					<b class="text-white">Replay</b>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-4 col-sm-4 col-xs-4">
																			<div class="br-6">
																				<div style="min-height:100px;" class="body bg-red br-6 text-center">
																					<i class="material-icons">delete</i><br/>
																					<b class="text-white">Delete</b>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div style="min-height:100px;" class="col-md-5 col-sm-12 col-xs-12 bg-white br-6">
																	<h2 style="color:#333; font-weight:200;">
																		<button type="button" style="min-width:80px;height:80px;"class="pull-left m-t-25 iconInstaAccount btn bgBlueGreen btn-circle-lg waves-effect waves-circle waves-float">
																			<i style="font-size:24px;" class="fa fa-user text-white"></i>
																		</button>
																		&nbsp;Ig_account name
																	</h2>
																	<small style="color:#333;">Hi text here</small>
																</div>
																<div class="col-md-7 col-sm-12 col-xs-12">
																	<div class="row">
																		<div class="col-md-4 col-sm-4 col-xs-4">
																			<div class="br-6">
																				<div style="min-height:100px;" class="body bg-white br-6 text-center">
																					<b class="text-primary">Saturday<br/>21/4/2016</b>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-4 col-sm-4 col-xs-4">
																			<div class="br-6">
																				<div style="min-height:100px;" class="body bg-cyan br-6 text-center">
																					<i class="fa fa-mail-reply fa-2x"></i><br/>
																					<b class="text-white">Replay</b>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-4 col-sm-4 col-xs-4">
																			<div class="br-6">
																				<div style="min-height:100px;" class="body bg-red br-6 text-center">
																					<i class="fa fa-trash fa-2x"></i><br/>
																					<b class="text-white">Delete</b>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div id="DMRequest" class="tab-pane">
														<div class="col-md-12 col-sm-12 col-xs-12">
															<div class="clearfix"></div><br/>
															<div class="row">
																<div style="min-height:100px;" class="col-md-5 col-sm-12 col-xs-12 bg-white br-6">
																	<h2 style="color:#333;font-weight:200;">
																		<button type="button" style="min-width:80px;height:80px;"class="pull-left m-t-25 iconInstaAccount btn bgBlueGreen btn-circle-lg waves-effect waves-circle waves-float">
																			<i style="font-size:24px;" class="fa fa-user text-white"></i>
																		</button>
																		&nbsp;Ig_account name
																	</h2>
																	<small style="color:#333;">Request For</small>
																</div>
																<div class="col-md-7 col-sm-12 col-xs-12">
																	<div class="row">
																		<div class="col-md-4 col-sm-4 col-xs-4">
																			<div class="br-6">
																				<div style="min-height:100px;" class="body bg-white br-6 text-center">
																					<b class="text-primary">Saturday<br/>21/4/2016</b>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-4 col-sm-4 col-xs-4">
																			<div class="br-6">
																				<div style="min-height:100px;" class="body bgGreenLight br-6 text-center">
																					<i class="fa fa-check fa-2x"></i><br/>
																					<b class="text-white">Accept</b>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-4 col-sm-4 col-xs-4">
																			<div class="br-6">
																				<div style="min-height:100px;" class="body bg-red br-6 text-center">
																					<i class="fa fa-times fa-2x"></i><br/>
																					<b class="text-white">Decline</b>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div style="min-height:100px;" class="col-md-5 col-sm-12 col-xs-12 bg-white br-6">
																	<h2 style="color:#333;font-weight:200;">
																		<button type="button" style="min-width:80px;height:80px;"class="pull-left m-t-25 iconInstaAccount btn bgBlueGreen btn-circle-lg waves-effect waves-circle waves-float">
																			<i style="font-size:24px;" class="fa fa-user text-white"></i>
																		</button>
																		&nbsp;Ig_account name
																	</h2>
																	<small style="color:#333;">Request For</small>
																</div>
																<div class="col-md-7 col-sm-12 col-xs-12">
																	<div class="row">
																		<div class="col-md-4 col-sm-4 col-xs-4">
																			<div class="br-6">
																				<div style="min-height:100px;" class="body bg-white br-6 text-center">
																					<b class="text-primary">Saturday<br/>21/4/2016</b>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-4 col-sm-4 col-xs-4">
																			<div class="br-6">
																				<div style="min-height:100px;" class="body bgGreenLight br-6 text-center">
																					<i class="fa fa-check fa-2x"></i><br/>
																					<b class="text-white">Accept</b>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-4 col-sm-4 col-xs-4">
																			<div class="br-6">
																				<div style="min-height:100px;" class="body bg-red br-6 text-center">
																					<i class="fa fa-times fa-2x"></i><br/>
																					<b class="text-white">Decline</b>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
													
													<div id="DMAuto" class="tab-pane">
														<div class="col-md-12 col-sm-12 col-xs-12">
															<div class="clearfix"></div><br/>
															<span>Cara setting auto reply</span>&nbsp;<i class="fa fa-question-circle"></i>
															<br><br>
															<textarea class="form-control"></textarea>
															<br>
															<button class="btn btn-md br-6 bg-cyan pull-left"><i class="fa fa-save"></i>&nbsp;Save</button>
														</div>
													</div>
													
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<section id="userSetScript">
		<script type="text/javascript" src="{{ asset('/new-dashboard/js/jquery-ui.js') }}"></script>

		<!-- Noui Js -->
		<script type="text/javascript" src="{{ asset('/new-dashboard/plugins/nouislider/nouislider.js') }}"></script>
	
		<!-- Input Mask Plugin Js -->
		<script type="text/javascript" src="{{ asset('/new-dashboard/plugins/jquery-inputmask/jquery.inputmask.bundle.js') }}"></script>

	
		<!-- Bootstrap Tags Input Plugin Js -->
		<script type="text/javascript" src="{{ asset('/new-dashboard/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js') }}"></script>
</section>
	
@endsection
