@extends('member.index')

@section('content-auto-manage')
<?php use Celebgramme\Models\SettingMeta; ?>

<script type="text/javascript">

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

		$(document).click(function(e) {
				e.stopPropagation();
				var target = e.target;

				if ( $(target).is(".hint") != true ) {
//						$('.hint').hide();
					if (!$(target).is('.glyphicon-question-sign') ) {
							$('.glyphicon-question-sign').find(".hint").hide();
					}
					if (!$(target).is('.glyphicon-menu-down')  ) {
							$('.glyphicon-menu-down').find(".hint").hide();
					}
				}
		});
		
  $(document).ready(function() {
		$('.tooltipPlugin').tooltipster({
				theme: 'tooltipster-noir',
				contentAsHTML: true,
				interactive:true,
		});
		$("#extra-time").hide();
    // $('.add-spin-comment').click(function(e){
		$( "body" ).on( "click", ".add-spin-comment", function(e) {
			e.preventDefault();
			var $select = $("#textarea-comments").selectize();
			var selectize = $select[0].selectize;
			selectize.addOption({value:$(this).text(),text:$(this).text()}); //option can be created manually or loaded using Ajax
			selectize.addItem($(this).text()); 			
			console.log($(this).text());
    });
    $('#button-package').click(function(e){
			if ($("#extra-time").is(":visible") ){
				$("#extra-time").hide();
				$("#normal-time").fadeIn(1000);
				$(this).html("Normal Time Package");
			} else 
			if ($("#normal-time").is(":visible") ){
				$("#normal-time").hide();
				$("#extra-time").fadeIn(1000);
				$(this).html("Extra Time Package");
			}
    });
		
    // $( "body" ).on( "click", ".button-action", function(e) {
    $('.button-action').click(function(e){
      e.preventDefault();
      action = "";
      if ($(this).val()=="Start") { action = "start"; }
      if ($(this).val()=="Stop") { action = "stop"; }
      call_action(action,$(this).attr("data-id"));
    });

    $("#alert").hide();

		$('#followButton').click(function(e){
			$("#activity").val("follow");
			$('#followButton').addClass('btn-success');
			$('#unfollowButton').removeClass('btn-success');
			$('#div-unfollow-whitelist').fadeOut(500);

			$('.status-unfollow').fadeIn(500);
			if ( ( $("#select-follow-source").val() == "followers of username" ) || ( $("#select-follow-source").val() == "following of username" )) {
				$("#div-usernames").fadeIn(500);
			}
			
		});
		$('#unfollowButton').click(function(e){
			$("#activity").val("unfollow");
			$('#followButton').removeClass('btn-success');
			$('#unfollowButton').addClass('btn-success');
			$('#div-unfollow-whitelist').fadeIn(500);

			$('.status-unfollow').fadeOut(500);
			$('#div-usernames').fadeOut(500);
			
		});
		


		
		/*status follow like comment (on off nya) */
		$('#statusFollowOnButton').click(function(e){
			$("#status_follow_unfollow").val("on");
			$('#statusFollowOnButton').addClass('btn-primary');
			$('#statusFollowOffButton').removeClass('btn-danger');
			$(".status-follow").fadeIn(500);
			
			if ($('#unfollowButton').hasClass("btn-success")) {
				$('#div-unfollow-whitelist').fadeIn(500);
			}

			if ( $("#select-follow-source").val() == "hashtags" ) {
				$("#div-usernames").fadeOut(500);
				$("#div-hashtags").fadeIn(500);
			}
			if ( ( $("#select-follow-source").val() == "followers of username" ) || ( $("#select-follow-source").val() == "following of username" )) {
				$("#div-usernames").fadeIn(500);
				if (($('#statusLikeOffButton').hasClass("btn-danger")) && ($('#statusCommentOffButton').hasClass("btn-danger"))) {
					$('#div-hashtags').fadeOut(500);
				}
			}
		});
		$('#statusFollowOffButton').click(function(e){
			$("#status_follow_unfollow").val("off");
			$('#statusFollowOnButton').removeClass('btn-primary');
			$('#statusFollowOffButton').addClass('btn-danger');
			$(".status-follow").fadeOut(500);
			$('#div-unfollow-whitelist').fadeOut(500);
			$("#div-usernames").fadeOut(500);

			if ( (!$('#statusFollowOffButton').hasClass("btn-danger") && ( ( $("#select-follow-source").val() == "followers of username" ) || ( $("#select-follow-source").val() == "following of username" )) ) 
				&& ($('#statusLikeOffButton').hasClass("btn-danger")) && ($('#statusCommentOffButton').hasClass("btn-danger"))  ) 
			{
				$('#div-hashtags').fadeOut(500);
			}
		});

		$('#statusLikeOnButton').click(function(e){
			$("#status_like").val("on");
			$('#statusLikeOnButton').addClass('btn-primary');
			$('#statusLikeOffButton').removeClass('btn-danger');
			$("#div-hashtags").fadeIn(500);
		});
		$('#statusLikeOffButton').click(function(e){
			$("#status_like").val("off");
			$('#statusLikeOnButton').removeClass('btn-primary');
			$('#statusLikeOffButton').addClass('btn-danger');
			
			if ( (!$('#statusFollowOffButton').hasClass("btn-danger") && ( ( $("#select-follow-source").val() == "followers of username" ) || ( $("#select-follow-source").val() == "following of username" )) ) 
				&& ($('#statusLikeOffButton').hasClass("btn-danger")) && ($('#statusCommentOffButton').hasClass("btn-danger"))  ) 
			{
				$('#div-hashtags').fadeOut(500);
			}
		});

		$('#statusCommentOnButton').click(function(e){
			$("#status_comment").val("on");
			$('#statusCommentOnButton').addClass('btn-primary');
			$('#statusCommentOffButton').removeClass('btn-danger');
			$('#div-comment').fadeIn(500);
			$("#div-hashtags").fadeIn(500);
		});
		$('#statusCommentOffButton').click(function(e){
			$("#status_comment").val("off");
			$('#statusCommentOnButton').removeClass('btn-primary');
			$('#statusCommentOffButton').addClass('btn-danger');
			$('#div-comment').fadeOut(500);
			
			if ( (!$('#statusFollowOffButton').hasClass("btn-danger") && ( ( $("#select-follow-source").val() == "followers of username" ) || ( $("#select-follow-source").val() == "following of username" )) ) 
				&& ($('#statusLikeOffButton').hasClass("btn-danger")) && ($('#statusCommentOffButton').hasClass("btn-danger"))  ) 
			{
				$('#div-hashtags').fadeOut(500);
			}
		});
		
		
		
		
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

		$('.hint').hide();

		// $('.hint').click(function(e){
			// e.preventDefault();
			// e.stopPropagation();
		// });
		
		// $('.glyphicon-menu-down').click(function(e){
			// $(this).find('.hint').slideToggle();
		// });

		// $('.glyphicon-question-sign').click(function(e){
			// $(this).find('.hint').slideToggle();
		// });
		
		$( "#select-follow-source" ).change(function() {
			if ( $( this ).val() == "hashtags" ) {
				$("#div-usernames").fadeOut(500);
				$("#div-hashtags").fadeIn(500);
			}
			if ( ( $( this ).val() == "followers of username" ) || ( $( this ).val() == "following of username" )) {
				$("#div-usernames").fadeIn(500);
				if (($('#statusLikeOffButton').hasClass("btn-danger")) && ($('#statusCommentOffButton').hasClass("btn-danger"))) {
					$("#div-hashtags").fadeOut(500);
				}
			}
		});
		
    $('.selectize-default').selectize({
      plugins:['remove_button'],
      delimiter: ';',
      persist: false,
			onChange: function(value) {
               // alert(value);
				// console.log($(this).parent());
      },
      create: function(input) {
        return {
          value: input,
          text: input
        }
      },
    });
		
		
		
	// show current input values
	$('textarea.selectize-default,select.selectize-default,input.selectize-default').each(function() {
		var $container = $('<div style="font-size:11px;">').addClass('value').html('Current count: ');
		var $value = $('<span>').appendTo($container);
		var $input = $(this);
		var update = function(e) { 
			// $value.text(JSON.stringify($input.val())); 

			var str,res;
			str = JSON.stringify($input.val());
			res = str.split(";");
			if ($input.val() == "") {
				$value.text("0"); 
			} else {
				$value.text(res.length); 
			}
			// console.log(res.length);
			// $container.insertAfter($input.next());
		}

		$(this).on('change', update);
		update();

		$container.insertAfter($input.next());
		
		// $container.insertAfter($input.next());
	});
	
	$('.button-copy').click(function(e){
		$("#textarea-copy").val($("#"+$(this).attr("data-text")).val());
	});
		
	// $('#button-ok-copy').click(function(e){
		// console.log("asd");
		// e.preventDefault();
		// copyToClipboard($("#textarea-copy"));
	// });
		

  });
