@extends('member.index')

@section('content')
<script type="text/javascript">
  $(document).ready(function() {
    $("#alert").hide();
  });
</script>
  <table class="table table-striped">
    <tr>
      <td> No Invoice </td>
      <td> Package </td>
      <td> Total </td>
      <td> Created </td>
    </tr>
    <?php 
      if ($invoice->count() >0) {
        foreach ($invoice as $arr) {
    ?>
          <tr>
            <td> {{$arr->no_invoice}} </td>
            <td> {{$arr->package_name}} </td>
            <td> {{$arr->total}} </td>
            <td> {{$arr->created_at}} </td>
          </tr>
    <?php } } else { ?> 
    <tr>
      <td colspan=3> Tidak ada data </td>
    </tr>
    <?php }  ?> 
  </table>
@endsection
