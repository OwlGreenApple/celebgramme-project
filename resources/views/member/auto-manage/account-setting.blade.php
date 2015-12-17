@extends('member.index')

@section('content-auto-manage')
<script type="text/javascript">

  $(document).ready(function() {


    $("#alert").hide();

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
                $("#balance").val(data.balance);
                $("#span-balance").html(data.balance);
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
  <div class="col-md-8">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Main Settings</h3>
      </div>
      <div class="panel-body">

        <div class="col-md-4">
          <label>Activity Speed</label>
          <select class="form-control">
            <option>normal</option>
            <option>slow</option>
            <option>fast</option>
          </select>
        </div>
        <div class="col-md-4">
          <label>Media Source</label>
          <select class="form-control">
            <option>Tags</option>
            <option>Locations</option>
            <option>Followers of usernames</option>
            <option>My Feed</option>
          </select>
        </div>
        <div class="col-md-4">
          <label>Media Age</label>
          <select class="form-control">
            <option>Newest</option>
            <option>1 Hour</option>
            <option>12 Hours</option>
            <option>1 Day</option>
            <option>3 Days</option>
            <option>1 Week</option>
            <option>2 Weeks</option>
            <option>1 Month</option>
            <option>Any</option>
          </select>
        </div>
        <div class="col-md-4">
          <label>Media Type</label>
          <select class="form-control">
            <option>Any</option>
            <option>Photos</option>
            <option>Videos</option>
          </select>
        </div>
        <div class="col-md-4">
          <label>Min likes filter</label>
          <input type="number" class="form-control">
        </div>
        <div class="col-md-4">
          <label>Max likes filter</label>
          <input type="number" class="form-control">
        </div>

      </div>
    </div>
  </div>  
</div>                        

<div class="row">
  <div class="col-md-8">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Comment</h3>
      </div>
      <div class="panel-body">

        <div class="row">
          <div class="col-md-4 checkbox">
            <label><input type="checkbox" value="">Comment same user</label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <label>Comments</label>
            <textarea class="selectize-default"></textarea>
          </div>
        </div>


      </div>
    </div>
  </div>  
</div>                        

<div class="row">
  <div class="col-md-8">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Follow</h3>
      </div>
      <div class="panel-body">

        <div class="row">
          <div class="col-md-4 checkbox">
            <label><input type="checkbox" value="">Follow same user</label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 checkbox">
            <label><input type="checkbox" value="">Follow private user</label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <label>Follow source</label>
            <select class="form-control">
              <option>Media</option>
              <option>Followers of username</option>
              <option>Following of username</option>
            </select>
          </div>
        </div>


      </div>
    </div>
  </div>  
</div>                        

<div class="row">
  <div class="col-md-8">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Unfollow</h3>
      </div>
      <div class="panel-body">

        <div class="row">
          <div class="col-md-4 checkbox">
            <label><input type="checkbox" value="">Unfollow who dont follow me</label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <label>Unfollow source</label>
            <select class="form-control">
              <option>Media</option>
              <option>Followers of username</option>
              <option>Following of username</option>
            </select>
          </div>
        </div>


      </div>
    </div>
  </div>  
</div>                        

<div class="row">
  <div class="col-md-8">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Tags</h3>
      </div>
      <div class="panel-body">

        <div class="row">
          <div class="col-md-12">
            <label>Tags</label>
            <textarea class="selectize-default"></textarea>
          </div>
        </div>

      </div>
    </div>
  </div>  
</div>                    

<div class="row">
  <div class="col-md-8">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Locations</h3>
      </div>
      <div class="panel-body">

        <div class="row">
          <div class="col-md-12">
            <label>Locations</label>
            <textarea class="selectize-default"></textarea>
          </div>
        </div>

      </div>
    </div>
  </div>  
</div>                    

<div class="row">
  <div class="col-md-3">
    <input type="button" value="Save" class="btn btn-info col-md-8 col-sm-12">    
  </div>                    
</div>                    

@endsection