</script>



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





<div class="row">
  <div class="col-sm-12 col-md-12">            
    <div class="alert alert-danger col-sm-18 col-md-18" id="alert">
    </div>  
  </div>          
</div>                        
<?php if (SettingMeta::getMeta($settings->id,"auto_unfollow") == "yes" )  { ?>
<div class="row">
  <div class="col-sm-10 col-md-10">            
    <div class="alert alert-info col-sm-18 col-md-18" id="">
			*Proses auto unfollow akan dijalankan karena jumlah following anda telah mencapai 7250
    </div>  
  </div>          
</div>                        
<?php } ?>


<form enctype="multipart/form-data" id="form-setting">


<div class="row">
  <div class="col-md-12 col-sm-12">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Data Users</h3>
      </div>
      <div class="panel-body">
<div class="col-md-5 col-xs-5 col-sm-5 border-styling ">
	<?php 
	/*
	$photo = url('images/profile-default.png');
	$json_url = "https://api.instagram.com/v1/users/search?q=".$settings->insta_username."&client_id=03eecaad3a204f51945da8ade3e22839";
	$json = @file_get_contents($json_url);
	if($json == TRUE) { 
		$links = json_decode($json);
		if (count($links->data)>0) {
			// $photo = $links->data[0]->profile_picture;
			$photo = url('images/profile-default.png');
			foreach($links->data as $link){
				if (strtoupper($link->username) == strtoupper($settings->insta_username)){
					$photo = $link->profile_picture;
				}
			}
		} else {
			$photo = url('images/profile-default.png');
		}
	}
	*/
		if (SettingMeta::getMeta($settings->id,"photo_filename") == "0") {
			$photo = url('images/profile-default.png');
		} else {
			$photo = url("images/pp/". SettingMeta::getMeta($settings->id,"photo_filename"));
		}
	?>	
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="row"> <img src="{{$photo}}" class="circle-image"> </div>
		<div class="row"> <label>{{$settings->insta_username}}</label></div>
	</div>
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="row status-activity"> <p> Status activity : <?php if ($settings->status=='stopped') { echo '<span class="glyphicon glyphicon-stop"></span> <span style="color:#c12e2a; font-weight:Bold;">Stopped</span>'; } 
		else {echo '<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> <span style="color:#5cb85c; font-weight:Bold;">Started</span>';}?></p>
		</div>
		<?php if ($settings->status=='started') { ?>
		<div class="row"> 
			<p>Total waktu per akun:
				<span style="color:#5abe5a;">{{$view_timeperaccount}}</span>
			</p>
		</div>
		<?php } ?>
		<div class="row">
			<p>Total waktu berlangganan :
				<span style="color:#5abe5a;">{{$view_totaltime}}</span>
			</p>
		</div>
		<div class="row im-centered"> 
			<input type="button" value="Save" class="btn btn-info" id="button-save">
			<button data-id="{{$settings->id}}" class="btn <?php if ($settings->status=='stopped') { echo 'btn-success'; } else {echo 'btn-danger';} ?> button-action btn-{{$settings->id}}" value="<?php if ($settings->status=='stopped') { echo 'Start'; } else {echo 'Stop';}?>">
				<?php if ($settings->status=='stopped') { echo "<span class='glyphicon glyphicon-play'></span> Start"; } else {echo "<span class='glyphicon glyphicon-stop'></span> Stop";}?> 
			</button>
		</div>
	</div>
