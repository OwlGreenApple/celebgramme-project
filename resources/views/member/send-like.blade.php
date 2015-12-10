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
        if ($("#balance").val()==0){
          $("#alert").show();
          $("#alert").html("Balance anda 0, anda tidak dapat menambah like ");
          $("#alert").addClass('alert-danger');
          $("#alert").removeClass('alert-success');

        }
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


    <?php if ( session('cpa') ) { 
      $temp_session = session('cpa');
    ?>
      $("#alert").addClass('alert-success');
      $("#alert").removeClass('alert-danger');
      $("#alert").show();
      $("#alert").html("Bonus like sudah dimasukkan, silahkan isi CPA offer <a href='<?php echo $temp_session; ?>' target='_blank' >disini</a>");
    <?php } ?>

  });
</script>

  <form enctype="multipart/form-data" id="form-like">
    <div class="form-group form-group-sm row">
      <label class="col-xs-8 col-sm-2 control-label" for="formGroupInputSmall">Photo URL</label>
      <div class="col-sm-8 col-md-6">
        <input type="text" class="form-control" placeholder="" name="photo">
      </div>
    </div>  
    <div class="form-group form-group-sm row">
      <label class="col-xs-8 col-sm-2 control-label" for="formGroupInputSmall">Send Like</label>
      <div class="col-sm-8 col-md-6">
        <input type="number" class="form-control" placeholder="" name="like" onkeypress="return isNumberKey(event)" id="send-like" value=0>
      </div>
    </div>  
    <div class="form-group form-group-sm row">
      <label class="col-xs-8 col-sm-2 control-label" for="formGroupInputSmall">Now Balance</label>
      <div class="col-sm-8 col-md-6">
        <input type="text" class="form-control" placeholder="" disabled id="balance-now">
      </div>
    </div>  
    <div class="form-group form-group-sm row">
      <div class="col-xs-8 col-sm-2">
      </div>
      <div class="col-sm-8 col-md-6">
        <input class="btn btn-default" type="button" value="Process" id="button-process">
      </div>
    </div>  
  </form>
@endsection
