<div>
	<div class="row">
		<div class="col-md-1 col-sm-1 col-xs-1">
			<button class="btn form-control " value=""  href="#DMInbox" data-toggle="tab"> 
				<span class="glyphicon glyphicon-arrow-left">
				</span>
			</button>
		</div>
		<div class="col-md-2 col-sm-2 col-xs-2">
			<h5>
			<!--<img src="{{$data_pic}}" class="img-circle" style="width:50px;height:50px;"> &nbsp --> {{$username_user}}</h5>
		</div>
	</div>
  <div class="row" style="overflow: auto; max-height: 500px;">
		<?php 
			$items = array_reverse($chatAll->thread->items);
			foreach($items as $data) { ?>
				<div style="height:50px;overflow:hidden;">
				<div class="col-md-1 col-sm-1 col-xs-1">
				&nbsp
				</div>	
				<div class="col-md-9 col-sm-9 col-xs-9">
					<div class="<?php if ($chatAll->thread->users[0]->pk != $data->user_id ) { echo "fr"; } else { echo "fl"; } ?> well" style="padding:10px; <?php if ($chatAll->thread->users[0]->pk == $data->user_id ) { echo "background-color:#fff;"; }?>">
						{{$data->text}}
					</div>
				</div>
				<div class="col-md-2 col-sm-2 col-xs-2">
				</div>
				</div>
				
		<?php } ?>
  </div>	
	<div class="row">
		<div class="col-md-1 col-sm-1 col-xs-1">
		</div>	
		<div class="col-md-9 col-sm-9 col-xs-9">
			<input type="text" id="text-message-inbox" class="form-control">
		</div>
		<div class="col-md-2 col-sm-2 col-xs-2">
			<input type="button" class="btn form-control button-message-inbox" value="Send" data-pk-id="{{$chatAll->thread->users[0]->pk}}" data-setting-id="{{$setting_id}}" data-thread-id="{{$thread_id}}" data-username="{{$username_user}}" data-pic="{{$data_pic}}">
		</div>
	</div>
	<!--
	<a id="button-like-inbox">like</a>
	-->
</div>