</div>
<div class="col-md-7 col-xs-7 col-sm-7 pricing" style="margin-left:-10px;">
	<div class="row">
		<div class="col-md-12 col-sm-12">
			<p>Perpanjang waktu berlangganan anda :</p>
		</div>
	</div>
	<div class="row">
		<div id="normal-time">
			<div class="col-md-3 col-sm-3 border-price">
				<div class="row im-centered"> 
					<p class="header">7</p>
				</div>
				<div class="row im-centered"> 
					<p class="header-description">Days</p>
				</div>
				<div class="row im-centered"> 
					<strong>Rp. 100.000</strong>
				</div>
				<div class="row im-centered"> 
				</div>
				<div class="row im-centered button-price"> 
					<a href="{{url('buy-more/16')}}"><input type="button" value="Buy now" class="btn btn-success"></a>
				</div>
			</div>
			<div class="col-md-3 col-sm-3 border-price">
				<div class="row im-centered"> 
					<p class="header">28 </p>
				</div>
				<div class="row im-centered"> 
					<p class="header-description">Days</p>
				</div>
				<div class="row im-centered"> 
					<strong>Rp. 195.000</strong>
				</div>
				<div class="row im-centered"> 
				</div>
				<div class="row im-centered button-price"> 
					<a href="{{url('buy-more/17')}}"><input type="button" value="Buy now" class="btn btn-success"></a>
				</div>
			</div>
			<div class="col-md-3 col-sm-3 border-price">
				<div class="row im-centered"> 
					<p class="header">88 </p>
				</div>
				<div class="row im-centered"> 
					<p class="header-description">Days</p>
				</div>
				<div class="row im-centered"> 
					<strong>Rp. 395.000</strong>
				</div>
				<div class="row im-centered"> 
				</div>
				<div class="row im-centered button-price"> 
					<a href="{{url('buy-more/18')}}"><input type="button" value="Buy now" class="btn btn-success"></a>
				</div>
			</div>
		</div>
		<div id="extra-time">
			<div class="col-md-3 col-sm-3 border-price">
				<div class="row im-centered"> 
					<p class="header">178 </p>
				</div>
				<div class="row im-centered"> 
					<p class="header-description">Days</p>
				</div>
				<div class="row im-centered"> 
					<strong>Rp. 695.000</strong>
				</div>
				<div class="row im-centered"> 
				</div>
				<div class="row im-centered button-price"> 
					<a href="{{url('buy-more/19')}}"><input type="button" value="Buy now" class="btn btn-success"></a>
				</div>
			</div>
			<div class="col-md-3 col-sm-3 border-price">
				<div class="row im-centered"> 
					<p class="header">268 </p>
				</div>
				<div class="row im-centered"> 
					<p class="header-description">Days</p>
				</div>
				<div class="row im-centered"> 
					<strong>Rp. 995.000</strong>
				</div>
				<div class="row im-centered"> 
				</div>
				<div class="row im-centered button-price"> 
					<a href="{{url('buy-more/25')}}"><input type="button" value="Buy now" class="btn btn-success"></a>
				</div>
			</div>
			<div class="col-md-3 col-sm-3 border-price">
				<div class="row im-centered"> 
					<p class="header">358 </p>
				</div>
				<div class="row im-centered"> 
					<p class="header-description">Days</p>
				</div>
				<div class="row im-centered"> 
					<strong>Rp. 1.258.000</strong>
				</div>
				<div class="row im-centered"> 
				</div>
				<div class="row im-centered button-price"> 
					<a href="{{url('buy-more/20')}}"><input type="button" value="Buy now" class="btn btn-success"></a>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 col-sm-12">
			<a href="#" id="button-package">Extra Time Package</a>
		</div>
	</div>

