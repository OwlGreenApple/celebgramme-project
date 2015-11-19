@extends('member.index')

@section('content')
<script type="text/javascript">
  $(document).ready(function() {
    $("#alert").hide();
  });
</script>
<form action="{{url('process-veritrans')}}" method="POST">
  <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
  <input class="btn btn-default" type="submit" value="Process" id="button-process">
</form>
@endsection
