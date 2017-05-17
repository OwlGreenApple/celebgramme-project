@extends('new-dashboard.main')

@section('content')
<script type="text/javascript">
		function getTimeRemaining(endtime){
			var t = endtime;
			var seconds = Math.floor( (t) % 60 );
			var minutes = Math.floor( (t/60) % 60 );
			var hours = Math.floor( (t/(60*60)) % 24 );
			var days = Math.floor( t/(60*60*24) );
			return {
				'total': t,
				'days': days,
				'hours': hours,
				'minutes': minutes,
				'seconds': seconds
			};
		}

		function initializeClock(id, endtime){
			var clock = document.getElementById(id);
			var daysSpan = clock.querySelector('.days');
			var hoursSpan = clock.querySelector('.hours');
			var minutesSpan = clock.querySelector('.minutes');
			var secondsSpan = clock.querySelector('.seconds');

			function updateClock(){
				var t = getTimeRemaining(endtime);

				daysSpan.innerHTML = t.days;
				hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
				minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
				secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);

				// if(t.total<=0){
					// clearInterval(timeinterval);
				// }
			}

			updateClock();
			//var timeinterval = setInterval(updateClock,1000);
		}

    function loadaccount(){
        $.ajax({
            type: 'GET',
            url: "<?php echo url('load-account'); ?>",
            data: {
            },
            dataType: 'text',
            beforeSend: function()
            {
              $("#div-loading").show();
            },
            success: function(result) {
                // $('#result').html(data);
                $("#div-loading").hide();
                $("#account-all").html(result);

							setTimeout(function(){
									var max = -1;
									$(".same-height").each(function() {
											var h = $(this).height(); 
											max = h > max ? h : max;
									});
									$(".same-height").each(function() {
											$(this).height(max); 
									});
									
									$( "body" ).on( "click", ".delete-button", function() {
										$("#id-setting").val($(this).attr("data-id"));
									});
							}, 1000);
								
            }
        })
        return false;
    }


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
                  $("#alert").removeClass('btn-danger');
                  if(data.action=='start'){
                    $(".btn-"+data.id).html("<span class='glyphicon glyphicon-stop'></span> Stop");
                    $(".btn-"+data.id).val("Stop");
                    $(".btn-"+data.id).parent().parent().parent().find(".status-activity p").html(' Status activity : <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> <span style="color:#5cb85c; font-weight:Bold;">Started</span>');
                    $(".btn-"+data.id).removeClass("btn-success");
                    $(".btn-"+data.id).addClass("btn-danger");
                  }
                  if(data.action=='stop'){
                    $(".btn-"+data.id).html("<span class='glyphicon glyphicon-play'></span> Start");
                    $(".btn-"+data.id).val("Start");
                    $(".btn-"+data.id).parent().parent().parent().find(".status-activity p").html(' Status activity : <span class="glyphicon glyphicon-stop"></span> <span style="color:#c12e2a; font-weight:Bold;">Stopped</span>');
                    $(".btn-"+data.id).removeClass("btn-danger");
                    $(".btn-"+data.id).addClass("btn-success");
                  }
									loadaccount();
                }
                else if(data.type=='error')
                {
                  $("#alert").addClass('btn-danger');
                  $("#alert").removeClass('alert-success');
                }
            }
        })
        return false;
    }
  $(document).ready(function() {
    $("#alert").hide();
		loadaccount();
		initializeClock('clockdiv', <?php echo $user->active_auto_manage ?>);
		
		
		$('#terms-add-account1').attr('checked', false); // Unchecks it
		$('#terms-add-account2').attr('checked', false); // Unchecks it
		$('#terms-add-account3').attr('checked', false); // Unchecks it
		$('#terms-add-account4').attr('checked', false); // Unchecks it
		$('#terms-add-account5').attr('checked', false); // Unchecks it
		$('#terms-add-account6').attr('checked', false); // Unchecks it
		$('#terms-add-account7').attr('checked', false); // Unchecks it
		$('#terms-add-account8').attr('checked', false); // Unchecks it
		$('#terms-add-account9').attr('checked', false); // Unchecks it
		$('#terms-add-account10').attr('checked', false); // Unchecks it
		$('#btn-add-account').click(function(e){
			$("#username").prop('disabled', true);
			$("#password").prop('disabled', true);
			$("#confirm_password").prop('disabled', true);
			$("#button-process").prop('disabled', true);
		});
		$('.checkbox-term').click(function(){
			if( ($("#terms-add-account1").prop("checked") == true) && ($("#terms-add-account2").prop("checked") == true) && ($("#terms-add-account3").prop("checked") == true) && ($("#terms-add-account4").prop("checked") == true) && ($("#terms-add-account5").prop("checked") == true) && ($("#terms-add-account6").prop("checked") == true) && ($("#terms-add-account7").prop("checked") == true) && ($("#terms-add-account8").prop("checked") == true) && ($("#terms-add-account9").prop("checked") == true) ){
				$("#username").prop('disabled', false);
				$("#password").prop('disabled', false);
				$("#confirm_password").prop('disabled', false);
				$("#button-process").prop('disabled', false);
			}
		});
		
    $( "body" ).on( "click", "#delete-setting", function() {
			$.ajax({
					headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					type: 'POST',
					url: "<?php echo url('delete-setting'); ?>",
					data: {
						id : $("#id-setting").val(),
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
								$("#alert").addClass('btn-danger');
								$("#alert").removeClass('alert-success');
							}
							else if(data.type=='error')
							{
								$("#alert").addClass('alert-success');
								$("#alert").removeClass('btn-danger');
							}
							$("#username").val("");
							$("#password").val("");
							$("#confirm_password").val("");
							loadaccount();
					}
			});
    });
    $( "body" ).on( "click", ".edit-cred", function() {
      $("#setting_id").val($(this).attr("data-id"));
      $("#edit_username").val($(this).attr("data-username"));
      $("#hidden-username").val($(this).attr("data-username"));
    });
    $( "body" ).on( "click", ".button-action", function() {
      action = "";
      if ($(this).val()=="Start") { action = "start"; }
      if ($(this).val()=="Stop") { action = "stop"; }
      call_action(action,$(this).attr("data-id"));
    });
    $('#button-start-all').click(function(e){
      call_action('start','all');
    });
    $('#button-stop-all').click(function(e){
      call_action('stop','all');
    });
    $('#button-edit-password').click(function(e){
      if ($("#edit_password").val() != $("#edit_confirm_password").val()) {
        $("#alert").addClass('btn-danger');
        $("#alert").removeClass('alert-success');
        $("#alert").show();
        $("#alert").html("password anda tidak sesuai");
      } else {
        $.ajax({
            headers: {  
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: "<?php echo url('process-edit-password'); ?>",
            data: $("#form-edit-password").serialize(),
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
                  $("#alert").removeClass('btn-danger');
                }
                else if(data.type=='error')
                {
                  $("#alert").addClass('btn-danger');
                  $("#alert").removeClass('alert-success');
                }
                $("#username").val("");
                $("#password").val("");
                loadaccount();
            }
        });
      }
    });
    $('#button-process').click(function(e){
      if ($("#password").val() != $("#confirm_password").val()) {
        $("#alert").addClass('btn-danger');
        $("#alert").removeClass('alert-success');
        $("#alert").show();
        $("#alert").html("password anda tidak sesuai");
      } else {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: "<?php echo url('process-save-credential'); ?>",
            data: $("#form-credential").serialize(),
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
                  $("#alert").removeClass('btn-danger');

									$("#username").val("");
									$("#password").val("");
									$("#confirm_password").val("");
									$('#terms-add-account1').attr('checked', false); // Unchecks it
									$('#terms-add-account2').attr('checked', false); // Unchecks it
									$('#terms-add-account3').attr('checked', false); // Unchecks it
									$('#terms-add-account4').attr('checked', false); // Unchecks it
									$('#terms-add-account5').attr('checked', false); // Unchecks it
									$('#terms-add-account6').attr('checked', false); // Unchecks it
									$('#terms-add-account7').attr('checked', false); // Unchecks it
									$('#terms-add-account8').attr('checked', false); // Unchecks it
									$('#terms-add-account9').attr('checked', false); // Unchecks it
									// $('#terms-add-account10').attr('checked', false); // Unchecks it
									// $("#confirm_password").val("");
                }
                else if(data.type=='error')
                {
									$('#terms-add-account9').attr('checked', false); // Unchecks it
                  $("#alert").addClass('btn-danger');
                  $("#alert").removeClass('alert-success');
                }
                loadaccount();
            }
        });
      }
    });
		
		
  });
