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
  <div class="row"> <img src="{{url('images/profile-default.png')}}" class=""> </div>
  <div class="row"> <label>{{$data->insta_username}}</label></div>
  <div class="row"> <p> Status activity : <?php if ($data->status=='stopped') { echo 'Stopped'; } else {echo "Started";}?></p></div>
  <div class="row"> 
    <div class="im-centered">
    <input type="button" data-id="{{$data->id}}" value="<?php if ($data->status=='stopped') { echo 'Start'; } else {echo "Stop";}?>" class="btn btn-info button-action">
    <a href="{{url('account-setting/'.$data->id)}}"><input type="button" value="Setting" class="btn btn-primary"></a>
    </div>
  </div>
</div>
<?php } } ?>
</body></html>