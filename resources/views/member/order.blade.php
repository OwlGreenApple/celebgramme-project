@extends('new-dashboard.main')

@section('content')
<script type="text/javascript">
  var $tr_delete;

  $(document).ready(function() {
    $("#alert").hide();
  });

  $( "body" ).on( "click", ".btn-delete", function() {
    $("#id-delete").val($(this).attr("data-id"));

    $tr_delete = $(this).closest('tr');
  });

  $( "body" ).on( "click", "#btn-delete-ok", function() {
    $.ajax({
      url: '<?php echo url('delete-order'); ?>',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type: 'post',
      data: {
        id : $("#id-delete").val(),
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
          $tr_delete.remove();
          $("#alert").addClass('alert-success');
          $("#alert").removeClass('alert-danger');
        }
        else if(data.type=='error')
        {
          $("#alert").addClass('alert-danger');
          $("#alert").removeClass('alert-success');
        }
      }  
    });
  });
</script>

<!-- Modal confirm delete-->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        Delete
      </div>
      <div class="modal-body">
        Delete Order?
      </div>

      <input type="hidden" id="id-delete">

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" data-dismiss="modal" class="btn btn-danger" id="btn-delete-ok">Delete</button>
      </div>
    </div>
  </div>
</div>

  <div class="alert alert-danger col-sm-18 col-md-18" id="alert">
  </div>
  
  <table class="table table-striped">
    <tr>
      <td> No Order </td>
      <td> Package </td>
      <td> Total </td>
      <td> Status </td>
      <td> Created </td>
      <td> </td>
    </tr>
    <?php 
      if ($orders->count() >0) {
        foreach ($orders as $arr) {
    ?>
          <tr class=".row-{{$arr->id}}">
            <td> 
						<?php 
							$shortcode = str_replace('OCLB', '', $arr->no_order);
							echo $shortcode;
						?>
						</td>
            <td> 
						<?php 
						  if ($arr->type == "daily-activity") {
								echo $arr->package_name;
							}
							else if ($arr->type == "max-account") {
								echo $arr->added_account." Akun";
							}
						?>
						</td>
            <td> {{"Rp. ".number_format($arr->total-$arr->discount,0,'','.')}} </td>
            <td> 
							<?php 
							  if ($arr->order_status=="pending") {
									if ($arr->image=="") {
										echo "<span style='color:#c12e2a;font-weight:Bold;'>Pending</span>";
									} else {
										echo "<span style='color:#FCF403;font-weight:Bold;'>Waiting Admin confirmation</span>";
									}
								} else if ($arr->order_status=="success") {
									echo "<span style='color:#1e80e1;font-weight:Bold;'>Success</span>";
								}
							?>
						</td>
            <td> {{$arr->created_at}} </td>
            <td> 
              <?php if($arr->order_status!='success' && $arr->image=="") { ?>
                <a href="{{url('confirm-payment').'/'.$shortcode}}">
                  <input type="button" class="btn btn-info" value="Konfirmasi">
                </a> 
              <?php } ?>
              <?php if($arr->order_status=='pending' && $arr->image=="") { ?>
                <button class="btn btn-danger btn-delete" data-toggle="modal" data-target="#confirm-delete" data-id="{{$arr->id}}">
                  Delete
                </button>
              <?php } ?>
            </td>
          </tr>
    <?php } } else { ?> 
    <tr>
      <td colspan=5> Tidak ada data </td>
    </tr>
    <?php }  ?> 
  </table>
@endsection
