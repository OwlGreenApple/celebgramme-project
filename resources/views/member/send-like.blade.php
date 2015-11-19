@extends('member.index')

@section('content')
<script type="text/javascript">
  templike=0;
  function isNumberKey(evt){
      var charCode = (evt.which) ? evt.which : event.keyCode
      if (charCode > 31 && (charCode < 48 || charCode > 57))
          return false;
        
      templike=$("#send-like").val();
      return true;
  }
  $(document).ready(function() {
    $("input:text").focus(function() { $(this).select(); } );
    $("#send-like").focus(function() { $(this).select(); } );
    $("#alert").hide();
    $( "#send-like" ).keyup(function() {
      if ($("#send-like").val()=="") {
        $("#send-like").val(0);
      }
      if (parseInt($("#balance").val())<parseInt($("#send-like").val()) ) {
        $("#send-like").val(templike);
      }
      $("#balance-now").val(parseInt($("#balance").val())-parseInt($("#send-like").val()));
    });    
    $('#button-process').click(function(e){
      $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type: 'POST',
          url: "<?php echo url('process-like'); ?>",
          data: $("#form-like").serialize(),
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
        <input type="number" class="form-control" placeholder="" name="like" onkeypress="return isNumberKey(event)" id="send-like" value=0>
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
