@extends('new-dashboard.main')

@section('content')
<div class="row">
	<div class="col-lg-12 col-md-12">
		<div class="container-fluid">
			<div class="block-header">
				<h2><i class="fa fa-dashboard"></i>&nbsp;Dashboard</h2>
			</div>
			<div class="clearfix"></div><br>
			<div class="col-md-6 col-sm-12 col-xs-12">
				<div class="card">
					<div style="min-height:378px;" class="body bg-lightGrey">
						<div class="row margin-0">
							<h3>Total waktu berlangganan</h3>
							<div class="col-md-3 col-sm-3 col-xs-3">
								<h3 class="text-blue">31</h3>
								<p>Days</p>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-3">
								<h3 class="text-blue">21</h3>
								<p>Hours</p>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-3">
								<h3 class="text-blue">11</h3>
								<p>Minutes</p>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-3">
								<h3 class="text-blue">41</h3>
								<p>Seconds</p>
							</div>
						</div>
						<div class="row margin-0">
							<p>Status server<h5 class="text-blue">Normal</h5>Total waktu per akun&nbsp;<h5 class="text-blue">44 Days 7:31</h5>
								Maksimum Akun&nbsp;<h5 class="text-blue">10</h5>
							</p>
						</div>
					</div>            
				</div>
			</div>
			<div class="col-md-6 col-sm-12 col-xs-12">
				<div class="card">
					<div class="body bg-lightGrey">
						<div class="row margin-0">
							<div class="col-md-12 col-sm-4 col-xs-12">
								<div class="card resposiveText br-6 cursorActive">
									<div class="body bgBlueGreen text-center br-6">
										<button type="button" id="btnIcon" class="text-center btn  btn-circle waves-effect waves-circle waves-float">
											<i class="fa fa-plus text-white"></i>
										</button>
										<h4 class="text-white">Add IG Account</h4>
									</div>
								</div>
							</div>
							<div class="col-md-6 col-sm-4 col-xs-12">
								<div class="card resposiveText br-6 cursorActive">
									<div class="body bgGreenLight text-center br-6">
										<button type="button" id="btnIcon" class="text-center btn btn-circle waves-effect waves-circle waves-float">
											<i class="fa fa-play text-white"></i>
										</button>
										<h4 class="text-white">Play All</h4>
									</div>
								</div>
							</div>
							<div class="col-md-6 col-sm-4 col-xs-12">
								<div class="card resposiveText br-6 cursorActive">
									<div class="body bg-red text-center br-6">
										<button type="button" id="btnIcon" class="text-center btn btn-circle waves-effect waves-circle waves-float">
											<i class="fa fa-stop text-white"></i>
										</button>
										<h4 class="text-white">Stop All</h4>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="clearfix"></div><br>
			<div class="row">
				<div class="col-md-12">
					<h4><i class="fa fa-instagram"></i>&nbsp;Instagram Accounts</h4>
				</div>
				<div class="col-md-4 col-sm-12 col-xs-12">
					<div class="card">
                        <div class="header bg-cyan br-t-6">
                            <h2>
								<button type="button" class="pull-left m-r-25 iconInstaAccount btn bgBlueGreen btn-circle-lg waves-effect waves-circle waves-float">
									<i class="fa fa-user"></i>
								</button>
                                &nbsp;Instagram Name
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row">
								<div class="col-md-3 col-sm-3 col-xs-3 startStopArea">
									<center>
									<small><b>Activity</b></small>
									<img src="{{asset('/new-dashboard/images/startIcon.png')}}"class="img-responsive">
									<span class="confirmStart text-success col-teal">Started</span>
									</center>
								</div>
								<div class="col-md-3 col-sm-3 col-xs-3">
									<center>
									<small><b>Following</b></small>
									<img src="{{asset('/new-dashboard/images/followingIcon.png')}}"class="img-responsive">
									7250
									</center>
								</div>
								<div class="col-md-3 col-sm-3 col-xs-3 text=center">
									<center>
									<small><b>Followers</b></small>
									<img src="{{asset('/new-dashboard/images/followersIcon.png')}}"class="img-responsive">
									11203
									</center>
								</div>
								<div class="col-md-3 col-sm-3 col-xs-3">
									<center>
									<small style="font-size:80%;white-space: nowrap;"><b>DM Inbox</b></small>
									<img src="{{asset('/new-dashboard/images/mailIcon.png')}}" class="img-responsive">
									5
									</center>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12">
									<p class="text-center">*Proses unfollow anda akan diaktifkan
									karena jumlah following anda mencapai 7200</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 col-sm-6 col-xs-6">
									<button class="btn bg-red btn-block text-center waves-effect btnStop br-6">
										<i class="fa fa-stop"></i>&nbsp;<span>Stop</span>
									</button>
								</div>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<button class="btn bgBlueGreen btn-block text-center text-white waves-effect btnSetting br-6"><i class="fa fa-cog"></i>&nbsp;Setting</button>
								</div>
							</div>
                        </div>
                    </div>
				</div>
				<div class="col-md-4 col-sm-12 col-xs-12">
					<div class="card">
                        <div class="header bg-cyan br-t-6">
                            <h2>
								<button type="button" class="pull-left m-r-25 iconInstaAccount btn bgBlueGreen btn-circle-lg waves-effect waves-circle waves-float">
									<i class="fa fa-user"></i>
								</button>
                                &nbsp;Instagram Name
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row">
								<div class="col-md-3 col-sm-3 col-xs-3 startStopArea">
									<center>
									<small><b>Activity</b></small>
									<img src="{{asset('/new-dashboard/images/stopIcon.png')}}"class="img-responsive">
									<span class="confirmStop text-danger col-pink">Stoped</span>
									</center>
								</div>
								<div class="col-md-3 col-sm-3 col-xs-3">
									<center>
									<small><b>Following</b></small>
									<img src="{{asset('/new-dashboard/images/followingIcon.png')}}"class="img-responsive">
									7250
									</center>
								</div>
								<div class="col-md-3 col-sm-3 col-xs-3 text=center">
									<center>
									<small><b>Followers</b></small>
									<img src="{{asset('/new-dashboard/images/followersIcon.png')}}"class="img-responsive">
									11203
									</center>
								</div>
								<div class="col-md-3 col-sm-3 col-xs-3">
									<center>
									<small style="font-size:80%;white-space: nowrap;"><b>DM Inbox</b></small>
									<img src="{{asset('/new-dashboard/images/mailIcon.png')}}" class="img-responsive">
									5
									</center>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12">
									<p class="text-center">*Proses unfollow anda akan diaktifkan
									karena jumlah following anda mencapai 7200</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 col-sm-6 col-xs-6">
									<button class="btn bgGreenLight btn-block text-center waves-effect btnStart br-6">
										<i class="fa fa-play"></i>&nbsp;<span>Start</span>
									</button>
								</div>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<button class="btn bgBlueGreen btn-block text-center text-white waves-effect br-6"><i class="fa fa-cog"></i>&nbsp;Setting</button>
								</div>
							</div>
                        </div>
                    </div>
				</div>
				<div class="col-md-4 col-sm-12 col-xs-12">
					<div class="card">
                        <div class="header bg-cyan br-t-6">
                            <h2>
								<button type="button" class="pull-left m-r-25 iconInstaAccount btn bgBlueGreen btn-circle-lg waves-effect waves-circle waves-float">
									<i class="fa fa-user"></i>
								</button>
                                &nbsp;Instagram Name
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row">
								<div class="col-md-3 col-sm-3 col-xs-3 startStopArea">
									<center>
									<small><b>Activity</b></small>
									<img src="{{asset('/new-dashboard/images/startIcon.png')}}"class="img-responsive">
									<span class="confirmStart text-success col-teal">Started</span>
									</center>
								</div>
								<div class="col-md-3 col-sm-3 col-xs-3">
									<center>
									<small><b>Following</b></small>
									<img src="{{asset('/new-dashboard/images/followingIcon.png')}}"class="img-responsive">
									7250
									</center>
								</div>
								<div class="col-md-3 col-sm-3 col-xs-3 text=center">
									<center>
									<small><b>Followers</b></small>
									<img src="{{asset('/new-dashboard/images/followersIcon.png')}}"class="img-responsive">
									11203
									</center>
								</div>
								<div class="col-md-3 col-sm-3 col-xs-3">
									<center>
									<small style="font-size:80%;white-space: nowrap;"><b>DM Inbox</b></small>
									<img src="{{asset('/new-dashboard/images/mailIcon.png')}}" class="img-responsive">
									5
									</center>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12">
									<p class="text-center">*Proses unfollow anda akan diaktifkan
									karena jumlah following anda mencapai 7200</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 col-sm-6 col-xs-6">
									<button class="btn bg-red btn-block text-center waves-effect btnStop br-6">
										<i class="fa fa-stop"></i>&nbsp;<span>Stop</span>
									</button>
								</div>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<button class="btn bgBlueGreen btn-block text-center text-white waves-effect br-6"><i class="fa fa-cog"></i>&nbsp;Setting</button>
								</div>
							</div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
