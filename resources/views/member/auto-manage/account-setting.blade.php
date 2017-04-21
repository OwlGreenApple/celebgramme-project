@extends('member.index')

@section('content-auto-manage')
<?php use Celebgramme\Models\SettingMeta; 
use Celebgramme\Models\SettingHelper; 
?>

<script>
	function call_action(action,id){
		$.ajax({
				type: 'GET',
				url: "<?php echo url('call-action'); ?>",
				data: {
					action : action,
					id : id,
				},
				dataType: 'text',
				beforeSend: function()
				{
					$("#div-loading").show();
				},
				success: function(result) {
						// $('#result').html(data);
						$("#div-loading").hide();
						var data = jQuery.parseJSON(result);
						$("#alert").show();
						$("#alert").html(data.message);
						if(data.type=='success')
						{
							$("#alert").addClass('alert-success');
							$("#alert").removeClass('alert-danger');
							if(data.action=='start'){
								$(".btn-"+data.id).html("<span class='glyphicon glyphicon-stop'></span> Stop");
								$(".btn-"+data.id).val("Stop");
								$(".btn-"+data.id).parent().parent().parent().find(".status-activity p").html(' Status activity : <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> <span style="color:#5cb85c; font-weight:Bold;">Started</span>');
								$(".btn-"+data.id).removeClass("btn-success");
								$(".btn-"+data.id).addClass("btn-danger");
							}
							if(data.action=='stop'){
								$(".btn-"+data.id).html("<span class='glyphicon glyphicon-play'></span> Start");
								$(".btn-"+data.id).val("Start");
								$(".btn-"+data.id).parent().parent().parent().find(".status-activity p").html(' Status activity : <span class="glyphicon glyphicon-stop"></span> <span style="color:#c12e2a; font-weight:Bold;">Stopped</span>');
								$(".btn-"+data.id).removeClass("btn-danger");
								$(".btn-"+data.id).addClass("btn-success");
							}
						}
						else if(data.type=='error')
						{
							$("#alert").html($("#alert").html());
							$("#alert").addClass('alert-danger');
							$("#alert").removeClass('alert-success');
						}
				}
		})
		return false;
	}

	$(document).ready(function(){
    $('#button-save,#button-save2').click(function(e){
      $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type: 'POST',
          url: "<?php echo url('process-save-setting'); ?>",
          data: $("#form-setting").serialize(),
          dataType: 'text',
          beforeSend: function()
          {
            $("#div-loading").show();
          },
          success: function(result) {
              // $('#result').html(data);
              // console.log(result);return false;
              window.scrollTo(0, 0);
              $("#div-loading").hide();
              var data = jQuery.parseJSON(result);
              $("#alert").show();
              $("#alert").html(data.message);
              if(data.type=='success')
              {
                $("#alert").addClass('alert-success');
                $("#alert").removeClass('alert-danger');
              }
              else if(data.type=='error')
              {
                $("#alert").addClass('alert-danger');
                $("#alert").removeClass('alert-success');
              }
          }
      })
    });

    $('.selectize-target').selectize({
			persist: false,
			delimiter: ';',
			options: [
				<?php echo $strCategory; ?>
			],
			optgroups: [
				<?php echo $strClassCategory; ?>
			],
			optgroupField: 'class',
			labelField: 'name',
			searchField: ['name'],
			render: {
					optgroup_header: function(data, escape) {
							return '<div class="optgroup-header" style="font-size:16px;"><strong>' + escape(data.label) + '</strong></div>';
					}
			},
			plugins:['remove_button']
    });

		<?php if ($settings->status_auto) { ?>
			$(".advanced-manual-setting").addClass("hide");
		<?php } ?>

		$('#activity-speed').on('change', function() {
			if (this.value=="turbo") {
				alert("Ada Resiko anda akan diban oleh instagram menggunakan speed ini.");
			}
		})
	});
	