</script>

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
						<div class="row margin-0" id="clockdiv">
							<h3>Total waktu berlangganan</h3>
							<div class="col-md-3 col-sm-3 col-xs-3">
								<h3 class="days text-blue">31</h3>
								<p>Days</p>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-3">
								<h3 class="hours text-blue">21</h3>
								<p>Hours</p>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-3">
								<h3 class="minutes text-blue">11</h3>
								<p>Minutes</p>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-3">
								<h3 class="seconds text-blue">41</h3>
								<p>Seconds</p>
							</div>
						</div>
						<div class="row margin-0">
							<p>
								Status server<h5 class="text-blue">{{$status_server}}</h5>
								Total waktu per akun&nbsp;<h5 class="text-blue">{{$timeperaccount}}</h5>
								Maksimum Akun&nbsp;<h5 class="text-blue">{{$user->max_account}}</h5>
							</p>
						</div>
					</div>            
				</div>
			</div>
			<div class="col-md-6 col-sm-12 col-xs-12">
				<div class="card">
					<div class="body bg-lightGrey" style="padding-top: 40px;padding-bottom: 0px;">
						<div class="row margin-0">
							<div class="col-md-12 col-sm-4 col-xs-12">
								<div class="card resposiveText br-6 cursorActive">
									<div class="body bgBlueGreen text-center br-6" data-toggle="modal" data-target="#myModal" id="btn-add-account">
										<button type="button" class="btnIcon text-center btn  btn-circle waves-effect waves-circle waves-float">
											<i class="fa fa-plus text-white"></i>
										</button>
										<h4 class="text-white">Add IG Account</h4>
									</div>
								</div>
							</div>
							<div class="col-md-6 col-sm-4 col-xs-12">
								<div class="card resposiveText br-6 cursorActive">
									<div class="body bgGreenLight text-center br-6" id="button-start-all">
										<button type="button" class="btnIcon text-center btn btn-circle waves-effect waves-circle waves-float">
											<i class="fa fa-play text-white"></i>
										</button>
										<h4 class="text-white">Start All</h4>
									</div>
								</div>
							</div>
							<div class="col-md-6 col-sm-4 col-xs-12">
								<div class="card resposiveText br-6 cursorActive">
									<div class="body bg-red text-center br-6" id="button-stop-all">
										<button type="button" class="btnIcon text-center btn btn-circle waves-effect waves-circle waves-float">
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
  <?php if ($user->type=="not-confirmed") { ?> 
    <div class="col-sm-12 col-md-12">            
      <div class="alert alert-danger col-sm-18 col-md-18">
        Silahkan konfirmasi email terlebih dahulu. Klik <a href="" id="link-activation">disini</a> untuk kirim email konfirmasi ulang.
      </div>  
    </div>          
  <?php } ?>
  <?php if (!is_null($order)) { ?> 
    <div class="col-sm-12 col-md-12">            
      <div class="alert alert-danger col-sm-18 col-md-18">
        Anda belum melakukan konfirmasi pembayaran. silahkan klik <a href="{{url('confirm-payment')}}">disini</a> untuk melakukan konfirmasi pembayaran
      </div>  
    </div>          
  <?php } ?>
  <div class="col-sm-12 col-md-12">            
    <div class="alert btn-danger col-sm-18 col-md-18" id="alert">
    </div>  
  </div>          
  @if (session('error'))
    <div class="col-sm-12 col-md-12">            
      <div class="alert alert-danger col-sm-18 col-md-18" >
        {{ session('error') }}
      </div>  
    </div>          
  @endif
