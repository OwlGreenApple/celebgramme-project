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
				if($('#no_order').val()=="") {
					error_message+="No Order tidak boleh kosong <br>";
					flag=false;
				}
				if($('#nama_bank').val()=="") {
					error_message+="Nama Bank tidak boleh kosong <br>";
					flag=false;
				}
				if($('#no_rekening').val()=="") {
					error_message+="No Rekening tidak boleh kosong <br>";
					flag=false;
				}
				if($('#nama').val()=="") {
					error_message+="Nama Rekening tidak boleh kosong <br>";
					flag=false;
				}
				if($('#photo').val()=="") {
					error_message+="File tidak boleh kosong <br>";
					flag=false;
				}else{
				}
				var ext = $('#photo').val().split('.').pop().toLowerCase();
				if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
					error_message+="Extension file hanya boleh gif, png, jpg, jpeg <br>";
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
		
		$("body").on('change', '#photo',
			function(e) {
				var f=this.files[0];
				
				if ((f.size>5000000)||(f.fileSize>5000000)){
				// if ((f.size>300000)||(f.fileSize>300000)){
					$(this).val('');
					alert('Image tidak boleh lebih besar dari 5MB');
				}
		});
		
    $("input:text").focus(function() { $(this).select(); } );
    $("#send-like").focus(function() { $(this).select(); } );
    $("#alert").hide();
		// $("body").on('change', '#no_order',function(e) {
		$("#no_order").keyup(function() {
      $.ajax({
          type: 'get',
          url: "<?php echo url('get-payment-total'); ?>",
          data: {
						no_order : $("#no_order").val()
					},
          dataType: 'text',
          beforeSend: function()
          {
            $("#div-loading").show();
          },
          success: function(result) {
						// $('#result').html(data);
						$("#div-loading").hide();
						console.log(result);
						$("#total").html(result);
						$("#hidden-total").val(result);
          }
      });

		
		});
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
			4800-227-122<br>
			Sugiarto Lasjim<br><br>
		</div>  

		<div class="col-sm-4 col-md-4">
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
      <label class="col-xs-8 col-sm-4 control-label" for="formGroupInputSmall">Nama Bank</label>
      <div class="col-sm-8 col-md-6">
        <input type="text" class="form-control" placeholder="Nama Bank" name="nama_bank" id="nama_bank">
      </div>
    </div>  
    <div class="form-group form-group-sm row">
      <label class="col-xs-8 col-sm-4 control-label" for="formGroupInputSmall">No Rekening</label>
      <div class="col-sm-8 col-md-6">
        <input type="text" class="form-control" placeholder="No Rekening" name="no_rekening" id="no_rekening">
      </div>
    </div>  
    <div class="form-group form-group-sm row">
      <label class="col-xs-8 col-sm-4 control-label" for="formGroupInputSmall">Nama Pemilik Rekening</label>
      <div class="col-sm-8 col-md-6">
        <input type="text" class="form-control" placeholder="Nama Pemilik Rekening" name="nama" id="nama">
      </div>
    </div>  
    <div class="form-group form-group-sm row">
      <label class="col-xs-8 col-sm-4 control-label" for="formGroupInputSmall">Jumlah Transfer</label>
      <div class="col-sm-8 col-md-6">
			<!--
        <input type="number" class="form-control" placeholder="Jumlah Transfer" name="total" onkeypress="return isNumberKey(event)" id="total" value=0>
				-->
				<input type="hidden" name="total" id="hidden-total">
				<label class="control-label" for="formGroupInputSmall" id="total"></label>
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
