														<div class="col-md-12 col-sm-12 col-xs-12">
															<div class="clearfix"></div><br/>
															
															<?php
																if (count($pendingInboxResponse->inbox->threads) > 0 ) {
																	foreach ($pendingInboxResponse->inbox->threads as $data_arr) {
																		$date_message = substr($data_arr->items[0]->timestamp,0,10);
																		$text_message = $data_arr->items[0]->text;
																		if (strlen($text_message)>=42) {
																			$text_message = substr($text_message,0,42)." ...";
																		}
															?>
															<div class="row">
																<div style="min-height:100px;" class="col-md-5 col-sm-12 col-xs-12 bg-white br-6">
																	<h2 style="color:#333;font-weight:200;">
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
																					<b class="text-primary">
																						{{date("l", $date_message)}}<br>{{date("Y-m-d", $date_message)}}
																					</b>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-4 col-sm-4 col-xs-4">
																			<div class="br-6">
																				<div style="min-height:100px;cursor:pointer;" class="body bgGreenLight br-6 text-center button-accept-request" data-thread-id="{{$data_arr->thread_id}}">
																					<i class="fa fa-check fa-2x"></i><br/>
																					<b class="text-white">Accept</b>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-4 col-sm-4 col-xs-4">
																			<div class="br-6">
																				<div style="min-height:100px;cursor:pointer;" class="body bg-red br-6 text-center button-decline-request" data-toggle="modal" data-target="#confirm-decline" data-thread-id="{{$data_arr->thread_id}}">
																					<i class="fa fa-times fa-2x"></i><br/>
																					<b class="text-white">Decline</b>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														<?php 
																}
															}
														?>
															
														</div>