</div>                        
			
			
			<div class="row">
				<div class="col-md-12">
					<h4><i class="fa fa-instagram"></i>&nbsp;Instagram Accounts</h4>
				</div>
				<div id="account-all">
				</div>
				<!--
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
-->
			</div>
		</div>
	</div>
</div>






















  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Instagram Login</h4>
        </div>
        <div class="modal-body">
          <form enctype="multipart/form-data" id="form-credential">
            <div class="form-group form-group-sm row">
								<div class="col-sm-12 col-md-12">
									<p>HARAP DIBACA Sebelum menambah akun <br>
									Pastikan akun Instagram anda memenuhi ketentuan di bawah ini:
									</p> 
								</div>  
            </div>  
            <div class="form-group form-group-sm row ">
							<div class="col-sm-12 col-md-12">
								<input type="checkbox" class="checkbox-term" id="terms-add-account1"> <label for="terms-add-account1" class="control-label">UMUR akun Instagram minimal 10 hari</label>
							</div>
							<div class="col-sm-12 col-md-12">
								<input type="checkbox" class="checkbox-term" id="terms-add-account2"> <label for="terms-add-account2" class="control-label">TIDAK menggunakan system automation Instagram selain Celebgramme </label>
								<p style="font-size:11px; margin-left:20px;"> PS : Stop Celebgramme terlebih dahulu apabila akan melakukan <br>activity Instagram secara manual(Follow / Like / Comment / Unfollow / Post)</p>
							</div>
							<div class="col-sm-12 col-md-12">
								<input type="checkbox" class="checkbox-term" id="terms-add-account3"> <label for="terms-add-account3" class="control-label">TIDAK membeli followers selama menggunakan Celebgramme</label>
							</div>
							<div class="col-sm-12 col-md-12">
								<input type="checkbox" class="checkbox-term" id="terms-add-account4"> <label for="terms-add-account4" class="control-label">Email & No HP sudah terhubung dengan Account Instagram ini</label>
							</div>
							<div class="col-sm-12 col-md-12">
								<input type="checkbox" class="checkbox-term" id="terms-add-account5"> <label for="terms-add-account5" class="control-label">PUNYA AKSES ke Email & No HP tersebut</label>
							</div>
							<div class="col-sm-12 col-md-12">
								<input type="checkbox" class="checkbox-term" id="terms-add-account6"> <label for="terms-add-account6" class="control-label">Akun Instagram memiliki 10 Post Photo / Video</label>
							</div>
							<div class="col-sm-12 col-md-12">
								<input type="checkbox" class="checkbox-term" id="terms-add-account7"> <label for="terms-add-account7" class="control-label">Turn OFF 2 Factor Authentications ( Khusus followers >1000 ) <a href="https://celebgramme.freshdesk.com/solution/articles/9000093394--instagram-error-instagram-selalu-minta-verifikasi-no-telp" target="_blank"> >> Link Help << </a> </label>
							</div>
							<div class="col-sm-12 col-md-12">
								<input type="checkbox" class="checkbox-term" id="terms-add-account9"> <label for="terms-add-account9" class="control-label">Saya sudah membaca dan mempelajari <a href="https://docs.google.com/document/d/1GWfW5kU5yvchCZlqPxEksK7NrMqgQGP0saqCG2VNGlQ" target="_blank"> Tutorial Celebgramme </a> </label>
							</div>
							<div class="col-sm-12 col-md-12">
								<input type="checkbox" class="checkbox-term" id="terms-add-account8"> <label for="terms-add-account8" class="control-label">Saya sudah membaca & menyetujui <a href="http://celebgramme.com/terms-conditions" target="_blank">TERMS & CONDITIONS</a> Celebgramme </label>
							</div>
							<div class="col-sm-12 col-md-12">
								<input type="checkbox" class="checkbox-term" id="terms-add-account10"> <label for="terms-add-account10" class="control-label">Silahkan login ke <a href="https://www.instagram.com/accounts/login/?force_classic_login" target="_blank">Instagram</a> via browser, jangan logout sebelum add account </label>
							</div>
            </div>  
            <div class="form-group form-group-sm row">
              <label class="col-xs-8 col-sm-2 control-label" for="formGroupInputSmall">Username</label>
              <div class="col-sm-8 col-md-6">
                <input type="text" class="form-control" placeholder="Your username" name="username" id="username">
              </div>
            </div>  
            <div class="form-group form-group-sm row">
              <label class="col-xs-8 col-sm-2 control-label" for="formGroupInputSmall">Password</label>
              <div class="col-sm-8 col-md-6">
                <input type="password" class="form-control" placeholder="Your password" name="password" id="password">
              </div>
            </div>  
            <div class="form-group form-group-sm row">
              <label class="col-xs-8 col-sm-2 control-label" for="formGroupInputSmall">Confirm Password</label>
              <div class="col-sm-8 col-md-6">
                <input type="password" class="form-control" placeholder="Confirm your password" name="confirm_password" id="confirm_password">
              </div>
            </div>  
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" id="button-process">Submit</button>
        </div>
      </div>
      
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="myModal-edit-password" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit Password</h4>
        </div>
        <div class="modal-body">
          <form enctype="multipart/form-data" id="form-edit-password">
            <div class="form-group form-group-sm row">
              <label class="col-xs-8 col-sm-2 control-label" for="formGroupInputSmall">Username</label>
              <div class="col-sm-8 col-md-6">
                <input type="text" class="form-control" placeholder="Your username" name="edit_username" id="edit_username" disabled>
                <input type="hidden" name="hidden_username" id="hidden-username">
              </div>
            </div>  
            <div class="form-group form-group-sm row">
              <label class="col-xs-8 col-sm-2 control-label" for="formGroupInputSmall">Password</label>
              <div class="col-sm-8 col-md-6">
                <input type="password" class="form-control" placeholder="Your password" name="edit_password" id="edit_password">
              </div>
            </div>  
            <div class="form-group form-group-sm row">
              <label class="col-xs-8 col-sm-2 control-label" for="formGroupInputSmall">Confirm Password</label>
              <div class="col-sm-8 col-md-6">
                <input type="password" class="form-control" placeholder="Confirm your password" name="edit_confirm_password" id="edit_confirm_password">
              </div>
            </div>  
            <input type="hidden" name="setting_id" id="setting_id">
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" id="button-edit-password">Submit</button>
        </div>
      </div>
      
    </div>
  </div>

	
  <!-- Modal confirm delete-->
	<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
					<div class="modal-content">
							<div class="modal-header">
									Delete Account
							</div>
							<div class="modal-body">
									Are you sure want to delete ?
							</div>
							<input type="hidden" id="id-setting">
							<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
									<button type="button" class="btn btn-danger btn-ok" id="delete-setting" data-dismiss="modal">Delete</button>
							</div>
					</div>
			</div>
	</div>	

@endsection
