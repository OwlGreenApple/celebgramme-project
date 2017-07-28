															<div class="clearfix"></div><br/>
															
															
															<nav>
																<ul class="pagination" id="pagination">
																<?php 
																if ($page=="") {
																	$currentPage = 1;
																} else {
																	$currentPage = $page;
																}
																
																$startPage = $currentPage - 4;
																$endPage = $currentPage + 4;

																if ($startPage <= 0) {
																		$endPage -= ($startPage - 1);
																		$startPage = 1;
																}

																if ($endPage > $totalPage)
																		$endPage = $totalPage;

																if ($startPage > 1) { 
																?>
																	<li <?php if ($currentPage==1) { echo 'class="active"'; } ?>>
																		<a href="#">1</a>
																	</li>
																	<li>
																		<a href="#" style="pointer-events: none;cursor: default;">..</a>
																	</li>
																<?php
																}
																
																for($ii=$startPage; $ii<=$endPage; $ii++) {
																?>
																	<li <?php if ($currentPage==$ii) { echo 'class="active"'; } ?>>
																		<a href="#">{{$ii}}</a>
																	</li>
																<?php 
																} 
																
																
																if ($endPage < $totalPage) { 
																?>
																	<li>
																		<a href="#" style="pointer-events: none;cursor: default;">..</a>
																	</li>
																	<li <?php if ($currentPage==$totalPage) { echo 'class="active"'; } ?>>
																		<a href="#">{{$totalPage}}</a>
																	</li>
																	
																<?php
																}
																?>
																</ul>
															</nav>  
															
															
															
															
															
															<?php
																$counter =0;
																if (count($arr_inbox) > 0 ) {
																	foreach ($arr_inbox as $data_arr) {
																		// $date_message = substr($data_arr->items[0]->timestamp,0,10);
																		// $text_message = $data_arr->items[0]->text;
																		// if (strlen($text_message)>=42) {
																			// $text_message = substr($text_message,0,115)." ...";
																		// }
																		
																		//klo ga ada usernya di break
																		// if ( (is_null($data_arr->users)) || (empty($data_arr->users)) ) {
																			// continue;
																		// }
																		
																		//checking new message or not
																		// $status_new_message = false;
																		if ($data_arr['pk'] == $data_arr['user_id']) {
																			// $status_new_message = true;
																			$counter += 1;
																		}
															?>
															<div class="row">
																<div style="padding:5px;margin-bottom:-5px;" class="col-md-5 col-sm-12 col-xs-12 bg-white br-6 same-height">
																	<div style="margin-top:10px;" class="col-md-2 col-sm-2 col-xs-2 ">
																		<img src="{{$data_arr['profile_pic_url']}}" class="img-circle" style="width:50px;height:50px;">
																	</div>
																	<div style="" class="col-md-10 col-sm-10 col-xs-10 ">
																		<div>
																			<h5 style="color:#333;font-weight:200;">
																				{{$data_arr['username']}}
																			</h5>
																			<?php if ($data_arr['status_new_message']) { echo '<label class="label bgBlueGreen " style="position:absolute;top:0px;right:0px;">waiting response</label> '; } ?>
																		</div>
																		<small style="color:#333;<?php if ($data_arr['status_new_message']) { echo "font-weight:Bold;"; } ?>">{{$data_arr['text_message']}}</small>
																	</div>
																</div>
																<div style="margin-bottom:-5px;" class="col-md-7 col-sm-12 col-xs-12 ">
																	<div class="row">
																		<div class="col-md-4 col-sm-12 col-xs-12 br-6" style="margin-right: -16px;">
																			<div style="padding:5px;" class="bg-white br-6 text-center same-height">
																				<b class="text-primary" style="margin-top:15px;display:block">{{$data_arr['date_message1']}}<br>{{$data_arr['date_message2']}}</b>
																			</div>
																		</div>
																		<div class="col-md-4 col-sm-12 col-xs-12 br-6" style="">
																			<div style="padding:5px;cursor:pointer;" class="bg-cyan br-6 text-center button-reply same-height" data-thread-id="{{$data_arr['thread_id']}}" data-username="{{$data_arr['username']}}" data-pic="{{$data_arr['profile_pic_url']}}" href="#chat-all" data-toggle="tab" >
																				<i class="fa fa-mail-reply fa-2x" style="margin-top:15px;display:block"></i>
																				<b class="text-white">Reply</b>
																			</div>
																		</div>
																		<!--
																		<div class="col-md-4 col-sm-4 col-xs-4">
																			<div class="br-6">
																				<div style="min-height:100px;" class="body bg-red br-6 text-center">
																					<i class="material-icons">delete</i><br/>
																					<b class="text-white">Delete</b>
																				</div>
																			</div>
																		</div>
																		-->
																	</div>
																</div>
															</div>
															<?php 															
																	}
																}
															?>
															<!--
															PAGINATION
															-->
															
															<nav>
																<ul class="pagination" id="pagination">
																<?php 
																if ($page=="") {
																	$currentPage = 1;
																} else {
																	$currentPage = $page;
																}
																
																$startPage = $currentPage - 4;
																$endPage = $currentPage + 4;

																if ($startPage <= 0) {
																		$endPage -= ($startPage - 1);
																		$startPage = 1;
																}

																if ($endPage > $totalPage)
																		$endPage = $totalPage;

																if ($startPage > 1) { 
																?>
																	<li <?php if ($currentPage==1) { echo 'class="active"'; } ?>>
																		<a href="#">1</a>
																	</li>
																	<li>
																		<a href="#" style="pointer-events: none;cursor: default;">..</a>
																	</li>
																<?php
																}
																
																for($ii=$startPage; $ii<=$endPage; $ii++) {
																?>
																	<li <?php if ($currentPage==$ii) { echo 'class="active"'; } ?>>
																		<a href="#">{{$ii}}</a>
																	</li>
																<?php 
																} 
																
																
																if ($endPage < $totalPage) { 
																?>
																	<li>
																		<a href="#" style="pointer-events: none;cursor: default;">..</a>
																	</li>
																	<li <?php if ($currentPage==$totalPage) { echo 'class="active"'; } ?>>
																		<a href="#">{{$totalPage}}</a>
																	</li>
																	
																<?php
																}
																?>
																</ul>
															</nav>  
															
															
															
															
															
															
															
<script>
	$("#badge-dm-inbox").html("<?php 
	if ($counter > 0 ) {
		echo $counter;
	}
	?>");
	$("#badge-dm-request").html("<?php 
	if (count($pendingInboxResponse->getInbox()->getThreads()) > 0 ) {
		echo count($pendingInboxResponse->getInbox()->getThreads()); 
	}
	?>");
	
	
	$('#pagination a').click(function(e){
		e.preventDefault();
		e.stopPropagation();
		$("#pagination li").removeClass("active");
		$(this).parent().addClass("active");
		if ($(this).html() == "«") {
			page -= 1; 
		} else 
		if ($(this).html() == "»") {
			page += 1; 
		} else {
			page = parseInt($(this).html());
		}
		load_dm_inbox(page);
	});
	
</script>