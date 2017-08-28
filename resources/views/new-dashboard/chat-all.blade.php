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
			// $items = array_reverse($chatAll->thread->items);
			$items = array_reverse($chatAll->getThread()->getItems());
			foreach($items as $data) { ?>
				<div style="min-height:50px;overflow:hidden;">
				<div class="col-md-1 col-sm-1 col-xs-1">
				&nbsp
				</div>	
				<div class="col-md-9 col-sm-9 col-xs-9">
					<div class="<?php if ($chatAll->thread->getUsers()[0]->getPk() != $data->getUserId() ) { echo "fr"; } else { echo "fl"; } ?> well" style="padding:10px; <?php if ($chatAll->thread->getUsers()[0]->getPk() == $data->getUserId() ) { echo "background-color:#fff;"; }?>">
						<?php 
							if (strtolower($data->getItemType()) == "text" ) {
								echo $data->getText();
							}
							else if (strtolower($data->getItemType()) == "live_video_share" ) {
								echo "Live video return null result";
							}
							else {	
								$url_img = "";
								$message = "";
								$mode_message = ""; $caption = ""; $insta_username="";
								$like = false; $shareData = null;
								if (strtolower($data->getItemType()) == "link" ) {
									$mode_message = "link";
									$shareData = $data->getLink();
									
									if (!is_null($shareData->getText())) {
										$message = $shareData->getText();
									}
									if (!is_null($shareData->getLinkContext())) {
										$url_img = $shareData->getLinkContext()->getLinkImageUrl();
									}
								}
								if (strtolower($data->getItemType()) == "reel_share" ) {
									$shareData = $data->getReelShare();
									if (!is_null($shareData->getText())) {
										$message = $shareData->getText();
									}
									$mode_message = "insta_stories";
									
									
									if (!is_null($shareData->getMedia())) {
										if (!is_null($shareData->getMedia()->getId())) {
											if (!is_null($i->media->getInfo($shareData->getMedia()->getId())->getItems())) {
												$res_url = $i->media->getInfo($shareData->getMedia()->getId())->getItems()[0]->getImageVersions2()->getCandidates()[0]->getUrl();
												if (!is_null($res_url)) {
													$url_img = $res_url;
												}
											}
										}
									}
								}
								else if (strtolower($data->getItemType()) == "media_share" ) {
									$shareData = $data->getMediaShare();
									$mode_message = "photo_share";
									
									//klo media_share
									$mediaShare = $data->getMediaShare();
									if (!is_null($mediaShare)) {
										//dari image biasa 
										$res_url = $mediaShare->getImageVersions2();
										if (!is_null($res_url)) {
											if (!is_null($res_url->getCandidates())) {
												$url_img = $res_url->getCandidates()[0]->getUrl();
											}
										}
										
										//dari image carousel 
										$res_url = $mediaShare->getCarouselMedia();
										if (!is_null($res_url)) {
											if (!is_null($res_url[0]->getImageVersions2())) {
												if (!is_null($res_url[0]->getImageVersions2()->getCandidates())) {
													$url_img = $res_url[0]->getImageVersions2()->getCandidates()[0]->getUrl();
												}
											}
										}
										
										if (!is_null($mediaShare->getCaption())) {
											$caption = $mediaShare->getCaption()->getText();
										}
										if (!is_null($mediaShare->getUser())) {
											$insta_username = $mediaShare->getUser()->getUsername();
										}
									}
								}
								else if (strtolower($data->getItemType()) == "media" ) {
									$shareData = $data;
									$mode_message = "photo_share";
									//klo media
									if (!is_null($shareData->getMedia()->getImageVersions2()->getCandidates())) {
										$url_img = $shareData->getMedia()->getImageVersions2()->getCandidates()[0]->getUrl();
									}
									
									// $insta_username = $i->people->getInfoById($data->getUserId())->getUser()->getUsername();
								}
								else if (strtolower($data->getItemType()) == "raven_media" ) {
									$shareData = $data->getRavenMedia();
									$mode_message = "photo_share";
									if (!is_null($shareData->getImageVersions2())) {
										$url_img = $shareData->getImageVersions2()->getCandidates()[0]->getUrl();
									}
									else {
										$mode_message = "placeholder";
										$placeholder_title = "";
										$placeholder_content = "Delivered";
									}
									
								}
								else if (strtolower($data->getItemType()) == "placeholder" ) {
									$mode_message = "placeholder";
									$placeholder_title = "";
									$placeholder_content = "";
									if (!is_null($data->getPlaceholder())) {
										$placeholder_title = $data->getPlaceholder()->getTitle();
										$placeholder_content = $data->getPlaceholder()->getMessage();
									}
								}
								else if (strtolower($data->getItemType()) == "like" ) {
									$like = true;
								}
								
								if ( (!is_null($shareData)) && (!$like) ) {
									if (!is_null($data->getText())) {
										$message .= $data->getText();
									}
								}
								
								if (!$like) {
									if ($url_img <> "") {
										// echo '<img class="img-responsive" src="'.$url_img.'" style="width:200px;height:100%;"><br>'.$message;
										if ($mode_message == "insta_stories" ) {
											echo '<h5>Reply their stories</h5><img class="img-responsive" src="'.$url_img.'" style="width:200px;height:100%;"><br>'.$message;
										}
										if ($mode_message == "link" ) {
											echo $message.'<br><img class="img-responsive" src="'.$url_img.'" style="width:200px;height:100%;">';
										}
										if ($mode_message == "photo_share" ) {
											if (strlen($caption)>=15) {
												$caption = substr($caption,0,15)." ...";
											}
											echo '<a href="instagram.com/'.$insta_username.'" target="_blank"><h4 style="font-weight: bold;">'.$insta_username.'</h4></a> <img class="img-responsive" src="'.$url_img.'" style="width:200px;height:100%;"> <label>'.$caption.'</label> <br>'.$message;
										}
									} 
									else if ($mode_message=="placeholder") {
										if ($placeholder_title<> "" ) {
											echo '<h5>'.$placeholder_title.'</h5><br>'.$placeholder_content;
										} 
										else {
											echo $placeholder_content;
										}
									}
									else {
										echo $message;
									}
									
								} else {
									echo '<span class="glyphicon glyphicon-heart"></span>';
								}
							}
						?>
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
