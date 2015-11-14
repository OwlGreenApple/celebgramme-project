@extends('member.index')

@section('content')
<script type="text/javascript">
  function isNumberKey(evt){
      var charCode = (evt.which) ? evt.which : event.keyCode
      if (charCode > 31 && (charCode < 48 || charCode > 57))
          return false;
      return true;
  }
  $(document).ready(function() {
    $("#alert").hide();
    $('#button-process').click(function(e){
      $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type: 'POST',
          url: "<?php echo url('process-like'); ?>",
          data: $("#form-like").serialize(),
          success: function(result) {
              // $('#result').html(data);
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

  <form enctype="multipart/form-data" id="form-like">
    <div class="form-group form-group-sm row">
      <label class="col-sm-1 control-label" for="formGroupInputSmall">Photo URL</label>
      <div class="col-xs-4">
        <input type="text" class="form-control" placeholder="" name="photo">
      </div>
    </div>  
    <div class="form-group form-group-sm row">
      <label class="col-sm-1 control-label" for="formGroupInputSmall">Send Like</label>
      <div class="col-xs-4">
        <input type="number" class="form-control" placeholder="" name="like" onkeypress="return isNumberKey(event)">
      </div>
    </div>  
    <div class="form-group form-group-sm row">
      <label class="col-sm-1 control-label" for="formGroupInputSmall">Now Balance</label>
      <div class="col-xs-4">
        <input type="text" class="form-control" placeholder="" disabled id="balance-now">
      </div>
    </div>  
    <div class="form-group form-group-sm row">
      <div class="col-sm-1">
      </div>
      <div class="col-xs-4">
        <input class="btn btn-default" type="button" value="Process" id="button-process">
      </div>
    </div>  
  </form>
@endsection
