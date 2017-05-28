<style>
a:hover, a:active, a:focus {
  /* styling for any way a link is about to be used */
	text-decoration:none!important;outline: none!important;
}
input[type="text"]:disabled,input[type="password"]:disabled {
    background: #dddddd;
}
</style>
				<?php 
				use Celebgramme\Models\SettingMeta; 
				use Celebgramme\Models\Proxies; 
				use \InstagramAPI\Instagram;
				
				if (isset($datas)) { 
					foreach ($datas as $data ) {
					if (SettingMeta::getMeta($data->id,"photo_filename") == "0") {
						$photo = url('images/profile-default.png');
					} else {
						$photo = url("images/pp/". SettingMeta::getMeta($data->id,"photo_filename"));
					}
					
					//hitung unseen_count DM
					$unseen_count = SettingMeta::getMeta($data->id,"unseen_count");
					// if (!$data->error_cred) {
						// try {
							// $i = new Instagram(false,false,[
								// "storage"       => "mysql",
								// "dbhost"       => Config::get('automation.DB_HOST'),
								// "dbname"   => Config::get('automation.DB_DATABASE'),
								// "dbusername"   => Config::get('automation.DB_USERNAME'),
								// "dbpassword"   => Config::get('automation.DB_PASSWORD'),
							// ]);
							
							// $i->setUser(strtolower($data->insta_username), $data->insta_password);
							// $proxy = Proxies::find($data->proxy_id);
							// if (!is_null($proxy)) {
								// $i->setProxy("http://".$proxy->cred."@".$proxy->proxy.":".$proxy->port);					
							// }
							
							// $i->login(false,300);
							// $pendingInboxResponse = $i->getPendingInbox();
							// $unseen_count = $pendingInboxResponse->inbox->unseen_count;
						// }
						// catch (Exception $e) {
							// return $e->getMessage();
						// }
					// }
					
				?>
				<div class="col-md-4 col-sm-12 col-xs-12">
					<div class="card same-height">
                        <div class="header bg-cyan br-t-6">
													<span data-id="{{$data->id}}" class="delete-button glyphicon glyphicon-remove" style="cursor:pointer;" aria-hidden="true" data-toggle="modal" data-target="#confirm-delete" ></span> 
													<a href="https://instagram.com/{{$data->insta_username}}" target="_blank">
                            <h2>
															<img src="{{$photo}}" class="img-circle" style="width:70px;height:70px;">
                                &nbsp; {{$data->insta_username}}
                            </h2>
													</a>
													
                        </div>
                        <div class="body">
                            <div class="row">
								<div class="col-md-3 col-sm-3 col-xs-3 startStopArea">
									<center>
									<small><b>Activity</b></small>
									<!--
									<img src="{{asset('/new-dashboard/images/startIcon.png')}}"class="img-responsive">
									<span class="confirmStart text-success col-teal">Started</span>
									-->
									<?php 
									$stopped_icon = asset('/new-dashboard/images/stopIcon.png');
									$started_icon = asset('/new-dashboard/images/startIcon.png');
									
									if ($data->status=='stopped') { 
										echo '<img src="'.$stopped_icon.'" class="img-responsive">
									<span class="confirmStop text-danger col-pink">Stopped</span>'; 
									} 
									else {
										echo '<img src="'.$started_icon.'" class="img-responsive" style="animation: spin 2s infinite linear;">
										<span class="confirmStart text-success col-teal">Started</span>';}
									?>
								</div>
								<div class="col-md-3 col-sm-3 col-xs-3">
									<center>
									<small><b>Following</b></small>
									<img src="{{asset('/new-dashboard/images/followingIcon.png')}}"class="img-responsive">
									<?php echo number_format(intval (SettingMeta::getMeta($data->id,"following")),0,'','.'); ?>
									</center>
								</div>
								<div class="col-md-3 col-sm-3 col-xs-3 text=center">
									<center>
									<small><b>Followers</b></small>
									<img src="{{asset('/new-dashboard/images/followersIcon.png')}}"class="img-responsive">
									<?php echo number_format(intval (SettingMeta::getMeta($data->id,"followers")),0,'','.'); ?>
									</center>
								</div>
								<div class="col-md-3 col-sm-3 col-xs-3">
									<center>
									<small style="font-size:80%;white-space: nowrap;"><b>DM Inbox</b></small>
									<img src="{{asset('/new-dashboard/images/mailIcon.png')}}" class="img-responsive">
										{{$unseen_count}}
									</center>
								</div>
							</div>
							<?php if ($data->error_cred) { ?>
							<div class="row im-centered"> 
								<p class="text-danger" style="font-size:12px;"> <strong>
								<?php 
									if ( SettingMeta::getMeta($data->id,"error_message_cred") == "0"  ) {
								?>
									*Data login error <br>
									silahkan input kembali password anda 
									<?php } else { 
										echo SettingMeta::getMeta($data->id,"error_message_cred");
									} ?>
									---> <a href="#" data-id="{{$data->id}}" data-username="{{$data->insta_username}}" class="edit-cred" data-toggle="modal" data-target="#myModal-edit-password">CLICK disini</a> <--- </strong></p>
							</div>
							<?php } ?>
							
							<div class="row">
								<?php if (SettingMeta::getMeta($data->id,"auto_unfollow") == "yes" )  { ?>
								<div class="col-md-12 col-sm-12 col-xs-12">
									<p class="text-center">*Proses auto unfollow akan dijalankan karena jumlah following anda telah mencapai 7250</p>
								</div>
								<?php } ?>
							</div>
							<div class="row">
								<div class="col-md-6 col-sm-6 col-xs-6">
									<!--
									<button class="btn bg-red btn-block text-center waves-effect btnStop br-6">
										<i class="fa fa-stop"></i>&nbsp;<span>Stop</span>
									</button>
									-->
									<button data-id="{{$data->id}}" class="btn br-6 btn-block text-center waves-effect <?php if ($data->status=='stopped') { echo 'btn-success'; } else {echo 'btn-danger';} ?> button-action btn-{{$data->id}}" value="<?php if ($data->status=='stopped') { echo 'Start'; } else {echo 'Stop';}?>">
										<?php if ($data->status=='stopped') { echo "<span class='glyphicon glyphicon-play'></span> Start"; } else {echo "<span class='glyphicon glyphicon-stop'></span> Stop";}?> 
									</button>
									
								</div>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<a href="{{url('setting/'.$data->id)}}">
										<button class="btn bgBlueGreen btn-block text-center text-white waves-effect br-6">
											<span class='glyphicon glyphicon-cog'></span> Setting
										</button>
									</a>
								</div>
							</div>
                        </div>
                    </div>
				</div>
				<?php } } ?>
