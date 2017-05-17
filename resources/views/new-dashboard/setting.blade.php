@extends('new-dashboard.main')

@section('content')

<?php 
use Celebgramme\Models\SettingMeta; 
use Celebgramme\Models\SettingHelper; 

	$is_auto_get_likes = 0;
	$target_categories = "";
	$setting_helper = SettingHelper::where("setting_id","=",$settings->id)->first();
	if (!is_null($setting_helper)){
		$is_auto_get_likes = $setting_helper->is_auto_get_likes;
		$target_categories = $setting_helper->target;
	}

?>
<script>
	function call_action(action,id){
		$.ajax({
				type: 'GET',
				url: "<?php echo url('call-action'); ?>",
				data: {
					action : action,
					id : id,
				},
				dataType: 'text',
				beforeSend: function()
				{
					$("#div-loading").show();
				},
				success: function(result) {
						// $('#result').html(data);
						$("#div-loading").hide();
						var data = jQuery.parseJSON(result);
						$("#alert").show();
						$("#alert").html(data.message);
						if(data.type=='success')
						{
							$("#alert").addClass('alert-success');
							$("#alert").removeClass('alert-danger');
							if(data.action=='start'){
								$(".btn-"+data.id).html("<span class='glyphicon glyphicon-stop'></span> Stop");
								$(".btn-"+data.id).val("Stop");
								// $(".btn-"+data.id).parent().parent().parent().find(".status-activity p").html(' Status activity : <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> <span style="color:#5cb85c; font-weight:Bold;">Started</span>');
								$(".btn-"+data.id).removeClass("btn-success");
								$(".btn-"+data.id).addClass("btn-danger");
								$(".span-status-activity").html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> <span style="color:#5cb85c; font-weight:Bold;">Started</span>');
							}
							if(data.action=='stop'){
								$(".btn-"+data.id).html("<span class='glyphicon glyphicon-play'></span> Start");
								$(".btn-"+data.id).val("Start");
								// $(".btn-"+data.id).parent().parent().parent().find(".status-activity p").html(' Status activity : <span class="glyphicon glyphicon-stop"></span> <span style="color:#c12e2a; font-weight:Bold;">Stopped</span>');
								$(".btn-"+data.id).removeClass("btn-danger");
								$(".btn-"+data.id).addClass("btn-success");
								$(".span-status-activity").html('<span class="glyphicon glyphicon-stop" style="color:black;"></span> <span style="color:#c12e2a; font-weight:Bold;">Stopped</span>');
							}
						}
						else if(data.type=='error')
						{
							$("#alert").html($("#alert").html());
							$("#alert").addClass('alert-danger');
							$("#alert").removeClass('alert-success');
						}
				}
		})
		return false;
	}

	$(document).ready(function() {
		$(".demo-tagsinput-area").each(function(){
			$(this).resizable({
			alsoResize: $(this).find('.form-line')
			});
		});
		activateNouislide();

		
    $('#button-save,#button-save2').click(function(e){
      $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type: 'POST',
          url: "<?php echo url('process-save-setting'); ?>",
          data: $("#form-setting").serialize(),
          dataType: 'text',
          beforeSend: function()
          {
            $("#div-loading").show();
          },
          success: function(result) {
              // $('#result').html(data);
              // console.log(result);return false;
              window.scrollTo(0, 0);
              $("#div-loading").hide();
              var data = jQuery.parseJSON(result);
              $("#alert").show();
              $("#alert").html(data.message);
              if(data.type=='success')
              {
                $("#alert").addClass('alert-success');
                $("#alert").removeClass('alert-danger');
              }
              else if(data.type=='error')
              {
                $("#alert").addClass('alert-danger');
                $("#alert").removeClass('alert-success');
              }
          }
      })
    });

    $('.selectize-target').selectize({
			persist: false,
			delimiter: ';',
			options: [
				<?php echo $strCategory; ?>
			],
			optgroups: [
				<?php echo $strClassCategory; ?>
			],
			optgroupField: 'class',
			labelField: 'name',
			searchField: ['name'],
			render: {
					optgroup_header: function(data, escape) {
							return '<div class="optgroup-header" style="font-size:16px;"><strong>' + escape(data.label) + '</strong></div>';
					}
			},
			plugins:['remove_button']
    });

		<?php if ($settings->status_auto) { ?>
			$(".advanced-manual-setting").addClass("hide");
		<?php } ?>

		$('#activity-speed').on('change', function() {
			if (this.value=="turbo") {
				alert("Ada Resiko anda akan diban oleh instagram menggunakan speed ini.");
			}
		})
		
		
		//buat kepentingan V3, nanti klo uda jalan bisa dipindah di asset
		$( "body" ).on( "click", ".btn-open-message", function() {
			$.ajax({
				type: 'GET',
				url: "<?php echo url('check-message'); ?>",
				data: {
					thread_id : $(this).attr("data-thread-id"),
					setting_id : $(this).attr("data-setting-id")
				},
				dataType: 'text',
				beforeSend: function()
				{
					$("#div-loading").show();
				},
				success: function(result) {
					$("#div-loading").hide();
					var data = jQuery.parseJSON(result);
					console.log(data);
					$("#div-testing-email").html(data.resultEmailData);
					// var dataMessage = jQuery.parseJSON(data.listMessageResponse);
					// console.log(dataMessage);
					
					// if(data.type=='success')
					// {
					// }
					// else if(data.type=='error')
					// {
					// }
				}
			})
		});
		$( "body" ).on( "click", ".button-like-inbox", function(e) {
			e.preventDefault();
			$.ajax({
				type: 'GET',
				url: "<?php echo url('action-direct-message'); ?>",
				data: {
					pk_id : $(this).attr("data-pk-id"),
					message : $("#text-message-inbox").val(),
					setting_id : $(this).attr("data-setting-id"),
					thread_id : $(this).attr("data-thread-id"),
					type : 'like',
				},
				dataType: 'text',
				beforeSend: function()
				{
					$("#div-loading").show();
				},
				success: function(result) {
					$("#div-loading").hide();
					var data = jQuery.parseJSON(result);
					$("#div-testing-email").html(data.resultEmailData);
					// var dataMessage = jQuery.parseJSON(data.listMessageResponse);
					// console.log(dataMessage);
					
					// if(data.type=='success')
					// {
					// }
					// else if(data.type=='error')
					// {
					// }
				}
			})
		});
		$( "body" ).on( "click", ".button-message-inbox", function() {
			$.ajax({
				type: 'GET',
				url: "<?php echo url('action-direct-message'); ?>",
				data: {
					pk_id : $(this).attr("data-pk-id"),
					message : $("#text-message-inbox").val(),
					setting_id : $(this).attr("data-setting-id"),
					thread_id : $(this).attr("data-thread-id"),
					type : 'message',
				},
				dataType: 'text',
				beforeSend: function()
				{
					$("#div-loading").show();
				},
				success: function(result) {
					$("#div-loading").hide();
					var data = jQuery.parseJSON(result);
					$("#div-testing-email").html(data.resultEmailData);
					// var dataMessage = jQuery.parseJSON(data.listMessageResponse);
					// console.log(dataMessage);
					
					// if(data.type=='success')
					// {
					// }
					// else if(data.type=='error')
					// {
					// }
				}
			})
		});
		
	});	
