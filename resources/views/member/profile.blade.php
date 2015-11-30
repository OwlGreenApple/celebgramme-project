@extends('member.index')

@section('content')
<script type="text/javascript">
  function cek_form() {
    var flag=true;
    error_message="";
    if($('#old_password').val()=="") {
      error_message+="Password lama belum diisi <br>";
      flag=false;
    }else{
    }
    if($('#new_password').val()=="") {
      error_message+="Password baru belum diisi <br>";
      flag=false;
    }else{
    }
    if($('#konfirmasi_new_password').val()=="") {
      error_message+="Konfirmasi password baru belum diisi <br>";
      flag=false;
    }else{
    }
    if($('#new_password').val()!==$('#konfirmasi_new_password').val()) {
      error_message+="Password baru dan konfirmasi password baru tidak sama <br>";
      flag=false;
    }
        
    if (flag==false) {
      $("#alert").addClass('alert-danger');
      $("#alert").removeClass('alert-success');
      $("#alert").show();
      $("#alert").html(error_message);
    }
    return flag;
  }  
  $(document).ready(function() {
    $("#alert").hide();
    $('#button-process').click(function(e){
      var uf = $('#form-confirm');
      var fd = new FormData(uf[0]);
      if (cek_form()==true) {
      $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type: 'POST',
          url: "<?php echo url('change-profile'); ?>",
          data: fd,
          processData:false,
          contentType: false,
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
      });
      }
    });
  });
</script>
  <form enctype="multipart/form-data" id="form-confirm">
    <div class="form-group form-group-sm row">
      <label class="col-xs-8 col-sm-4 control-label" for="formGroupInputSmall">Password Lama</label>
      <div class="col-sm-8 col-md-6">
        <input type="password" class="form-control" placeholder="Password Lama" name="old_password" id="old_password">
      </div>
    </div>  
    <div class="form-group form-group-sm row">
      <label class="col-xs-8 col-sm-4 control-label" for="formGroupInputSmall">Password Baru</label>
      <div class="col-sm-8 col-md-6">
        <input type="password" class="form-control" placeholder="Password Baru" name="new_password" id="new_password">
      </div>
    </div>  
    <div class="form-group form-group-sm row">
      <label class="col-xs-8 col-sm-4 control-label" for="formGroupInputSmall">Konfirmasi Password Baru</label>
      <div class="col-sm-8 col-md-6">
        <input type="password" class="form-control" placeholder="Konfirmasi Password Baru" name="konfirmasi_new_password" id="konfirmasi_new_password">
      </div>
    </div>  
    <div class="form-group form-group-sm row">
      <div class="col-xs-8 col-sm-4">
      </div>
      <div class="col-sm-8 col-md-6">
        <input class="btn btn-default" type="button" value="Process" id="button-process">
      </div>
    </div>  
  </form>
@endsection