</div>
      </div>
    </div>
  </div>  
</div>                        


<div class="row">
  <div class="col-md-12 col-sm-12">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Profile
						<span class="glyphicon glyphicon-question-sign hint-button tooltipPlugin" title="<div class='panel-heading'>Profile</div><div class='panel-content'>Jumlah Followers & Following ini hanya merupakan INFO saja ( bukan Real time ) <br> & hanya di update beberapa kali dalam sehari untuk memperingan kerja server</div>">
						</span>
				
				</h3>
      </div>
      <div class="panel-body">

				<div class="col-md-4">
					<label>Followers Saat Join</label>
					<?php echo number_format(intval (SettingMeta::getMeta($settings->id,"followers_join")),0,'','.'); ?>
				</div>				

				<div class="col-md-4">
					<label>Following Saat Join</label>
					<?php echo number_format(intval (SettingMeta::getMeta($settings->id,"following_join")),0,'','.'); ?>
				</div>				

				<div class="col-md-4">
				<label><br></label>
				<label><br></label>
				</div>				

				<?php 
				$followers = intval (SettingMeta::getMeta($settings->id,"followers"));
				$following = intval (SettingMeta::getMeta($settings->id,"following"));
				
				?>
				<div class="col-md-4">
					<label>Followers Hari ini</label>
					{{number_format($followers,0,'','.')}}
				</div>				

				<div class="col-md-4">
					<label>Following Hari ini</label>
					{{number_format($following,0,'','.')}}
				</div>				

      </div>
    </div>
  </div>  
</div>                    

<div class="row">
  <div class="col-md-12 col-sm-12">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Main Settings</h3>
      </div>
      <div class="panel-body">

        <div class="col-md-4">
          <label>Activity Speed</label> 
					<span class="glyphicon glyphicon-question-sign tooltipPlugin" title="<div class='panel-heading'>Activity speed</div><div class='panel-content'>Jika Akun anda BARU / Tdk aktif, START dgn SLOW/NORMAL speed utk 5 hari <br>• <strong>Slow</strong> = Melakukan 550 Likes, 120 comments, 350 follow/unfollow /hari <br>• <strong>Normal</strong> = Melakukan 1200 likes, 180 comments, 450 follow/unfollows /hari. <br>• <strong>Fast</strong> = Melakukan 1800 likes, 240 comments, 600 follow/unfollows /hari. <br></div>">
					</span>
          <select class="form-control" name="data[activity_speed]" title="Slow - Kecepatan yang aman untuk melakukan sekitar 480 Likes, 144 comments, 336 follows, 240 unfollow per hari ( kecepatan terbaik untuk awal pemakaian )">
            <option value="normal" <?php if ($settings->activity_speed=='normal') echo "selected" ?>>Normal</option>
            <option value="slow" <?php if ($settings->activity_speed=='slow') echo "selected" ?>>Slow</option>
            <option value="fast" <?php if ($settings->activity_speed=='fast') echo "selected" ?>>Fast</option>
          </select>
        </div>
				<!--
        <div class="col-md-4">
          <label>Media Source</label> 
					<span class="glyphicon glyphicon-question-sign" title="">
						<div class="hint">Aktifitas Follow, Like & Comments akan menggunakan Sumber Media ini<br>
						  Pilih Sumber Media untuk aktivitas Anda : <br>
							Hashtags - untuk menentukan Media sesuai Hashtags <br>
							Usernames - untuk menentukan Media berdasarkan Username <br>
							
						</div>
					</span>
          <select class="form-control" name="data[media_source]" id="select-media-source">
            <option value="hashtags" <?php if ($settings->media_source=='hashtags') echo "selected" ?>>Hashtags</option>
            <option value="usernames" <?php if ($settings->media_source=='usernames') echo "selected" ?>>Usernames</option>
          </select>
        </div>
				-->
        <div class="col-md-4">
          <label>Media Age</label> 
					<span class="glyphicon glyphicon-question-sign tooltipPlugin" title="<div class='panel-heading'>Media Age</div><div class='panel-content'>Pilih Umur Media / Media Age yang akan berinteraksi dengan anda.<br>
							<strong>Latest</strong> : Hanya post terbaru (default)<br>
							<strong>Any</strong>    : Post kapan saja<br>
