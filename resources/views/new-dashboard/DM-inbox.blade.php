														<div class="col-md-12 col-sm-12 col-xs-12">
															<div class="clearfix"></div><br/>
															<?php
																if (count($inboxResponse->inbox->threads) > 0 ) {
																	foreach ($inboxResponse->inbox->threads as $data_arr) {
																		$date_message = substr($data_arr->items[0]->timestamp,0,10);
																		$text_message = $data_arr->items[0]->text;
																		if (strlen($text_message)>=42) {
																			$text_message = substr($text_message,0,42)." ...";
																		}
															?>
															<div class="row">
																<div style="min-height:100px;" class="col-md-5 col-sm-12 col-xs-12 bg-white br-6">
																	<h2 style="color:#333;font-weight:200;">
																		<!--
																		<button type="button" style="min-width:80px;height:80px;"class="pull-left m-t-25 iconInstaAccount btn bgBlueGreen btn-circle-lg waves-effect waves-circle waves-float">
																			<i style="font-size:24px;" class="fa fa-user text-white"></i>
																		</button>
																		-->
																		<img src="{{$data_arr->users[0]->profile_pic_url}}" class="img-circle" style="width:50px;height:50px;">
																		
																		&nbsp;{{$data_arr->users[0]->username}}
																	</h2>
																	<small style="color:#333;">{{$text_message}}</small>
																</div>
																<div class="col-md-7 col-sm-12 col-xs-12">
																	<div class="row">
																		<div class="col-md-4 col-sm-4 col-xs-4">
																			<div class="br-6">
																				<div style="min-height:100px;" class="body bg-white br-6 text-center">
																					<b class="text-primary">{{date("l", $date_message)}}<br>{{date("Y-m-d", $date_message)}}</b>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-4 col-sm-4 col-xs-4">
																			<div class="br-6">
																<div style="min-height:100px;cursor:pointer;" class="body bg-cyan br-6 text-center button-reply" data-thread-id="{{$data_arr->thread_id}}" data-username="{{$data_arr->users[0]->username}}" data-pic="{{$data_arr->users[0]->profile_pic_url}}" href="#chat-all" data-toggle="tab" >
																					<i class="fa fa-mail-reply fa-2x"></i><br/>
																					<b class="text-white">Reply</b>
																				</div>
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
															
														</div>
