@extends('member.index')

@section('content')
<script type="text/javascript">
  $(document).ready(function() {
    $("#alert").hide();
  });
</script>
  <table class="table table-striped">
    <tr>
      <td> No Order </td>
      <td> Package </td>
      <td> Total </td>
      <td> Status </td>
      <td> Created </td>
    </tr>
    <?php 
      if ($orders->count() >0) {
        foreach ($orders as $arr) {
    ?>
          <tr>
            <td> 
						<?php 
							$shortcode = str_replace('OCLB', '', $arr->no_order);
							echo $shortcode;
						?>
						</td>
            <td> {{$arr->package_name}} </td>
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
          </tr>
    <?php } } else { ?> 
    <tr>
      <td colspan=5> Tidak ada data </td>
    </tr>
    <?php }  ?> 
  </table>
@endsection
