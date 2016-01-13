@extends('member.index')

@section('content-auto-manage')
<script type="text/javascript">

		function toggleFollow(){
				// $('#followButton').toggleClass('btn-success');
				// $('#unfollowButton').toggleClass('btn-primary');
				// if ( $('#followButton').hasClass( "btn-success" ) ) {
					// $("#activity").val("follow");
				// } else {
					// $("#activity").val("unfollow");
				// }
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
                  $("#alert").addClass('alert-danger');
                  $("#alert").removeClass('alert-success');
                }
            }
        })
        return false;
    }

  $(document).ready(function() {

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
		});
		$('#unfollowButton').click(function(e){
			$("#activity").val("unfollow");
			$('#followButton').removeClass('btn-success');
			$('#unfollowButton').addClass('btn-success');
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


		$( "body" ).on( "click", ".glyphicon-question-sign", function() {
			$(this).find('.hint').slideToggle();
		});
		// $( ".glyphicon-question-sign" ).hover(
			// function() {
				// $(this).find('.hint').slideToggle();
			// }, function() {
				// $(this).find('.hint').slideToggle();
			// }
		// );		
		
    $('.selectize-default').selectize({
      plugins:['remove_button'],
      delimiter: ',',
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
<form enctype="multipart/form-data" id="form-setting">


<div class="row">
  <div class="col-md-12 col-sm-12">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Data Users</h3>
      </div>
      <div class="panel-body">
<div class="col-md-5 col-sm-5 border-styling ">
  <div class="row"> 
	<?php 
	$photo = url('images/profile-default.png');
	$json_url = "https://api.instagram.com/v1/users/search?q=".$settings->insta_username."&client_id=03eecaad3a204f51945da8ade3e22839";
	$json = @file_get_contents($json_url);
	if($json == TRUE) { 
		$links = json_decode($json);
		if (count($links->data)>0) {
			$photo = $links->data[0]->profile_picture;
		} else {
			$photo = url('images/profile-default.png');
		}
	}
	
	?>	
	<img src="{{$photo}}" class=""> </div>
  <div class="row"> <label>{{$settings->insta_username}}</label></div>
  <div class="row status-activity"> <p> Status activity : <?php if ($settings->status=='stopped') { echo '<span class="glyphicon glyphicon-stop"></span> <span style="color:#c12e2a; font-weight:Bold;">Stopped</span>'; } 
  else {echo '<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> <span style="color:#5cb85c; font-weight:Bold;">Started</span>';}?></p></div>
  <div class="row im-centered"> 

    <button data-id="{{$settings->id}}" class="btn <?php if ($settings->status=='stopped') { echo 'btn-success'; } else {echo 'btn-danger';} ?> button-action btn-{{$settings->id}}" value="<?php if ($settings->status=='stopped') { echo 'Start'; } else {echo 'Stop';}?>">
      <?php if ($settings->status=='stopped') { echo "<span class='glyphicon glyphicon-play'></span> Start"; } else {echo "<span class='glyphicon glyphicon-stop'></span> Stop";}?> 
    </button>
  </div>
</div>
<div class="col-md-7 col-sm-7 pricing" style="margin-left:-10px;">
  <div class="col-md-4 col-sm-4">
    <div class="row im-centered"> 
      <p class="header">7</p>
    </div>
    <div class="row im-centered"> 
      <p class="header-description">Days</p>
    </div>
    <div class="row im-centered"> 
      Rp. 100.000
    </div>
    <div class="row im-centered"> 
    </div>
    <div class="row im-centered button-price"> 
      <a href="{{url('buy-more/1')}}"><input type="button" value="Buy now" class="btn btn-success"></a>
    </div>
  </div>
  <div class="col-md-4 col-sm-4">
    <div class="row im-centered"> 
      <p class="header">28 </p>
    </div>
    <div class="row im-centered"> 
      <p class="header-description">Days</p>
    </div>
    <div class="row im-centered"> 
      Rp. 175.000
    </div>
    <div class="row im-centered"> 
    </div>
    <div class="row im-centered button-price"> 
      <a href="{{url('buy-more/2')}}"><input type="button" value="Buy now" class="btn btn-success"></a>
    </div>
  </div>
  <div class="col-md-4 col-sm-4">
    <div class="row im-centered"> 
      <p class="header">88 </p>
    </div>
    <div class="row im-centered"> 
      <p class="header-description">Days</p>
    </div>
    <div class="row im-centered"> 
      Rp. 395.000
    </div>
    <div class="row im-centered"> 
    </div>
    <div class="row im-centered button-price"> 
      <a href="{{url('buy-more/3')}}"><input type="button" value="Buy now" class="btn btn-success"></a>
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
        <h3 class="panel-title">Profile</h3>
      </div>
      <div class="panel-body">

				<?php use Celebgramme\Models\SettingMeta; ?>
				<div class="col-md-4">
					<label>Followers Join</label>
					<span class="glyphicon glyphicon-question-sign hint-button" title="">
					<div class="hint">Jumlah follower saat join</div>
					</span>
					<?php echo number_format(intval (SettingMeta::getMeta($settings->id,"followers_join")),0,'','.'); ?>
				</div>				

				<div class="col-md-4">
					<label>Following Join</label>
					<span class="glyphicon glyphicon-question-sign hint-button" title="">
					<div class="hint">Jumlah following saat join</div>
					</span>
					<?php echo number_format(intval (SettingMeta::getMeta($settings->id,"following_join")),0,'','.'); ?>
				</div>				

				<div class="col-md-4">
				<label><br></label>
				<label><br></label>
				</div>				

				<?php 
				$followers = 0;
				$following = 0;
				$json_url = "https://api.instagram.com/v1/users/search?q=".$settings['insta_username']."&client_id=03eecaad3a204f51945da8ade3e22839";
				$json = @file_get_contents($json_url);
				if($json == TRUE) { 
					$links = json_decode($json);
					if (count($links->data)>0) {
						$id = $links->data[0]->id;
					} 
					$json_url ='https://api.instagram.com/v1/users/'.$id.'?client_id=03eecaad3a204f51945da8ade3e22839';
					$json = @file_get_contents($json_url);
					if($json == TRUE) { 
						$links = json_decode($json);
						if (count($links->data)>0) {
							$followers = $links->data->counts->followed_by;
							$following = $links->data->counts->follows;
						}
					}
				}
				?>
				<div class="col-md-4">
					<label>Followers Now</label>
					<span class="glyphicon glyphicon-question-sign hint-button" title="">
					<div class="hint">Jumlah follower saat ini</div>
					</span>
					{{$followers}}
				</div>				

				<div class="col-md-4">
					<label>Following Now</label>
					<span class="glyphicon glyphicon-question-sign hint-button" title="">
					<div class="hint">Jumlah following saat ini</div>
					</span>
					{{$following}}
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
					<label>Activity</label>
					<span class="glyphicon glyphicon-question-sign hint-button" title="">
					<div class="hint">PILIH salah satu Follow / Unfollow. Tidak bisa bersamaan</div>
					</span>
					<div class="btn-group col-md-12 col-sm-12" role="group" aria-label="..." style="margin-left:-15px;">
						<button type="button" class="btn <?php if ($settings->activity=="follow") echo 'btn-success' ?>" id="followButton" onclick="toggleFollow();" style="color:#fff;">Follow</button>
						<button type="button" class="btn <?php if ($settings->activity=="unfollow") echo 'btn-primary' ?>" id="unfollowButton" onclick="toggleFollow();" style="color:#fff;">Unfollow</button>
						<input type="hidden" value="{{$settings->activity}}" name="data[activity]" id="activity">
					</div>				
				</div>				
				

        <div class="col-md-4">
          <label>Activity Speed</label> 
					<span class="glyphicon glyphicon-question-sign" title="">
						<div class="hint">Slow - Melakukan sekitar 550 Likes, 120 comments, 350 follows/unfollow /hari <br>
							Normal - Melakukan sekitar 1200 likes, 180 comments, 450 follows/unfollows /hari. <br>
							Fast - Melakukan 1800 likes, 240 comments, 600 follows/unfollows /hari. <br>
						<br>
						</div>
					</span>
          <select class="form-control" name="data[activity_speed]" title="Slow - Kecepatan yang aman untuk melakukan sekitar 480 Likes, 144 comments, 336 follows, 240 unfollow per hari ( kecepatan terbaik untuk awal pemakaian )">
            <option value="normal" <?php if ($settings->activity_speed=='normal') echo "selected" ?>>normal</option>
            <option value="slow" <?php if ($settings->activity_speed=='slow') echo "selected" ?>>slow</option>
            <option value="fast" <?php if ($settings->activity_speed=='fast') echo "selected" ?>>fast</option>
          </select>
        </div>
        <div class="col-md-4">
          <label>Media Source</label> 
					<span class="glyphicon glyphicon-question-sign" title="">
						<div class="hint">Pilih Sumber Media untuk aktivitas Anda : <br>
							Hashtags - untuk menentukan Media sesuai Hashtags <br>
							Usernames - untuk menentukan Media berdasarkan username <br>
							
						</div>
					</span>
          <select class="form-control" name="data[media_source]">
            <option value="hashtags" <?php if ($settings->media_source=='hashtags') echo "selected" ?>>Hashtags</option>
            <option value="usernames" <?php if ($settings->media_source=='usernames') echo "selected" ?>>Usernames</option>
          </select>
        </div>
        <div class="col-md-4">
          <label>Media Age</label> 
					<span class="glyphicon glyphicon-question-sign" title="">
						<div class="hint">
							Pilih Umur / Media Age yang akan berinteraksi dengan anda. Dari yang Terbaru s/d Terlama. <br>
							Contoh: pilih 1 Day untuk berinteraksi dengan Media yang diposting berumur MAX 1 hari.						
						</div>
					</span>
          <select class="form-control" name="data[media_age]">
            <option value="newest" <?php if ($settings->media_age=='newest') echo "selected" ?>>Newest</option>
            <option value="1 hour" <?php if ($settings->media_age=='1 hour') echo "selected" ?>>1 Hour</option>
            <option value="12 hours" <?php if ($settings->media_age=='12 hours') echo "selected" ?>>12 Hours</option>
            <option value="1 day" <?php if ($settings->media_age=='1 day') echo "selected" ?>>1 Day</option>
            <option value="3 day" <?php if ($settings->media_age=='3 day') echo "selected" ?>>3 Days</option>
            <option value="1 week" <?php if ($settings->media_age=='1 week') echo "selected" ?>>1 Week</option>
            <option value="2 week" <?php if ($settings->media_age=='2 week') echo "selected" ?>>2 Weeks</option>
            <option value="1 month" <?php if ($settings->media_age=='1 month') echo "selected" ?>>1 Month</option>
            <option value="any" <?php if ($settings->media_age=='any') echo "selected" ?>>Any</option>
          </select>
        </div>
        <div class="col-md-4">
          <label>Media Type</label> 
					<span class="glyphicon glyphicon-question-sign" title="">
						<div class="hint">Media yang dipakai untuk interaksi, pilih Foto atau Video. Anda juga dapat memilih keduanya.</div>
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
        <h3 class="panel-title">Media source : Hashtags</h3>
      </div>
      <div class="panel-body">

        <div class="row">
          <div class="col-md-12">
            <label>Hashtags</label> 
						<span class="glyphicon glyphicon-question-sign" title="">
							<div class="hint">
								Tambahkan MIN 1 Hashtag jika anda menggunakan Hashtags di Media Source. <br>
								*Catatan: bahwa simbol # (tanda pagar) tidak diperlukan. Rekomendasi : 10 tags atau lebih<br>
								Anda dapat menambahkan MAX 100 Hashtags.
							</div>
						</span>
            <textarea class="selectize-default" name="data[tags]">{{$settings->tags}}</textarea>
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
        <h3 class="panel-title">Media source : Usernames</h3>
      </div>
      <div class="panel-body">

        <div class="row">
          <div class="col-md-12">
            <label>Usernames</label> 
						<span class="glyphicon glyphicon-question-sign" title="">
							<div class="hint">Tambahkan MIN 1 username jika anda menggunakan Followers/Following of Usernames di Media Source. <br>
								Anda dapat menambahkan MAX 50 usernames.
							</div>
						</span>
            <textarea class="selectize-default" name="data[username]">{{$settings->username}}</textarea>
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
        <h3 class="panel-title">Unfollow whitelist</h3>
      </div>
      <div class="panel-body">

        <div class="row">
          <div class="col-md-12">
            <label>Usernames</label> 
						<span class="glyphicon glyphicon-question-sign" title="">
							<div class="hint">Saat anda melakukan UNFOLLOW, usernames di whitelist ini akan diabaikan / tidak akan di "UNFOLLOW"
							usulan penggunaan : teman, pasangan, rekan sekerja & siapapun yang anda mau KEEP FOLLOW
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
<div class="row">
  <div class="col-md-12 col-sm-12">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Comment</h3>
      </div>
      <div class="panel-body">

        <div class="row">
          <div class="col-md-4 checkbox">
            <label><input type="checkbox" name="data[dont_comment_su]" <?php if($settings->dont_comment_su) echo "checked"; ?> >Dont Comment same user</label> 
						<span class="glyphicon glyphicon-question-sign" title="">
							<div class="hint">Ketika anda memberikan centang ke kotak ini, anda tidak akan memberikan comment lebih dari 1 pada foto atau video pada user yang sama.</div>
						</span>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <label>Comments</label> 
						<span class="glyphicon glyphicon-question-sign" title="">
							<div class="hint">
								Tambahkan setidaknya satu komentar, jika anda mengaktifkan fitur comments <br>
								untuk setiap posting komentar baru, komentar akan dipilih secara acak dari daftar ini. Celebgramme hanya akan memberikan 1 kali komentar pada setiap posting foto atau video. <br>
								Kami menyarankan, paling tidak 10 komentar netral yang berbeda seperti : nice!, awesome!, beautiful!, itu keren!, dll <br>
								Komentar tidak boleh lebih dari 300 karakter. <br>
								Komentar tidak boleh berisi lebih dari 4 hashtag <br>
								Komentar tidak boleh berisi lebih dari 1 URL <br>
								Komentar tidak boleh terdiri dari huruf kapital semua. <br>
								Komentar sebisa mungkin harus berbeda satu sama lain. <br>
								Anda dapat menambahkan sampai dengan 100 comments.
							</div>
						</span>
            <textarea class="selectize-default" name="data[comments]">{{$settings->comments}}</textarea>
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
        <h3 class="panel-title">Follow</h3>
      </div>
      <div class="panel-body">

        <div class="row">
          <div class="col-md-4 checkbox">
            <label><input type="checkbox" name="data[dont_follow_su]" <?php if($settings->dont_follow_su) echo "checked"; ?> >Don't Follow same user</label> 
						<span class="glyphicon glyphicon-question-sign" title="">
							<div class="hint">Tidak akan follow user yang sama sebanyak 2 kali setelah anda Unfollow mereka.</div>
						</span>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 checkbox">
            <label><input type="checkbox" name="data[dont_follow_pu]" <?php if($settings->dont_follow_pu) echo "checked"; ?> >Dont Follow private user</label> 
						<span class="glyphicon glyphicon-question-sign" title="">
							<div class="hint">Tidak Follow user yang akun nya di private</div>
						</span>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <label>Follow source</label> 
						<span class="glyphicon glyphicon-question-sign" title="">
							<div class="hint">Pilih sesuai Media Source diatas atau berdasarkan Username Followers/Following.</div>
						</span>
            <select class="form-control" name="data[follow_source]">
              <option value="media" <?php if ($settings->follow_source=='media') echo "selected" ?>>Media Source</option>
              <option value="followers of username" <?php if ($settings->follow_source=='followers of username') echo "selected" ?>>Followers of username</option>
              <option value="following of username" <?php if ($settings->follow_source=='following of username') echo "selected" ?>>Following of username</option>
            </select>
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
@endsection
