<div>
	<?php foreach($listMessageResponse->thread->items as $data) { ?>
		{{$data->text}}
		<br>
	<?php } ?>
	<input type="text" id="text-message-inbox">
	<input type="button" class="button-message-inbox" value="send" data-pk-id="{{$listMessageResponse->thread->users[0]->pk}}" data-setting-id="{{$setting_id}}" data-thread-id="{{$thread_id}}"><a id="button-like-inbox">like</a>
</div>
