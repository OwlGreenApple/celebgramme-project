@extends('member.index')

@section('content-auto-manage')
<script type="text/javascript">

  $(document).ready(function() {


    $("#alert").hide();

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
  <div class="col-sm-8 col-md-8">            
    <div class="alert alert-danger col-sm-18 col-md-18" id="alert">
    </div>  
  </div>          
</div>                        
<form enctype="multipart/form-data" id="form-setting">

<div class="row">
  <div class="col-md-8">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Main Settings</h3>
      </div>
      <div class="panel-body">

        <div class="col-md-4">
          <label>Activity Speed</label>
          <select class="form-control" name="data[activity_speed]">
            <option value="normal" <?php if ($settings->activity_speed=='normal') echo "selected" ?>>normal</option>
            <option value="slow" <?php if ($settings->activity_speed=='slow') echo "selected" ?>>slow</option>
            <option value="fast" <?php if ($settings->activity_speed=='fast') echo "selected" ?>>fast</option>
          </select>
        </div>
        <div class="col-md-4">
          <label>Media Source</label>
          <select class="form-control" name="data[media_source]">
            <option value="tags" <?php if ($settings->media_source=='tags') echo "selected" ?>>Tags</option>
            <option value="locations" <?php if ($settings->media_source=='locations') echo "selected" ?>>Locations</option>
            <option value="followers of usernames" <?php if ($settings->media_source=='followers of usernames') echo "selected" ?>>Followers of usernames</option>
            <option value="my feed" <?php if ($settings->media_source=='my feed') echo "selected" ?>>My Feed</option>
          </select>
        </div>
        <div class="col-md-4">
          <label>Media Age</label>
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
          <select class="form-control" name="data[media_type]">
            <option value="any" <?php if ($settings->media_type=='any') echo "selected" ?>>Any</option>
            <option value="photos" <?php if ($settings->media_type=='photos') echo "selected" ?>>Photos</option>
            <option value="videos" <?php if ($settings->media_type=='videos') echo "selected" ?>>Videos</option>
          </select>
        </div>
        <div class="col-md-4">
          <label>Min likes filter</label>
          <input type="number" class="form-control" name="data[min_likes_media]" value="{{$settings->min_likes_media}}">
        </div>
        <div class="col-md-4">
          <label>Max likes filter</label>
          <input type="number" class="form-control" name="data[max_likes_media]" value="{{$settings->max_likes_media}}">
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
            <label><input type="checkbox" name="data[comment_su]" <?php if($settings->comment_su) echo "checked"; ?> >Comment same user</label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <label>Comments</label>
            <textarea class="selectize-default" name="data[comments]">{{$settings->comments}}</textarea>
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
            <label><input type="checkbox" name="data[follow_su]" <?php if($settings->follow_su) echo "checked"; ?> >Follow same user</label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 checkbox">
            <label><input type="checkbox" name="data[follow_pu]" <?php if($settings->follow_pu) echo "checked"; ?> >Follow private user</label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <label>Follow source</label>
            <select class="form-control" name="data[follow_source]">
              <option value="media" <?php if ($settings->follow_source=='media') echo "selected" ?>>Media</option>
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
  <div class="col-md-8">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Unfollow</h3>
      </div>
      <div class="panel-body">

        <div class="row">
          <div class="col-md-4 checkbox">
            <label><input type="checkbox" name="data[unfollow_wdfm]" <?php if($settings->unfollow_wdfm) echo "checked"; ?> >Unfollow who dont follow me</label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <label>Unfollow source</label>
            <select class="form-control" name="data[unfollow_source]">
              <option value="media" <?php if ($settings->unfollow_source=='media') echo "selected" ?>>Media</option>
              <option value="followers of username" <?php if ($settings->unfollow_source=='followers of username') echo "selected" ?>>Followers of username</option>
              <option value="following of username" <?php if ($settings->unfollow_source=='following of username') echo "selected" ?>>Following of username</option>
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
            <textarea class="selectize-default" name="data[tags]">{{$settings->tags}}</textarea>
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
            <textarea class="selectize-default" name="data[locations]">{{$settings->locations}}</textarea>
          </div>
        </div>

      </div>
    </div>
  </div>  
</div>                    

<div class="row">
  <div class="col-md-3">
    <input type="button" value="Save" class="btn btn-info col-md-8 col-sm-12" id="button-save">    
  </div>                    
</div>                    
<input type="hidden" name="data[id]" value="{{$settings->setting_id}}">
</form>
@endsection
