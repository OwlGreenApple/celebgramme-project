@extends('new-dashboard.main')

@section('content')
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
	setting_id = <?php echo $settings->id; ?>;
	function load_dm_inbox(page,is_refresh){
		var timer;
			$.ajax({
				type: 'GET',
				url: "<?php echo url('get-dm-inbox'); ?>",
				data: {
					setting_id : setting_id,
					is_sort : $("#sort-inbox-select").val(),
					page : page,
					is_refresh : is_refresh,
					search : $("#search-username-dminbox").val(),
				},
				dataType: 'text',
				beforeSend: function()
				{
					clearTimeout(timer);
					timer = setTimeout(function()
					{
						$("#alert-heavy-load").modal("show");
					},
					15000);
					$("#div-loading").show();
				},
				success: function(result) {
					$("#div-loading").hide();
					clearTimeout(timer);
					$("#alert-heavy-load").modal("hide");
					var data = jQuery.parseJSON(result);
					// var dataMessage = jQuery.parseJSON(data.listMessageResponse);
					// console.log(dataMessage);
					
					if(data.type=='success')
					{
						$("#dm-inbox-content").html(data.resultEmailData);
					}
					make_height_same();
					
				}
			});			
	}
	function load_auto_responder(){
		$.ajax({
			type: 'GET',
			url: "<?php echo url('get-auto-responder'); ?>",
			data: {
				setting_id : setting_id,
			},
			dataType: 'text',
			beforeSend: function()
			{
				$("#div-loading").show();
			},
			success: function(result) {
				$("#div-loading").hide();
				var data = jQuery.parseJSON(result);
				// var dataMessage = jQuery.parseJSON(data.listMessageResponse);
				// console.log(dataMessage);
				
				if(data.type=='success')
				{
					$("#div-auto-responder").html(data.resultData);
				}
				window.scrollTo(0, 0);
				// else if(data.type=='error')
				// {
				// }
			}
		})
	}
	function save_auto_responder(){
			$.ajax({
				type: 'get',
				url: "<?php echo url('save-welcome-message'); ?>",
				data: {
					setting_id : setting_id,
					// message : $("#textarea-welcome-message").val(),
					message : textareaWelcomeMessage[0].emojioneArea.getText(),
					is_auto_responder : $("#is_auto_responder").val(),
					delay_dm : $("#delay_dm").val(),
				},
				dataType: 'text',
				beforeSend: function()
				{
					$("#div-loading").show();
				},
				success: function(result) {
					// window.scrollTo(0, 0);
					$("#div-loading").hide();
					var data = jQuery.parseJSON(result);
					// $("#alert").show();
					$("#server-response-alert p").html(data.message);
					$('#server-respond').modal('show');
					if(data.type=='success')
					{
						// $("#server-response-alert p").addClass('alert-success');
						// $("#server-response-alert p").removeClass('alert-danger');
					}
					else if(data.type=='error')
					{
						// $("#server-response-alert p").addClass('alert-danger');
						// $("#server-response-alert p").removeClass('alert-success');
					}
				}
			})
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
							$("#alert").removeClass('alert-danger');
							if(data.action=='start'){
								$(".btn-"+data.id).html("<span class='glyphicon glyphicon-stop'></span> Stop");
								$(".btn-"+data.id).val("Stop");
								// $(".btn-"+data.id).parent().parent().parent().find(".status-activity p").html(' Status activity : <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> <span style="color:#5cb85c; font-weight:Bold;">Started</span>');
								$(".btn-"+data.id).removeClass("btn-success");
								$(".btn-"+data.id).addClass("btn-danger");
								$(".span-status-activity").html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> <span style="color:#5cb85c; font-weight:Bold;">Started</span>');
								$("#button-direct-message").prop('disabled', false);								
							}
							if(data.action=='stop'){
								$(".btn-"+data.id).html("<span class='glyphicon glyphicon-play'></span> Start");
								$(".btn-"+data.id).val("Start");
								// $(".btn-"+data.id).parent().parent().parent().find(".status-activity p").html(' Status activity : <span class="glyphicon glyphicon-stop"></span> <span style="color:#c12e2a; font-weight:Bold;">Stopped</span>');
								$(".btn-"+data.id).removeClass("btn-danger");
								$(".btn-"+data.id).addClass("btn-success");
								$(".span-status-activity").html('<span class="glyphicon glyphicon-stop" style="color:black;"></span> <span style="color:#c12e2a; font-weight:Bold;">Stopped</span>');
								$("#button-direct-message").prop('disabled', true);								
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
	
	function make_height_same(){
		var max = -1;
		$(".same-height").each(function() {
				var h = $(this).height(); 
				max = h > max ? h : max;
		});
		$(".same-height").each(function() {
				$(this).height(max); 
		});
	}

	
	function push_all_following(){
		<?php 
		foreach($arr_user_whitelist as $data_whitelist) { 
		?>
			selectizeWhitelist.addItem("<?php echo $data_whitelist['value']; ?>");
		<?php } ?>
	}
	
	$(document).ready(function() {
		$(".demo-tagsinput-area").each(function(){
			$(this).resizable({
			alsoResize: $(this).find('.form-line')
			});
		});
		activateNouislide();
		load_auto_responder();
		
    $('#button-back-inbox').click(function(e){
			make_height_same();
		});
    $('#button-save,#button-save2').click(function(e){
			e.preventDefault();
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
					// message : $("#text-message-inbox").val(),
					setting_id : $(this).attr("data-setting-id"),
					data_thread_id : $(this).attr("data-thread-id"),
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
					// message : $("#text-message-inbox").val(),
					message : sendInboxData[0].emojioneArea.getText(),
					setting_id : $(this).attr("data-setting-id"),
					// data_thread_id : $(this).attr("data-thread-id"),
					data_username : $(this).attr("data-username"),
					data_pic : $(this).attr("data-pic"),
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
					// $("#div-testing-email").html(data.resultEmailData);
					// var dataMessage = jQuery.parseJSON(data.listMessageResponse);
					// console.log(dataMessage);
					
					if(data.type=='success')
					{
						$("#chat-all").html(data.resultEmailData);
					}
					// else if(data.type=='error')
					// {
					// }
				}
			})
		});
		$( "body" ).on( "change", "#sort-inbox-select", function(e) {
			e.preventDefault();
			load_dm_inbox(1,0);
		});
		$( "body" ).on( "click", ".btnDmIn, .btnMessage", function(e) {
			e.preventDefault();
			load_dm_inbox(1,0);
		});
		$( "body" ).on( "click", "#button-refresh-DMInbox", function(e) {
			e.preventDefault();
			load_dm_inbox(1,"1");
		});

		/*
		Buat search username
		*/
		var typingTimer;                //timer identifier
		var doneTypingInterval = 2000;  //time in ms, 2 second for example
		$( "#search-username-dminbox" ).keyup(function(e) {
			e.preventDefault();
			// load_dm_inbox(1,0);
			clearTimeout(typingTimer);
			// if ($('#search-username-dminbox').val()) {
				typingTimer = setTimeout(doneTyping, doneTypingInterval);
			// }
		});
		//user is "finished typing," do something
		function doneTyping () {
			//do something
			load_dm_inbox(1,0);
		}		

		
		$( "body" ).on( "click", ".button-reply", function(e) {
			$.ajax({
				type: 'GET',
				url: "<?php echo url('get-chat-all'); ?>",
				data: {
					data_thread_id : $(this).attr("data-thread-id"),
					data_username : $(this).attr("data-username"),
					data_pic : $(this).attr("data-pic"),
					setting_id : setting_id,
				},
				dataType: 'text',
				beforeSend: function()
				{
					$("#div-loading").show();
				},
				success: function(result) {
					$("#div-loading").hide();
					var data = jQuery.parseJSON(result);
					// var dataMessage = jQuery.parseJSON(data.listMessageResponse);
					// console.log(dataMessage);
					
					if(data.type=='success')
					{
						$("#chat-all").html(data.resultEmailData);
					}
					// else if(data.type=='error')
					// {
					// }
				}
			})
		});
		$( "body" ).on( "click", ".btnDmRe", function(e) {
			$.ajax({
				type: 'GET',
				url: "<?php echo url('get-dm-req'); ?>",
				data: {
					setting_id : setting_id,
				},
				dataType: 'text',
				beforeSend: function()
				{
					$("#div-loading").show();
				},
				success: function(result) {
					$("#div-loading").hide();
					var data = jQuery.parseJSON(result);
					// var dataMessage = jQuery.parseJSON(data.listMessageResponse);
					// console.log(dataMessage);
					
					if(data.type=='success')
					{
						$("#DMRequest").html(data.resultEmailData);
					}
					// else if(data.type=='error')
					// {
					// }
					make_height_same();
					
				}
			})
		});
		$( "body" ).on( "click", ".button-accept-request", function() {
			//dm req approve
			$.ajax({
				type: 'GET',
				url: "<?php echo url('action-dm-req'); ?>",
				data: {
					setting_id : setting_id,
					data_thread_id : $(this).attr("data-thread-id"),
					data_pic : $(this).attr("data-pic"),
					data_username : $(this).attr("data-username"),
					type : 'approve',
				},
				dataType: 'text',
				beforeSend: function()
				{
					$("#div-loading").show();
				},
				success: function(result) {
					$("#div-loading").hide();
					var data = jQuery.parseJSON(result);
					// $("#div-testing-email").html(data.resultEmailData);
					// var dataMessage = jQuery.parseJSON(data.listMessageResponse);
					// console.log(dataMessage);
					
					if(data.type=='success')
					{
						// $("#DMRequest").html(data.resultEmailData);
						$("#chat-all").html(data.resultEmailData);
					}
					// else if(data.type=='error')
					// {
					// }
				}
			})
		});
		$( "body" ).on( "click", ".button-decline-request", function() {
			$("#hidden-thread-id").val($(this).attr("data-thread-id"));
		});
		$( "body" ).on( "click", "#decline-dm-req", function() {
			//dm req decline
			$.ajax({
				type: 'GET',
				url: "<?php echo url('action-dm-req'); ?>",
				data: {
					setting_id : setting_id,
					data_thread_id : $("#hidden-thread-id").val(),
					type : 'decline',
				},
				dataType: 'text',
				beforeSend: function()
				{
					$("#div-loading").show();
				},
				success: function(result) {
					$("#div-loading").hide();
					var data = jQuery.parseJSON(result);
					// $("#div-testing-email").html(data.resultEmailData);
					// var dataMessage = jQuery.parseJSON(data.listMessageResponse);
					// console.log(dataMessage);
					
					if(data.type=='success')
					{
						$("#DMRequest").html(data.resultEmailData);
					}
					// else if(data.type=='error')
					// {
					// }
				}
			})
		});
		$( "body" ).on( "click", "#button-save-welcome-message", function(e) {
			e.preventDefault();
			//save welcome message
			save_auto_responder();
		});
		$( "body" ).on( "click", "#AutoResponderOnButton", function(e) {
			e.preventDefault();
			save_auto_responder();
		});
		$( "body" ).on( "click", "#AutoResponderOffButton", function(e) {
			e.preventDefault();
			save_auto_responder();
		});
		$( "body" ).on( "click", "#button-create-auto-responder", function(e) {
			e.preventDefault();
			$("#id-auto-responder").val("new");
		});
		$( "body" ).on( "click", ".button-edit-auto-responder", function(e) {
			e.preventDefault();
			$("#id-auto-responder").val($(this).attr("data-id"));
			$("#num_of_day").val($(this).attr("data-num"));
			// $("#message_responder").val($(this).attr("data-message"));
			welcomeMessageResponder[0].emojioneArea.setText($(this).attr("data-message"));
		});
		$( "body" ).on( "click", "#button-submit-auto-responder", function(e) {
			$("#message_responder").val(welcomeMessageResponder[0].emojioneArea.getText());
        $.ajax({                                      
          url: '<?php echo url('submit-auto-responder'); ?>',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type: 'post',
          data: $("#form-auto-responder").serialize(),
          beforeSend: function()
          {
            $("#div-loading").show();
          },
          dataType: 'text',
          success: function(result)
          {
            var data = jQuery.parseJSON(result);
            // $("#alert").show();
            // $("#alert").html(data.message);
						$("#server-response-alert p").html(data.message);
						$('#server-respond').modal('show');
            if(data.type=='success') {
              // refresh_autoresponder();
							load_auto_responder();
              // $("#alert").addClass("alert-success");
              // $("#alert").removeClass("alert-danger");
							
							$("#id-auto-responder").val("new");
							$("#num_of_day").val("");
							// $("#message_responder").val("");
							welcomeMessageResponder[0].emojioneArea.setText("");
            } else if (data.type=='error') {
              // $("#alert").addClass("alert-danger");
              // $("#alert").removeClass("alert-success");
            }
						// window.scrollTo(0, 0);
						$("#div-loading").hide();
          }
        });
		});
		$( "body" ).on( "click", ".button-delete-auto-responder", function(e) {
			e.preventDefault();
			$("#hidden-auto-responder-setting-id").val($(this).attr("data-id"));
		});
		$( "body" ).on( "click", "#button-delete-auto-responder-setting", function() {
			//dm req decline
			$.ajax({
				type: 'GET',
				url: "<?php echo url('delete-auto-responder'); ?>",
				data: {
					setting_id : setting_id,
					id : $("#hidden-auto-responder-setting-id").val(),
				},
				dataType: 'text',
				beforeSend: function()
				{
					$("#div-loading").show();
				},
				success: function(result) {
            var data = jQuery.parseJSON(result);
            $("#alert").show();
            $("#alert").html(data.message);
            if(data.type=='success') {
              // refresh_autoresponder();
							load_auto_responder();
              $("#alert").addClass("alert-success");
              $("#alert").removeClass("alert-danger");
            } else if (data.type=='error') {
              $("#alert").addClass("alert-danger");
              $("#alert").removeClass("alert-success");
            }
						window.scrollTo(0, 0);
						$("#div-loading").hide();
				}
			})
		});
		
		$( "#input-username-blacklist" ).autocomplete({
			source: function( request, response ) {
				$.ajax( {
					url: "<?php echo url('auto-complete-username'); ?>",
					dataType: "json",
					data: {
						search : $('#input-username-blacklist').val(),
						setting_id : setting_id,
					},
					beforeSend: function()
					{
						$("#div-loading").show();
					},
					success: function( data ) {
						$("#div-loading").hide();
						// console.log(data);
						// response( data );
						$(".ui-autocomplete").empty(); 
						$.each(data , function (index, value){
							// console.log(index + ':' + value); 
							$('.ui-autocomplete').append('<li class="ui-menu-item"><div id="ui-id-'+index+'" tabindex="-1" class="ui-menu-item-wrapper">'+value+'</div></li>');
							var position = $("#input-username-blacklist").offset(); // position = { left: 42, top: 567 }
							$.each(position, function(key, element) {
								if (key == "top") {
									positionTop = element + $("#input-username-blacklist").height() + 12;
								}
								if (key == "left") {
									positionLeft = element;
								}
							});							
							$('.ui-autocomplete').css("position","absolute");
							$('.ui-autocomplete').css("top",positionTop+"px");
							$('.ui-autocomplete').css("left",positionLeft+"px");
							$('.ui-autocomplete li').click(function(e) 
							{ 
								// alert($(this).find("span.t").text());
								$( "#input-username-blacklist" ).val($(this).find("div").text());
								$(".ui-autocomplete").hide();
							});							
						});						
						$(".ui-autocomplete").show();
					}
				} );
			},
			minLength: 3,
			// select: function( event, ui ) {
				// console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
				// $( "#input-username-blacklist" ).val(ui.item.value);
			// },
			delay : 250 
			// close: function() {
			// }	,
			// autoFocus: true
		});
		
		$( "#input-username-blacklist" ).focus(function() {
			$(".ui-autocomplete").show();
		});
		
		
		$( "body" ).on( "click", "#button-whitelist-user-all-following", function(e) {
			e.preventDefault();
			$("#div-loading").show();
			// push_all_following();
			<?php 
			foreach($arr_user_whitelist as $data_whitelist) { 
			?>
				selectizeWhitelist.addItem("<?php echo $data_whitelist['value']; ?>");
			<?php } ?>
			
			$("#div-loading").hide();
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
	.selectize-input {min-height: 80px!important; }
  .ui-autocomplete {
    max-height: 250px;
    overflow-y: auto;
    /* prevent horizontal scrollbar */
    overflow-x: hidden;
  }
	
</style>
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
<?php if ($settings->error_cred) { ?>
<div class="row">
  <div class="col-sm-12 col-md-12">            
    <div class="alert alert-danger col-sm-18 col-md-18">
			<?php 
				if ( SettingMeta::getMeta($settings->id,"error_message_cred") == "0"  ) {
			?>
				*Data login error <br>
				Silahkan input kembali password anda 
				<?php } else { 
					echo SettingMeta::getMeta($settings->id,"error_message_cred");
				} ?>
			
    </div>  
  </div>          
</div>                        
<?php } ?>
			<div class="row">
			<div class="col-md-5 col-sm-12 col-xs-12">
				<h5>Profile</h5>
				<div class="card h-l-300">
					<div class=" h-l-300 body bg-lightGrey">
						<div class="row h-l-300">
							<div class="col-md-12 col-sm-12 col-xs-12 bl-blue" style="margin-bottom:0px;">
								<div class="row">
									<div class="col-md-2 col-sm-5 col-xs-4"></div>								
									<div class="col-md-3 col-sm-7 col-xs-8">
										<?php 
											if (SettingMeta::getMeta($settings->id,"photo_filename") == "0") {
												$photo = url('images/profile-default.png');
											} else {
												$photo = url("images/pp/". SettingMeta::getMeta($settings->id,"photo_filename"));
											}
										?>	
										<img src="{{$photo}}" class="m-t-20 img-circle" style="width:87px;height:87px;">
									</div>
									<div class="col-md-0 col-sm-1 col-xs-1"></div>
									<div class="col-md-7 col-sm-10 col-xs-10 startStopArea text-center" style="margin-top:-10px;">
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
									</div>
								</div>
								<div class="row" style="padding:0 20px;">
										<input type="button" value="Save" class="btn btn-lg btn-info col-md-5 col-sm-5 col-xs-12" id="button-save" style="margin-bottom:5px;margin-left:10px;">
										<div class="col-md-1 col-sm-1 col-xs-12"></div>
										<button data-id="{{$settings->id}}" class="btn-lg col-md-5 col-sm-5 col-xs-12 btn <?php if ($settings->status=='stopped') { echo 'btn-success'; } else {echo 'btn-danger';} ?> button-action btn-{{$settings->id}}" value="<?php if ($settings->status=='stopped') { echo 'Start'; } else {echo 'Stop';}?>" style="margin-bottom:5px;margin-left:10px;">
										<?php if ($settings->status=='stopped') { echo "<span class='glyphicon glyphicon-play'></span> Start"; } else {echo "<span class='glyphicon glyphicon-stop'></span> Stop";}?> 
										</button>
										<div class="col-md-1 col-sm-1 col-xs-12"></div>
										
								</div>
							</div>
							<div class="col-md-12 col-sm-12 col-xs-12 bl-blue">
								<div class="row ">
									<div class="col-md-3 col-sm-9 col-xs-9">
										<small style="font-size:11px;white-space:nowrap;">Followers Saat Join&nbsp;:&nbsp;</small>
									</div>
									<div class="col-md-3 col-sm-3 col-xs-3 text-right">
										<?php echo number_format(intval (SettingMeta::getMeta($settings->id,"followers_join")),0,'','.'); ?>
									</div>
									<div class="col-md-3 col-sm-9 col-xs-9">
										<small style="font-size:11px;white-space:nowrap;">Following Saat Join&nbsp;:&nbsp;</small>
									</div>
									<div class="col-md-3 col-sm-3 col-xs-3 text-right">
										<?php echo number_format(intval (SettingMeta::getMeta($settings->id,"following_join")),0,'','.'); ?>
									</div>
								</div>
								<?php 
									$followers = intval (SettingMeta::getMeta($settings->id,"followers"));
									$following = intval (SettingMeta::getMeta($settings->id,"following"));
								?>
								<div class="row">
									<div class="col-md-3 col-sm-9 col-xs-9">
										<small style="font-size:11px;white-space:nowrap;">Followers Hari ini&nbsp;:&nbsp;</small>
									</div>
									<div class="col-md-3 col-sm-3 col-xs-3 text-right">
										{{number_format($followers,0,'','.')}}
									</div>
									<div class="col-md-3 col-sm-9 col-xs-9">
										<small style="font-size:11px;white-space:nowrap;">Following Hari ini&nbsp;:&nbsp;</small>
									</div>
									<div class="col-md-3 col-sm-3 col-xs-3 text-right">
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
								<a class="btn btn-link btn-block bg-cyan br-6 text-white text-center" data-toggle="tab" id="button-package-normal">Normal</a>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-6 text-white">
								<a class="btn btn-link btn-block bgBlueGreen text-white br-6 text-center" data-toggle="tab"  id="button-package-extra">Extra</a>
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
													<h3 class="text-white" style="margin-top:0px;">30<br><small class="text-white">Days</small></h3>
													<h4 class="text-white" style="white-space:nowrap;">Rp. 195.000,-</h4>
													<a href="{{url('buy-more/16')}}">
														<button class="btn btn-lg bgGreenLight text-white br-6">Buy Now</button>
													</a>
												</div>
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="card resposiveText br-6">
												<div class="body bgBlueGreen text-center br-6">
													<h3 class="text-white" style="margin-top:0px;">60<br><small class="text-white">Days</small></h3>
													<h4 class="text-white" style="white-space:nowrap;">Rp. 295.000,-</h4>
													<a href="{{url('buy-more/17')}}">
														<button class="btn btn-lg bgGreenLight text-white br-6">Buy Now</button>
													</a>
												</div>
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="card resposiveText br-6">
												<div class="body bgBlueGreen text-center br-6">
													<h3 class="text-white" style="margin-top:0px;">90<br><small class="text-white">Days</small></h3>
													<h4 class="text-white" style="white-space:nowrap;">Rp. 395.000,-</h4>
													<a href="{{url('buy-more/18')}}">
														<button class="btn btn-lg bgGreenLight text-white br-6">Buy Now</button>
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
													<h3 class="text-white" style="margin-top:0px;">180<br><small class="text-white">Days</small></h3>
													<h4 class="text-white" style="white-space:nowrap;">Rp. 695.000,-</h4>
													<a href="{{url('buy-more/19')}}">
														<button class="btn btn-lg bgGreenLight text-white br-6">Buy Now</button>
													</a>
												</div>
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="card resposiveText br-6">
												<div class="body bgBlueGreen text-center br-6">
													<h3 class="text-white" style="margin-top:0px;">270<br><small class="text-white">Days</small></h3>
													<h4 class="text-white" style="white-space:nowrap;">Rp. 995.000,-</h4>
													<a href="{{url('buy-more/25')}}">
														<button class="btn btn-lg bgGreenLight text-white br-6">Buy Now</button>
													</a>
												</div>
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="card resposiveText br-6">
												<div class="body bgBlueGreen text-center br-6">
													<h3 class="text-white" style="margin-top:0px;">360<br><small class="text-white">Days</small></h3>
													<h4 class="text-white" style="white-space:nowrap;">Rp. 1285.000,-</h4>
													<a href="{{url('buy-more/20')}}">
														<button class="btn btn-lg bgGreenLight text-white br-6">Buy Now</button>
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
			
			<div class="row tabButton" style="margin-left:0px;">
					<div class="col-md-2 col-sm-12 col-xs-12 padding-0">
						<button class="btn btn-lg bg-cyan btn-block btnGeneral br-6" data-toggle="tab" href="#general"><i class="fa fa-cog"></i>&nbsp;General</button>
					</div>
					<div class="col-md-2 col-sm-12 col-xs-12 padding-0">
						<button class="btn btn-lg bg-grey btn-block br-6"  style="font-size:inherit;"data-toggle="tab" href="#DMAuto" id="button-direct-auto-responder" data-is-refresh="0"><i class="fa fa-envelope text-white"></i>&nbsp;DM Auto Reply &nbsp 
						</button>
					</div>
					<div class="col-md-2 col-sm-12 col-xs-12 padding-0">
						<button class="btn btn-lg bg-grey btn-block btnMessage br-6"  style="font-size:inherit;"data-toggle="tab" href="#message" <?php if ( ($settings->error_cred) || ($settings->status<>"started") ) { echo "disabled"; } ?> id="button-direct-message" data-is-refresh="0"><i class="fa fa-envelope text-white"></i>&nbsp;Direct Message &nbsp 
						</button>
					</div>
					<div class="col-md-1 col-sm-1 col-xs-1 padding-0">
						<h3 style="margin-top:5px;">
						<span class="glyphicon glyphicon-question-sign hint-button tooltipPlugin" title="<div class='panel-heading'>Direct Message</div><div class='panel-content'>
							▲ Press START to Turn ON DM Tab Function
																</div>"></span>
						</h3>
					</div>
			</div>
			<div class="clearfix"></div><br>
<form enctype="multipart/form-data" id="form-setting">
			<div class="row" style="margin-left:0px;">
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
															Global Settings &nbsp;<!--<img class="cursorActive tooltipPlugin " src="{{asset('/new-dashboard/images/questionIcon.png')}}" title="<div class='panel-heading'>Choose Settings</div><div class='panel-content'>Pilih salah satu : FULL AUTO atau Manual settings.<br> FULL AUTO = Fast Settings, Pilih kategori Target anda & Start,<br> FULL AUTO hanya untuk Follow, Like & Auto Like My Posts ( tidak termasuk Comment ). <br>Manual = Setting manual customized semua fitur Celebgramme. <br> <i>*PS: Settings yang AKTIF adalah yang TERAKHIR dipilih</i></div>">-->
														</h2>
													</div>
													<div class="body" style="background:transparent;box-shadow:none;">
														<div class="row btnGroupOO">
															<div class="col-md-6 col-sm-4 col-xs-4">
																<b>Settings</b>
																	<span class="glyphicon glyphicon-question-sign hint-button tooltipPlugin" title="<div class='panel-heading'>Choose Settings</div><div class='panel-content'><b>FULL AUTO</b> = Pilih kategori Target anda & Start <br><b>MANUAL</b> = Bebas atur sendiri fitur Celebgramme. <br></div>">
																	</span>																
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
																<b>Speed</b>
																<span class="glyphicon glyphicon-question-sign tooltipPlugin" title="<div class='panel-heading'>Activity speed</div><div class='panel-content'>• <b>Slow</b> = 200-250 Likes, 100-150 follow/unfollow <br>• <b>Normal</b> = 250-300 likes, 150-200 follow/unfollows <br>• <b>Fast</b> = 300-350 likes, 300-350 follow/unfollows <br>• <b>Turbo</b> = 600-750 likes, 400-480 follow/unfollows <br><i>*Proses aktifitas harian</i> <br></div>">
																</span>
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
												<div class="card" style="background:transparent;box-shadow:none;margin-bottom:-40px;">
													<div class="header">
														<h2>
															Auto Like Settings &nbsp;<!--<img class="cursorActive tooltipPlugin" src="{{asset('/new-dashboard/images/questionIcon.png')}}" title="<div class='panel-heading'>Usernames whitelist</div><div class='panel-content'>• Saat anda UNFOLLOW. <strong>Usernames di 'Whitelist' ini akan diabaikan / tidak akan di 'UNFOLLOW'</strong><br>
							• <strong>Usulan penggunaan : </strong>teman, pasangan, rekan sekerja & siapapun yang anda mau KEEP FOLLOW <br> List Username yang TIDAK akan di FLC (Follow, Like & Comment)<br>
						Masukkan usernames SAJA disini (tanpa @), contoh: darthvader, hitler, kimjongil, dsbnya<br>
						<i>*PS: berguna sekali untuk TIDAK follow, like, comment 'mantan' & 'kompetitor' anda</i><br></div>">-->
														</h2>
													</div>
													<div class="body" style="background:transparent;box-shadow:none;">
														<div class="row btnGroupOO">
															<div class="col-md-6 col-sm-6 col-xs-6">
																<b>Auto Like My Posts</b>
																<span class="glyphicon glyphicon-question-sign hint-button tooltipPlugin" title="<div class='panel-heading'>Auto Like My Post</div><div class='panel-content'><b>ON</b> - 30 Likes Otomatis untuk 3 Post terakhir anda/hari <br><b>OFF</b> - Tidak dapat Likes <br></div>">
																</span>
															</div>
															<div class="col-md-6 col-sm-6 col-xs-6 padding-0">
																<button type="button" class="btn <?php if ($is_auto_get_likes) echo 'btn-primary' ?>" id="AutoLikesOnButton" style="color:#fff;">ON</button>
																<button type="button" class="btn <?php if (!$is_auto_get_likes) echo 'btn-danger' ?>" id="AutoLikesOffButton" style="color:#fff;">OFF</button>
																<input type="hidden" value="{{$is_auto_get_likes}}" name="data[is_auto_get_likes]" id="is_auto_get_likes">
															</div>
														</div>
														<div class="row btnGroupOO">
															<div class="col-md-6 col-sm-6 col-xs-6">
																<b>Auto Like My Followers</b>
																<span class="glyphicon glyphicon-question-sign hint-button tooltipPlugin" title="<div class='panel-heading'>Auto Like My Followers</div><div class='panel-content'><b>Likes Followers anda sendiri</b> <br>Likes dihitung dari total likes/hari <br></div>">
															</div>
															<div class="col-md-6 col-sm-6 col-xs-6 padding-0">
																<button type="button" class="btn <?php if ($settings->is_like_followers) echo 'btn-primary' ?>" id="AutoLikesFollowersOnButton" style="color:#fff;">ON</button>
																<button type="button" class="btn <?php if (!$settings->is_like_followers) echo 'btn-danger' ?>" id="AutoLikesFollowersOffButton" style="color:#fff;">OFF</button>
																<input type="hidden" value="<?php if (!is_null($settings->is_like_followers)) { echo $settings->is_like_followers; } else { echo "0"; } ?>" name="data[is_like_followers]" id="is_like_followers">
															</div>
														</div>
															<div class="col-md-offset-6 col-sm-offset-6 col-xs-offset-6">
																<div class="row">
																	<div class="col-md-6 col-sm-6 col-xs-6">
																		<input name="data[percent_like_followers]" type="radio" class="with-gap radio-col-light-blue" id="radio_3" value="25" <?php 
																			if ( ($settings->percent_like_followers== 25) || (is_null($settings->percent_like_followers)) ) {
																				echo 'checked';
																			}
																		?>>
																		<label for="radio_3">25%</label>
																	</div>
																	<div class="col-md-6 col-sm-6 col-xs-6">
																		<input name="data[percent_like_followers]" type="radio" id="radio_4" class="with-gap radio-col-light-blue" value="50" <?php if ($settings->percent_like_followers== 50) { echo 'checked'; } ?>>
																		<label for="radio_4">50%</label>
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
															Black List & White List &nbsp;<!--<img class="cursorActive tooltipPlugin" src="{{asset('/new-dashboard/images/questionIcon.png')}}" title="<div class='panel-heading'>Profile</div><div class='panel-content'>Jumlah Followers & Following ini hanya merupakan INFO saja ( bukan Real time ) <br> & hanya di update beberapa kali dalam sehari untuk memperingan kerja server</div>">-->
														</h2>
													</div>
													<div class="body" style="background:transparent;box-shadow:none;">
														<div class="row">
															<div class="col-md-3 col-sm-3 col-xs-3">
																<b>Black List</b>
																<span class="glyphicon glyphicon-question-sign tooltipPlugin" title="<div class='panel-heading'>Blacklist </div><div class='panel-content'><b>List dari akun yang tidak akan anda 'Follow/Likes'</b> <br>Berguna untuk tidak memfollow/likes : mantan, kompetitor & akun yang anda tidak suka. <br></div>">
																</span>
																
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
															<div class="col-md-3 col-sm-12 col-xs-12">
																<input type="text" class="" placeholder="Username" id="input-username-blacklist"> 
																<span class="glyphicon glyphicon-question-sign tooltipPlugin" title="<div class='panel-heading'>Search Username </div><div class='panel-content'>Fitur ini mempermudah untuk mencari username yang available <br>dan memasukkan kedaftar blacklist anda, sehingga anda memasukkan username IG yang valid. </div>">
																</span>
																&nbsp
																<button class="btn btn-sm br-6 bg-cyan" id="button-input-user">Enter</button>
															</div>
														</div>
														<div class="row">
																<p data-toggle="modal" data-target="#myModal" style="cursor:pointer;position: absolute;right: 35px;z-index: 10;" class="button-copy" data-text="textarea-unfollow-blacklist">copy</p>															
															<div class="col-md-12 col-sm-12 col-xs-12">
																<textarea class="" id="textarea-unfollow-blacklist" name="data[usernames_blacklist]">{{$settings->usernames_blacklist}}</textarea>
																<script>
																	var $selectBlacklist = $('#textarea-unfollow-blacklist').selectize({
																				plugins:['remove_button'],
																				delimiter: ';',
																				persist: false,
																				onChange: function(value) {
																				},
																				create: function(input) {
																					return {
																						value: input,
																						text: input
																					}
																				},
																			});
																	var selectizeBlacklist = $selectBlacklist[0].selectize;
																	
																	$( "body" ).on( "click", "#button-input-user", function(e) {
																		e.preventDefault();
																		// selectizeBlacklist.addOption({value: $("#input-username-blacklist").val(), text: $("#input-username-blacklist").val()});
																		selectizeBlacklist.addOption({value: $("#input-username-blacklist").val(), text: $("#input-username-blacklist").val()});
																		// selectizeBlacklist.refreshOptions();
																		selectizeBlacklist.addItem($("#input-username-blacklist").val(),false);
																	});

																</script>
																
															</div>
														</div>
														<div class="row">
															<div class="col-md-3 col-sm-3 col-xs-3">
																<b>White List</b>
																<span class="glyphicon glyphicon-question-sign tooltipPlugin" title='<div class="panel-heading">Usernames whitelist</div><div class="panel-content"><b>List yang tidak akan di "UNFOLLOW"</b> <br>Ex : Pasangan, teman, boss & akun yang anda suka <br></div>'></span>
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
															<div class="col-md-3 col-sm-3 col-xs-3">
																<button class="btn btn-sm br-6 bg-cyan" id="button-whitelist-user-all-following">Add All Following</button>
															</div>
														</div>
														<div class="row">
															<div class="col-md-12 col-sm-12 col-xs-12">
																<p align="right" data-toggle="modal" data-target="#myModal" style="cursor:pointer;position: absolute;right: 35px;z-index: 10;" class="button-copy" data-text="textarea-unfollow-whitelist">copy</p>
																<!--<textarea class="selectize-default" id="textarea-unfollow-whitelist" name="data[usernames_whitelist]">{{$settings->usernames_whitelist}}</textarea>-->
																<!--<select name="data[usernames_whitelist]" id="textarea-unfollow-whitelist">
																</select>-->
																<textarea class="" id="textarea-unfollow-whitelist" name="data[usernames_whitelist]">{{$settings->usernames_whitelist}}</textarea>
																<script>
																	var $selectWhitelist = $('#textarea-unfollow-whitelist').selectize({
																				plugins:['remove_button'],
																				delimiter: ';',
																				persist: false,
																				onChange: function(value) {
																					// var current = selectizeWhitelist.getValue(); 
																					/*var arr = value.split(';');
																					console.log(arr[arr.length - 1]);*/
																				},
																				create: function(input) {
																					return {
																						value: input,
																						text: input
																					}
																				},
																			});
																	var selectizeWhitelist = $selectWhitelist[0].selectize;
																	// selectizeWhitelist.addOption({value: "whatever", text: 'whatever'});
																	selectizeWhitelist.addOption(<?php echo $j_arr_user_whitelist; ?>);
																	// selectizeWhitelist.refreshOptions();		

																</script>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										
                    <div class="row" id="target-categories" <?php if (!$settings->status_auto) echo "style='display:none;'"; ?>>
                      <div class="col-md-12 col-sm-12">
												<div class="card m-b-0 m-t--50" style="background:transparent;box-shadow:none;">
													<div class="header">
                            <h2 class="panel-title">Full Auto: Target Categories
                                <span class="glyphicon glyphicon-question-sign hint-button tooltipPlugin" title="<div class='panel-heading'>Target Categories</div><div class='panel-content'>Silahkan Pilih Target Kategori (max 10)<br>yang akan anda Follow & Like<br>Fitur Full Auto Settings akan OTOMATIS<br>berjalan sesuai dengan target kategori yang anda pilih.</div>">
                                </span>
                            </h2>
                          </div>
													<div class="body" style="background:transparent;box-shadow:none;">
                            <div class="row">
                              <div class="col-md-12 col-sm-12 col-xs-12">
                                <textarea class="selectize-target" id="textarea-target-categories" name="data[target_categories]">{{$target_categories}}</textarea>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>  
                    </div>       

                    
                    
										<div class="row advanced-manual-setting <?php if ($settings->status_auto) echo "hide"; ?>">
											<div class="col-md-12 col-sm-12 col-xs-12">
												<div class="card m-b-0 m-t--50" style="background:transparent;box-shadow:none;">
													<div class="header">
														<h2>
															Follow &nbsp;<!--<img class="cursorActive tooltipPlugin" src="{{asset('/new-dashboard/images/questionIcon.png')}}" title="<div class='panel-heading'>Profile</div><div class='panel-content'>Jumlah Followers & Following ini hanya merupakan INFO saja ( bukan Real time ) <br> & hanya di update beberapa kali dalam sehari untuk memperingan kerja server</div>">-->
														</h2>
													</div>
													<div class="body" style="background:transparent;box-shadow:none;">
														<div class="row">
															<div class="col-md-4 col-sm-4 col-xs-4">
																<b>Status</b>
																	<span class="glyphicon glyphicon-question-sign hint-button tooltipPlugin" title="<div class='panel-heading'>Follow Status</div><div class='panel-content'><strong>Status ON </strong>akan melakukan 'Follow/Unfollow' <br> <strong>Status OFF </strong>Tidak akan melakukan 'Follow/Unfollow' <br></div>">
																	</span>
															</div>
															<div class="col-md-3 col-sm-8 col-xs-8">
																<div class="row btnGroupOO">
																	<button type="button" class="btn <?php if ($settings->status_follow_unfollow=="on") echo 'btn-primary' ?>" id="statusFollowOnButton" style="color:#fff;">ON</button>
																	<button type="button" class="btn <?php if ($settings->status_follow_unfollow=="off") echo 'btn-danger' ?>" id="statusFollowOffButton" style="color:#fff;">OFF</button>
																	<input type="hidden" value="{{$settings->status_follow_unfollow}}" name="data[status_follow_unfollow]" id="status_follow_unfollow">
																</div>
															</div>
														</div>
														<div class="row status-follow" <?php if ($settings->status_follow_unfollow=="off") echo "style='display:none;'" ?>>
															<div class="col-md-4 col-sm-4 col-xs-4">
																<b>Activity</b>
																<span class="glyphicon glyphicon-question-sign hint-button tooltipPlugin" title="<div class='panel-heading'>Follow Activity</div><div class='panel-content'>PILIH salah satu <strong>Follow / Unfollow</strong>. Tidak bisa bersamaan</div>">
																</span>
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
														<div class="row status-follow status-unfollow" <?php if ($settings->status_follow_unfollow=="off") echo "style='display:none;'" ?>>
															<div class="col-md-4 col-sm-4 col-xs-4">
																<b>Follow Source</b>
																<span class="glyphicon glyphicon-question-sign tooltipPlugin" title="<div class='panel-heading'>Follow Source</div><div class='panel-content'>
																Pilih 1 dari 2 Follow Sources ini (Hanya yang dipilih yang dijalankan) : <br>
																<strong>Jika Follow Source</strong> : 'HASHTAGS' akan Follow sesuai Hashtags tsb.<br>
																<strong>Jika Follow Source</strong> : 'Followers of Usernames' akan Follow sesuai followers dari username yang dimasukkan tsb</div>"></span>
															</div>
															<div class="col-md-3 col-sm-8 col-xs-8">
																<div class="row">
																	<select class="form-control btnGroupOO" name="data[follow_source]" id="select-follow-source" style="margin-left:-5px;">
																		<option value="hashtags" <?php if ($settings->follow_source=='hashtags') echo "selected" ?>>Hashtags</option>
																		<option value="followers of username" <?php if ($settings->follow_source=='followers of username') echo "selected" ?>>Followers of username</option>
										<!--							<option value="following of username" <?php if ($settings->follow_source=='following of username') echo "selected" ?>>Following of username</option>-->
																</select>
																</div>
															</div>
														</div>

														<div class="row">
															<div class="col-md-4 col-sm-4 col-xs-4">
																<b>Auto Switch Follow-Unfollow</b>
																	<span class="glyphicon glyphicon-question-sign hint-button tooltipPlugin" title="<div class='panel-heading'>Auto Switch Follow-Unfollow</div><div class='panel-content'><b>ON</b> - Setelah follow/unfollow selesai, akan otomatis melakukan kebalikannya.<br><b>OFF</b> - Setelah follow selesai, hanya akan unfollow 1x saja.<br></div>">
																	</span>
															</div>
															<div class="col-md-3 col-sm-8 col-xs-8">
																<div class="row btnGroupOO">
																	<button type="button" class="btn <?php if ($settings->is_auto_follow) echo 'btn-primary' ?>" id="statusAutoFollowOnButton" style="color:#fff;">ON</button>
																	<button type="button" class="btn <?php if (!$settings->is_auto_follow) echo 'btn-danger' ?>" id="statusAutoFollowOffButton" style="color:#fff;">OFF</button>
																	<input type="hidden" value="{{$settings->is_auto_follow}}" name="data[is_auto_follow]" id="status_auto_follow">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-md-4 col-sm-4 col-xs-4">
																<b>Max Follow</b>
																	<span class="glyphicon glyphicon-question-sign hint-button tooltipPlugin" title="<div class='panel-heading'>Max Follow</div><div class='panel-content'>Angka maximum yang akan difollow. <br>Sesudah angka ini tercapai, celebgramme akan otomatis melakukan Unfollow. <br>Pilih angka antara 1000 s/d 7000, lebih besar lebih efektif.<br></div>">
																	</span>
															</div>
															<div class="col-md-3 col-sm-8 col-xs-8">
																<div class="row">
																	<input type="number" value="{{$settings->max_follow}}" name="data[max_follow]" id="max_follow" class="form-control" style="margin-left:-5px;">
																</div>
															</div>
														</div>

														
														<div class="row status-follow status-unfollow" <?php if ($settings->status_follow_unfollow=="off") echo "style='display:none;'" ?>>
															<div class="col-md-12 col-sm-12 col-xs-12">
																<div class="row">
																	<div class="col-md-4 col-sm-5 col-xs-5">
																		<b>Don't Follow Private User</b>
																		<span class="glyphicon glyphicon-question-sign tooltipPlugin" title="<div class='panel-heading'>Dont Follow Private users</div><div class='panel-content'>
																		Jika Dont Follow Private Users dicentang,<br> Maka proses follow tidak akan memfollow account-account IG yang private
																		</div>"></span>
																	</div>
																	<div class="col-md-8 col-sm-7 col-xs-7">
																		<div class="row btnGroupOO">
																			<button type="button" class="btn <?php if ($settings->dont_follow_pu) echo 'btn-primary' ?>" id="DontFollowPUOnButton" style="color:#fff;">ON</button>
																			<button type="button" class="btn <?php if (!$settings->dont_follow_pu) echo 'btn-danger' ?>" id="DontFollowPUOffButton" style="color:#fff;">OFF</button>
																			<input type="hidden" value="{{$settings->dont_follow_pu}}" name="data[dont_follow_pu]" id="dont_follow_pu">
																		</div>
																	</div>
																</div>
															</div>
															<div class="col-md-12 col-sm-12 col-xs-12 status-follow status-unfollow" <?php if ($settings->status_follow_unfollow=="off") echo "style='display:none;'" ?>>
																<div class="row">
																	<div class="col-md-4 col-sm-5 col-xs-5">
																		<b>Don't Follow Same User</b>
																		<span class="glyphicon glyphicon-question-sign tooltipPlugin" title="<div class='panel-heading'>Dont Follow Same users</div><div class='panel-content'>
																		Jika Dont Follow Same Users dicentang,<br> Maka proses follow tidak akan memfollow account-account IG yang pernah difollow oleh celebgramme
																		</div>"></span>
																	</div>
																	<div class="col-md-8 col-sm-7 col-xs-7">
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
										
										<div class="row advanced-manual-setting <?php if ($settings->status_auto) echo "hide"; ?>" id="div-hashtags" <?php if ( ( ($settings->status_follow_unfollow=="off") && ($settings->status_like=="off") && ($settings->status_comment=="off")) || ( (($settings->follow_source=='followers of username') || ($settings->follow_source=='following of username')) && ($settings->status_follow_unfollow=="on")&& ($settings->status_like=="off")&& ($settings->status_comment=="off") )  ) echo "style='display:none;'" ?>>
											<div class="col-md-12 col-sm-12 col-xs-12">
												<div class="card m-b-0 m-t--50" style="background:transparent;box-shadow:none;">
													<div class="header">
														<h2>
															Media Source &nbsp;: &nbsp; #Hashtags &nbsp;<img class="cursorActive tooltipPlugin" src="{{asset('/new-dashboard/images/questionIcon.png')}}" title="<div class='panel-heading'>Media source : Hashtags</div><div class='panel-content'>
															Pastikan Hashtags yang anda input, <br>
															mengandung minimal 1000 posts tiap hashtagsnya<br>
															• ADD <strong>MIN 10 Hashtags</strong> <br>
															• TIDAK PERLU ADD <strong>simbol # (tanda pagar) </strong><br>
															• Anda dapat menambahkan <strong>MAX 50 Hashtags</strong>
															</div>">
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
										
										<div class="row advanced-manual-setting <?php if ($settings->status_auto) echo 'hide'; ?>" id="div-usernames" <?php if ( ($settings->follow_source=='hashtags') || ($settings->status_follow_unfollow=="off") ) echo "style='display:none;'" ?>>
											<div class="col-md-12 col-sm-12 col-xs-12">
												<div class="card m-b-0 m-t--50" style="background:transparent;box-shadow:none;">
													<div class="header">
														<h2>
															Media Source &nbsp;: &nbsp; Usernames &nbsp;<img class="cursorActive tooltipPlugin" src="{{asset('/new-dashboard/images/questionIcon.png')}}" title='<div class="panel-heading">Media source : Usernames</div><div class="panel-content">• <strong>Add MIN 10 username</strong> jika menggunakan "Usernames" di Media Source. <br>• Anda dapat menambahkan <strong>MAX 50 usernames.</strong></div>'>
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
										
										
										<div class="row advanced-manual-setting <?php if ($settings->status_auto) echo "hide"; ?>">
											<div class="col-md-12 col-sm-12 col-xs-12">
												<div class="card m-b-0 m-t--50" style="background:transparent;box-shadow:none;">
													<div class="header">
														<h2>
															Like &nbsp; <img class="cursorActive tooltipPlugin" src="{{asset('/new-dashboard/images/questionIcon.png')}}" title='<div class="panel-heading">Like</div><div class="panel-content"><b>ON</b> - Aktifitas mengelike akun target dijalankan<br><b>OFF</b> - Aktifitas mengelike di STOP<br></div>'>
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
														<!--
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
														
														<div class="row" id="div-comment" <?php if ($settings->status_comment=="off")   { echo "style='display:none;'"; } ?>>
															<div class="col-md-12 col-sm-12 col-xs-12">
																<div class="row">
																	<div class="col-md-6 col-sm-12 col-xs-12">
																		<label>Comments</label> &nbsp <span class="glyphicon glyphicon-question-sign tooltipPlugin" title="<div class='panel-heading'>Comments</div><div class='panel-content'>
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
																		<label>Copy contoh spin comment</label> &nbsp <span class="glyphicon glyphicon-menu-down tooltipPlugin" title='<div class="panel-content">								{asli|serius}, {nice|kerennn|cool|wow|keren|cooooolll|niceeeee} {sekaleee|sekali|banget|beneran|bener} {photo|foto|shot|poto|pic}{kamu|ini} 
																		<br> <br>
																		{nice|kerennn|cool|wow|keren|cooooolll|niceeeee} {sekaleee|sekali|banget|beneran|bener} {photo|foto|shot|poto|pic}{kamu|ini} <br> <br>
																		{wow|amazing|incredible|whoa|seriously} {your|the|this} {photo|picture|photograph|image|foto} {is awesome|rocks !|very nice} 
																		</div>'>
																		</span> 
																	</div>
																</div>
																<div class="row">
																	<div class="col-md-6 col-sm-12 col-xs-12">
																		<label>Penjelasan spin comment</label> &nbsp <span class="glyphicon glyphicon-question-sign tooltipPlugin" title='<div class="panel-heading">Penjelasan fitur spin comment</div>								<div class="panel-content"><strong>Gunakan Feature "Spin Comment" </strong>contoh : <br>
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
																<div class="col-md-6 col-sm-12 col-xs-12">
																	<p data-toggle="modal" data-target="#myModalCreateCommentEmoji" style="cursor:pointer;">Create Emoji</p>
																</div>
															</div>
															<div class="row">
																<div class="col-md-12 col-sm-12 col-xs-12">
																	<p align="right" data-toggle="modal" data-target="#myModal" style="cursor:pointer;position: absolute;right: 35px;z-index: 10;" class="button-copy" data-text="textarea-comments">copy</p>
																	<input type="text" id="textarea-comments" class="" name="data[comments]" value="{{$settings->comments}}">
																	<script>
																	var $selectComment = $('#textarea-comments').selectize({
																				plugins:['remove_button'],
																				delimiter: ';',
																				persist: false,
																				onChange: function(value) {
																				},
																				create: function(input) {
																					return {
																						value: input,
																						text: input
																					}
																				},
																			});
																	var selectizeComment = $selectComment[0].selectize;
																	</script>
																</div>
																
																
  <div class="modal fade" id="myModalCreateCommentEmoji" role="dialog">
    <div class="modal-dialog">
    
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Create your Emoji Comment 
										<span class="glyphicon glyphicon-question-sign tooltipPlugin" title="<div class='panel-heading'>Create your Emoji Comment</div><div class='panel-content'>• Fasilitas untuk mencreate emoji ke textbox comment. <br>• Text yang sama persis tidak dapat di paste ke textbox comment</div>">
							
					</span>

					</h4>
        </div>
        <div class="modal-body">
					<div id="textarea-add-comment">
					</div>
					<script>
						textareaAddComment = $("#textarea-add-comment").emojioneArea({
							pickerPosition: "bottom",
						});
						textareaAddComment[0].emojioneArea.setText("");
					</script>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" id="button-create-comment">Add comment</button>
        </div>
      </div>
<script>
document.getElementById("button-create-comment").addEventListener("click", function() {
	selectizeComment.addOption({value: textareaAddComment[0].emojioneArea.getText(), text: textareaAddComment[0].emojioneArea.getText()});
	selectizeComment.addItem(textareaAddComment[0].emojioneArea.getText(),false);
	textareaAddComment[0].emojioneArea.setText("");
});
</script>
      
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
                    
										
										<div class="row advanced-manual-setting <?php if ($settings->status_auto) echo "hide"; ?>">
											<div class="col-md-12 col-sm-12 col-xs-12">
												<div class="card m-b-0 m-t--50" style="background:transparent;box-shadow:none;">
													<div class="header">
														<h2>
															Custom Days Activity &nbsp; <img class="cursorActive tooltipPlugin" src="{{asset('/new-dashboard/images/questionIcon.png')}}" title='<div class="panel-heading">Custom Days Activity</div><div class="panel-content"><b>Bebas pilih Follow-Like</b> di hari yang anda inginkan, <br>agar aktifitas terlihat lebih natural.<br></div>'>
														</h2>
													</div>
													<div class="body" style="background:transparent;box-shadow:none;">
														<div class="row">
															<div class="col-md-4 col-sm-4 col-xs-4">
																<b>Sunday</b>
															</div>
															<div class="col-md-3 col-sm-8 col-xs-8">
																	<input type="checkbox" <?php if ($settings->is_sunday_follow) { echo "checked"; } ?> name="data[is_sunday_follow]" id="is_sunday_follow" class="follow-day-activity" <?php if ($settings->status_follow_unfollow=="off") { echo "readonly"; } ?>>
																	<label for="is_sunday_follow">Follow</label>
																	&nbsp &nbsp
																	<input type="checkbox" <?php if ($settings->is_sunday_like) { echo "checked"; } ?> name="data[is_sunday_like]" id="is_sunday_like" class="like-day-activity" <?php if ($settings->status_like=="off") { echo "readonly"; } ?>>
																	<label for="is_sunday_like">Like</label>
															</div>
														</div>
														
														<div class="row">
															<div class="col-md-4 col-sm-4 col-xs-4">
																<b>Monday</b>
															</div>
															<div class="col-md-3 col-sm-8 col-xs-8">
																	<input type="checkbox" <?php if ($settings->is_monday_follow) { echo "checked"; } ?> name="data[is_monday_follow]" id="is_monday_follow" class="follow-day-activity" <?php if ($settings->status_follow_unfollow=="off") { echo "readonly"; } ?>>
																	<label for="is_monday_follow">Follow</label>
																	&nbsp &nbsp
																	<input type="checkbox" <?php if ($settings->is_monday_like) { echo "checked"; } ?> name="data[is_monday_like]" id="is_monday_like" class="like-day-activity" <?php if ($settings->status_like=="off") { echo "readonly"; } ?>>
																	<label for="is_monday_like">Like</label>
															</div>
														</div>
														
														<div class="row">
															<div class="col-md-4 col-sm-4 col-xs-4">
																<b>Tuesday</b>
															</div>
															<div class="col-md-3 col-sm-8 col-xs-8">
																	<input type="checkbox" <?php if ($settings->is_tuesday_follow) { echo "checked"; } ?> name="data[is_tuesday_follow]" id="is_tuesday_follow" class="follow-day-activity" <?php if ($settings->status_follow_unfollow=="off") { echo "readonly"; } ?>>
																	<label for="is_tuesday_follow">Follow</label>
																	&nbsp &nbsp
																	<input type="checkbox" <?php if ($settings->is_tuesday_like) { echo "checked"; } ?> name="data[is_tuesday_like]" id="is_tuesday_like" class="like-day-activity" <?php if ($settings->status_like=="off") { echo "readonly"; } ?>>
																	<label for="is_tuesday_like">Like</label>
															</div>
														</div>
														
														<div class="row">
															<div class="col-md-4 col-sm-4 col-xs-4">
																<b>Wednesday</b>
															</div>
															<div class="col-md-3 col-sm-8 col-xs-8">
																	<input type="checkbox" <?php if ($settings->is_wednesday_follow) { echo "checked"; } ?> name="data[is_wednesday_follow]" id="is_wednesday_follow" class="follow-day-activity" <?php if ($settings->status_follow_unfollow=="off") { echo "readonly"; } ?>>
																	<label for="is_wednesday_follow">Follow</label>
																	&nbsp &nbsp
																	<input type="checkbox" <?php if ($settings->is_wednesday_like) { echo "checked"; } ?> name="data[is_wednesday_like]" id="is_wednesday_like" class="like-day-activity" <?php if ($settings->status_like=="off") { echo "readonly"; } ?>>
																	<label for="is_wednesday_like">Like</label>
															</div>
														</div>
														
														<div class="row">
															<div class="col-md-4 col-sm-4 col-xs-4">
																<b>Thursday</b>
															</div>
															<div class="col-md-3 col-sm-8 col-xs-8">
																	<input type="checkbox" <?php if ($settings->is_thursday_follow) { echo "checked"; } ?> name="data[is_thursday_follow]" id="is_thursday_follow" class="follow-day-activity" <?php if ($settings->status_follow_unfollow=="off") { echo "readonly"; } ?>>
																	<label for="is_thursday_follow">Follow</label>
																	&nbsp &nbsp
																	<input type="checkbox" <?php if ($settings->is_thursday_like) { echo "checked"; } ?> name="data[is_thursday_like]" id="is_thursday_like" class="like-day-activity" <?php if ($settings->status_like=="off") { echo "readonly"; } ?>>
																	<label for="is_thursday_like">Like</label>
															</div>
														</div>
														
														<div class="row">
															<div class="col-md-4 col-sm-4 col-xs-4">
																<b>Friday</b>
															</div>
															<div class="col-md-3 col-sm-8 col-xs-8">
																	<input type="checkbox" <?php if ($settings->is_friday_follow) { echo "checked"; } ?> name="data[is_friday_follow]" id="is_friday_follow" class="follow-day-activity" <?php if ($settings->status_follow_unfollow=="off") { echo "readonly"; } ?>>
																	<label for="is_friday_follow">Follow</label>
																	&nbsp &nbsp
																	<input type="checkbox" <?php if ($settings->is_friday_like) { echo "checked"; } ?> name="data[is_friday_like]" id="is_friday_like" class="like-day-activity" <?php if ($settings->status_like=="off") { echo "readonly"; } ?>>
																	<label for="is_friday_like">Like</label>
															</div>
														</div>
														
														<div class="row">
															<div class="col-md-4 col-sm-4 col-xs-4">
																<b>Saturday</b>
															</div>
															<div class="col-md-3 col-sm-8 col-xs-8">
																	<input type="checkbox" <?php if ($settings->is_saturday_follow) { echo "checked"; } ?> name="data[is_saturday_follow]" id="is_saturday_follow" class="follow-day-activity" <?php if ($settings->status_follow_unfollow=="off") { echo "readonly"; } ?>>
																	<label for="is_saturday_follow">Follow</label>
																	&nbsp &nbsp
																	<input type="checkbox" <?php if ($settings->is_saturday_like) { echo "checked"; } ?> name="data[is_saturday_like]" id="is_saturday_like" class="like-day-activity" <?php if ($settings->status_like=="off") { echo "readonly"; } ?>>
																	<label for="is_saturday_like">Like</label>
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
                            <button id="button-save2" class="btn btn-lg btn-block bg-cyan">Save</button>
                          </div>
                          <div class="col-md-6 col-sm-6 col-xs-6">
                            <button data-id="{{$settings->id}}" class="btn btn-lg btn-block bgGreenLight btn <?php if ($settings->status=='stopped') { echo 'btn-success'; } else {echo 'btn-danger';} ?> button-action btn-{{$settings->id}}" value="<?php if ($settings->status=='stopped') { echo 'Start'; } else {echo 'Stop';}?>">
                            <?php if ($settings->status=='stopped') { echo "<span class='glyphicon glyphicon-play'></span> Start"; } else {echo "<span class='glyphicon glyphicon-stop'></span> Stop";}?>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                    
										
										
										
									</div>
								</div>
							<!--</div>-->
						<!--</div>-->
					</div>
					<input type="hidden" name="data[id]" value="{{$settings->id}}">
</form>
					
					<div id="DMAuto" class="tab-pane fade">
						<div class="clearfix"></div><br>
						<div class="row">
							<div class="col-md-12">
								<div class="card m-b-0">
									<div class="body bg-lightGrey margin-0 padding-0">
										<div class="row">
											<div class="col-md-12 col-sm-12 col-xs-12">
												<div class="tab-content">									
													<div class="row">
														<div class="clearfix"></div><br/>
														<div class="header">
															<h2>
																DM Auto Reply &nbsp;
															</h2>
														</div>
														<div class="body" style="background:transparent;box-shadow:none;">
															<div class="row">
																<div class="col-md-5 col-sm-12 col-xs-12">
																	<button type="button" class="btn <?php if ($settings->is_auto_responder) echo 'btn-primary' ?>" id="AutoResponderOnButton" style="color:#fff;margin-left:0px;">ON</button>
																	<button type="button" class="btn <?php if (!$settings->is_auto_responder) echo 'btn-danger' ?>" id="AutoResponderOffButton" style="color:#fff;">OFF</button>
																	<input type="hidden" value="<?php if (!is_null($settings->is_auto_responder)) { echo $settings->is_auto_responder; } else { echo "0"; } ?>" name="data[is_auto_responder]" id="is_auto_responder">
																	<span class="glyphicon glyphicon-question-sign tooltipPlugin" title='<div class="panel-heading">Turn OFF selama sedang aktif mengirim DM</div>								<div class="panel-content"> <span style="color:#fb483a">WARNING TURN OFF DM Auto Responder </span> Jika: <br> - Anda aktif mengirim Direct Message <br> - Membeli / menambah Followers dalam jumlah banyak <br> 
																	Untuk mencegah akun anda di ban Auto DM nya oleh Instagram. Karena Instagram mempunyai jumlah maximum Direct Message per-jamnya.<br> 
																		</div>'>
																	</span>
																</div>
															</div>
														</div>
														<div class="header">
															<h2>
																Welcome Message To New Followers (DM Auto Reply) &nbsp;
																<span class="glyphicon glyphicon-question-sign tooltipPlugin" title="<div class='panel-heading'>Welcome Message New Followers ( DM Auto Reply )</div><div class='panel-content'>Fitur ini mengirimkan Direct Message (DM) kepada <br>
																New Users saat pertama kali Menjadi Followers anda <br>
																- Gunakan fitur ini untuk menyapa mereka, sebagai WELCOME message <br>
																- Gunakan fitur SPIN message, sehingga lebih banyak variasi DM Auto Reply <br>
																- Gunakan minimal 5 suku kata tiap spin messagenya. ex : <br>
																{hai |halo |hello |moshi2 |hari ini, } <br>
																- Anda dapat meletakkan spasi (' ') didalam spin message, seperti contoh spin message diatas <br>
																- Atau anda juga dapat meletakkan spasi (' ') setelah atau antar spin message. <br>
																ex : {hai|halo|hello|moshi2|hari ini,} {kamu|anda|kk|gan|you}
																</div>">
																</span>
															</h2>
														</div>
														<!--<div class="col-md-12 col-sm-12 col-xs-12">
															<span>Welcome Message To New Followers (DM Auto Reply) </span>
															<span class="glyphicon glyphicon-question-sign tooltipPlugin" title="<div class='panel-heading'>Welcome Message New Followers ( DM Auto Reply )</div><div class='panel-content'>Fitur ini mengirimkan Direct Message (DM) kepada <br>
															New Users saat pertama kali Menjadi Followers anda <br>
															- Gunakan fitur ini untuk menyapa mereka, sebagai WELCOME message <br>
															- Gunakan fitur SPIN message, sehingga lebih banyak variasi DM Auto Reply
															</div>">
															</span>
															
														</div>-->
														
														<div class="body" style="background:transparent;box-shadow:none;">
															<div class="row">
																<div class="col-md-3 col-sm-3 col-xs-3">
																	<b>Delay DM Auto reply</b>
																		<span class="glyphicon glyphicon-question-sign hint-button tooltipPlugin" title="<div class='panel-heading'>Delay DM Auto reply</div><div class='panel-content'><b>Waktu minimum DM Auto reply dikirimkan</b> <br>setelah user baru memfollow akun anda.<br></div>">
																		</span>
																</div>
																<div class="col-md-3 col-sm-8 col-xs-8">
																	<div class="row">
																		<select class="form-control" style="margin-left:-5px;" name="data[delay_dm]" id="delay_dm">
																			<option value="3" <?php if ($settings->is_friday_like) { echo "selected"; } ?>>3 Menit<option>
																			<option value="5" <?php if ($settings->is_friday_like) { echo "selected"; } ?>>5 Menit<option>
																			<option value="15" <?php if ($settings->is_friday_like) { echo "selected"; } ?>>15 Menit<option>
																		</select>
																	</div>
																</div>
														
																<div class="col-md-12 col-sm-12 col-xs-12">
																	<label>Penjelasan spin message</label> &nbsp <span class="glyphicon glyphicon-question-sign tooltipPlugin" title='<div class="panel-heading">Penjelasan fitur spin message</div>								<div class="panel-content"><strong>Gunakan Feature "Spin Message" </strong>contoh : <br>
																	{wihh|wow|beneran,|asli|masak sih} {foto|image|photo|your post|postingan} {kamu|anda|nya|kk|gan}{keren|cool|mantappp|sipp|amazing|beautiful} <br>
																		*contoh diatas akan menghasilkan <strong>5x5x5x6 = 750 kombinasi messages </strong> sekaligus <br>
																	Gunakan minimal 5 suku kata tiap spin messagenya. ex : <br>
																	{hai|halo|hello|moshi2|hari ini, } <br>
																		</div>'>
																	</span>
																</div>
																<div class="col-md-12 col-sm-12 col-xs-12">
																	<label>Petunjuk tanda spin message</label> &nbsp
																	<?php $tempurl = url("images/petunjuk-spin.jpg"); ?>
																	<span class="glyphicon glyphicon-search tooltipPlugin" title='<div class="panel-heading">Petunjuk tanda baca spin message</div><div class="panel-content"><img src="{{$tempurl}}" width="800" height="250">
																	</div>'>
																	</span>
																	
																</div>
																
																<div class="col-md-12 col-sm-12 col-xs-12">
																	<!--<textarea class="form-control" placeholder="Input Your Welcome Message to New Followers" id="textarea-welcome-message" value="{{$settings->messages}}">{{$settings->messages}}</textarea> -->
																	<div id="textarea-welcome-message"></div>
																	<script>
																		textareaWelcomeMessage = $("#textarea-welcome-message").emojioneArea({
																			pickerPosition: "bottom",
																		});
																		textareaWelcomeMessage[0].emojioneArea.setText(<?php echo json_encode($settings->messages); ?>);
																	</script>
																	
																</div>
																<div class="col-md-2 col-sm-12 col-xs-12">
																	<button class="form-control btn btn-md bg-cyan pull-left" id="button-save-welcome-message" style="margin-left:0px;">Save</button>
																</div>
															</div>
														</div>
													</div>
													<!--
													<div class="header">
														<h2>
															DM Auto Responder &nbsp;
															<span class="glyphicon glyphicon-question-sign tooltipPlugin"  title="<div class='panel-heading'>Auto Responder</div><div class='panel-content'>Fitur DM Auto responder ini berguna untuk melakukan Follow up <br>
															dengan followers baru anda. Anda dapat menambahkan up to 5 Auto DM <br>
															dengan pilihan hari yang berbeda-beda ( dihitung sejak followers tersebut <br>
															menjadi followers anda )
															</div>"></span>
														</h2>
													</div>
													<div class="body" style="background:transparent;box-shadow:none;">
														<div class="row">
															<div class="row">
																<div class="col-md-2 col-sm-12 col-xs-12">
																	<button class="btn btn-md bg-cyan pull-left" data-toggle="modal" data-target="#add-autoresponder" id="button-create-auto-responder" style="margin-left:0px;">
																	<span class="glyphicon glyphicon-plus"></span> &nbsp Add Auto Responder</button>
																</div>
															</div>
															
															<div class="row" id="div-auto-responder">
															</div>
														</div>
													</div>
													-->
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
											<div class="" style="margin-bottom:-35px;margin-left:20px;">	
												<div class="btnTab">
														<div class="col-md-2 col-sm-12 col-xs-12 padding-0">
															<button class="btn btn-lg bg-cyan btn-block br-6 btnDmIn" data-toggle="tab" href="#DMInbox" data-is-refresh="0" id="button-back-inbox"><i class="fa fa-envelope"></i>&nbsp;<small class="text-white">DM Inbox</small> &nbsp <span class="badge br-6" id="badge-dm-inbox"></span></button>
														</div>
														<div class="col-md-2 col-sm-12 col-xs-12 padding-0">
															<button class="btn btn-lg bg-grey btn-block br-6 btnDmRe"  style="font-size:inherit;"data-toggle="tab" href="#DMRequest"><i class="fa fa-envelope text-white"></i>&nbsp;<small class="text-white">DM Request</small> &nbsp <span class="badge br-6" id="badge-dm-request"></span></button>
														</div>
														<!--<div class="col-md-3 col-sm-12 col-xs-12 padding-0">
															<button class="btn btn-lg bg-grey btn-block br-6 btnDmAu"  style="font-size:inherit;"data-toggle="tab" href="#DMAuto"><i class="fa fa-envelope text-white"></i>&nbsp;<small class="text-white">DM Auto Responder</small></button>
														</div>
														-->
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12 col-sm-12 col-xs-12">
												<div class="tab-content">
													<div id="DMInbox" class="tab-pane fade in active">
														<div class="row">
															<div class="col-md-2 col-sm-12 col-xs-12">
																<label>Sort by :</label>
															</div>
															<div class="col-md-3 col-sm-12 col-xs-12">
																<select class="form-control" id="sort-inbox-select">
																	<option value="1">
																		Waiting Responses
																	</option>
																	<option value="0">
																		All Responses
																	</option>
																</select>
															</div>
															<div class="col-md-3 col-sm-12 col-xs-12">
																<button class="btn btn-lg br-6 bg-cyan" id="button-refresh-DMInbox" data-is-refresh="1">Refresh</button>
															</div>
														</div>
														<div class="row">
															<div class="col-md-2 col-sm-12 col-xs-12">
																<input type="text" class="form-control" placeholder="search username" id="search-username-dminbox"> 
															</div>
														</div>
														<div class="col-md-12 col-sm-12 col-xs-12" id="dm-inbox-content">
														</div>
														
													
													</div>
													<div id="DMRequest" class="tab-pane">
													</div>
													
													<div id="chat-all" class="tab-pane">
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
					<!--<textarea id="textarea-copy" class="form-control" style="min-height:100px;height:auto;"></textarea>-->
					<input type="hidden" id="hidden-textarea-copy">
					<div id="textarea-copy">
					</div>
					<script>
						textareaCopyEmoji = $("#textarea-copy").emojioneArea({
							pickerPosition: "bottom",
						});
					</script>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" id="button-ok-copy">Copy All</button>
        </div>
      </div>
<script>
document.getElementById("button-ok-copy").addEventListener("click", function() {
	$("#hidden-textarea-copy").val(textareaCopyEmoji[0].emojioneArea.getText());
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
				Benar-benar UNIK, di SPIN dengan Kombinasi RATUSAN comments. <br>
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
	
	
	
	
  <!-- Modal confirm decline-->
	<div class="modal fade" id="confirm-decline" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
					<div class="modal-content">
							<div class="modal-header">
									Decline User
							</div>
							<div class="modal-body">
									Are you sure want to decline ?
							</div>
							<input type="hidden" id="hidden-thread-id">
							<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
									<button type="button" class="btn btn-danger btn-ok" id="decline-dm-req" data-dismiss="modal">Decline</button>
							</div>
					</div>
			</div>
	</div>	

	
  <!-- Modal Add Auto Responder-->
	<div class="modal fade" id="add-autoresponder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
					<div class="modal-content">
							<div class="modal-header">
									Auto responder
							</div>
							<div class="modal-body">
								<!-- form auto responder -->
								<form enctype="multipart/form-data" id="form-auto-responder">
									<div class="form-group form-group-sm row">
										<label class="col-xs-12 col-sm-12 md-12 control-label" for="formGroupInputSmall">Message&nbsp <span class="glyphicon glyphicon-question-sign tooltipPlugin" title='<div class="panel-heading">Penjelasan fitur spin message</div>								<div class="panel-content"><strong>Gunakan Feature "Spin Message" </strong>contoh : <br>
																	{wihh|wow|beneran,|asli|masak sih} {foto|image|photo|your post|postingan} {kamu|anda|nya|kk|gan}{keren|cool|mantappp|sipp|amazing|beautiful} <br>
																		*contoh diatas akan menghasilkan <strong>5x5x5x6 = 750 kombinasi messages </strong> sekaligus <br>
																	Gunakan minimal 5 suku kata tiap spin messagenya. ex : <br>
																	{hai|halo|hello|moshi2|hari ini, } <br>
																	</div>'>
																</span>
										</label> 
										<div class="col-sm-12 col-md-12 col-xs-12">
											<!--<textarea class="form-control" id="message_responder" name="message_responder" style="height:70px;"></textarea>-->
											<input type="hidden" id="message_responder" name="message_responder">
											<div id="divInput-message-responder"></div>
											<script>
											//auto responder, bukan welcome message
												welcomeMessageResponder = $("#divInput-message-responder").emojioneArea({
																				pickerPosition: "bottom",
																			});
												// welcomeMessageResponder[0].emojioneArea.setText("");
											</script>
											
										</div>
									</div>  
									<div class="form-group form-group-sm row">
										<label class="col-xs-12 col-sm-3 control-label" for="formGroupInputSmall">Hari ke -
											<span class="glyphicon glyphicon-question-sign tooltipPlugin" title="<div class='panel-heading'>Hari ke-</div><div class='panel-content'>Dihitung sejak Hari pertama user tersebut menjadi followers anda <br>
											Tidak ada batasan pada hari ke berapa. DM akan otomatis dikirim. <br>
											contoh :  <br>
											Hari ke-2  --> Jangan lupa cek Youtube channel kami yah kak, di .... <br>
											Hari ke-7 --> Setiap hari minggu ada sale di akun kita, dicek yah kak <br>
											dan lain sebagainya.
											</div>">
											</span>
										
										</label>
										<div class="col-sm-12 col-md-9">
											<input type="number" class="form-control" placeholder="Jumlah hari sejak di follow" name="num_of_day" id="num_of_day">
										</div>
									</div>  
									<input type="hidden" id="id-auto-responder" name="id-auto-responder">
									<input type="hidden" name="setting_id" value="<?php echo $settings->id; ?>">
								</form>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
								<button type="button" class="btn btn-info btn-ok" id="button-submit-auto-responder" data-dismiss="modal">Submit</button>
							</div>
					</div>
			</div>
	</div>	
	
	
  <!-- Modal delete auto responder-->
	<div class="modal fade" id="delete-auto-responder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
					<div class="modal-content">
							<div class="modal-header">
									Delete Auto Responder
							</div>
							<div class="modal-body">
									Are you sure want to delete ?
							</div>
							<input type="hidden" id="hidden-auto-responder-setting-id">
							<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
									<button type="button" class="btn btn-danger btn-ok" id="button-delete-auto-responder-setting" data-dismiss="modal">Delete</button>
							</div>
					</div>
			</div>
	</div>		
	
  <!-- Modal Show Alert Waiting with heavy load-->
	<div class="modal fade" id="alert-heavy-load" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index:10000000000;">
			<div class="modal-dialog">
					<div class="modal-content">
							<div class="modal-header">
									<h3>Please Wait</h3>
							</div>
							<div class="modal-body">
									Hi {{$settings->insta_username}}, System kami sedang melakukan <br> proses loading messages di inbox anda. <br> Silahkan menunggu maximum 3-5 menit & <br> Terima kasih atas kesabarannya. <br> 
									<span style="font-weight:Bold;color:#fb483a;">DO NOT CLOSE THIS WINDOWS <br>PLEASE WAIT UNTIL ALL DM LOADING IS COMPLETED</span>
							</div>
							<input type="hidden" id="hidden-auto-responder-setting-id">
							<div class="modal-footer">
									<button type="button" class="btn btn-info btn-ok" id="" data-dismiss="modal">OK</button>
							</div>
					</div>
			</div>
	</div>	
	
  <!-- Modal server respond for error or succes message display-->
	<div class="modal fade" id="server-respond" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
					<div class="modal-content">
							<div class="modal-header">
									Server Response
							</div>
							<div class="modal-body" id="server-response-alert">
								<p></p>
							</div>
							<input type="hidden" id="hidden-auto-responder-setting-id">
							<div class="modal-footer">
									<button type="button" class="btn btn-info btn-ok" id="" data-dismiss="modal">OK</button>
							</div>
					</div>
			</div>
	</div>		
	
	
	
@endsection
