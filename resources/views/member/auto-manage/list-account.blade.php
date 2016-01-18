<script>
	$("#total-account-start").html("<?php echo $account_active; ?>");
	$("#time-account-start").html("<?php echo $view_timeperaccount; ?>");

</script>
<?php 
if (isset($datas)) { 
  foreach ($datas as $data ) {
		$photo = url('images/profile-default.png');
		$json_url = "https://api.instagram.com/v1/users/search?q=".$data->insta_username."&client_id=03eecaad3a204f51945da8ade3e22839";
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
<div class="col-md-5 border-styling">
	<div class="row"> 
		<div class="col-md-10 col-sm-10"></div>
		<div class="col-md-2 col-sm-2">
			<span data-id="{{$data->id}}" class="delete-button glyphicon glyphicon-remove" style="left:20px;cursor:pointer;" aria-hidden="true" data-toggle="modal" data-target="#confirm-delete" ></span> 
		</div> 
	</div>
	<div class="col-md-5 col-sm-5">
		<div class="row"> <img src="{{$photo}}" class="circle-image"> </div>
		<div class="row"> <label>{{$data->insta_username}}</label></div>
	</div>
	<div class="col-md-7 col-sm-7">	
		<div class="row status-activity"> <p> Status activity : <br><?php if ($data->status=='stopped') { echo '<span class="glyphicon glyphicon-stop"></span> <span style="color:#c12e2a; font-weight:Bold;">Stopped</span>'; } 
		else {echo '<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> <span style="color:#5cb85c; font-weight:Bold;">Started</span>';}?></p></div>
		<?php if ($data->error_cred) { ?>
		<div class="row"> 
			<p class="text-danger"> *Data login tidak sesuai <br>
				silahkan <a href="#" data-id="{{$data->id}}" class="edit-cred" data-toggle="modal" data-target="#myModal-edit-password">Edit</a></p>
		</div>
		<?php } ?>
		<?php if ($data->status=='started') { ?>
		<div class="row"> 
			<p>Remaining Time :	<span style="color:#5cb85c; font-weight:Bold;">{{$view_timeperaccount}}</span></p>
		</div>
		<?php } ?>
		<div class="row"> 
			<button data-id="{{$data->id}}" class="btn <?php if ($data->status=='stopped') { echo 'btn-success'; } else {echo 'btn-danger';} ?> button-action btn-{{$data->id}}" value="<?php if ($data->status=='stopped') { echo 'Start'; } else {echo 'Stop';}?>">
				<?php if ($data->status=='stopped') { echo "<span class='glyphicon glyphicon-play'></span> Start"; } else {echo "<span class='glyphicon glyphicon-stop'></span> Stop";}?> 
			</button>
			<a href="{{url('account-setting/'.$data->id)}}"><input type="button" value="Setting" class="btn btn-primary"></a>
		</div>
	</div>
</div>
<?php } } ?>