</script>
<script type="text/javascript" src="{{ asset('/js/setting.js') }}"></script>
<style>
	.gold-fullauto-setting {
		background: #fff499; 
		background: -moz-linear-gradient(top, #fff499 0%, #efdd37 100%); 
		background: -webkit-linear-gradient(top, #fff499 0%,#efdd37 100%); 
		background: linear-gradient(to bottom, #fff499 0%,#efdd37 100%); 
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fff499', endColorstr='#efdd37',GradientType=0 ); 
		color : #000!important; outline:none!important;
	}
	.black-blacklist {
		background: #7d7e7d; 
		background: -moz-linear-gradient(top,  #7d7e7d 0%, #0e0e0e 100%); 
		background: -webkit-linear-gradient(top,  #7d7e7d 0%,#0e0e0e 100%); 
		background: linear-gradient(to bottom,  #7d7e7d 0%,#0e0e0e 100%); 
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#7d7e7d', endColorstr='#0e0e0e',GradientType=0 ); 
	}
	.selectize-input {
		min-height:100px;
	}
	.btn {
		margin-left: -5px;
    box-shadow: none!important;		
	}
</style>
<form enctype="multipart/form-data" id="form-setting">
<div class="row">
	<div class="col-lg-12 col-md-12">
		<div class="container-fluid">
			<div class="block-header">
				<h2><i class="fa fa-cog"></i>&nbsp;User Settings</h2>
			</div>
			<div class="clearfix"></div><br>
			
<div class="row">
  <div class="col-sm-12 col-md-12">            
    <div class="alert alert-danger col-sm-18 col-md-18" id="alert">
    </div>  
  </div>          
</div>                        
			<div class="row">
			<div class="col-md-5 col-sm-12 col-xs-12">
				<h5>Profile</h5>
				<div class="card h-l-300">
					<div class=" h-l-300 body bg-lightGrey">
						<div class="row h-l-300">
							<div class="col-md-8 col-sm-12 col-xs-12 ">
								<div class="row">
									<div class="col-md-3 col-sm-3 col-xs-3">
										<?php 
											if (SettingMeta::getMeta($settings->id,"photo_filename") == "0") {
												$photo = url('images/profile-default.png');
											} else {
												$photo = url("images/pp/". SettingMeta::getMeta($settings->id,"photo_filename"));
											}
										?>	
										<img src="{{$photo}}" class="m-t-20 img-circle" style="width:50px;height:50px;">
									</div>
									<div class="col-md-9 col-sm-9 col-xs-9 padding-0 startStopArea">
										<p style="white-space: nowrap;" class="padding-0">
										<h5 class="text-primary">&nbsp;{{$settings->insta_username}}</h5>
											<small>Status Activity &nbsp;: &nbsp;
											<span class="text-success col-teal span-status-activity">
												<?php 
												if ($settings->status=='stopped') { 
													echo '<span class="glyphicon glyphicon-stop" style="color:black;"></span> <span style="color:#c12e2a; font-weight:Bold;">Stopped</span>'; 
												} 
												else {
													echo '<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> <span style="color:#5cb85c; font-weight:Bold;">Started</span>';
												}?>
											</span>
											<br>
												Total waktu berlangganan<br/>
												<b class="text-primary">{{$view_totaltime}}</b>
											</small>
										</p>
									</div>
								</div>
								<div class="row">
										<input type="button" value="Save" class="btn btn-info col-md-5 col-sm-5 col-xs-12" id="button-save" style="margin-bottom:5px;margin-left:0px;">
										<div class="col-md-1 col-sm-1 col-xs-12"></div>
										<button data-id="{{$settings->id}}" class="col-md-5 col-sm-5 col-xs-12 btn <?php if ($settings->status=='stopped') { echo 'btn-success'; } else {echo 'btn-danger';} ?> button-action btn-{{$settings->id}}" value="<?php if ($settings->status=='stopped') { echo 'Start'; } else {echo 'Stop';}?>" style="margin-bottom:5px;margin-left:0px;">
										<?php if ($settings->status=='stopped') { echo "<span class='glyphicon glyphicon-play'></span> Start"; } else {echo "<span class='glyphicon glyphicon-stop'></span> Stop";}?> 
										</button>
										<div class="col-md-1 col-sm-1 col-xs-12"></div>
										
								</div>
							</div>
							<div class="col-md-4 col-sm-12 col-xs-12 bl-blue">
								<div class="row padding-0">
									<div class="col-md-9 col-sm-9 col-xs-9 padding-0">
										<small style="font-size:11px;white-space:nowrap;">Followers Saat Join&nbsp;:&nbsp;</small>
									</div>
									<div class="col-md-3 col-sm-3 col-xs-3 padding-0 text-center">
										<?php echo number_format(intval (SettingMeta::getMeta($settings->id,"followers_join")),0,'','.'); ?>
									</div>
								</div>
								<div class="row padding-0">
									<div class="col-md-9 col-sm-9 col-xs-9 padding-0">
										<small style="font-size:11px;white-space:nowrap;">Following Saat Join&nbsp;:&nbsp;</small>
									</div>
									<div class="col-md-3 col-sm-3 col-xs-3 padding-0 text-center">
										<?php echo number_format(intval (SettingMeta::getMeta($settings->id,"following_join")),0,'','.'); ?>
									</div>
								</div>
								<?php 
								$followers = intval (SettingMeta::getMeta($settings->id,"followers"));
								$following = intval (SettingMeta::getMeta($settings->id,"following"));
								
								?>
								<div class="row padding-0">
									<div class="col-md-9 col-sm-9 col-xs-9 padding-0">
										<small style="font-size:11px;white-space:nowrap;">Followers Hari ini&nbsp;:&nbsp;</small>
									</div>
									<div class="col-md-3 col-sm-3 col-xs-3 padding-0 text-center">
										{{number_format($followers,0,'','.')}}
									</div>
								</div>
								<div class="row padding-0">
									<div class="col-md-9 col-sm-9 col-xs-9 padding-0">
										<small style="font-size:11px;white-space:nowrap;">Following Hari ini&nbsp;:&nbsp;</small>
									</div>
									<div class="col-md-3 col-sm-3 col-xs-3 padding-0 text-center">
										{{number_format($following,0,'','.')}}
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
								<a class="btn btn-link btn-block bg-cyan br-6 text-white text-center" data-toggle="tab" href="#normal" id="button-package-normal">Normal</a>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-6 text-white">
								<a class="btn btn-link btn-block bgBlueGreen text-white br-6 text-center" data-toggle="tab" href="#extra" id="button-package-extra">Extra</a>
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
													<a href="{{url('buy-more/16')}}">
														<button class="btn btn-sm bgGreenLight text-white br-6">Buy Now</button>
													</a>
												</div>
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="card resposiveText br-6">
												<div class="body bgBlueGreen text-center br-6">
													<h3 class="text-white">60<br><small class="text-white">Days</small></h3>
													<h4 class="text-white" style="white-space:nowrap;">Rp. 295.000,-</h4>
													<a href="{{url('buy-more/17')}}">
														<button class="btn btn-sm bgGreenLight text-white br-6">Buy Now</button>
													</a>
												</div>
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="card resposiveText br-6">
												<div class="body bgBlueGreen text-center br-6">
													<h3 class="text-white">90<br><small class="text-white">Days</small></h3>
													<h4 class="text-white" style="white-space:nowrap;">Rp. 395.000,-</h4>
													<a href="{{url('buy-more/18')}}">
														<button class="btn btn-sm bgGreenLight text-white br-6">Buy Now</button>
													</a>
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
													<a href="{{url('buy-more/19')}}">
														<button class="btn btn-sm bgGreenLight text-white br-6">Buy Now</button>
													</a>
												</div>
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="card resposiveText br-6">
												<div class="body bgBlueGreen text-center br-6">
													<h3 class="text-white">270<br><small class="text-white">Days</small></h3>
													<h4 class="text-white" style="white-space:nowrap;">Rp. 995.000,-</h4>
													<a href="{{url('buy-more/25')}}">
														<button class="btn btn-sm bgGreenLight text-white br-6">Buy Now</button>
													</a>
												</div>
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="card resposiveText br-6">
												<div class="body bgBlueGreen text-center br-6">
													<h3 class="text-white">360<br><small class="text-white">Days</small></h3>
													<h4 class="text-white" style="white-space:nowrap;">Rp. 1285.000,-</h4>
													<a href="{{url('buy-more/20')}}">
														<button class="btn btn-sm bgGreenLight text-white br-6">Buy Now</button>
													</a>
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
					<div class="col-md-2 col-sm-6 col-xs-6">
						<button class="btn btn-sm bg-cyan btn-block btnGeneral br-6" data-toggle="tab" href="#general"><i class="fa fa-cog"></i>&nbsp;General</button>
					</div>
					<div class="col-md-2 col-sm-6 col-xs-6 padding-0">
						<button class="btn btn-sm bg-grey btn-block btnMessage br-6"  style="font-size:inherit;"data-toggle="tab" href="#message"><i class="fa fa-envelope text-white"></i>&nbsp;Direct Message</button>
					</div>
			</div>
			<div class="clearfix"></div><br>
			<div class="row">
				<div class="tab-content">
					<div id="general" class="tab-pane fade in active">
						<!--<div class="row">-->
							<!--<div class="col-md-12">-->
								<div class="card m-b-0">
									<div class="body bg-lightGrey">
										<div class="row">
											<div class="col-md-6 col-sm-6 col-xs-12">
												<div class="card m-b-0" style="background:transparent;box-shadow:none;">
													<div class="header">
														<h2>
															Global Settings &nbsp;<img class="cursorActive tooltipPlugin " src="{{asset('/new-dashboard/images/questionIcon.png')}}" title="<div class='panel-heading'>Choose Settings</div><div class='panel-content'>Pilih salah satu : FULL AUTO atau Manual settings.<br> FULL AUTO = Fast Settings, Pilih kategori Target anda & Start,<br> FULL AUTO hanya untuk Follow, Like & Auto Like My Posts ( tidak termasuk Comment ). <br>Manual = Setting manual customized semua fitur Celebgramme. <br> <i>*PS: Settings yang AKTIF adalah yang TERAKHIR dipilih</i></div>">
														</h2>
													</div>
													<div class="body" style="background:transparent;box-shadow:none;">
														<div class="row btnGroupOO">
															<div class="col-md-6 col-sm-4 col-xs-4">
																<b>Choose Settings</b>
															</div>
																<!--<button class="btn btn-block bg-grey btnOff">Full Auto</button>-->
																<button type="button" class="btn <?php if ($settings->status_auto) echo 'gold-fullauto-setting' ?>" id="button-fullauto" style="outline:none;color:#fff;">Full Auto</button><!--
															</div>
															<div class="col-md-3 col-sm-4 col-xs-4 padding-0">
																<button class="btn btn-block bg-cyan btnOn">Manual</button>-->
																<button type="button" class="btn <?php if (!$settings->status_auto) echo 'btn-info' ?>" id="button-advanced" style="outline:none;color:#fff;">Manual</button>
																<input type="hidden" value="{{$settings->status_auto}}" name="data[status_auto]" id="status_auto">
														</div>
														<div class="row">
															<div class="col-md-6 col-sm-6 col-xs-6">
																<b>Activity Speed</b>
															</div>
															<div class="col-md-6 col-sm-6 col-xs-6 padding-0">
																<div class="cursorActive" id="rating_slider">
																</div>
																<input type="hidden" name="data[activity_speed]" id="activity-speed" value="{{$settings->activity_speed}}">
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<div class="card m-b-0" style="background:transparent;box-shadow:none;">
													<div class="header">
														<h2>
															Auto Like Settings &nbsp;<img class="cursorActive tooltipPlugin" src="{{asset('/new-dashboard/images/questionIcon.png')}}" title="<div class='panel-heading'>Usernames whitelist</div><div class='panel-content'>• Saat anda UNFOLLOW. <strong>Usernames di 'Whitelist' ini akan diabaikan / tidak akan di 'UNFOLLOW'</strong><br>
							• <strong>Usulan penggunaan : </strong>teman, pasangan, rekan sekerja & siapapun yang anda mau KEEP FOLLOW <br> List Username yang TIDAK akan di FLC (Follow, Like & Comment)<br>
						Masukkan usernames SAJA disini (tanpa @), contoh: darthvader, hitler, kimjongil, dsbnya<br>
						<i>*PS: berguna sekali untuk TIDAK follow, like, comment 'mantan' & 'kompetitor' anda</i><br></div>">
														</h2>
													</div>
													<div class="body" style="background:transparent;box-shadow:none;">
														<div class="row btnGroupOO">
															<div class="col-md-6 col-sm-6 col-xs-6">
																<b>Auto Like My Post</b>
															</div>
															<div class="col-md-3 col-sm-3 col-xs-3 padding-0">
																<button type="button" class="btn <?php if ($is_auto_get_likes) echo 'btn-primary' ?>" id="AutoLikesOnButton" style="color:#fff;">ON</button>
																<button type="button" class="btn <?php if (!$is_auto_get_likes) echo 'btn-danger' ?>" id="AutoLikesOffButton" style="color:#fff;">OFF</button>
																<input type="hidden" value="{{$is_auto_get_likes}}" name="data[is_auto_get_likes]" id="is_auto_get_likes">
															</div>
														</div>
														<div class="row btnGroupOO">
															<div class="col-md-6 col-sm-6 col-xs-6">
																<b>Auto Like My Follower</b>
															</div>
															<div class="col-md-3 col-sm-3 col-xs-3 padding-0">
																<button type="button" class="btn <?php if ($settings->is_like_followers) echo 'btn-primary' ?>" id="AutoLikesFollowersOnButton" style="color:#fff;">ON</button>
																<button type="button" class="btn <?php if (!$settings->is_like_followers) echo 'btn-danger' ?>" id="AutoLikesFollowersOffButton" style="color:#fff;">OFF</button>
																<input type="hidden" value="{{$settings->is_like_followers}}" name="data[is_like_followers]" id="is_like_followers">
															</div>
														</div>
														<div class="row">
															<div class="col-md-offset-6 col-sm-offset-6 col-xs-offset-6 col-sm-6 col-xs-6">
																<div class="row">
																	<div class="col-md-6 col-sm-6 col-xs-6">
																		<input name="data[percent_like_followers]" type="radio" class="with-gap radio-col-light-blue" id="radio_3" value="25" <?php if ($settings->percent_like_followers== 25) echo 'checked' ?>>
																		<label for="radio_3">25%</label>
																	</div>
																	<div class="col-md-6 col-sm-6 col-xs-6">
																		<input name="data[percent_like_followers]" type="radio" id="radio_4" class="with-gap radio-col-light-blue" value="50" <?php if ($settings->percent_like_followers== 50) echo 'checked' ?>>
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
															Black List & White List &nbsp;<img class="cursorActive tooltipPlugin" src="{{asset('/new-dashboard/images/questionIcon.png')}}" title="<div class='panel-heading'>Profile</div><div class='panel-content'>Jumlah Followers & Following ini hanya merupakan INFO saja ( bukan Real time ) <br> & hanya di update beberapa kali dalam sehari untuk memperingan kerja server</div>">
														</h2>
													</div>
													<div class="body" style="background:transparent;box-shadow:none;">
														<div class="row">
															<div class="col-md-3 col-sm-3 col-xs-3">
																<b>Black List</b>
															</div>
															<div class="col-md-7 col-sm-7 col-xs-7">
																<div class="row btnGroupOO">
																	<button type="button" class="btn <?php if ($settings->status_blacklist) {echo'black-blacklist';} ?>" id="BlacklistOnButton" style="color:#fff;"><strong>ON</strong></button>
																	<button type="button" class="btn <?php if (!$settings->status_blacklist) {echo'black-blacklist';} ?>" id="BlacklistOffButton" style="color:#fff;">OFF</button>
																	<input type="hidden" value="{{$settings->status_blacklist}}" name="data[status_blacklist]" id="status_blacklist">
																</div>
															</div>
														</div>
														<div class="row">
																<p data-toggle="modal" data-target="#myModal" style="cursor:pointer;position: absolute;right: 35px;z-index: 10;" class="button-copy" data-text="textarea-unfollow-blacklist">copy</p>															
															<div class="col-md-12 col-sm-12 col-xs-12">
																<textarea class="selectize-default" id="textarea-unfollow-blacklist" name="data[usernames_blacklist]">{{$settings->usernames_blacklist}}</textarea>
															</div>
														</div>
														<div class="row">
															<div class="col-md-3 col-sm-3 col-xs-3">
																<b>White List</b>
															</div>
															<div class="col-md-4 col-sm-9 col-xs-9">
																<div class="row btnGroupOO">
																	<button type="button" class="btn <?php if ($settings->status_whitelist) {echo'black-blacklist';} ?>" id="WhitelistOnButton" style="color:#fff;"><strong>ON</strong></button>
																	<button type="button" class="btn <?php if (!$settings->status_whitelist) {echo'black-blacklist';} ?>" id="WhitelistOffButton" style="color:#fff;">OFF</button>
																	<input type="hidden" value="{{$settings->status_whitelist}}" name="data[status_whitelist]" id="status_whitelist">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-md-12 col-sm-12 col-xs-12">
																<p align="right" data-toggle="modal" data-target="#myModal" style="cursor:pointer;position: absolute;right: 35px;z-index: 10;" class="button-copy" data-text="textarea-unfollow-whitelist">copy</p>
																<textarea class="selectize-default" id="textarea-unfollow-whitelist" name="data[usernames_whitelist]">{{$settings->usernames_whitelist}}</textarea>
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
															Follow &nbsp;<img class="cursorActive tooltipPlugin" src="{{asset('/new-dashboard/images/questionIcon.png')}}" title="<div class='panel-heading'>Profile</div><div class='panel-content'>Jumlah Followers & Following ini hanya merupakan INFO saja ( bukan Real time ) <br> & hanya di update beberapa kali dalam sehari untuk memperingan kerja server</div>">
														</h2>
													</div>
													<div class="body" style="background:transparent;box-shadow:none;">
														<div class="row">
															<div class="col-md-4 col-sm-4 col-xs-4">
																<b>Status</b>
															</div>
															<div class="col-md-3 col-sm-8 col-xs-8">
																<div class="row btnGroupOO">
																	<button type="button" class="btn <?php if ($settings->status_follow_unfollow=="on") echo 'btn-primary' ?>" id="statusFollowOnButton" style="color:#fff;">ON</button>
																	<button type="button" class="btn <?php if ($settings->status_follow_unfollow=="off") echo 'btn-danger' ?>" id="statusFollowOffButton" style="color:#fff;">OFF</button>
																	<input type="hidden" value="{{$settings->status_follow_unfollow}}" name="data[status_follow_unfollow]" id="status_follow_unfollow">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-md-4 col-sm-4 col-xs-4">
																<b>Activity</b>
															</div>
															<div class="col-md-3 col-sm-8 col-xs-8">
																<div class="row btnGroupOO">
																	<button type="button" class="btn <?php if ($settings->activity=="follow") echo 'btn-success' ?>" id="followButton" style="color:#fff;">Follow</button>
																	<button type="button" class="btn <?php if ($settings->activity=="unfollow") echo 'btn-success' ?>" id="unfollowButton" style="color:#fff;">Unfollow</button>
																	<input type="hidden" value="{{$settings->activity}}" name="data[activity]" id="activity">

																	<input type="hidden" value="{{$settings->status_follow}}" name="data[status_follow]" id="status_follow">
																	<input type="hidden" value="{{$settings->status_unfollow}}" name="data[status_unfollow]" id="status_unfollow">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-md-4 col-sm-4 col-xs-4">
																<b>Follow Source</b>
															</div>
															<div class="col-md-3 col-sm-8 col-xs-8">
																<select class="form-control" name="data[follow_source]" id="select-follow-source">
																	<option value="hashtags" <?php if ($settings->follow_source=='hashtags') echo "selected" ?>>Hashtags</option>
																	<option value="followers of username" <?php if ($settings->follow_source=='followers of username') echo "selected" ?>>Followers of username</option>
										<!--							<option value="following of username" <?php if ($settings->follow_source=='following of username') echo "selected" ?>>Following of username</option>-->
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
																			<button type="button" class="btn <?php if ($settings->dont_follow_pu) echo 'btn-primary' ?>" id="DontFollowPUOnButton" style="color:#fff;">ON</button>
																			<button type="button" class="btn <?php if (!$settings->dont_follow_pu) echo 'btn-danger' ?>" id="DontFollowPUOffButton" style="color:#fff;">OFF</button>
																			<input type="hidden" value="{{$settings->dont_follow_pu}}" name="data[dont_follow_pu]" id="dont_follow_pu">
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
																			<button type="button" class="btn <?php if ($settings->dont_follow_su) echo 'btn-primary' ?>" id="DontFollowSUOnButton" style="color:#fff;">ON</button>
																			<button type="button" class="btn <?php if (!$settings->dont_follow_su) echo 'btn-danger' ?>" id="DontFollowSUOffButton" style="color:#fff;">OFF</button>
																			<input type="hidden" value="{{$settings->dont_follow_su}}" name="data[dont_follow_su]" id="dont_follow_su">
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
															Media Source &nbsp;: &nbsp; #Hashtags &nbsp;<img class="cursorActive tooltipPlugin" src="{{asset('/new-dashboard/images/questionIcon.png')}}" title="<div class='panel-heading'>Profile</div><div class='panel-content'>Jumlah Followers & Following ini hanya merupakan INFO saja ( bukan Real time ) <br> & hanya di update beberapa kali dalam sehari untuk memperingan kerja server</div>">
														</h2>
													</div>
													<div class="body" style="background:transparent;box-shadow:none;">
														<div class="row">
															<div class="col-md-12 col-sm-12 col-xs-12">
																<p align="right" data-toggle="modal" data-target="#myModal" style="cursor:pointer;position: absolute;right: 35px;z-index: 10;" class="button-copy" data-text="textarea-hashtags">copy</p>
																<textarea class="selectize-default" id="textarea-hashtags" name="data[hashtags]">{{$settings->hashtags}}</textarea>
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
															Media Source &nbsp;: &nbsp; Usernames &nbsp;<img class="cursorActive tooltipPlugin" src="{{asset('/new-dashboard/images/questionIcon.png')}}" title="<div class='panel-heading'>Profile</div><div class='panel-content'>Jumlah Followers & Following ini hanya merupakan INFO saja ( bukan Real time ) <br> & hanya di update beberapa kali dalam sehari untuk memperingan kerja server</div>">
														</h2>
													</div>
													<div class="body" style="background:transparent;box-shadow:none;">
														<div class="row">
															<div class="col-md-12 col-sm-12 col-xs-12">
																<p align="right" data-toggle="modal" data-target="#myModal" style="cursor:pointer;position: absolute;right: 35px;z-index: 10;" class="button-copy" data-text="textarea-username">copy</p>
																<textarea class="selectize-default" id="textarea-username" name="data[username]">{{$settings->username}}</textarea>
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
															Like &nbsp; & &nbsp; Comment &nbsp;<img class="cursorActive tooltipPlugin" src="{{asset('/new-dashboard/images/questionIcon.png')}}" title="<div class='panel-heading'>Profile</div><div class='panel-content'>Jumlah Followers & Following ini hanya merupakan INFO saja ( bukan Real time ) <br> & hanya di update beberapa kali dalam sehari untuk memperingan kerja server</div>">
														</h2>
													</div>
													<div class="body" style="background:transparent;box-shadow:none;">
														<div class="row">
															<div class="col-md-4 col-sm-4 col-xs-4">
																<b>Like</b>
															</div>
															<div class="col-md-3 col-sm-8 col-xs-8">
																<div class="row btnGroupOO">
																	<button type="button" class="btn <?php if ($settings->status_like=="on") echo 'btn-primary' ?>" id="statusLikeOnButton" style="color:#fff;z-index:99;">ON</button>
																	<button type="button" class="btn <?php if ($settings->status_like=="off") echo 'btn-danger' ?>" id="statusLikeOffButton" style="color:#fff;z-index:99;">OFF</button>
																	<input type="hidden" value="{{$settings->status_like}}" name="data[status_like]" id="status_like">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-md-4 col-sm-4 col-xs-5">
																<b>Comment</b>
															</div>
															<div class="col-md-3 col-sm-8 col-xs-7">
																<div class="row btnGroupOO">
																	<button type="button" class="btn <?php if ($settings->status_comment=="on") echo 'btn-primary' ?>" id="statusCommentOnButton" data-toggle="modal" data-target="#myModalCommentNotification" style="color:#fff;">ON</button>
																	<button type="button" class="btn <?php if ($settings->status_comment=="off") echo 'btn-danger' ?>" id="statusCommentOffButton" style="color:#fff;">OFF</button>
																	<input type="hidden" value="{{$settings->status_comment}}" name="data[status_comment]" id="status_comment">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-md-12 col-sm-12 col-xs-12">
																<div class="row">
																	<div class="col-md-6 col-sm-12 col-xs-12">
																		<label>Comments</label> &nbsp <span class="glyphicon glyphicon-question-sign tooltipPlugin" title="<div class='panel-heading'>Comments</div><div class='panel-content'>• <strong>Tambahkan : </strong><@owner> , untuk men-tag owner dari post tersebut<br>
																		• <strong>Tambahkan : </strong><@followers> , untuk men-tag followers anda<br>
																		• <strong>Tambahkan : </strong><@following> , untuk men-tag following anda<br>
																		• <strong>Komentar akan dipilih secara acak </strong>dari daftar ini. <br>
																		• <strong>Celebgramme hanya memberikan 1x komentar </strong>pada setiap posting <br>
																		• <strong>Komentar jangan menggunakan Hashtags </strong><br>
																		• <strong>Komentar jangan menggunakan URL </strong> <br>
																		• <strong>Komentar tidak boleh </strong>terdiri dari huruf kapital semua. <br>
																		• <strong>Komentar HARUS berbeda </strong>satu sama lain. <br>
																		</div>">
																		</span>
																	</div>
																	<div class="col-md-6 col-sm-12 col-xs-12">
																		<label>Copy contoh spin comment</label> &nbsp <span class="glyphicon glyphicon-menu-down tooltipPlugin" title='<div class="panel-content">								{asli|serius}, {nice|kerennn|cool|wow|keren|cooooolll|niceeeee} {sekaleee|sekali|banget|beneran|bener} {photo|foto|shot|poto|pic}{kamu|ini} <@owner>
																		<br> <br>
																		{nice|kerennn|cool|wow|keren|cooooolll|niceeeee} {sekaleee|sekali|banget|beneran|bener} {photo|foto|shot|poto|pic}{kamu|ini} <@owner> <br> <br>
																		{wow|amazing|incredible|whoa|seriously} {your|the|this} {photo|picture|photograph|image|foto} {is awesome|rocks !|very nice} <@owner> 
																		</div>'>
																		</span> 
																	</div>
																</div>
																<div class="row">
																	<div class="col-md-6 col-sm-12 col-xs-12">
																		<label>Penjelasan fitur spin comment</label> &nbsp <span class="glyphicon glyphicon-question-sign tooltipPlugin" title='<div class="panel-heading">Penjelasan fitur spin comment</div>								<div class="panel-content"><strong>Gunakan Feature "Spin Comment" </strong>contoh : <br>
																		{wihh|wow|beneran,|asli}{foto|image|photo}{kamu|anda|nya}{keren|cool|mantappp|sipp|amazing|beautiful} <br>
																			*contoh diatas akan menghasilkan <strong>4x3x3x6 = 216 kombinasi comments </strong> sekaligus <br>
																			*<strong>Spin Comment default akan digunakan</strong>, utk menghindari Comment yang sama berulang-ulang
																			</div>'>
																		</span>
																	</div>
																	<div class="col-md-6 col-sm-12 col-xs-12">
																		<label>Petunjuk tanda spin comment</label> &nbsp
																		<?php $tempurl = url("images/petunjuk-spin.jpg"); ?>
																		<span class="glyphicon glyphicon-search tooltipPlugin" title='<div class="panel-heading">Petunjuk tanda baca spin comment</div><div class="panel-content"><img src="{{$tempurl}}" width="800" height="250">
																		</div>'>
																		</span>
																		
																	</div>
																</div>
															<div class="row">
																<div class="col-md-12 col-sm-12 col-xs-12">
																	<p align="right" data-toggle="modal" data-target="#myModal" style="cursor:pointer;position: absolute;right: 35px;z-index: 10;" class="button-copy" data-text="textarea-comments">copy</p>
																	<input type="text" id="textarea-comments" class="selectize-default" name="data[comments]" value="{{$settings->comments}}">
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
																<button id="button-save2" class="btn btn-block bg-cyan">Save</button>
															</div>
															<div class="col-md-6 col-sm-6 col-xs-6">
																<button data-id="{{$settings->id}}" class="btn btn-block bgGreenLight btn <?php if ($settings->status=='stopped') { echo 'btn-success'; } else {echo 'btn-danger';} ?> button-action btn-{{$settings->id}}" value="<?php if ($settings->status=='stopped') { echo 'Start'; } else {echo 'Stop';}?>">
																<?php if ($settings->status=='stopped') { echo "<span class='glyphicon glyphicon-play'></span> Start"; } else {echo "<span class='glyphicon glyphicon-stop'></span> Stop";}?>
																</button>
															</div>
														</div>
													</div>
												</div>
												
											</div>
											
										</div>
										
										
										
									</div>
								</div>
							<!--</div>-->
						<!--</div>-->
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
</form>




<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Copy your text
										<span class="glyphicon glyphicon-question-sign tooltipPlugin" title="<div class='panel-heading'>Copy your text</div><div class='panel-content'>• Fasilitas untuk mencopy ke textbox lain. <br>• Silahkan edit dulu sebelum melakukan copy paste ke textbox lain. <br>• Text yang sama persis tidak dapat di paste ke textbox lain</div>">
							
					</span>

					</h4>
        </div>
        <div class="modal-body">
					<textarea id="textarea-copy" class="form-control" style="min-height:100px;height:auto;"></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" id="button-ok-copy">Copy All</button>
        </div>
      </div>
<script>
document.getElementById("button-ok-copy").addEventListener("click", function() {
    copyToClipboard(document.getElementById("textarea-copy"));
});
		function copyToClipboard(elem) {
				// create hidden text element, if it doesn't already exist
				var targetId = "_hiddenCopyText_";
				var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
				var origSelectionStart, origSelectionEnd;
				if (isInput) {
						// can just use the original source element for the selection and copy
						target = elem;
						origSelectionStart = elem.selectionStart;
						origSelectionEnd = elem.selectionEnd;
				} else {
						// must use a temporary form element for the selection and copy
						target = document.getElementById(targetId);
						if (!target) {
								var target = document.createElement("textarea");
								target.style.position = "absolute";
								target.style.left = "-9999px";
								target.style.top = "0";
								target.id = targetId;
								document.body.appendChild(target);
						}
						target.textContent = elem.textContent;
				}
				// select the content
				var currentFocus = document.activeElement;
				target.focus();
				target.setSelectionRange(0, target.value.length);
				
				// copy the selection
				var succeed;
				try {
						succeed = document.execCommand("copy");
				} catch(e) {
						succeed = false;
				}
				// restore original focus
				if (currentFocus && typeof currentFocus.focus === "function") {
						currentFocus.focus();
				}
				
				if (isInput) {
						// restore prior selection
						elem.setSelectionRange(origSelectionStart, origSelectionEnd);
				} else {
						// clear temporary content
						target.textContent = "";
				}
				return succeed;
		}

</script>
      
    </div>
  </div>



	
<!-- Modal -->
  <div class="modal fade" id="myModalCommentNotification" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
          <h4 class="modal-title">BACA DAHULU, Jika anda akan menggunakan fitur COMMENT : 
					</h4>
        </div>
        <div class="modal-body">
				<p>
				*PENTING : Ada update terbaru instagram yang menyaring comments spam.<br> 
				Pastikan Comments yang anda buat: <br>
				Benar-benar UNIK, di SPIN dengan Kombinasi RATUSAN comments, <br>
				& gunakan juga <@owner> & <@followers> untuk menghindari comment yang sama. <br>
				Apabila anda masih ragu, Cukup gunakan fitur LIKE & FOLLOW SAJA. <br>
				Tidak perlu menggunakan Comment terlebih dahulu.<br>
				<br>
				Terima kasih atas perhatiannya<br>

				</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" id="button-ok-info-comment" data-dismiss="modal" >OK</button>
        </div>
      </div>
    </div>
  </div>
	
	
	
		<link href="{{ asset('/css/main.css') }}" rel="stylesheet">
		<link href="{{ asset('/selectize/css/selectize.bootstrap3.css') }}" rel="stylesheet">

		
<section id="userSetScript">
		<script type="text/javascript" src="{{ asset('/new-dashboard/js/jquery-ui.js') }}"></script>

		<!-- Noui Js -->
		<script type="text/javascript" src="{{ asset('/new-dashboard/plugins/nouislider/nouislider.js') }}"></script>
	
		<!-- Input Mask Plugin Js -->
		<script type="text/javascript" src="{{ asset('/new-dashboard/plugins/jquery-inputmask/jquery.inputmask.bundle.js') }}"></script>

	
		<!-- Bootstrap Tags Input Plugin Js -->
		<script type="text/javascript" src="{{ asset('/new-dashboard/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js') }}"></script>
		
		<script type="text/javascript" src="{{ asset('/selectize/js/standalone/selectize.js') }}"></script>
		
</section>
	
@endsection
