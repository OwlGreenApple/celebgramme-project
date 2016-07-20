<script>
	$("#total-account-start").html("<?php echo $account_active; ?>");
	$("#time-account-start").html("<?php echo $view_timeperaccount; ?>");

</script>
<?php 
use Celebgramme\Models\SettingMeta; 
if (isset($datas)) { 
  foreach ($datas as $data ) {
/*
		$photo = url('images/profile-default.png');
		$json_url = "https://api.instagram.com/v1/users/search?q=".$data->insta_username."&client_id=03eecaad3a204f51945da8ade3e22839";
		$json = @file_get_contents($json_url);
		if($json == TRUE) { 
			$links = json_decode($json);
			if (count($links->data)>0) {
				$photo = url('images/profile-default.png');
				foreach($links->data as $link){
					if (strtoupper($link->username) == strtoupper ($data->insta_username)){
						$photo = $link->profile_picture;
					}
				}
			} else {
				$photo = url('images/profile-default.png');
			}
		}
	*/
		if (SettingMeta::getMeta($data->id,"photo_filename") == "0") {
			$photo = url('images/profile-default.png');
		} else {
			$photo = url("images/pp/". SettingMeta::getMeta($data->id,"photo_filename"));
		}
		?>	
		<li class="col-md-4 col-xs-4 col-sm-4 border-styling">
			<div class="row"> 
				<div class="col-md-10 col-sm-10 col-xs-10"></div>
				<div class="col-md-2 col-sm-2 col-xs-2">
					<span data-id="{{$data->id}}" class="delete-button glyphicon glyphicon-remove" style="cursor:pointer;" aria-hidden="true" data-toggle="modal" data-target="#confirm-delete" ></span> 
				</div> 
			</div>
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="row-fluid"> <img src="{{$photo}}" class="circle-image"> </div>
				<div class="row-fluid"> <label>{{$data->insta_username}}</label></div>
			</div>
			<div class="col-md-12 col-sm-12 col-xs-12">	
				<div class="row status-activity im-centered"> <p> Status activity : <?php if ($data->status=='stopped') { echo '<span class="glyphicon glyphicon-stop"></span> <span style="color:#c12e2a; font-weight:Bold;">Stopped</span>'; } 
				else {echo '<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> <span style="color:#5cb85c; font-weight:Bold;">Started</span>';}?><br>
				Following : <?php echo number_format(intval (SettingMeta::getMeta($data->id,"following")),0,'','.'); ?> <br>
				Followers : <?php echo number_format(intval (SettingMeta::getMeta($data->id,"followers")),0,'','.'); ?></p></div>
				<?php if ($data->error_cred) { ?>
				<div class="row im-centered"> 
					<p class="text-danger" style="font-size:12px;"> <strong>*Data login tidak sesuai <br>
						silahkan ubah username password anda <br>
						---> <a href="#" data-id="{{$data->id}}" data-username="{{$data->insta_username}}" class="edit-cred" data-toggle="modal" data-target="#myModal-edit-password">CLICK disini</a> <--- </strong></p>
				</div>
				<?php } ?>
				<?php if ($data->status=='started') { ?>
				<!--
				<div class="row im-centered"> 
					<p>Remaining Time :	<br><span style="color:#5cb85c; font-weight:Bold;">{{$view_timeperaccount}}</span></p>
				</div>
				-->
				<?php } ?>
				
				<?php if (SettingMeta::getMeta($data->id,"auto_unfollow") == "yes" )  { ?>
					<div class="row im-centered"> 
						<p>*Proses auto unfollow akan dijalankan karena jumlah following anda telah mencapai 7250 </p>
					</div>
				<?php } ?>

				<div class="row im-centered"> 
					<button data-id="{{$data->id}}" class="btn <?php if ($data->status=='stopped') { echo 'btn-success'; } else {echo 'btn-danger';} ?> button-action btn-{{$data->id}}" value="<?php if ($data->status=='stopped') { echo 'Start'; } else {echo 'Stop';}?>">
						<?php if ($data->status=='stopped') { echo "<span class='glyphicon glyphicon-play'></span> Start"; } else {echo "<span class='glyphicon glyphicon-stop'></span> Stop";}?> 
					</button>
					<a href="{{url('account-setting/'.$data->id)}}"><input type="button" value="Setting" class="btn btn-primary"></a>
				</div>
			</div>
		</li>
<?php } } ?>
