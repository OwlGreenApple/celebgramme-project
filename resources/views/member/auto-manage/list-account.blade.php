<html>
    <head>
      <title>Celebgramme</title>
      <meta name="csrf-token" content="{{ csrf_token() }}" />
      <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/images/celebgramme-favicon.png') }}">
      <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
      <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
      <link href="{{ asset('/css/bootstrap-theme.min.css') }}" rel="stylesheet">
      <link href="{{ asset('/css/main.css') }}" rel="stylesheet">
    </head>
    <body>

<?php 
if (isset($datas)) { 
  foreach ($datas as $data ) {
?>
<div class="col-md-5 border-styling">
	<div class="row"> 
		<div class="col-md-10 col-sm-10"></div>
		<div class="col-md-2 col-sm-2">
			<span data-id="{{$data->id}}" class="delete-button glyphicon glyphicon-remove" style="left:20px;cursor:pointer;" aria-hidden="true"></span> 
		</div> 
	</div>
	<?php 
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
  <div class="row"> <img src="{{$photo}}" class=""> </div>
  <div class="row"> <label>{{$data->insta_username}}</label></div>
  <div class="row status-activity"> <p> Status activity : <?php if ($data->status=='stopped') { echo '<span class="glyphicon glyphicon-stop"></span> <span style="color:#c12e2a; font-weight:Bold;">Stopped</span>'; } 
  else {echo '<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> <span style="color:#5cb85c; font-weight:Bold;">Started</span>';}?></p></div>
  <div class="row"> 

  <?php if ($data->error_cred) { ?>
    <p class="text-danger"> *Data login tidak sesuai <br>
      silahkan <a href="#" data-id="{{$data->id}}" class="edit-cred" data-toggle="modal" data-target="#myModal-edit-password">Edit</a></p>
  <?php } else { ?>
  <p><br><br></p>
  <?php }  ?>
  </div>
  <div class="row"> 
    <div class="im-centered">
    <button data-id="{{$data->id}}" class="btn <?php if ($data->status=='stopped') { echo 'btn-success'; } else {echo 'btn-danger';} ?> button-action btn-{{$data->id}}" value="<?php if ($data->status=='stopped') { echo 'Start'; } else {echo 'Stop';}?>">
      <?php if ($data->status=='stopped') { echo "<span class='glyphicon glyphicon-play'></span> Start"; } else {echo "<span class='glyphicon glyphicon-stop'></span> Stop";}?> 
    </button>
    <a href="{{url('account-setting/'.$data->id)}}"><input type="button" value="Setting" class="btn btn-primary"></a>
    </div>
  </div>
</div>
<?php } } ?>
</body></html>