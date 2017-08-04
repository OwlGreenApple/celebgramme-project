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
							// else if (strtolower($data->getItemType()) == "reel_share" ) {
							else {	
								$url_img = "";
								$message = "";
								if (strtolower($data->getItemType()) == "reel_share" ) {
									$shareData = $data->getReelShare();
								}
								else if (strtolower($data->getItemType()) == "media_share" ) {
									$shareData = $data->getMediaShare();
								}
								else if (strtolower($data->getItemType()) == "raven_media" ) {
									$shareData = $data->getRavenMedia();
								}
								
								//harus ada pengecekan klo carousel atau image biasa, klo carousel diambil gambar yang pertama
								
								if (!is_null($shareData)) {
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
									if (!is_null($data->getText())) {
										$message = $data->getText();
									}
									// $message = $shareData->getText();
									
									//klo ada media_sharenya
									$mediaShare = $data->getMediaShare();
									if (!is_null($mediaShare)) {
										//dari image biasa 
										$res_url = $mediaShare->getImageVersions2()->getCandidates()[0]->getUrl();
										if (!is_null($res_url)) {
											$url_img = $res_url;
										}
										
										//dari image carousel 
										$res_url = $mediaShare->getCarouselMedia()[0]->getCandidates()[0]->getUrl();
										if (!is_null($res_url)) {
											$url_img = $res_url;
										}
									}
									
								}
								
								if ($url_img <> "") {
									echo '<img class="img-responsive" src="'.$url_img.'" style="width:200px;height:100%;"><br>'.$message;
								} else {
									echo $message;
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
