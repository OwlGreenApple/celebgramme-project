@extends('new-dashboard.main')

@section('content')
		<div class="row">
			<div class="col-md-8 col-sm-8 col-xs-12">
				<div class="container-fluid">
					<div class="block-header">
						<div class="col-md-6 col-sm-12 col-xs-12 padding-0 text-left">
							<h2 class="pull-left" style="font-size:15pt;">Hi, {{$user->fullname}} </h2>
						</div>
						<div class="col-md-6 col-sm-12 col-xs-12 padding-0">
							<a href="{{url('dashboard')}}"><button id="btnToDashboard" class="btn btn-block waves-effect br-6"><span>Masuk ke Dashboard</span>&nbsp;&nbsp;<i class="material-icons text-white">input</i>&nbsp;</button></a>
						</div>
					</div>
					<div class="clearfix"></div><br/>
					
					<div class="row">
						<div class="col-md-12">
							<div class="tab-content">
							<!--
								<div role="tabpanel" class="tab-pane animated flipInX active" id="home_animation_1">
									<iframe src="//www.youtube.com/embed/ePbKGoIGAXY" style="width:100%;border:0px;">
									</iframe>
								</div>
								-->
								<div role="tabpanel" class="tab-pane animated flipInX" id="profile_animation_1">
									<iframe src="https://www.youtube.com/embed/dXoCVy43AIM" style="width:100%;border:0px;">
									</iframe>
								</div>
								<div role="tabpanel" class="tab-pane animated flipInX" id="messages_animation_1">
									<iframe src="https://www.youtube.com/embed/6vrUD1Uo1xE" style="width:100%;border:0px;">
									</iframe>
								</div>
<div class="embed-responsive embed-responsive-16by9" id="home_animation_1">
  <iframe class="embed-responsive-item" src="//www.youtube.com/embed/ePbKGoIGAXY"></iframe>
</div>								
								<div role="tabpanel" class="tab-pane animated flipInX" id="settings_animation_1">
									<b>Settings Content</b>
									<p>
										Lorem ipsum dolor sit amet, ut duo atqui exerci dicunt, ius impedit mediocritatem an. Pri ut tation electram moderatius.
										Per te suavitate democritum. Duis nemore probatus ne quo, ad liber essent
										aliquid pro. Et eos nusquam accumsan, vide mentitum fabellas ne est, eu munere
										gubergren sadipscing mel.
									</p>
								</div>
							</div>
						</div>
					</div>
					<div class="clearfix"></div><br/>
					<div class="row" role="tablist" id="videoTabs">
						<div class="col-md-4 col-sm-6 col-xs-6" role="presentation">
							<a href="#home_animation_1" data-toggle="tab" aria-expanded="true" class="info-box waves-effect btn-video active">
								<div class="icon">
									<img src="{{asset('/new-dashboard/images/filmIcon.png')}}" class="filmIcon">
								</div>
								<div class="content">
									<p class="text-white">Tutorial Video</p>
								</div>
							</a>
						</div>
						<div class="col-md-4 col-sm-6 col-xs-6" role="presentation">
							<a href="#profile_animation_1" data-toggle="tab" aria-expanded="false" class="info-box waves-effect btn-video">
								
								<div class="icon">
									<img src="{{asset('/new-dashboard/images/filmIcon.png')}}" class="filmIcon">
								</div>
								<div class="content">
									<p class="text-white">Tutorial Video</p>
								</div>
							</a>
						</div>
						<div class="col-md-4 col-sm-6 col-xs-6" role="presentation">
							<a href="#messages_animation_1" data-toggle="tab" aria-expanded="false" class="info-box waves-effect btn-video">
								
								<div class="icon">
									<img src="{{asset('/new-dashboard/images/filmIcon.png')}}" class="filmIcon">
								</div>
								<div class="content">
									<p class="text-white">Tutorial Video</p>
								</div>
							</a>
						</div>
						
						<!--
						<div class="col-md-4 col-sm-6 col-xs-6" role="presentation">
							<a href="#settings_animation_1" data-toggle="tab" aria-expanded="false" class="info-box waves-effect btn-video">
								<div class="icon">
									<img src="{{asset('/new-dashboard/images/filmIcon.png')}}" class="filmIcon">
								</div>
								<div class="content">
									<p class="text-white">Tutorial Video</p>
								</div>
							</a>
						</div>
						<div class="col-md-4 col-sm-6 col-xs-6" role="presentation">
							<a href="#messages_animation_1" data-toggle="tab" aria-expanded="false" class="info-box waves-effect btn-video">
								
								<div class="icon">
									<img src="{{asset('/new-dashboard/images/filmIcon.png')}}" class="filmIcon">
								</div>
								<div class="content">
									<p class="text-white">Tutorial Video</p>
								</div>
							</a>
						</div>
						<div class="col-md-4 col-sm-6 col-xs-6" role="presentation">
							<a href="#settings_animation_1" data-toggle="tab" aria-expanded="false" class="info-box waves-effect btn-video">
								
								<div class="icon">
									<img src="{{asset('/new-dashboard/images/filmIcon.png')}}" class="filmIcon">
								</div>
								<div class="content">
									<p class="text-white">Tutorial Video</p>
								</div>
							</a>
						</div>
						-->
					</div>
				</div>
			</div>
			<div class="col-md-3 col-sm-3 col-xs-6" id="wrapCntentPromotion">
				<div class="row clearfix">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="card margin-0 br-t-6">
							<div class="header bg-themeDefault text-center br-t-6">
								<h2>Promotions</h2>
							</div>
							<div class="">
								<div style="padding:0;"class="card text-center margin-0 col-md-12 col-sm-12 col-xs-12">
									<a href="https://celebpost.in" target="_blank"><img src="{{asset('/images/promo-1.jpg')}}"class="img-responsive" ></a>
								</div>
								<div style="padding:0;" class="card text-center margin-0 col-md-6 col-sm-6 col-xs-6">
									<a href="http://line.me/ti/p/@vyd1834h" target="_blank"><img src="{{asset('/images/promo-2.jpg')}}"class="img-responsive" ></a>
								</div>
								<div style="padding:0;" class="card text-center col-md-6 col-xs-6 col-sm-6 margin-0">
									<a href="http://m.me/celebgramme" target="_blank"><img src="{{asset('/images/promo-3.jpg')}}"class="img-responsive" ></a>
								</div>
								<div style="padding:0;"  class="card text-center col-md-12 col-sm-12 col-xs-12 margin-0">
									<a href="http://m.me/digimaru.id?ref=7%20Secret%20Ads" target="_blank"><img src="{{asset('/images/promo-4.jpg')}}"class="img-responsive" ></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-11 col-sm-11">
				<div class="panel-group" id="accordion_1" role="tablist" aria-multiselectable="true">
					<div class="panel">
						<div class="panel-heading bgBlueGreen br-6" role="tab" id="headingOne_1">
							<h4 class="panel-title">
								<a role="button" data-toggle="collapse" data-parent="#accordion_1" href="#collapseOne_1" aria-expanded="true" aria-controls="collapseOne_1" class="">
									Hal yang perlu Anda perhatikan saat Anda memulai
									<button type="button" id="iconCollapse" class="text-center pull-right btn btn-info btn-circle waves-effect waves-circle waves-float">
										<i class="fa fa-chevron-down"></i>
									</button>
								</a>
							</h4>
						</div>
						<div id="collapseOne_1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne_1" aria-expanded="true">
							<div class="panel-body">
								<?php echo $content; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
        

@endsection
