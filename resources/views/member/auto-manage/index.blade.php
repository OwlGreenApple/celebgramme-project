@extends('member.index')

@section('content-auto-manage')
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

							var max = -1;
							$(".border-styling").each(function() {
									var h = $(this).height(); 
									max = h > max ? h : max;
							});
							$(".border-styling").each(function() {
									$(this).height(max); 
							});
							
								
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


		$(document).click(function(e) {
				var target = e.target;

				if (!$(target).is('.glyphicon-question-sign') && !$(target).parents().is('.glyphicon-question-sign')) {
						$('.glyphicon-question-sign').find(".hint").hide();
				}
				if (!$(target).is('.glyphicon-menu-down') && !$(target).parents().is('.glyphicon-menu-down')) {
						$('.glyphicon-menu-down').find(".hint").hide();
				}
		});
		
  $(document).ready(function() {

		initializeClock('clockdiv', <?php echo $user->active_auto_manage ?>);

    $("#alert").hide();
    loadaccount();

    $( "body" ).on( "click", ".delete-button", function() {
			$("#id-setting").val($(this).attr("data-id"));
    });
		
    $( "body" ).on( "click", "#delete-setting", function() {
			//alert($(this).attr("data-id"));
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
                $("#confirm_password").val("");
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
                }
                else if(data.type=='error')
                {
                  $("#alert").addClass('btn-danger');
                  $("#alert").removeClass('alert-success');
                }
                loadaccount();
            }
        });
      }
    });

		$( "body" ).on( "click", ".glyphicon-menu-down", function(e) {
			$(this).find('.hint').slideToggle();
		});

		$( "body" ).on( "click", ".glyphicon-question-sign", function(e) {
			$(this).find('.hint').slideToggle();
		});


  });
</script>
<!--
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
-->
<?php   if ($user->type<>"not-confirmed") { ?>
<div class="row">
              <div class="col-md-10 col-sm-10">
                <div class="panel panel-info ">
                  <div class="panel-heading">
                    <h3 class="panel-title">Steps</h3>
                  </div>
                  <div class="panel-body">
                    1. Add Account Instagram anda<br>
                    2. Click Setting di setiap Account Instagram anda<br>
                    3. Tetapkan Setting yang anda inginkan, sesudah selesai Click START<br>
										<p style="color:#a94442;">4. Dilarang keras menggunakan sistem auto manage / instagram bot yang lain saat anda menjalankan Celebgramme <br>
										5. Untuk menggunakan Celebgramme akun jangan di private<br>
										6. System Celebgramme akan otomatis melakukan Unfollow apabila akun anda mendekati batas following Instagram (7500 following)<br>
										7. Dilarang mengganti username/password selama menggunakan Celebgramme services
										</p>
                  </div>
                </div>
              </div>
</div>
<div class="row">
              <div class="col-md-10 col-sm-10">
                <div class="panel panel-info ">
                  <div class="panel-heading">
                    <h3 class="panel-title">Dashboard</h3>
                  </div>
                  <div class="panel-body">
                    <div class="col-md-4 col-sm-8">
                      <input type="button" value="Add Account" class="btn btn-primary col-md-8 col-sm-12" data-toggle="modal" data-target="#myModal" id="btn-add-account">
                    </div>                        
                    <div class="col-md-4 col-sm-8">
                      <input type="button" value="Start All" class="btn btn-success col-md-8 col-sm-12" id="button-start-all">
                    </div>                        
                    <div class="col-md-4 col-sm-8">
                      <input type="button" value="Stop All" class="btn btn-danger col-md-8 col-sm-12" id="button-stop-all">
                    </div>                        
                  </div>
                </div>
              </div>  
</div>                        
<?php } ?>

