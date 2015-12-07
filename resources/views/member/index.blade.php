<!DOCTYPE html>
<html>
    <head>
      <title>Celebgramme12312</title>
      <meta name="csrf-token" content="{{ csrf_token() }}" />
      <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/images/celebgramme-favicon.png') }}">
      <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
      <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
      <link href="{{ asset('/css/bootstrap-theme.min.css') }}" rel="stylesheet">
      <link href="{{ asset('/css/main.css') }}" rel="stylesheet">
      <script type="text/javascript" src="{{ asset('/js/jquery-1.11.3.js') }}"></script>
      <script>
        $(document).ready(function(){
          $("#div-loading").hide();

          $('#link-activation').click(function(e){
            e.preventDefault();
            $.ajax({
                type: 'GET',
                url: "<?php echo url('resend-activation'); ?>",
                data: {},
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
                    }
                    else if(data.type=='error')
                    {
                      $("#alert").addClass('alert-danger');
                      $("#alert").removeClass('alert-success');
                    }
                }
            })
          });


        });
        
      </script>
    </head>
    <body>
    
    <div id="div-loading">
      <div class="loadmain"></div>
      <div class="background-load"></div>
    </div>
    
      <nav class="navbar navbar-inverse ">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><img src="{{asset('/images/logo-celebgramme.png')}}" style="width:100%;max-width:200px;height:30px;"></a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
              <li></li>
            </ul>
          </div>
        </div>
      </nav>    
    
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-3 col-md-2 fl">    
            <ul class="nav nav-sidebar">
              <li><a href="{{url('send-like')}}">Send Likes</a></li>
              <li><a href="{{url('order')}}">Order History</a></li>
              <li><a href="{{url('buy-more')}}">Buy More</a></li>
              <li><a href="{{url('confirm-payment')}}">Confirm Payment</a></li>
              <li><a href="{{url('edit-profile')}}">Ganti Password</a></li>
              <li><a href="{{url('logout')}}">Logout</a></li>
            </ul>
          
          </div>
          
          <div class="col-sm-8 col-md-8  fl">
            <div class="row">
              <div class="col-sm-4">
                <div class="panel panel-info">
                  <div class="panel-heading">
                    <h3 class="panel-title">Daily Balance</h3>
                  </div>
                  <div class="panel-body">
                    <span id="span-balance">{{$user->balance}}</span> <input type=hidden value="{{$user->balance}}" id="balance">
                  </div>
                </div>
              </div>          
              <div class="col-sm-4">
                <div class="panel panel-info">
                  <div class="panel-heading">
                    <h3 class="panel-title">Valid until</h3>
                  </div>
                  <div class="panel-body">
                    <?php 
                      if ( $user->valid_until == "0000-00-00 00:00:00" ) {
                        echo "-";
                      }
                      else {
                        echo date("j F Y",strtotime($user->valid_until));
                      }
                    ?>
                  </div>
                </div>
              </div>          
            </div>          
            <div class="row">
              <div class="col-sm-8 col-md-8">            
                <div class="alert alert-danger col-sm-18 col-md-18" id="alert">
                  <?php if ($user->type=="not-confirmed") { ?> 
                  Silahkan konfirmasi email terlebih dahulu. Klik <a href="" id="link-activation">disini</a> untuk kirim email konfirmasi ulang.
                  <?php } ?>
                </div>  
              </div>          
            </div>          
            <div class="row">
              <div class="col-sm-8">            
                <?php if ($user->type=="not-confirmed") { ?>
                <?php } else { ?>
                @yield('content')
                <?php } ?>                
              </div>          
            </div>          
            
          </div>
          
          <div class="fn">
          </div>
          
        </div>
      </div>
    
    </body>
</html>
