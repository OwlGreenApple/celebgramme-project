<!DOCTYPE html>
<html>
    <head>
      <title>Celebgramme</title>
      <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/images/celebgramme-favicon.png') }}">
      <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
      <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
      <link href="{{ asset('/css/bootstrap-theme.min.css') }}" rel="stylesheet">
      <link href="{{ asset('/css/sign-in.css') }}" rel="stylesheet">
      <script type="text/javascript">
        $(document).ready(function() {
          $("#alert").hide();
        });
      </script>
    </head>
    <body>
      <div class="container">  
<form action="{{url('payment/process')}}" method="POST">

  <table class="table table-striped">
    <tr>
      <td>
        Daily Likes
      </td>
      <td>
        Price(1 day)
      </td>
      <td>
        Price(7 day)
      </td>
      <td>
        Price(28 day)
      </td>
    </tr>
    <tr>
      <td>
        200
      </td>
      <td>
        <input type="radio" name="package" value="1" id="a1" checked>
        <label for="a1">10.000</label>
      </td>
      <td>
        <input type="radio" name="package" value="2" id="a2">
        <label for="a2">60.000</label>
      </td>
      <td>
        <input type="radio" name="package" value="3" id="a3">
        <label for="a3">180.000</label>
      </td>
    </tr>
    <tr>
      <td>
        500
      </td>
      <td>
        <input type="radio" name="package" value="4" id="b1">
        <label for="b1">15.000</label>
      </td>
      <td>
        <input type="radio" name="package" value="5" id="b2">
        <label for="b2">90.000</label>
      </td>
      <td>
        <input type="radio" name="package" value="6" id="b3">
        <label for="b3">270.000</label>
      </td>
    </tr>
    <tr>
      <td>
        1000
      </td>
      <td>
        <input type="radio" name="package" value="7" id="c1">
        <label for="c1">20.000</label>
      </td>
      <td>
        <input type="radio" name="package" value="8" id="c2">
        <label for="c2">120.000</label>
      </td>
      <td>
        <input type="radio" name="package" value="9" id="c3">
        <label for="c3">360.000</label>
      </td>
    </tr>
    <tr>
      <td>
        2000
      </td>
      <td>
        <input type="radio" name="package" value="10" id="d1">
        <label for="d1">30.000</label>
      </td>
      <td>
        <input type="radio" name="package" value="11" id="d2">
        <label for="d2">180.000</label>
      </td>
      <td>
        <input type="radio" name="package" value="12" id="d3">
        <label for="d3">540.000</label>
      </td>
    </tr>
    <tr>
      <td>
        3000
      </td>
      <td>
        <input type="radio" name="package" value="13" id="e1">
        <label for="e1">40.000</label>
      </td>
      <td>
        <input type="radio" name="package" value="14" id="e2">
        <label for="e2">240.000</label>
      </td>
      <td>
        <input type="radio" name="package" value="15" id="e3">
        <label for="e3">720.000</label>
      </td>
    </tr>
  </table>

  <div class="form-group form-group-sm row">
    <label class="col-xs-8 col-sm-4 control-label" for="formGroupInputSmall">Metode Pembayaran</label>
    <div class="col-sm-8 col-md-6">
      <select class="form-control" name="payment-method">
        <option value="1">Bank transfer</option>
        <option value="2">Veritrans</option>
      </select>
    </div>
  </div>  
  <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
  <input class="btn btn-default" type="submit" value="Process" id="button-process">
</form>
      </div>
    </body>
</html>
