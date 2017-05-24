															<?php foreach($auto_responder_setting as $data_auto_responder) { ?>
																<div class="col-md-12 col-sm-12 col-xs-12">
																	<div class="col-md-2 col-sm-12 col-xs-12">
																		{{$data_auto_responder->num_of_day}} days
																	</div>
																	<div class="col-md-9 col-sm-12 col-xs-12">
																		<textarea class="form-control" style="height:70px;" disabled value="{{$data_auto_responder->message}}">{{$data_auto_responder->message}}</textarea>
																	</div>
																	<div class="col-md-1 col-sm-12 col-xs-12">
																		<button class="form-control btn bg-cyan button-edit-auto-responder" data-toggle="modal" data-target="#add-autoresponder" style="margin-left:0px;" data-id="{{$data_auto_responder->id}}" data-num="{{$data_auto_responder->num_of_day}}" data-message="{{$data_auto_responder->message}}"><span class="glyphicon glyphicon-cog"></span> </button>
																		<button class="form-control btn bg-red button-delete-auto-responder" data-toggle="modal" data-target="#delete-auto-responder" data-id="{{$data_auto_responder->id}}" style="margin-left:0px;"><span class="glyphicon glyphicon-minus"></span></button>
																	</div>
																</div>
															<?php } ?>
