@extends('member.index')

@section('content-auto-manage')
<script type="text/javascript">

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
                  $("#alert").removeClass('alert-danger');
                  if(data.action=='start'){
                    $(".btn-"+data.id).html("<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> Process");
                  }
                  if(data.action=='stop'){
                    $(".btn-"+data.id).html("start");
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


    $("#alert").hide();
    loadaccount();

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
    $('#button-process').click(function(e){
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
                $("#alert").removeClass('alert-danger');
              }
              else if(data.type=='error')
              {
                $("#alert").addClass('alert-danger');
                $("#alert").removeClass('alert-success');
              }
              $("#username").val("");
              $("#password").val("");
              loadaccount();
          }
      })
    });



  });
</script>
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<?php   if ($user->type<>"not-confirmed") { ?>
<div class="row">
              <div class="col-md-8">
                <div class="panel panel-info ">
                  <div class="panel-heading">
                    <h3 class="panel-title">Dashboard</h3>
                  </div>
                  <div class="panel-body">
                    <div class="col-md-4 col-sm-8">
                      <input type="button" value="Add Account" class="btn btn-primary col-md-8 col-sm-12" data-toggle="modal" data-target="#myModal">
                    </div>                        
                    <div class="col-md-4 col-sm-8">
                      <input type="button" value="Start All" class="btn btn-info col-md-8 col-sm-12" id="button-start-all">
                    </div>                        
                    <div class="col-md-4 col-sm-8">
                      <input type="button" value="Stop All" class="btn btn-warning col-md-8 col-sm-12" id="button-stop-all">
                    </div>                        
                  </div>
                </div>
              </div>  
</div>                        
<?php } ?>


<div class="row">
  <div class="col-sm-8">
      <div id="clockdiv">
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
      <script>
        function getTimeRemaining(endtime){
          var t = <?php echo $user->active_auto_manage ?>;
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

            if(t.total<=0){
              clearInterval(timeinterval);
            }
          }

          updateClock();
          var timeinterval = setInterval(updateClock,1000);
        }

        var deadline = 'December 31 2015 00:00:50 UTC+0700';
        initializeClock('clockdiv', deadline);
      </script>
  </div>
</div>



<div class="row">

  <?php if ($user->type=="not-confirmed") { ?> 
  <div class="col-sm-8 col-md-8">            
    <div class="alert alert-danger col-sm-18 col-md-18">
      Silahkan konfirmasi email terlebih dahulu. Klik <a href="" id="link-activation">disini</a> untuk kirim email konfirmasi ulang.
    </div>  
  </div>          
  <?php } ?>
  <div class="col-sm-8 col-md-8">
    <div class="alert alert-info col-sm-18 col-md-18" id="">
      Account jangan diprivate, harus dipublic supaya like bisa bertambah.
    </div>  
  </div>          
  <div class="col-sm-8 col-md-8">            
    <div class="alert alert-danger col-sm-18 col-md-18" id="alert">
    </div>  
  </div>          
  @if (session('error'))
    <div class="col-sm-8 col-md-8">            
      <div class="alert alert-danger col-sm-18 col-md-18" >
        {{ session('error') }}
      </div>  
    </div>          
  @endif
</div>                        

<?php if ($user->type<>"not-confirmed") { ?>
<div class="row">
  <div class="col-md-8" id="account-all">
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
  </div>                        
</div>      
<?php }?>


  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Instagram Credential</h4>
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
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" id="button-process">Submit</button>
        </div>
      </div>
      
    </div>
  </div>

@endsection
