@extends('new-dashboard.main')

@section('content')
<script>
	$(document).ready(function() {
		$('.info-box').click(function(e){
			$('.info-box').removeClass("active");
			$(this).addClass("active");
		});
	});
</script>
		<div class="row">
			<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
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
<div class="embed-responsive embed-responsive-16by9 tab-pane animated flipInX" id="digimaru_1" role="tabpanel" >
  <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/wYh0UZfzKeQ?rel=0&amp;showinfo=0"></iframe>
</div>								
<div class="embed-responsive embed-responsive-16by9 tab-pane animated flipInX" id="digimaru_2" role="tabpanel" >
  <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/lGixFHdEMq0?rel=0&amp;showinfo=0"></iframe>
</div>								
<div class="embed-responsive embed-responsive-16by9 tab-pane animated flipInX" id="digimaru_3" role="tabpanel" >
  <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/VWFvn2H1gP0?rel=0&amp;showinfo=0"></iframe>
</div>								
								-->


<div class="embed-responsive embed-responsive-16by9 tab-pane animated flipInX active" id="home_animation_1" role="tabpanel" >
  <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/ubUwXDx3aGQ?rel=0&amp;showinfo=0"></iframe>
</div>								
<div class="embed-responsive embed-responsive-16by9 tab-pane animated flipInX" id="profile_animation_1" role="tabpanel" >
  <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/TEzV1YXFBUQ?rel=0&amp;showinfo=0"></iframe>
</div>								
<div class="embed-responsive embed-responsive-16by9 tab-pane animated flipInX" id="messages_animation_1" role="tabpanel" >
  <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/DWhDVq5WB8s?rel=0&amp;showinfo=0"></iframe>
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
          <!--
						<div class="col-md-4 col-sm-12 col-xs-12" role="presentation">
							<a href="#digimaru_1" data-toggle="tab" aria-expanded="true" class="info-box waves-effect btn-video active">
								<div class="icon">
									<img src="{{asset('/new-dashboard/images/filmIcon.png')}}" class="filmIcon">
								</div>
								<div class="content">
									<p class="text-white">Next Workshop</p>
								</div>
							</a>
						</div>
						<div class="col-md-4 col-sm-12 col-xs-12" role="presentation">
							<a href="#digimaru_2" data-toggle="tab" aria-expanded="true" class="info-box waves-effect btn-video">
								<div class="icon">
									<img src="{{asset('/new-dashboard/images/filmIcon.png')}}" class="filmIcon">
								</div>
								<div class="content">
									<p class="text-white">Alumni Bandung</p>
								</div>
							</a>
						</div>
						<div class="col-md-4 col-sm-12 col-xs-12" role="presentation">
							<a href="#digimaru_3" data-toggle="tab" aria-expanded="true" class="info-box waves-effect btn-video">
								<div class="icon">
									<img src="{{asset('/new-dashboard/images/filmIcon.png')}}" class="filmIcon">
								</div>
								<div class="content">
									<p class="text-white">Alumni Surabaya</p>
								</div>
							</a>
						</div>
					-->
						<div class="col-md-4 col-sm-12 col-xs-12" role="presentation">
							<a href="#home_animation_1" data-toggle="tab" aria-expanded="true" class="info-box waves-effect btn-video">
								<div class="icon">
									<img src="{{asset('/new-dashboard/images/filmIcon.png')}}" class="filmIcon">
								</div>
								<div class="content">
									<p class="text-white">Tutorial</p>
								</div>
							</a>
						</div>
						<div class="col-md-4 col-sm-12 col-xs-12" role="presentation">
							<a href="#profile_animation_1" data-toggle="tab" aria-expanded="false" class="info-box waves-effect btn-video">
								<div class="icon">
									<img src="{{asset('/new-dashboard/images/filmIcon.png')}}" class="filmIcon">
								</div>
								<div class="content">
									<p class="text-white">DM Setup</p>
								</div>
							</a>
						</div>
						<div class="col-md-4 col-sm-12 col-xs-12" role="presentation">
							<a href="#messages_animation_1" data-toggle="tab" aria-expanded="false" class="info-box waves-effect btn-video">
								<div class="icon">
									<img src="{{asset('/new-dashboard/images/filmIcon.png')}}" class="filmIcon">
								</div>
								<div class="content">
									<p class="text-white">Celebpost</p>
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
			<div class="col-lg-3 col-md-3 col-sm-8 col-xs-8" id="wrapCntentPromotion">
				<div class="row clearfix">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="max-width:290px;">
						<div class="card margin-0 br-t-6">
							<div class="header bg-themeDefault text-center br-t-6">
								<h2>Promotions</h2>
							</div>
							<div class="">
								<div style="padding:0;"class="card text-center margin-0 col-md-12 col-sm-12 col-xs-12">
									<a href="http://celebpost.in/" target="_blank"><img src="{{asset('/images/promo-1b.jpg')}}"class="img-responsive" ></a>
								</div>
								<div style="padding:0;" class="card text-center margin-0 col-md-6 col-sm-6 col-xs-6">
									<a href="http://line.me/ti/p/@vyd1834h" target="_blank"><img src="{{asset('/images/promo-2.jpg')}}"class="img-responsive" ></a>
								</div>
								<div style="padding:0;" class="card text-center col-md-6 col-xs-6 col-sm-6 margin-0">
									<a href="http://m.me/celebgramme" target="_blank"><img src="{{asset('/images/promo-3.jpg')}}"class="img-responsive" ></a>
								</div>
								<div style="padding:0;"  class="card text-center col-md-12 col-sm-12 col-xs-12 margin-0">
									<a href="https://www.celebtools.co/likes2fans" target="_blank"><img src="{{asset('/images/gif-promo.gif')}}"class="img-responsive" ></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
    <br>
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
