@extends('member.index')

@section('content')
<div class="form-group form-group-sm row">
  <label class="col-sm-1 control-label" for="formGroupInputSmall">Full name</label>
  <div class="col-xs-4">
    <input type="text" class="form-control" placeholder="">
  </div>
</div>  
<div class="form-group form-group-sm row">
  <label class="col-sm-1 control-label" for="formGroupInputSmall">Phone number</label>
  <div class="col-xs-4">
    <input type="text" class="form-control" placeholder="">
  </div>
</div>  
<div class="form-group form-group-sm row">
  <label class="col-sm-1 control-label" for="formGroupInputSmall">Photo</label>
  <div class="col-xs-4">
    <input type="file" class="form-control">
  </div>
</div>  
<div class="form-group form-group-sm row">
  <div class="col-sm-1">
  </div>
  <div class="col-xs-4">
    <input class="btn btn-default" type="submit" value="Submit">
  </div>
</div>  
@endsection