<div class="row">

  <?php if ($user->type=="not-confirmed") { ?> 
    <div class="col-sm-10 col-md-10">            
      <div class="alert alert-danger col-sm-18 col-md-18">
        Silahkan konfirmasi email terlebih dahulu. Klik <a href="" id="link-activation">disini</a> untuk kirim email konfirmasi ulang.
      </div>  
    </div>          
  <?php } ?>
  <?php if (!is_null($order)) { ?> 
    <div class="col-sm-10 col-md-10">            
      <div class="alert alert-danger col-sm-18 col-md-18">
        Anda belum melakukan konfirmasi pembayaran. silahkan klik <a href="{{url('confirm-payment')}}">disini</a> untuk melakukan konfirmasi pembayaran
      </div>  
    </div>          
  <?php } ?>
  <div class="col-sm-10 col-md-10">            
    <div class="alert btn-danger col-sm-18 col-md-18" id="alert">
    </div>  
  </div>          
  @if (session('error'))
    <div class="col-sm-10 col-md-10">            
      <div class="alert alert-danger col-sm-18 col-md-18" >
        {{ session('error') }}
      </div>  
    </div>          
  @endif
</div>                        

<div class="row">
  <div class="col-sm-10 col-md-10">
			<h3>Total Waktu Berlangganan</h3>
      <div id="clockdiv" class="fl">
        <div class="fl">
          <span class="days"></span>
          <div class="smalltext">Days</div>
        </div>
        <div class="fl">
          <span class="hours"></span>
          <div class="smalltext">Hours</div>
        </div>
        <div class="fl">
          <span class="minutes"></span>
          <div class="smalltext">Minutes</div>
        </div>
        <div class="fl">
          <span class="seconds"></span>
          <div class="smalltext">Seconds</div>
        </div>
        <i class="fn">
        </i>
      </div>
			<div class="fl" style="margin-left:10px;">
				<span class="server-status"></span><label style="font-size:11px;"> &nbsp Server Status : </label>
				<span class="glyphicon glyphicon-question-sign hint-button" title="">
				<div class="hint">
					Normal - Server berjalan dengan normal <br>
					Delay - Server in High Traffic, Perubahan Settings akan Delay 120-360 menit <br>
					Maintenance - Perubahan settings akan dijalankan saat Status Server Normal/Delay 
				</div>
				</span>
				<span style="font-size:11px;color:#5abe5a;" >{{$status_server}}</span> <br>
				
				<label style="font-size:11px;"> Total waktu per akun : </label>
					<span class="glyphicon glyphicon-question-sign hint-button" title="">
					<div class="hint">Total waktu per akun start = Total waktu pembelian / total akun start <br>
						hanya akun yang di start saja yang dikurangi waktunya dari total waktu pembelian</div>
					</span>
				<span style="font-size:11px;color:#5abe5a;" id="time-account-start"></span> <br>
				
				<label style="font-size:11px;"> Maksimal akun : {{$user->max_account}}</label>
				
			</div>
			<div class="fn">
			</div>
  </div>
</div>
<!--
<div class="row">
	<div class="col-sm-10 col-md-10">
		<h3>Total Account start = <span id="total-account-start"></span> </h3>
	</div>
</div>

<div class="row">
  <div class="col-sm-10 col-md-10">
			<h3>Total Waktu per account start : <span id="time-account-start"></span></h3>
  </div>
</div>

<div class="row">
	<div class="col-sm-10 col-md-10">
		<p>* Total waktu per akun start = Total waktu pembelian / total akun start <br>
hanya akun yang di start saja yang dikurangi waktunya dari total waktu pembelian</p>
	</div>
</div>
-->



<?php if ($user->type<>"not-confirmed") { ?>
<div class="row">
  <ul class="col-md-10" id="account-all">
<!--
    <div class="col-md-5 border-styling">
      <div class="row"> <img src="#" class=""> </div>
      <div class="row"> <label>nama</label></div>
      <div class="row"> <p> Status activity : Stopped</p></div>
      <div class="row"> 
        <div class="im-centered">
        <input type="button" value="Start" class="btn btn-info">
        <input type="button" value="Setting" class="btn btn-primary">
        </div>
      </div>
    </div>
-->
  </ul>                        
</div>      
<?php }?>


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
                <input type="text" class="form-control" placeholder="Your username" name="edit_username" id="edit_username">
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