</div>">
					</span>
          <select class="form-control" name="data[media_age]">
            <option value="latest" <?php if ($settings->media_age=='latest') echo "selected" ?>>Latest</option>
            <!--<option value="newest" <?php if ($settings->media_age=='newest') echo "selected" ?>>Newest</option>
            <option value="1 hour" <?php if ($settings->media_age=='1 hour') echo "selected" ?>>1 Hour</option>
            <option value="12 hours" <?php if ($settings->media_age=='12 hours') echo "selected" ?>>12 Hours</option>
            <option value="1 day" <?php if ($settings->media_age=='1 day') echo "selected" ?>>1 Day</option>
            <option value="3 day" <?php if ($settings->media_age=='3 day') echo "selected" ?>>3 Days</option>
            <option value="1 week" <?php if ($settings->media_age=='1 week') echo "selected" ?>>1 Week</option>
            <option value="2 week" <?php if ($settings->media_age=='2 week') echo "selected" ?>>2 Weeks</option>
            <option value="1 month" <?php if ($settings->media_age=='1 month') echo "selected" ?>>1 Month</option>-->
            <option value="any" <?php if ($settings->media_age=='any') echo "selected" ?>>Any</option>
          </select>
        </div>
				<!--
        <div class="col-md-4">
          <label>Media Type</label> 
					<span class="glyphicon glyphicon-question-sign tooltipPlugin" title="<div class='panel-heading'>Media Type</div><div class='panel-content'><strong>Media yang dipakai untuk interaksi</strong>, Foto atau Video atau Semuanya </div>">
					</span>
          <select class="form-control" name="data[media_type]">
            <option value="any" <?php if ($settings->media_type=='any') echo "selected" ?>>Any</option>
            <option value="photos" <?php if ($settings->media_type=='photos') echo "selected" ?>>Photos</option>
            <option value="videos" <?php if ($settings->media_type=='videos') echo "selected" ?>>Videos</option>
          </select>
        </div>
				-->
      </div>
    </div>
  </div>  
</div>                        

<div class="row">
  <div class="col-md-12 col-sm-12">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Follow</h3>
      </div>
      <div class="panel-body">

        <div class="row">
					<div class="col-md-4">
						<label>Status</label>
						<span class="glyphicon glyphicon-question-sign hint-button tooltipPlugin" title="<div class='panel-heading'>Follow Status</div><div class='panel-content'><strong>Status ON </strong>akan melakukan 'Follow/Unfollow' <br>
						                  <strong>Status OFF </strong>Tidak akan melakukan 'Follow/Unfollow' <br>
															*PS: Status OFF berguna apabila anda hanya mau melakukan Aktifitas lain (Like & Comment) saja</div>">
						</span>
						<div class="btn-group col-md-12 col-sm-12" role="group" aria-label="..." style="margin-left:-15px;">
							<button type="button" class="btn <?php if ($settings->status_follow_unfollow=="on") echo 'btn-primary' ?>" id="statusFollowOnButton" style="color:#fff;">ON</button>
							<button type="button" class="btn <?php if ($settings->status_follow_unfollow=="off") echo 'btn-danger' ?>" id="statusFollowOffButton" style="color:#fff;">OFF</button>
							<input type="hidden" value="{{$settings->status_follow_unfollow}}" name="data[status_follow_unfollow]" id="status_follow_unfollow">
						</div>
					</div>


					<div class="col-md-4 status-follow" <?php if ($settings->status_follow_unfollow=="off") echo "style='display:none;'" ?>>
						<label>Activity</label>
						<span class="glyphicon glyphicon-question-sign hint-button tooltipPlugin" title="<div class='panel-heading'>Follow Activity</div><div class='panel-content'>PILIH salah satu <strong>Follow / Unfollow</strong>. Tidak bisa bersamaan</div>">
						</span>
						<div class="btn-group col-md-12 col-sm-12" role="group" aria-label="..." style="margin-left:-15px;">
							<button type="button" class="btn <?php if ($settings->activity=="follow") echo 'btn-success' ?>" id="followButton" style="color:#fff;">Follow</button>
							<button type="button" class="btn <?php if ($settings->activity=="unfollow") echo 'btn-success' ?>" id="unfollowButton" style="color:#fff;">Unfollow</button>
							<input type="hidden" value="{{$settings->activity}}" name="data[activity]" id="activity">
						</div>				
					</div>				
        </div>

        <div class="row status-follow status-unfollow" <?php if ($settings->status_follow_unfollow=="off") echo "style='display:none;'" ?>>
          <div class="col-md-12 checkbox">
            <label><input type="checkbox" name="data[dont_follow_su]" <?php if($settings->dont_follow_su) echo "checked"; ?> >Don't Follow same user</label> 
						<span class="glyphicon glyphicon-question-sign tooltipPlugin" title="<div class='panel-heading'>Dont follow same user</div><div class='panel-content'><strong>Tidak akan Follow lagi user yang sama</strong>, setelah anda Unfollow mereka.</div>">
						</span>
          </div>
					<!--
          <div class="col-md-12 checkbox" <?php if ($settings->status_follow_unfollow=="off") echo "style='display:none;'" ?>>
            <label><input type="checkbox" name="data[dont_follow_pu]" <?php if($settings->dont_follow_pu) echo "checked"; ?> >Dont Follow private user</label> 
						<span class="glyphicon glyphicon-question-sign tooltipPlugin" title="<div class='panel-heading'>Dont follow private user</div><div class='panel-content'><strong>Tidak akan Follow </strong>Private Account User</div>">
						</span>
          </div>
					-->
        </div>
        <div class="row status-follow status-unfollow" <?php if ($settings->status_follow_unfollow=="off") echo "style='display:none;'" ?>>
          <div class="col-md-4">
            <label>Follow source</label> 
						<span class="glyphicon glyphicon-question-sign tooltipPlugin" title="<div class='panel-heading'>Follow Source</div><div class='panel-content'>
						Pilih 1 dari 3 Follow Sources ini (Hanya yang dipilih yang dijalankan) : <br>
						<strong>Jika Follow Source</strong> : 'HASHTAGS' akan Follow sesuai Hashtags tsb.<br>
						<strong>Jika Follow Source</strong> : 'USERNAMES' bisa pilih mau Follow siapa. 'Followersnya/Following' nya username tsb.</div>"></span>
            <select class="form-control" name="data[follow_source]" id="select-follow-source">
							<option value="hashtags" <?php if ($settings->follow_source=='hashtags') echo "selected" ?>>Hashtags</option>
							<option value="followers of username" <?php if ($settings->follow_source=='followers of username') echo "selected" ?>>Followers of username</option>
