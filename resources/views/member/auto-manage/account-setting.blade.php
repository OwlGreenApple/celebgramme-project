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
                    $(".btn-"+data.id).html("<span class='glyphicon glyphicon-stop'></span> Start");
                    $(".btn-"+data.id).val("Start");
                    $(".btn-"+data.id).parent().parent().parent().find(".status-activity p").html(' Status activity : <span class="glyphicon glyphicon-stop"></span> <span style="color:#c12e2a; font-weight:Bold;">Stopped</span>');
                    $(".btn-"+data.id).removeClass("btn-danger");
                    $(".btn-"+data.id).addClass("btn-success");
                  }
                }
                else if(data.type=='error')
                {
									url = "<?php echo url('auto-manage') ?>";
									str = " <a href='"+url+"'>disini</a>";
									$("#alert").html($("#alert").html()+str);
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
		$("#extra-time").hide();
    $('.add-spin-comment').click(function(e){
			e.preventDefault();
			var $select = $("#textarea-comments").selectize();
			var selectize = $select[0].selectize;
			selectize.addOption({value:$(this).text(),text:$(this).text()}); //option can be created manually or loaded using Ajax
			selectize.addItem($(this).text()); 			
    });
    $('#button-package').click(function(e){
			if ($("#extra-time").is(":visible") ){
				$("#extra-time").hide();
				$("#normal-time").show();
				$(this).html("Normal Time Package");
			} else 
			if ($("#normal-time").is(":visible") ){
				$("#extra-time").show();
				$("#normal-time").hide();
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
			$('#div-unfollow-whitelist').hide();
		});
		$('#unfollowButton').click(function(e){
			$("#activity").val("unfollow");
			$('#followButton').removeClass('btn-success');
			$('#unfollowButton').addClass('btn-success');
			$('#div-unfollow-whitelist').show();
		});
		


		
		/*status follow like comment (on off nya) */
		$('#statusFollowOnButton').click(function(e){
			$("#status_follow_unfollow").val("on");
			$('#statusFollowOnButton').addClass('btn-primary');
			$('#statusFollowOffButton').removeClass('btn-danger');
			$(".status-follow").show();
			
			if ($('#unfollowButton').hasClass("btn-success")) {
				$('#div-unfollow-whitelist').show();
			}
		});
		$('#statusFollowOffButton').click(function(e){
			$("#status_follow_unfollow").val("off");
			$('#statusFollowOnButton').removeClass('btn-primary');
			$('#statusFollowOffButton').addClass('btn-danger');
			$(".status-follow").hide();
			$('#div-unfollow-whitelist').hide();
		});

		$('#statusLikeOnButton').click(function(e){
			$("#status_like").val("on");
			$('#statusLikeOnButton').addClass('btn-primary');
			$('#statusLikeOffButton').removeClass('btn-danger');
		});
		$('#statusLikeOffButton').click(function(e){
			$("#status_like").val("off");
			$('#statusLikeOnButton').removeClass('btn-primary');
			$('#statusLikeOffButton').addClass('btn-danger');
		});

		$('#statusCommentOnButton').click(function(e){
			$("#status_comment").val("on");
			$('#statusCommentOnButton').addClass('btn-primary');
			$('#statusCommentOffButton').removeClass('btn-danger');
			$('#div-comment').show();
		});
		$('#statusCommentOffButton').click(function(e){
			$("#status_comment").val("off");
			$('#statusCommentOnButton').removeClass('btn-primary');
			$('#statusCommentOffButton').addClass('btn-danger');
			$('#div-comment').hide();
		});
		
		
		
		
    $('#button-save').click(function(e){
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

		$('.hint').click(function(e){
			e.preventDefault();
			e.stopPropagation();
		});
		
		// $( "body" ).on( "click", ".glyphicon-menu-down", function(e) {
		$('.glyphicon-menu-down').click(function(e){
			$(this).find('.hint').slideToggle();
		});

		// $( "body" ).on( "click", ".glyphicon-question-sign", function(e) {
		$('.glyphicon-question-sign').click(function(e){
			$(this).find('.hint').slideToggle();
		});
		// $( ".glyphicon-question-sign" ).hover(
			// function() {
				// $(this).find('.hint').slideToggle();
			// }, function() {
				// $(this).find('.hint').slideToggle();
			// }
		// );		

		// $( "#select-media-source" ).change(function() {
			// if ( $( this ).val() == "usernames" ) {
				// $("#select-follow-source").html('<option value="followers of username">Followers of username</option><option value="following of username">Following of username</option>');
				// $("#div-usernames").show();
				// $("#div-hashtags").hide();
			// }
			// if ( $( this ).val() == "hashtags" ) {
				// $("#select-follow-source").html('<option value="hashtags">Hashtags</option>');
				// $("#div-usernames").hide();
				// $("#div-hashtags").show();
			// }
		// });
		
		$( "#select-follow-source" ).change(function() {
			if ( $( this ).val() == "hashtags" ) {
				$("#div-usernames").hide();
			}
			if ( ( $( this ).val() == "followers of username" ) || ( $( this ).val() == "following of username" )) {
				$("#div-usernames").show();
			}
		});
		
    $('.selectize-default').selectize({
      plugins:['remove_button'],
      delimiter: ';',
      persist: false,
      create: function(input) {
        return {
          value: input,
          text: input
        }
      },
    });


  });
</script>
  <link href="{{ asset('/selectize/css/selectize.bootstrap3.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="{{ asset('/selectize/js/standalone/selectize.js') }}"></script>

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
			<button data-id="{{$settings->id}}" class="btn <?php if ($settings->status=='stopped') { echo 'btn-success'; } else {echo 'btn-danger';} ?> button-action btn-{{$settings->id}}" value="<?php if ($settings->status=='stopped') { echo 'Start'; } else {echo 'Stop';}?>">
				<?php if ($settings->status=='stopped') { echo "<span class='glyphicon glyphicon-play'></span> Start"; } else {echo "<span class='glyphicon glyphicon-stop'></span> Stop";}?> 
			</button>
		</div>
	</div>
</div>
<div class="col-md-1 col-xs-1 col-sm-1">
</div>
<div class="col-md-6 col-xs-6 col-sm-6 pricing" style="margin-left:-10px;">
	<div class="row">
		<p>Perpanjang waktu berlangganan anda :</p>
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
		<a href="#" id="button-package">Extra Time Package</a>
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
        <h3 class="panel-title">Profile</h3>
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
					<span class="glyphicon glyphicon-question-sign" title="">
						<div class="hint">
						  Jika Akun anda BARU / Tdk aktif, START dgn "SLOW/NORMAL" speed utk 5 hari <br>
						  • <strong>Slow</strong> = Melakukan 550 Likes, 120 comments, 350 follow/unfollow /hari <br>
							• <strong>Normal</strong> = Melakukan 1200 likes, 180 comments, 450 follow/unfollows /hari. <br>
							• <strong>Fast</strong> = Melakukan 1800 likes, 240 comments, 600 follow/unfollows /hari. <br>
						<br>
						</div>
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
					<span class="glyphicon glyphicon-question-sign" title="">
						<div class="hint">
							Pilih Umur Media / Media Age yang akan berinteraksi dengan anda.<br>
							<strong>Latest</strong> : Hanya post terbaru (default)<br>
							<strong>Any</strong>    : Post kapan saja<br>
												
						</div>
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
        <div class="col-md-4">
          <label>Media Type</label> 
					<span class="glyphicon glyphicon-question-sign" title="">
						<div class="hint">Media yang dipakai untuk interaksi, Foto atau Video atau Semuanya </div>
					</span>
          <select class="form-control" name="data[media_type]">
            <option value="any" <?php if ($settings->media_type=='any') echo "selected" ?>>Any</option>
            <option value="photos" <?php if ($settings->media_type=='photos') echo "selected" ?>>Photos</option>
            <option value="videos" <?php if ($settings->media_type=='videos') echo "selected" ?>>Videos</option>
          </select>
        </div>
				
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
						<span class="glyphicon glyphicon-question-sign hint-button" title="">
						<div class="hint">Status ON akan melakukan "Follow/Unfollow" <br>
						                  Status OFF Tidak akan melakukan "Follow/Unfollow" <br>
															*PS: Status OFF berguna apabila anda hanya mau melakukan Aktifitas lain (Like & Comment) saja</div>
						</span>
						<div class="btn-group col-md-12 col-sm-12" role="group" aria-label="..." style="margin-left:-15px;">
							<button type="button" class="btn <?php if ($settings->status_follow_unfollow=="on") echo 'btn-primary' ?>" id="statusFollowOnButton" style="color:#fff;">ON</button>
							<button type="button" class="btn <?php if ($settings->status_follow_unfollow=="off") echo 'btn-danger' ?>" id="statusFollowOffButton" style="color:#fff;">OFF</button>
							<input type="hidden" value="{{$settings->status_follow_unfollow}}" name="data[status_follow_unfollow]" id="status_follow_unfollow">
						</div>
					</div>


					<div class="col-md-4 status-follow" <?php if ($settings->status_follow_unfollow=="off") echo "style='display:none;'" ?>>
						<label>Activity</label>
						<span class="glyphicon glyphicon-question-sign hint-button" title="">
						<div class="hint">PILIH salah satu Follow / Unfollow. Tidak bisa bersamaan</div>
						</span>
						<div class="btn-group col-md-12 col-sm-12" role="group" aria-label="..." style="margin-left:-15px;">
							<button type="button" class="btn <?php if ($settings->activity=="follow") echo 'btn-success' ?>" id="followButton" style="color:#fff;">Follow</button>
							<button type="button" class="btn <?php if ($settings->activity=="unfollow") echo 'btn-success' ?>" id="unfollowButton" style="color:#fff;">Unfollow</button>
							<input type="hidden" value="{{$settings->activity}}" name="data[activity]" id="activity">
						</div>				
					</div>				
        </div>

        <div class="row status-follow" <?php if ($settings->status_follow_unfollow=="off") echo "style='display:none;'" ?>>
          <div class="col-md-12 checkbox">
            <label><input type="checkbox" name="data[dont_follow_su]" <?php if($settings->dont_follow_su) echo "checked"; ?> >Don't Follow same user</label> 
						<span class="glyphicon glyphicon-question-sign" title="">
							<div class="hint">Tidak akan Follow lagi user yang sama, setelah anda Unfollow mereka.</div>
						</span>
          </div>
          <div class="col-md-12 checkbox" <?php if ($settings->status_follow_unfollow=="off") echo "style='display:none;'" ?>>
            <label><input type="checkbox" name="data[dont_follow_pu]" <?php if($settings->dont_follow_pu) echo "checked"; ?> >Dont Follow private user</label> 
						<span class="glyphicon glyphicon-question-sign" title="">
							<div class="hint">Tidak akan Follow Private Account User</div>
						</span>
          </div>
        </div>
        <div class="row status-follow" <?php if ($settings->status_follow_unfollow=="off") echo "style='display:none;'" ?>>
          <div class="col-md-4">
            <label>Follow source</label> 
						<span class="glyphicon glyphicon-question-sign" title="">
							<div class="hint">Jika Follow Source : "Usernames" bisa pilih mau Follow siapa. "Followersnya/Following" nya username tsb.</div>
						</span>
            <select class="form-control" name="data[follow_source]" id="select-follow-source">
							<option value="hashtags" <?php if ($settings->follow_source=='hashtags') echo "selected" ?>>Hashtags</option>
							<option value="followers of username" <?php if ($settings->follow_source=='followers of username') echo "selected" ?>>Followers of username</option>
							<option value="following of username" <?php if ($settings->follow_source=='following of username') echo "selected" ?>>Following of username</option>
            </select>
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
            <label>Fitur like & comment hanya menggunakan media source hashtags</label> 
						<span class="glyphicon glyphicon-question-sign" title="">
							<div class="hint">
								Anda wajib memasukkan Hashtags niche/target market, sehingga akan lebih banyak pilihan foto untuk dilike & comment <br>
								Masukkan minimum 10 Hashtags
							</div>
						</span>
						
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

<div class="row" id="div-usernames" <?php if ($settings->follow_source=='hashtags') echo "style='display:none;'" ?>>
  <div class="col-md-12 col-sm-12">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Media source : Usernames</h3>
      </div>
      <div class="panel-body">

        <div class="row">
          <div class="col-md-12">
            <label>Usernames</label> 
						<span class="glyphicon glyphicon-question-sign" title="">
							<div class="hint">
							  • Add MIN 1 username jika menggunakan "Usernames" di Media Source. <br>
								• Anda dapat menambahkan MAX 50 usernames.
							</div>
						</span>
            <textarea class="selectize-default" name="data[username]">{{$settings->username}}</textarea>
          </div>
        </div>

      </div>
    </div>
  </div>  
</div>                    

<div class="row" id="div-hashtags">
  <div class="col-md-12 col-sm-12">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Media source : Hashtags</h3>
      </div>
      <div class="panel-body">

        <div class="row">
          <div class="col-md-12">
            <label>Hashtags</label> 
						<span class="glyphicon glyphicon-question-sign" title="">
							<div class="hint">
								• ADD MIN 1 Hashtag jika anda memilih "Hashtags" di Media Source. <br>
								• TIDAK PERLU ADD simbol # (tanda pagar) <br>
								• Rekomendasi : 10 tags atau lebih<br>
								• Anda dapat menambahkan MAX 50 Hashtags
							</div>
						</span>
            <textarea class="selectize-default" name="data[hashtags]">{{$settings->hashtags}}</textarea>
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
          <div class="col-md-12">
            <label>Usernames whitelist</label> 
						<span class="glyphicon glyphicon-question-sign" title="">
							<div class="hint">
							• Saat anda UNFOLLOW. Usernames di "Whitelist" ini akan diabaikan / tidak akan di "UNFOLLOW"<br>
							• Usulan penggunaan : teman, pasangan, rekan sekerja & siapapun yang anda mau KEEP FOLLOW
							</div>
						</span>
            <textarea class="selectize-default" name="data[usernames_whitelist]">{{$settings->usernames_whitelist}}</textarea>
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
          <div class="col-md-12">
						<div class="col-md-5 col-sm-12 col-xm-12" style="padding-left:0px!important;">
							<label>Comments</label> 
							<span class="glyphicon glyphicon-question-sign" title="">
								<div class="hint">
									• Tambahkan : <@owner> , di akhir comment untuk men-"tag" owner dari post tersebut<br>
                  • Komentar akan dipilih secara acak dari daftar ini. <br>
									• Celebgramme hanya memberikan 1x komentar pada setiap posting <br>
									• Min 10 komentar netral contoh: nice!, awesome!, beautiful!, itu keren!, dll <br>
									• Komentar tidak boleh lebih dari 300 karakter. <br>
									• Komentar tidak boleh berisi lebih dari 4 hashtag <br>
									• Komentar tidak boleh berisi lebih dari 1 URL <br>
									• Komentar tidak boleh terdiri dari huruf kapital semua. <br>
									• Komentar sebisa mungkin harus berbeda satu sama lain. <br>
								</div>
							</span>
						</div>
						<div class="col-md-5 col-sm-12 col-xm-12" style="padding-left:0px!important;">
							<label>Copy contoh spin comment (click)</label>
							<span class="glyphicon glyphicon-menu-down" title="">
								<div class="hint">
								<a href="#" class="add-spin-comment">{asli|serius},{nice|kerennn|cool|wow|keren|cooooolll|niceeeee}{sekaleee|sekali|banget|beneran|bener}{photo|foto|shot|poto|pic}{kamu|ini} <@owner> </a>
								<br> <br>
								<a href="#" class="add-spin-comment">{nice|kerennn|cool|wow|keren|cooooolll|niceeeee}{sekaleee|sekali|banget|beneran|bener}{photo|foto|shot|poto|pic}{kamu|ini} <@owner></a> <br> <br>
								<a href="#" class="add-spin-comment">{wow|amazing|incredible|whoa|seriously} {your|the|this} {photo|picture|photograph|image|foto} {is awesome|rocks !|very nice} <@owner> </a>
								
								</div>
							</span>
						</div>
          </div>
          <div class="col-md-12">
						<label>Penjelasan fitur spin comment</label>
						<span class="glyphicon glyphicon-question-sign" title="">
							<div class="hint">
								*Gunakan Feature "Spin Comment" contoh : <br>
																	{wihh|wow|beneran,|asli}{foto|image|photo}{kamu|anda|nya}{keren|cool|mantappp|sipp|amazing|beautiful} <br>
																		*contoh diatas akan menghasilkan = 4x3x3x6 = 216 kombinasi comments sekaligus" <br>
																		*Spin Comment default akan digunakan, utk menghindari Comment yang sama berulang-ulang
							</div>
						</span>
          </div>
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
  <div class="col-md-3">
    <input type="button" value="Save" class="btn btn-info col-md-8 col-sm-12" id="button-save">    
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
