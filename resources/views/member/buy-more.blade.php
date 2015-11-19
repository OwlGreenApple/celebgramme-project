@extends('member.index')

@section('content')
<script type="text/javascript">
  $(document).ready(function() {
    $("#alert").hide();
  });
</script>
<form action="{{url('payment/veritransredirect')}}" method="POST">
  <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
  <input class="btn btn-default" type="button" value="Process bank transfer" id="button-process">
  <input class="btn btn-default" type="submit" value="Process veritrans" id="button-process">
  <input class="btn btn-default" type="button" value="Process paypal" id="button-process">
</form>
@endsection