<!--							<option value="following of username" <?php if ($settings->follow_source=='following of username') echo "selected" ?>>Following of username</option>-->
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>  
</div>                        

<div class="row" id="div-unfollow-whitelist" <?php if ( ($settings->activity=="follow") || ($settings->status_follow_unfollow=="off") ) { echo "style='display:none;'"; } ?>>
  <div class="col-md-12 col-sm-12">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Unfollow whitelist</h3>
      </div>
      <div class="panel-body">

        <div class="row">
          <div class="col-md-11">
            <label>Usernames whitelist</label> 
						<span class="glyphicon glyphicon-question-sign tooltipPlugin" title='<div class="panel-heading">Usernames whitelist</div><div class="panel-content">• Saat anda UNFOLLOW. <strong>Usernames di "Whitelist" ini akan diabaikan / tidak akan di "UNFOLLOW"</strong><br>
							• <strong>Usulan penggunaan : </strong>teman, pasangan, rekan sekerja & siapapun yang anda mau KEEP FOLLOW</div>'></span>
          </div>
					<div class="col-md-1 col-sm-1 col-xm-1">
						<p align="right" data-toggle="modal" data-target="#myModal" style="cursor:pointer;" class="button-copy" data-text="textarea-unfollow-whitelist">copy</p>
          </div>
					<div class="col-md-12">
						<textarea class="selectize-default" id="textarea-unfollow-whitelist" name="data[usernames_whitelist]">{{$settings->usernames_whitelist}}</textarea>
					</div>
					
        </div>

      </div>
    </div>
  </div>  
</div>                    

<div class="row" id="div-usernames" <?php if ( ($settings->follow_source=='hashtags') || ($settings->status_follow_unfollow=="off") ) echo "style='display:none;'" ?>>
  <div class="col-md-12 col-sm-12">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Media source : Usernames</h3>
      </div>
      <div class="panel-body">

        <div class="row">
          <div class="col-md-11">
            <label>Usernames</label> 
						<span class="glyphicon glyphicon-question-sign tooltipPlugin" title='<div class="panel-heading">Media source : Usernames</div><div class="panel-content">• <strong>Add MIN 10 username</strong> jika menggunakan "Usernames" di Media Source. <br>• Anda dapat menambahkan <strong>MAX 50 usernames.</strong></div>'></span>
          </div>
					<div class="col-md-1 col-sm-1 col-xm-1">
						<p align="right" data-toggle="modal" data-target="#myModal" style="cursor:pointer;" class="button-copy" data-text="textarea-username">copy</p>
          </div>
					<div class="col-md-12">						
						<textarea class="selectize-default" id="textarea-username" name="data[username]">{{$settings->username}}</textarea>
					</div>
        </div>

      </div>
    </div>
  </div>  
</div>                    

<div class="row" id="div-hashtags" <?php if ( ( ($settings->status_follow_unfollow=="off") && ($settings->status_like=="off") && ($settings->status_comment=="off")) || ( (($settings->follow_source=='followers of username') || ($settings->follow_source=='following of username')) && ($settings->status_follow_unfollow=="on") )  ) echo "style='display:none;'" ?>>
  <div class="col-md-12 col-sm-12">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Media source : Hashtags</h3>
      </div>
      <div class="panel-body">

        <div class="row">
          <div class="col-md-11">
            <label>Hashtags</label> 
						<span class="glyphicon glyphicon-question-sign tooltipPlugin" title="<div class='panel-heading'>Media source : Hashtags</div><div class='panel-content'>• ADD <strong>MIN 10 Hashtags</strong> <br>
								• TIDAK PERLU ADD <strong>simbol # (tanda pagar) </strong><br>
								• Anda dapat menambahkan <strong>MAX 50 Hashtags</strong>
</div>"></span>
          </div>
					<div class="col-md-1 col-sm-1 col-xm-1">
						<p align="right" data-toggle="modal" data-target="#myModal" style="cursor:pointer;" class="button-copy" data-text="textarea-hashtags">copy</p>
          </div>
					<div class="col-md-12">
						<textarea class="selectize-default" id="textarea-hashtags" name="data[hashtags]">{{$settings->hashtags}}</textarea>
					</div>
        </div>

      </div>
    </div>
  </div>  
</div>                    