</script>
<script type="text/javascript" src="{{ asset('/js/setting.js') }}"></script>
<style>
	.gold-fullauto-setting {
		background: #fff499; 
		background: -moz-linear-gradient(top, #fff499 0%, #efdd37 100%); 
		background: -webkit-linear-gradient(top, #fff499 0%,#efdd37 100%); 
		background: linear-gradient(to bottom, #fff499 0%,#efdd37 100%); 
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fff499', endColorstr='#efdd37',GradientType=0 ); 
		color : #000!important; outline:none!important;
	}
	.black-blacklist {
		background: #7d7e7d; 
		background: -moz-linear-gradient(top,  #7d7e7d 0%, #0e0e0e 100%); 
		background: -webkit-linear-gradient(top,  #7d7e7d 0%,#0e0e0e 100%); 
		background: linear-gradient(to bottom,  #7d7e7d 0%,#0e0e0e 100%); 
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#7d7e7d', endColorstr='#0e0e0e',GradientType=0 ); 
	}
</style>


<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Copy your text
										<span class="glyphicon glyphicon-question-sign tooltipPlugin" title="<div class='panel-heading'>Copy your text</div><div class='panel-content'>• Fasilitas untuk mencopy ke textbox lain. <br>• Silahkan edit dulu sebelum melakukan copy paste ke textbox lain. <br>• Text yang sama persis tidak dapat di paste ke textbox lain</div>">
							
					</span>

					</h4>
        </div>
        <div class="modal-body">
					<textarea id="textarea-copy" class="form-control" style="min-height:100px;height:auto;"></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" id="button-ok-copy">Copy All</button>
        </div>
      </div>
<script>
document.getElementById("button-ok-copy").addEventListener("click", function() {
    copyToClipboard(document.getElementById("textarea-copy"));
});
		function copyToClipboard(elem) {
				// create hidden text element, if it doesn't already exist
				var targetId = "_hiddenCopyText_";
				var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
				var origSelectionStart, origSelectionEnd;
				if (isInput) {
						// can just use the original source element for the selection and copy
						target = elem;
						origSelectionStart = elem.selectionStart;
						origSelectionEnd = elem.selectionEnd;
				} else {
						// must use a temporary form element for the selection and copy
						target = document.getElementById(targetId);
						if (!target) {
								var target = document.createElement("textarea");
								target.style.position = "absolute";
								target.style.left = "-9999px";
								target.style.top = "0";
								target.id = targetId;
								document.body.appendChild(target);
						}
						target.textContent = elem.textContent;
				}
				// select the content
				var currentFocus = document.activeElement;
				target.focus();
				target.setSelectionRange(0, target.value.length);
				
				// copy the selection
				var succeed;
				try {
						succeed = document.execCommand("copy");
				} catch(e) {
						succeed = false;
				}
				// restore original focus
				if (currentFocus && typeof currentFocus.focus === "function") {
						currentFocus.focus();
				}
				
				if (isInput) {
						// restore prior selection
						elem.setSelectionRange(origSelectionStart, origSelectionEnd);
				} else {
						// clear temporary content
						target.textContent = "";
				}
				return succeed;
		}

</script>
      
    </div>
  </div>



	
<!-- Modal -->
  <div class="modal fade" id="myModalCommentNotification" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
          <h4 class="modal-title">BACA DAHULU, Jika anda akan menggunakan fitur COMMENT : 
					</h4>
        </div>
        <div class="modal-body">
				<p>
				*PENTING : Ada update terbaru instagram yang menyaring comments spam.<br> 
				Pastikan Comments yang anda buat: <br>
				Benar-benar UNIK, di SPIN dengan Kombinasi RATUSAN comments, <br>
				& gunakan juga <@owner> & <@followers> untuk menghindari comment yang sama. <br>
				Apabila anda masih ragu, Cukup gunakan fitur LIKE & FOLLOW SAJA. <br>
				Tidak perlu menggunakan Comment terlebih dahulu.<br>
				<br>
				Terima kasih atas perhatiannya<br>

				</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" id="button-ok-info-comment" data-dismiss="modal" >OK</button>
        </div>
      </div>
    </div>
  </div>
	
	
	


<div class="row">
  <div class="col-sm-12 col-md-12">            
    <div class="alert alert-danger col-sm-18 col-md-18" id="alert">
    </div>  
  </div>          
</div>                        
<?php if (SettingMeta::getMeta($settings->id,"auto_unfollow") == "yes" )  { ?>
<div class="row">
  <div class="col-sm-12 col-md-12">            
    <div class="alert alert-info col-sm-18 col-md-18" id="">
			*Proses auto unfollow akan dijalankan karena jumlah following anda telah mencapai 7250
    </div>  
  </div>          
</div>                        
<?php } ?>


<form enctype="multipart/form-data" id="form-setting">


<div class="row">
  <div class="col-md-12 col-sm-12">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Data Users</h3>
      </div>
      <div class="panel-body">
<div class="col-md-5 col-xs-12 col-sm-5 border-styling ">
	<?php 
		if (SettingMeta::getMeta($settings->id,"photo_filename") == "0") {
			$photo = url('images/profile-default.png');
		} else {
			$photo = url("images/pp/". SettingMeta::getMeta($settings->id,"photo_filename"));
		}
	?>	
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="row"> <img src="{{$photo}}" class="circle-image"> </div>
		<div class="row"> <label>{{$settings->insta_username}}</label></div>
	</div>
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="row status-activity"> <p> Status activity : <?php if ($settings->status=='stopped') { echo '<span class="glyphicon glyphicon-stop"></span> <span style="color:#c12e2a; font-weight:Bold;">Stopped</span>'; } 
		else {echo '<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> <span style="color:#5cb85c; font-weight:Bold;">Started</span>';}?></p>
		</div>
		<?php if ($settings->status=='started') { ?>
		<div class="row"> 
			<p>Total waktu per akun:
				<span style="color:#5abe5a;">{{$view_timeperaccount}}</span>
			</p>
		</div>
		<?php } ?>
		<div class="row">
			<p>Total waktu berlangganan :
				<span style="color:#5abe5a;">{{$view_totaltime}}</span>
			</p>
		</div>
		<div class="row im-centered"> 
			<input type="button" value="Save" class="btn btn-info" id="button-save" style="margin-bottom:5px;">
			
			<button data-id="{{$settings->id}}" class="btn <?php if ($settings->status=='stopped') { echo 'btn-success'; } else {echo 'btn-danger';} ?> button-action btn-{{$settings->id}}" value="<?php if ($settings->status=='stopped') { echo 'Start'; } else {echo 'Stop';}?>" style="margin-bottom:5px;">
				<?php if ($settings->status=='stopped') { echo "<span class='glyphicon glyphicon-play'></span> Start"; } else {echo "<span class='glyphicon glyphicon-stop'></span> Stop";}?> 
			</button>
		</div>
	</div>
</div>
<div class="col-md-7 col-xs-12 col-sm-7 pricing" style="margin-left:-10px;">
	<?php if ($user->link_affiliate=="") { ?>
	<div class="row">
		<div class="col-md-12 col-sm-12">
			<p>Perpanjang waktu berlangganan anda : <a href="#" id="button-package">Extra Time Package</a></p>
			
		</div>
	</div>
	<div class="row">
		<div id="normal-time">
			<div class="col-md-3 col-sm-3 border-price">
				<div class="row im-centered"> 
					<p class="header">30</p>
				</div>
				<div class="row im-centered"> 
					<p class="header-description">Days</p>
				</div>
				<div class="row im-centered"> 
					<strong>Rp. 195.000</strong>
				</div>
				<div class="row im-centered"> 
				</div>
				<div class="row im-centered button-price"> 
					<a href="{{url('buy-more/16')}}"><input type="button" value="Buy now" class="btn btn-success"></a>
				</div>
			</div>
			<div class="col-md-3 col-sm-3 border-price">
				<div class="row im-centered"> 
					<p class="header">60 </p>
				</div>
				<div class="row im-centered"> 
					<p class="header-description">Days</p>
				</div>
				<div class="row im-centered"> 
					<strong>Rp. 295.000</strong>
				</div>
				<div class="row im-centered"> 
				</div>
				<div class="row im-centered button-price"> 
					<a href="{{url('buy-more/17')}}"><input type="button" value="Buy now" class="btn btn-success"></a>
				</div>
			</div>
			<div class="col-md-3 col-sm-3 border-price">
				<div class="row im-centered"> 
					<p class="header">90 </p>
				</div>
				<div class="row im-centered"> 
					<p class="header-description">Days</p>
				</div>
				<div class="row im-centered"> 
					<strong>Rp. 395.000</strong>
				</div>
				<div class="row im-centered"> 
				</div>
				<div class="row im-centered button-price"> 
					<a href="{{url('buy-more/18')}}"><input type="button" value="Buy now" class="btn btn-success"></a>
				</div>
			</div>
		</div>
		<div id="extra-time">
			<div class="col-md-3 col-sm-3 border-price">
				<div class="row im-centered"> 
					<p class="header">180 </p>
				</div>
				<div class="row im-centered"> 
					<p class="header-description">Days</p>
				</div>
				<div class="row im-centered"> 
					<strong>Rp. 695.000</strong>
				</div>
				<div class="row im-centered"> 
				</div>
				<div class="row im-centered button-price"> 
					<a href="{{url('buy-more/19')}}"><input type="button" value="Buy now" class="btn btn-success"></a>
				</div>
			</div>
			<div class="col-md-3 col-sm-3 border-price">
				<div class="row im-centered"> 
					<p class="header">270 </p>
				</div>
				<div class="row im-centered"> 
					<p class="header-description">Days</p>
				</div>
				<div class="row im-centered"> 
					<strong>Rp. 995.000</strong>
				</div>
				<div class="row im-centered"> 
				</div>
				<div class="row im-centered button-price"> 
					<a href="{{url('buy-more/25')}}"><input type="button" value="Buy now" class="btn btn-success"></a>
				</div>
			</div>
			<div class="col-md-3 col-sm-3 border-price">
				<div class="row im-centered"> 
					<p class="header">360 </p>
				</div>
				<div class="row im-centered"> 
					<p class="header-description">Days</p>
				</div>
				<div class="row im-centered"> 
					<strong>Rp. 1.258.000</strong>
				</div>
				<div class="row im-centered"> 
				</div>
				<div class="row im-centered button-price"> 
					<a href="{{url('buy-more/20')}}"><input type="button" value="Buy now" class="btn btn-success"></a>
				</div>
			</div>
		</div>
	</div>
	<?php } else { ?>
	<div class="row">
		<a target="_blank" href="{{$user->link_affiliate}}"><img src="{{url('images/button-buy-affiliate.png')}}" class="img-responsive"> </a>
	</div>
	<?php } ?>
	<div class="row">
		<div class="col-md-12 col-sm-12">
		<?php echo $ads_content; ?>
		</div>
	</div>
	
</div>
      </div>
    </div>
  </div>  
</div>                        


<div class="row">
  <div class="col-md-12 col-sm-12">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Profile
						<span class="glyphicon glyphicon-question-sign hint-button tooltipPlugin" title="<div class='panel-heading'>Profile</div><div class='panel-content'>Jumlah Followers & Following ini hanya merupakan INFO saja ( bukan Real time ) <br> & hanya di update beberapa kali dalam sehari untuk memperingan kerja server</div>">
						</span>
				
				</h3>
      </div>
      <div class="panel-body">

				<div class="col-md-4">
					<label>Followers Saat Join</label>
					<?php echo number_format(intval (SettingMeta::getMeta($settings->id,"followers_join")),0,'','.'); ?>
				</div>				

				<div class="col-md-4">
					<label>Following Saat Join</label>
					<?php echo number_format(intval (SettingMeta::getMeta($settings->id,"following_join")),0,'','.'); ?>
				</div>				

				<div class="col-md-4">
				<label><br></label>
				<label><br></label>
				</div>				

				<?php 
				$followers = intval (SettingMeta::getMeta($settings->id,"followers"));
				$following = intval (SettingMeta::getMeta($settings->id,"following"));
				
				?>
				<div class="col-md-4">
					<label>Followers Hari ini</label>
					{{number_format($followers,0,'','.')}}
				</div>				

				<div class="col-md-4">
					<label>Following Hari ini</label>
					{{number_format($following,0,'','.')}}
				</div>				

      </div>
    </div>
  </div>  
</div>                    

<!--<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="panel panel-info ">
      <div class="panel-heading">
      </div>
      <div class="panel-body">
        <div class="col-md-1 col-sm-12 col-xs-12">
        </div>
        <div class="col-md-4 col-sm-12 col-xs-12">
					<button class="btn col-md-10 col-sm-12 col-xs-12 <?php if ($settings->status_auto) echo "btn-primary"; ?>" id="button-fullauto" style="border-radius:15px;font-size:16px;color:#fff;outline:none;">
					<strong>FAST<br>Full Auto Settings</strong>
					</button>
        </div>
        <div class="col-md-2 col-sm-12 col-xs-12">
				<br>
        </div>
        <div class="col-md-4 col-sm-12 col-xs-12">
					<button class="btn col-md-10 col-sm-12 col-xs-12 <?php if (!$settings->status_auto) echo "btn-primary"; ?>" id="button-advanced" style="border-radius:15px;font-size:16px;color:#fff;outline:none;">
					<strong>ADVANCED<br>Manual Settings</strong>
					</button>
        </div>
        <div class="col-md-1 col-sm-1">
        </div>
				<input type="hidden" value="{{$settings->status_auto}}" name="data[status_auto]" id="status_auto">
      </div>
    </div>
  </div>  
</div>                        
-->

<ul class="nav nav-tabs">
	<li class="active button-mode" id="button-mode-setting"><a href="#">Settings</a></li>
	<li class="button-mode" id="button-mode-comment"><a href="#">Comments</a></li>
	<li class="button-mode" id="button-mode-like"><a href="#">Likes</a></li>
	<li class="button-mode" id="button-mode-mention"><a href="#">Mention</a></li>
	<li class="button-mode" id="button-mode-tagged"><a href="#">Tagged</a></li>
	<li class="button-mode" id="button-mode-follow"><a href="#">Follow</a></li>
</ul>



<div id="div-mode-setting" class="div-mode">
<div class="row">
  <div class="col-md-6 col-sm-6">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Global Settings</h3>
      </div>
      <div class="panel-body">

				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<label>Choose Settings</label> 
						<span class="glyphicon glyphicon-question-sign hint-button tooltipPlugin" title="<div class='panel-heading'>Choose Settings</div><div class='panel-content'>Pilih salah satu : FULL AUTO atau Manual settings.<br> FULL AUTO = Fast Settings, Pilih kategori Target anda & Start,<br> FULL AUTO hanya untuk Follow, Like & Auto Like My Posts ( tidak termasuk Comment ). <br>Manual = Setting manual customized semua fitur Celebgramme. <br> <i>*PS: Settings yang AKTIF adalah yang TERAKHIR dipilih</i></div>">
						</span>
						<div class="btn-group col-xs-12 col-md-12 col-sm-12 col-xs-12" role="group" aria-label="..." style="margin-left:-15px;">
							<button type="button" class="btn <?php if ($settings->status_auto) echo 'gold-fullauto-setting' ?>" id="button-fullauto" style="outline:none;color:#fff;">Full Auto</button>
							<button type="button" class="btn <?php if (!$settings->status_auto) echo 'btn-info' ?>" id="button-advanced" style="outline:none;color:#fff;">Manual</button>
							<input type="hidden" value="{{$settings->status_auto}}" name="data[status_auto]" id="status_auto">
						</div>				
					</div>

					
					<div class="col-md-12 col-sm-12 col-xs-12">
						<br>
					</div>
					
					<div class="col-md-5 col-sm-5 col-xs-12">
						<label>Activity Speed</label> 
						<span class="glyphicon glyphicon-question-sign tooltipPlugin" title="
						<div class='panel-heading'>Activity speed</div><div class='panel-content'>Jika Akun anda BARU / Tdk aktif, START dgn SLOW/NORMAL speed utk 5 hari <br>• <strong>Slow</strong> = Melakukan 200-250 Likes, 50 comments, 100-150 follow/unfollow /hari <br>• <strong>Normal</strong> = Melakukan 250-300 likes, 50 comments, 150-200 follow/unfollows /hari. <br>• <strong>Fast</strong> = Melakukan 300-350 likes, 50 comments, 300-350 follow/unfollows /hari. <br>
						• <strong>Turbo</strong> = Melakukan 600-750 likes, 50 comments, 600-750 follow/unfollows /hari. 
						</div>">
						</span>
						<select class="form-control" name="data[activity_speed]" title="" id="activity-speed">
							<option value="normal" <?php if ($settings->activity_speed=='normal') echo "selected" ?>>Normal</option>
							<option value="slow" <?php if ($settings->activity_speed=='slow') echo "selected" ?>>Slow</option>
							<option value="fast" <?php if ($settings->activity_speed=='fast') echo "selected" ?>>Fast</option>
							<option value="turbo" <?php if ($settings->activity_speed=='turbo') echo "selected" ?>>Turbo</option>
						</select>
					</div>
					

					
					
				</div>
      </div>
    </div>
  </div>  

	<div class="col-md-6 col-sm-6">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Blacklist & Whitelist Settings</h3>
      </div>
      <div class="panel-body">
				
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<label>Blacklist</label> 
						<span class="glyphicon glyphicon-question-sign tooltipPlugin" title="<div class='panel-heading'>Blacklist </div><div class='panel-content'>List Username yang TIDAK akan di FLC (Follow, Like & Comment)<br>
						Masukkan usernames SAJA disini (tanpa @), contoh: darthvader, hitler, kimjongil, dsbnya<br>
						<i>*PS: berguna sekali untuk TIDAK follow, like, comment 'mantan' & 'kompetitor' anda</i><br>
	</div>">
						</span>
						<div class="btn-group col-md-12 col-sm-12 col-xs-12" role="group" aria-label="..." style="margin-left:-15px;">
							<button type="button" class="btn <?php if ($settings->status_blacklist) {echo'black-blacklist';} ?>" id="BlacklistOnButton" style="color:#fff;"><strong>ON</strong></button>
							<button type="button" class="btn <?php if (!$settings->status_blacklist) {echo'black-blacklist';} ?>" id="BlacklistOffButton" style="color:#fff;">OFF</button>
						</div>
						<input type="hidden" value="{{$settings->status_blacklist}}" name="data[status_blacklist]" id="status_blacklist">
					</div>
					
					<div class="col-md-12 col-sm-12 col-xs-12">
						<br>
					</div>
					
					<div class="col-md-12 col-sm-12 col-xs-12">
						<label>Whitelist</label> 
						<span class="glyphicon glyphicon-question-sign tooltipPlugin" title='<div class="panel-heading">Usernames whitelist</div><div class="panel-content">• Saat anda UNFOLLOW. <strong>Usernames di "Whitelist" ini akan diabaikan / tidak akan di "UNFOLLOW"</strong><br>
							• <strong>Usulan penggunaan : </strong>teman, pasangan, rekan sekerja & siapapun yang anda mau KEEP FOLLOW</div>'></span>
						<div class="btn-group col-md-12 col-sm-12 col-xs-12" role="group" aria-label="..." style="margin-left:-15px;">
							<button type="button" class="btn <?php if ($settings->status_whitelist) {echo'black-blacklist';} ?>" id="WhitelistOnButton" style="color:#fff;"><strong>ON</strong></button>
							<button type="button" class="btn <?php if (!$settings->status_whitelist) {echo'black-blacklist';} ?>" id="WhitelistOffButton" style="color:#fff;">OFF</button>
						</div>
						<input type="hidden" value="{{$settings->status_whitelist}}" name="data[status_whitelist]" id="status_whitelist">
					</div>
				</div>


					
					
		  </div>
    </div>
  </div>
</div>  
<?php 
	$is_auto_get_likes = 0;
	$target_categories = "";
	$setting_helper = SettingHelper::where("setting_id","=",$settings->id)->first();
	if (!is_null($setting_helper)){
		$is_auto_get_likes = $setting_helper->is_auto_get_likes;
		$target_categories = $setting_helper->target;
	}
?>

<div class="row" id="target-categories" <?php if (!$settings->status_auto) echo "style='display:none;'"; ?>>
  <div class="col-md-12 col-sm-12">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Full Auto: Target Categories
						<span class="glyphicon glyphicon-question-sign hint-button tooltipPlugin" title="<div class='panel-heading'>Target Categories</div><div class='panel-content'>Silahkan Pilih Target Kategori (max 10)<br>yang akan anda Follow & Like<br>Fitur Full Auto Settings akan OTOMATIS<br>berjalan sesuai dengan target kategori yang anda pilih.</div>">
						</span>
				</h3>
      </div>
      <div class="panel-body">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<textarea class="selectize-target" id="textarea-target-categories" name="data[target_categories]">{{$target_categories}}</textarea>
					</div>
				</div>
      </div>
    </div>
  </div>  
</div>       

<div class="row advanced-manual-setting <?php if ($settings->status_auto) echo "hide"; ?>" >
  <div class="col-md-12 col-sm-12">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Auto Like My Post</h3>
      </div>
      <div class="panel-body">
        <div class="row">
					<div class="col-md-12 col-xs-12 col-sm-12">
						<label>Status
						</label>
						<span class="glyphicon glyphicon-question-sign hint-button tooltipPlugin" title="<div class='panel-heading'>Auto Like My Post ALMP</div><div class='panel-content'>Fitur Keren ini akan membuat POST anda terlihat POPULER<br>SETIAP POST Terbaru anda akan MENDAPATKAN LIKES secara OTOMATIS<br> Max 24 jam = 30 Likes / 3 post / Hari TERAKHIR<br> <i>*PS: artinya HANYA 3 Post Terakhir per HARI <br>yang akan mendapatkan 30 Likes / masing-masing post</i>  </div>">
						</span>
					</div>
					<div class="col-md-3 col-xs-12 col-sm-12">
						<div class="btn-group col-md-12 col-sm-12" role="group" aria-label="..." style="margin-left:-15px;">
							<button type="button" class="btn <?php if ($is_auto_get_likes) echo 'btn-primary' ?>" id="AutoLikesOnButton" style="color:#fff;">ON</button>
							<button type="button" class="btn <?php if (!$is_auto_get_likes) echo 'btn-danger' ?>" id="AutoLikesOffButton" style="color:#fff;">OFF</button>
							<input type="hidden" value="{{$is_auto_get_likes}}" name="data[is_auto_get_likes]" id="is_auto_get_likes">
						</div>
					</div>


        </div>
        
      </div>
    </div>
  </div>  
</div>                        

<div class="row" id="div-blacklist" <?php if (!$settings->status_blacklist) { echo 'style=display:none'; } ?>>
  <div class="col-md-12 col-sm-12">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Usernames blacklist
				</h3>
      </div>
      <div class="panel-body">
				<div class="row">
          <div class="col-md-11 col-sm-10 col-xs-10">
            <label>Blacklist</label> 
						<span class="glyphicon glyphicon-question-sign tooltipPlugin" title="<div class='panel-heading'>Blacklist </div><div class='panel-content'>List Username yang TIDAK akan di FLC (Follow, Like & Comment)<br>
						Masukkan usernames SAJA disini (tanpa @), contoh: darthvader, hitler, kimjongil, dsbnya<br>
						<i>*PS: berguna sekali untuk TIDAK follow, like, comment 'mantan' & 'kompetitor' anda</i><br>
	</div>">
						</span>
          </div>
					<div class="col-md-1 col-sm-1 col-xs-1">
						<p align="right" data-toggle="modal" data-target="#myModal" style="cursor:pointer;" class="button-copy" data-text="textarea-unfollow-blacklist">copy</p>
          </div>
					<div class="col-md-12 col-sm-12 col-xs-12">
						<textarea class="selectize-default" id="textarea-unfollow-blacklist" name="data[usernames_blacklist]">{{$settings->usernames_blacklist}}</textarea>
					</div>
				</div>
      </div>
    </div>
  </div>  
</div>       

<div class="row" id="div-unfollow-whitelist" <?php if (!$settings->status_whitelist) { echo 'style=display:none'; } ?> >
  <div class="col-md-12 col-sm-12">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Usernames whitelist
				</h3>
      </div>
      <div class="panel-body">

        <div class="row">
          <div class="col-md-11 col-sm-10 col-xs-10">
            <label>Whitelist</label> 
						<span class="glyphicon glyphicon-question-sign tooltipPlugin" title='<div class="panel-heading">Usernames whitelist</div><div class="panel-content">• Saat anda UNFOLLOW. <strong>Usernames di "Whitelist" ini akan diabaikan / tidak akan di "UNFOLLOW"</strong><br>
							• <strong>Usulan penggunaan : </strong>teman, pasangan, rekan sekerja & siapapun yang anda mau KEEP FOLLOW</div>'></span>
          </div>
					<div class="col-md-1 col-sm-1 col-xs-1">
						<p align="right" data-toggle="modal" data-target="#myModal" style="cursor:pointer;" class="button-copy" data-text="textarea-unfollow-whitelist">copy</p>
          </div>
					<div class="col-md-12 col-sm-12 col-xs-12">
						<textarea class="selectize-default" id="textarea-unfollow-whitelist" name="data[usernames_whitelist]">{{$settings->usernames_whitelist}}</textarea>
					</div>
					
        </div>

      </div>
    </div>
  </div>  
</div>                    

<div class="row advanced-manual-setting <?php if ($settings->status_auto) echo 'hide'; ?>" >
  <div class="col-md-12 col-sm-12">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Follow</h3>
      </div>
      <div class="panel-body">

        <div class="row">
					<div class="col-md-12 col-xs-12 col-sm-12">
						<label>Status</label>
						<span class="glyphicon glyphicon-question-sign hint-button tooltipPlugin" title="<div class='panel-heading'>Follow Status</div><div class='panel-content'><strong>Status ON </strong>akan melakukan 'Follow/Unfollow' <br>
						                  <strong>Status OFF </strong>Tidak akan melakukan 'Follow/Unfollow' <br>
															<i>*PS: Status OFF berguna apabila anda hanya mau melakukan Aktifitas lain (Like & Comment) saja</i></div>">
						</span>
						<div class="btn-group col-xs-12 col-md-12 col-sm-12" role="group" aria-label="..." style="margin-left:-15px;">
							<button type="button" class="btn <?php if ($settings->status_follow_unfollow=="on") echo 'btn-primary' ?>" id="statusFollowOnButton" style="color:#fff;">ON</button>
							<button type="button" class="btn <?php if ($settings->status_follow_unfollow=="off") echo 'btn-danger' ?>" id="statusFollowOffButton" style="color:#fff;">OFF</button>
							<input type="hidden" value="{{$settings->status_follow_unfollow}}" name="data[status_follow_unfollow]" id="status_follow_unfollow">
						</div>
					</div>
					<div class="col-md-12 col-xs-12 col-sm-12">
					<br>
					</div>


					<div class="col-md-12 col-xs-12 col-sm-12 status-follow" <?php if ($settings->status_follow_unfollow=="off") echo "style='display:none;'" ?>>
						<label>Activity</label>
						<span class="glyphicon glyphicon-question-sign hint-button tooltipPlugin" title="<div class='panel-heading'>Follow Activity</div><div class='panel-content'>PILIH salah satu <strong>Follow / Unfollow</strong>. Tidak bisa bersamaan</div>">
						</span>
						<div class="btn-group col-xs-12 col-md-12 col-sm-12" role="group" aria-label="..." style="margin-left:-15px;">
							<button type="button" class="btn <?php if ($settings->activity=="follow") echo 'btn-success' ?>" id="followButton" style="color:#fff;">Follow</button>
							<button type="button" class="btn <?php if ($settings->activity=="unfollow") echo 'btn-success' ?>" id="unfollowButton" style="color:#fff;">Unfollow</button>
							<input type="hidden" value="{{$settings->activity}}" name="data[activity]" id="activity">

							<input type="hidden" value="{{$settings->status_follow}}" name="data[status_follow]" id="status_follow">
							<input type="hidden" value="{{$settings->status_unfollow}}" name="data[status_unfollow]" id="status_unfollow">
							
						</div>				
					</div>				
        </div>
        
				<br>
					
        <div class="row status-follow status-unfollow" <?php if ($settings->status_follow_unfollow=="off") echo "style='display:none;'" ?>>
          <div class="col-md-4 col-sm-5 col-xs-5">
            <label>Follow source</label> 
						<span class="glyphicon glyphicon-question-sign tooltipPlugin" title="<div class='panel-heading'>Follow Source</div><div class='panel-content'>
						Pilih 1 dari 3 Follow Sources ini (Hanya yang dipilih yang dijalankan) : <br>
						<strong>Jika Follow Source</strong> : 'HASHTAGS' akan Follow sesuai Hashtags tsb.<br>
						<strong>Jika Follow Source</strong> : 'USERNAMES' bisa pilih mau Follow siapa. 'Followersnya/Following' nya username tsb.</div>"></span>
            <select class="form-control" name="data[follow_source]" id="select-follow-source">
							<option value="hashtags" <?php if ($settings->follow_source=='hashtags') echo "selected" ?>>Hashtags</option>
							<option value="followers of username" <?php if ($settings->follow_source=='followers of username') echo "selected" ?>>Followers of username</option>
<!--							<option value="following of username" <?php if ($settings->follow_source=='following of username') echo "selected" ?>>Following of username</option>-->
            </select>
          </div>
					<div class="col-md-3 col-sm-7 col-xs-7">
						<label for="">Dont follow private users</label> 
						<span class="glyphicon glyphicon-question-sign tooltipPlugin" title="<div class='panel-heading'>Dont Follow Private users</div><div class='panel-content'>
						Jika Dont Follow Private Users dicentang,<br> Maka proses follow tidak akan memfollow account-account IG yang private
						</div>"></span>
						<div class="btn-group col-xs-12 col-md-12 col-sm-12" role="group" aria-label="..." style="margin-left:-15px;">
							<button type="button" class="btn <?php if ($settings->dont_follow_pu) echo 'btn-primary' ?>" id="DontFollowPUOnButton" style="color:#fff;">ON</button>
							<button type="button" class="btn <?php if (!$settings->dont_follow_pu) echo 'btn-danger' ?>" id="DontFollowPUOffButton" style="color:#fff;">OFF</button>
							<input type="hidden" value="{{$settings->dont_follow_pu}}" name="data[dont_follow_pu]" id="dont_follow_pu">
						</div>
						
          </div>
					<div class="col-md-3 col-sm-7 col-xs-7">
						<label for="">Dont follow same users</label> 
						<span class="glyphicon glyphicon-question-sign tooltipPlugin" title="<div class='panel-heading'>Dont Follow Same users</div><div class='panel-content'>
						Jika Dont Follow Same Users dicentang,<br> Maka proses follow tidak akan memfollow account-account IG yang pernah difollow oleh celebgramme
						</div>"></span>
						<div class="btn-group col-xs-12 col-md-12 col-sm-12" role="group" aria-label="..." style="margin-left:-15px;">
							<button type="button" class="btn <?php if ($settings->dont_follow_su) echo 'btn-primary' ?>" id="DontFollowSUOnButton" style="color:#fff;">ON</button>
							<button type="button" class="btn <?php if (!$settings->dont_follow_su) echo 'btn-danger' ?>" id="DontFollowSUOffButton" style="color:#fff;">OFF</button>
							<input type="hidden" value="{{$settings->dont_follow_su}}" name="data[dont_follow_su]" id="dont_follow_su">
						</div>
						
          </div>
					
        </div>
      </div>
    </div>
  </div>  
</div>                        

<div class="row advanced-manual-setting <?php if ($settings->status_auto) echo 'hide'; ?>" id="div-usernames" <?php if ( ($settings->follow_source=='hashtags') || ($settings->status_follow_unfollow=="off") ) echo "style='display:none;'" ?>>
  <div class="col-md-12 col-sm-12">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Media source : Usernames</h3>
      </div>
      <div class="panel-body">

        <div class="row">
          <div class="col-md-11 col-sm-10 col-xs-10">
            <label>Usernames</label> 
						<span class="glyphicon glyphicon-question-sign tooltipPlugin" title='<div class="panel-heading">Media source : Usernames</div><div class="panel-content">• <strong>Add MIN 10 username</strong> jika menggunakan "Usernames" di Media Source. <br>• Anda dapat menambahkan <strong>MAX 50 usernames.</strong></div>'></span>
          </div>
					<div class="col-md-1 col-sm-1 col-xs-1">
						<p align="right" data-toggle="modal" data-target="#myModal" style="cursor:pointer;" class="button-copy" data-text="textarea-username">copy</p>
          </div>
					<div class="col-md-12 col-sm-12 col-xs-12">						
						<textarea class="selectize-default" id="textarea-username" name="data[username]">{{$settings->username}}</textarea>
					</div>
        </div>

      </div>
    </div>
  </div>  
</div>                    

<div class="row advanced-manual-setting <?php if ($settings->status_auto) echo 'hide'; ?>" id="div-hashtags" <?php if ( ( ($settings->status_follow_unfollow=="off") && ($settings->status_like=="off") && ($settings->status_comment=="off")) || ( (($settings->follow_source=='followers of username') || ($settings->follow_source=='following of username')) && ($settings->status_follow_unfollow=="on")&& ($settings->status_like=="off")&& ($settings->status_comment=="off") )  ) echo "style='display:none;'" ?>>
  <div class="col-md-12 col-sm-12">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Media source : Hashtags</h3>
      </div>
      <div class="panel-body">

        <div class="row">
          <div class="col-md-11 col-sm-10 col-xs-10">
            <label>Hashtags</label> 
						<span class="glyphicon glyphicon-question-sign tooltipPlugin" title="<div class='panel-heading'>Media source : Hashtags</div><div class='panel-content'>• ADD <strong>MIN 10 Hashtags</strong> <br>
								• TIDAK PERLU ADD <strong>simbol # (tanda pagar) </strong><br>
								• Anda dapat menambahkan <strong>MAX 50 Hashtags</strong>
</div>"></span>
          </div>
					<div class="col-md-1 col-sm-1 col-xs-1">
						<p align="right" data-toggle="modal" data-target="#myModal" style="cursor:pointer;" class="button-copy" data-text="textarea-hashtags">copy</p>
          </div>
					<div class="col-md-12 col-sm-12 col-xs-12">
						<textarea class="selectize-default" id="textarea-hashtags" name="data[hashtags]">{{$settings->hashtags}}</textarea>
					</div>
        </div>

      </div>
    </div>
  </div>  
</div>                    

<div class="row advanced-manual-setting <?php if ($settings->status_auto) echo 'hide'; ?>">
  <div class="col-md-12 col-sm-12">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Like & Comment</h3>
      </div>
      <div class="panel-body">

        <div class="row">
          <div class="col-md-12">
            <label>"Like & Comments" hanya menggunakan media source : Hashtags</label> 
						<span class="glyphicon glyphicon-question-sign tooltipPlugin" title='<div class="panel-heading">Like & Comment</div><div class="panel-content">Wajib mengisi <strong>min 10 "Hashtags" </strong>jika memakai Fitur "Like & Comments"</div>'></span>
						
          </div>
        </div>
				
        <div class="row">
					<div class="col-md-12">
						<label>Like</label> 
						<div class="btn-group col-md-12 col-sm-12" role="group" aria-label="..." style="margin-left:-15px;">
							<button type="button" class="btn <?php if ($settings->status_like=="on") echo 'btn-primary' ?>" id="statusLikeOnButton" style="color:#fff;z-index:99;">ON</button>
							<button type="button" class="btn <?php if ($settings->status_like=="off") echo 'btn-danger' ?>" id="statusLikeOffButton" style="color:#fff;z-index:99;">OFF</button>
							<input type="hidden" value="{{$settings->status_like}}" name="data[status_like]" id="status_like">
						</div>				
					</div>				
					
					<div class="col-md-12 col-xs-12 col-sm-12">
					<br>
					</div>
					
					<div class="col-md-12">
						<label>Comment</label> 
						<div class="btn-group col-md-12 col-sm-12" role="group" aria-label="..." style="margin-left:-15px;">
							<button type="button" class="btn <?php if ($settings->status_comment=="on") echo 'btn-primary' ?>" id="statusCommentOnButton" data-toggle="modal" data-target="#myModalCommentNotification" style="color:#fff;">ON</button>
							<button type="button" class="btn <?php if ($settings->status_comment=="off") echo 'btn-danger' ?>" id="statusCommentOffButton" style="color:#fff;">OFF</button>
							<input type="hidden" value="{{$settings->status_comment}}" name="data[status_comment]" id="status_comment">
						</div>				
					</div>
        </div>
				

      </div>
    </div>
  </div>  
</div>   


<div class="row advanced-manual-setting <?php if ($settings->status_auto) echo 'hide'; ?>" id="div-comment" <?php if ($settings->status_comment=="off")   { echo "style='display:none;'"; } ?>>
  <div class="col-md-12 col-sm-12">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Comment</h3>
      </div>
      <div class="panel-body">


          <div class="row">
						<div class="col-md-5 col-sm-12 col-xm-12"">
							<label>Comments</label> 
							<span class="glyphicon glyphicon-question-sign tooltipPlugin" title="<div class='panel-heading'>Comments</div><div class='panel-content'>• <strong>Tambahkan : </strong><@owner> , untuk men-tag owner dari post tersebut<br>
									• <strong>Tambahkan : </strong><@followers> , untuk men-tag followers anda<br>
									• <strong>Tambahkan : </strong><@following> , untuk men-tag following anda<br>
									• <strong>Komentar akan dipilih secara acak </strong>dari daftar ini. <br>
									• <strong>Celebgramme hanya memberikan 1x komentar </strong>pada setiap posting <br>
									• <strong>Komentar jangan menggunakan Hashtags </strong><br>
									• <strong>Komentar jangan menggunakan URL </strong> <br>
									• <strong>Komentar tidak boleh </strong>terdiri dari huruf kapital semua. <br>
									• <strong>Komentar HARUS berbeda </strong>satu sama lain. <br>
</div>"></span>
						</div>
						<div class="col-md-6 col-sm-12 col-xm-12"">
							<label>Copy contoh spin comment (click)</label>
							<span class="glyphicon glyphicon-menu-down tooltipPlugin" title='<div class="panel-content">								{asli|serius}, {nice|kerennn|cool|wow|keren|cooooolll|niceeeee} {sekaleee|sekali|banget|beneran|bener} {photo|foto|shot|poto|pic}{kamu|ini} <@owner>
								<br> <br>
								{nice|kerennn|cool|wow|keren|cooooolll|niceeeee} {sekaleee|sekali|banget|beneran|bener} {photo|foto|shot|poto|pic}{kamu|ini} <@owner> <br> <br>
								{wow|amazing|incredible|whoa|seriously} {your|the|this} {photo|picture|photograph|image|foto} {is awesome|rocks !|very nice} <@owner> 
</div>'>
							</span>
						</div>
          </div>
          <div class="row">
						<div class="col-md-5 col-sm-12 col-xs-12"">
							<label>Penjelasan fitur spin comment</label>
							<span class="glyphicon glyphicon-question-sign tooltipPlugin" title='<div class="panel-heading">Penjelasan fitur spin comment</div>								<div class="panel-content"><strong>Gunakan Feature "Spin Comment" </strong>contoh : <br>
																		{wihh|wow|beneran,|asli}{foto|image|photo}{kamu|anda|nya}{keren|cool|mantappp|sipp|amazing|beautiful} <br>
																			*contoh diatas akan menghasilkan <strong>4x3x3x6 = 216 kombinasi comments </strong> sekaligus <br>
																			*<strong>Spin Comment default akan digunakan</strong>, utk menghindari Comment yang sama berulang-ulang
	</div>'>
							</span>
						</div>
						<div class="col-md-6 col-sm-10 col-xs-10" ">
							<label>Petunjuk tanda baca spin comment</label>
							<?php $tempurl = url("images/petunjuk-spin.jpg"); ?>
							<span class="glyphicon glyphicon-search tooltipPlugin" title='<div class="panel-heading">Petunjuk tanda baca spin comment</div><div class="panel-content"><img src="{{$tempurl}}" width="800" height="250">
	</div>'>
							</span>
						</div>
						<div class="col-md-1 col-sm-1 col-xs-1">
							<p align="right" data-toggle="modal" data-target="#myModal" style="cursor:pointer;" class="button-copy" data-text="textarea-comments">copy</p>
						</div>
          </div>
          <div class="row">
						<div class="col-md-12">
							<input type="text" id="textarea-comments" class="selectize-default" name="data[comments]" value="{{$settings->comments}}">
						</div>
          </div>


      </div>
    </div>
  </div>  
</div>                        

<div class="row">
  <div class="col-md-12">
    <input type="button" value="Save" class="btn btn-info" id="button-save2">    
		
		<button data-id="{{$settings->id}}" class="btn <?php if ($settings->status=='stopped') { echo 'btn-success'; } else {echo 'btn-danger';} ?> button-action btn-{{$settings->id}}" value="<?php if ($settings->status=='stopped') { echo 'Start'; } else {echo 'Stop';}?>">
			<?php if ($settings->status=='stopped') { echo "<span class='glyphicon glyphicon-play'></span> Start"; } else {echo "<span class='glyphicon glyphicon-stop'></span> Stop";}?> 
		</button>
		
  </div>                    
</div>                    
<input type="hidden" name="data[id]" value="{{$settings->setting_id}}">
</form>
</div>                    


<div id="div-mode-comment" class="div-mode">
Inbox :
<br>
<?php 
	echo "total inbox: ".count($inboxResponse->inbox->threads)."<br>";
	echo "total pending: ".count($inboxResponse->pending_requests_users)."<br>";
?>
<br>
Request :
<?php
	if (count($pendingInboxResponse->inbox->threads) > 0 ) {
		foreach ($pendingInboxResponse->inbox->threads as $data_arr) {
			echo $data_arr->users[0]->username." - ".$data_arr->profile_pic_url."<br>";
		}
	}
?>
<br>
Inbox Real : <br>
<?php
	// if (count($inboxResponse->inbox->threads) > 0 ) {
		// foreach ($inboxResponse->inbox->threads as $data_arr) {
			// echo $data_arr->users[0]->username." - ".$data_arr->profile_pic_url."<br>";
			// echo $data_arr->items[0]->text."<br>";
			// echo date("Y-m-d H:i:s", (int)$data_arr->items[0]->timestamp)."<br>";
		// }
	// }
?>

</div>


<div id="div-mode-like" class="div-mode hide">
c
</div>


<div id="div-mode-mention" class="div-mode hide">
d
</div>


<div id="div-mode-tagged" class="div-mode hide">
e
</div>

<div id="div-mode-follow" class="div-mode hide">
f
</div>

<div class="row">
  <div class="col-md-12">
    <p align="center"> <br><br><br><br><br><br><br><br> @Copyright <a href="http://celebgramme.com/celebgramme">Celebgramme</a> versi 2 2016</p>    
  </div>                    
</div>                    


@endsection
