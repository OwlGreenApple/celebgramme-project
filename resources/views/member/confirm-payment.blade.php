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
  function cek_form() {
    var flag=true;
    error_message="";
				if($('#photo').val()=="") {
					error_message+="File tidak boleh kosong diisi";
					flag=false;
				}else{
				}
				var ext = $('#photo').val().split('.').pop().toLowerCase();
				if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
					error_message+="Extension file hanya boleh gif, png, jpg, jpeg";
					flag=false;
				}else{
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
    $("input:text").focus(function() { $(this).select(); } );
    $("#send-like").focus(function() { $(this).select(); } );
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
          url: "<?php echo url('process-payment'); ?>",
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

	<div class="row">
		<div class="col-sm-4 col-md-4">
			<p>
			<strong>TRANSFER Melalui : </strong>
			</p>
		</div>  
		<div class="col-sm-4 col-md-3">
			<strong>Bank BCA</strong><br>
			5335-927-122<br>
			Sugiarto Lasjim<br><br>
		</div>  

		<div class="col-sm-4 col-md-3">
			<strong>Bank Mandiri</strong><br>
			121-00-3592712-2<br>
			Sugiarto Lasjim<br><br>
		</div>  
	</div>  
  <form enctype="multipart/form-data" id="form-confirm">
    <div class="form-group form-group-sm row">
      <label class="col-xs-8 col-sm-4 control-label" for="formGroupInputSmall">No. Order</label>
      <div class="col-sm-8 col-md-6">
        <input type="text" class="form-control" placeholder="No. Order" name="no_order" id="no_order">
      </div>
    </div>  
    <div class="form-group form-group-sm row">
      <label class="col-xs-8 col-sm-4 control-label" for="formGroupInputSmall">Nama Pemilik Rekening</label>
      <div class="col-sm-8 col-md-6">
        <input type="text" class="form-control" placeholder="Nama Pemilik Rekening" name="nama">
      </div>
    </div>  
    <div class="form-group form-group-sm row">
      <label class="col-xs-8 col-sm-4 control-label" for="formGroupInputSmall">Jumlah Transfer</label>
      <div class="col-sm-8 col-md-6">
        <input type="number" class="form-control" placeholder="Jumlah Transfer" name="total" onkeypress="return isNumberKey(event)" id="total" value=0>
      </div>
    </div>  
    <div class="form-group form-group-sm row">
      <label class="col-xs-8 col-sm-4 control-label" for="formGroupInputSmall">Upload Bukti Transfer</label>
      <div class="col-sm-8 col-md-6">
        <input type="file" class="form-control" placeholder="" id="photo" name="photo">
      </div>
    </div>  
    <div class="form-group form-group-sm row">
      <label class="col-xs-8 col-sm-4 control-label" for="formGroupInputSmall">Keterangan</label>
      <div class="col-sm-8 col-md-6">
        <input type="text" class="form-control" placeholder="Keterangan (optional)" name="keterangan">
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