<div class="row">
  <div class="col-md-12 col-sm-12">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Like & Comment</h3>
      </div>
      <div class="panel-body">

        <div class="row">
          <div class="col-md-12">
            <label>"Like & Comments" hanya menggunakan media source : Hashtags</label> 
						<span class="glyphicon glyphicon-question-sign tooltipPlugin" title='<div class="panel-heading">Like & Comment</div><div class="panel-content">Wajib mengisi <strong>min 10 "Hashtags" </strong>jika memakai Fitur "Like & Comments"</div>'></span>
						
          </div>
        </div>
				
        <div class="row">
					<div class="col-md-12">
						<label>Like</label> 
						<div class="btn-group col-md-12 col-sm-12" role="group" aria-label="..." style="margin-left:-15px;">
							<button type="button" class="btn <?php if ($settings->status_like=="on") echo 'btn-primary' ?>" id="statusLikeOnButton" style="color:#fff;">ON</button>
							<button type="button" class="btn <?php if ($settings->status_like=="off") echo 'btn-danger' ?>" id="statusLikeOffButton" style="color:#fff;">OFF</button>
							<input type="hidden" value="{{$settings->status_like}}" name="data[status_like]" id="status_like">
						</div>				
					</div>				
        </div>
				
        <div class="row">
					<div class="col-md-12">
						<label>Comment</label> 
						<div class="btn-group col-md-12 col-sm-12" role="group" aria-label="..." style="margin-left:-15px;">
							<button type="button" class="btn <?php if ($settings->status_comment=="on") echo 'btn-primary' ?>" id="statusCommentOnButton" style="color:#fff;">ON</button>
							<button type="button" class="btn <?php if ($settings->status_comment=="off") echo 'btn-danger' ?>" id="statusCommentOffButton" style="color:#fff;">OFF</button>
							<input type="hidden" value="{{$settings->status_like}}" name="data[status_comment]" id="status_comment">
						</div>				
					</div>
        </div>
				

      </div>
    </div>
  </div>  
</div>   

<!--<div class="row">
  <div class="col-md-12 col-sm-12">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Likes</h3>
      </div>
      <div class="panel-body">

        <div class="col-md-4">
          <label>Likes min filter</label> 
					<span class="glyphicon glyphicon-question-sign" title="">
						<div class="hint">Likes hanya diberikan pada media (foto/video) yang mempunyai MIN Likes sesuai filter <br>
							Nilai yang disarankan : 1 - 5 <br>
							Nilai = 0 untuk menonaktifkan filter ini
						</div>
					</span>
          <input type="number" class="form-control" name="data[min_likes_media]" value="{{$settings->min_likes_media}}">
        </div>
        <div class="col-md-4">
          <label>Likes max filter</label> 
					<span class="glyphicon glyphicon-question-sign" title="">
						<div class="hint">Likes hanya diberikan pada media (foto/video) yang mempunyai MAX Likes sesuai filter <br>
							Nilai yang disarankan :50 - 100 <br>
							Nilai = 0 untuk menonaktifkan filter ini
						</div>
					</span>
          <input type="number" class="form-control" name="data[max_likes_media]" value="{{$settings->max_likes_media}}">
        </div>

      </div>
    </div>
  </div>  
</div>            
-->
<div class="row" id="div-comment" <?php if ($settings->status_comment=="off") { echo "style='display:none;'"; } ?>>
  <div class="col-md-12 col-sm-12">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Comment</h3>
      </div>
      <div class="panel-body">

<!--
        <div class="row">
          <div class="col-md-4 checkbox">
            <label><input type="checkbox" name="data[dont_comment_su]" <?php if($settings->dont_comment_su) echo "checked"; ?> >Dont Comment same user</label> 
						<span class="glyphicon glyphicon-question-sign" title="">
							<div class="hint">Ketika anda memberikan centang ke kotak ini, anda tidak akan memberikan comment lebih dari 1 pada foto atau video pada user yang sama.</div>
						</span>
          </div>
        </div>
-->
          <div class="row">
						<div class="col-md-5 col-sm-12 col-xm-12"">
							<label>Comments</label> 
							<span class="glyphicon glyphicon-question-sign tooltipPlugin" title="<div class='panel-heading'>Comments</div><div class='panel-content'>• <strong>Tambahkan : </strong><@owner> , untuk men-tag owner dari post tersebut<br>
									• apabila anda lupa, <strong>by Default Celebgramme akan selalu menambahkan</strong> tags <@owner> di akhir setiap comment anda<br>
                  • <strong>Komentar akan dipilih secara acak </strong>dari daftar ini. <br>
									• <strong>Celebgramme hanya memberikan 1x komentar </strong>pada setiap posting <br>
									• <strong>Min 10 komentar netral</strong> contoh: nice! <@owner>, awesome <@owner>, beautiful <@owner>, dll <br>
									• <strong>Komentar tidak boleh </strong>lebih dari 300 karakter. <br>
									• <strong>Komentar jangan menggunakan Hashtags </strong><br>
									• <strong>Komentar jangan menggunakan URL </strong> <br>
									• <strong>Komentar tidak boleh </strong>terdiri dari huruf kapital semua. <br>
									• <strong>Komentar HARUS berbeda </strong>satu sama lain. <br>
</div>"></span>
						</div>
						<div class="col-md-6 col-sm-12 col-xm-12"">
							<label>Copy contoh spin comment (click)</label>
							<span class="glyphicon glyphicon-menu-down tooltipPlugin" title='<div class="panel-content">								<a href="#" class="add-spin-comment">{asli|serius}, {nice|kerennn|cool|wow|keren|cooooolll|niceeeee} {sekaleee|sekali|banget|beneran|bener} {photo|foto|shot|poto|pic}{kamu|ini} <@owner></a>
								<br> <br>
								<a href="#" class="add-spin-comment">{nice|kerennn|cool|wow|keren|cooooolll|niceeeee} {sekaleee|sekali|banget|beneran|bener} {photo|foto|shot|poto|pic}{kamu|ini} <@owner></a> <br> <br>
								<a href="#" class="add-spin-comment">{wow|amazing|incredible|whoa|seriously} {your|the|this} {photo|picture|photograph|image|foto} {is awesome|rocks !|very nice} <@owner> </a>
</div>'>
							</span>
						</div>
          </div>
          <div class="row">
						<div class="col-md-5 col-sm-12 col-xm-12"">
							<label>Penjelasan fitur spin comment</label>
							<span class="glyphicon glyphicon-question-sign tooltipPlugin" title='<div class="panel-heading">Penjelasan fitur spin comment</div>								<div class="panel-content"><strong>Gunakan Feature "Spin Comment" </strong>contoh : <br>
																		{wihh|wow|beneran,|asli}{foto|image|photo}{kamu|anda|nya}{keren|cool|mantappp|sipp|amazing|beautiful} <br>
																			*contoh diatas akan menghasilkan <strong>4x3x3x6 = 216 kombinasi comments </strong> sekaligus <br>
																			*<strong>Spin Comment default akan digunakan</strong>, utk menghindari Comment yang sama berulang-ulang
	</div>'>
							</span>
						</div>
						<div class="col-md-6 col-sm-12 col-xm-12" ">
							<label>Petunjuk tanda baca spin comment</label>
							<?php $tempurl = url("images/petunjuk-spin.jpg"); ?>
							<span class="glyphicon glyphicon-search tooltipPlugin" title='<div class="panel-heading">Petunjuk tanda baca spin comment</div><div class="panel-content"><img src="{{$tempurl}}" width="800" height="250">
	</div>'>
							</span>
						</div>
						<div class="col-md-1 col-sm-1 col-xm-1">
							<p align="right" data-toggle="modal" data-target="#myModal" style="cursor:pointer;" class="button-copy" data-text="textarea-comments">copy</p>
						</div>
          </div>
          <div class="row">
						<div class="col-md-12">
							<input type="text" id="textarea-comments" class="selectize-default" name="data[comments]" value="{{$settings->comments}}">
						</div>
          </div>


      </div>
    </div>
  </div>  
</div>                        

<!--
<div class="row">
  <div class="col-md-12 col-sm-12">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Unfollow</h3>
      </div>
      <div class="panel-body">

        <div class="row">
          <div class="col-md-5 checkbox">
            <label><input type="checkbox" name="data[unfollow_wdfm]" <?php if($settings->unfollow_wdfm) echo "checked"; ?> >Unfollow who dont follow me</label> 
						<span class="glyphicon glyphicon-question-sign" title="">
						<div class="hint">Unfollow users yang tidak Follow back anda. <br>
						</div>
						</span>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <label>Unfollow source</label> 
						<span class="glyphicon glyphicon-question-sign" title="">
							<div class="hint">
								User yang mana yang akan di Unfollow? <br>
								Celebgramme - Unfollow Users yang anda dapatkan dari Celebgramme <br>
								All - Unfollow Semua Following anda
							</div>
						</span>
            <select class="form-control" name="data[unfollow_source]">
              <option value="celebgramme" <?php if ($settings->unfollow_source=='celebgramme') echo "selected" ?>>Celebgramme</option>
              <option value="all" <?php if ($settings->unfollow_source=='all') echo "selected" ?>>All</option>
            </select>
          </div>
        </div>


      </div>
    </div>
  </div>  
</div>                        
-->
<div class="row">
  <div class="col-md-2">
    <input type="button" value="Save" class="btn btn-info col-md-12 col-sm-12" id="button-save2">    
  </div>                    
  <div class="col-md-2">
		<button data-id="{{$settings->id}}" class="btn <?php if ($settings->status=='stopped') { echo 'btn-success'; } else {echo 'btn-danger';} ?> button-action btn-{{$settings->id}} col-md-12 col-sm-12" value="<?php if ($settings->status=='stopped') { echo 'Start'; } else {echo 'Stop';}?>">
			<?php if ($settings->status=='stopped') { echo "<span class='glyphicon glyphicon-play'></span> Start"; } else {echo "<span class='glyphicon glyphicon-stop'></span> Stop";}?> 
		</button>
		
  </div>                    
</div>                    
<input type="hidden" name="data[id]" value="{{$settings->setting_id}}">
</form>

<div class="row">
  <div class="col-md-12">
    <p align="center"> <br><br><br><br><br><br><br><br> @Copyright <a href="http://celebgramme.com/celebgramme">Celebgramme</a> 2016</p>    
  </div>                    
</div>                    


@endsection
