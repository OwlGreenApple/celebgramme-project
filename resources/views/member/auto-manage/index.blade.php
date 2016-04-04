@extends('member.index')

@section('content-auto-manage')
<script type="text/javascript">

		function getTimeRemaining(endtime){
			var t = endtime;
			var seconds = Math.floor( (t) % 60 );
			var minutes = Math.floor( (t/60) % 60 );
			var hours = Math.floor( (t/(60*60)) % 24 );
			var days = Math.floor( t/(60*60*24) );
			return {
				'total': t,
				'days': days,
				'hours': hours,
				'minutes': minutes,
				'seconds': seconds
			};
		}

		function initializeClock(id, endtime){
			var clock = document.getElementById(id);
			var daysSpan = clock.querySelector('.days');
			var hoursSpan = clock.querySelector('.hours');
			var minutesSpan = clock.querySelector('.minutes');
			var secondsSpan = clock.querySelector('.seconds');

			function updateClock(){
				var t = getTimeRemaining(endtime);

				daysSpan.innerHTML = t.days;
				hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
				minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
				secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);

				// if(t.total<=0){
					// clearInterval(timeinterval);
				// }
			}

			updateClock();
			//var timeinterval = setInterval(updateClock,1000);
		}


    function loadaccount(){
        $.ajax({
            type: 'GET',
            url: "<?php echo url('load-account'); ?>",
            data: {
            },
            dataType: 'text',
            beforeSend: function()
            {
              $("#div-loading").show();
            },
            success: function(result) {
                // $('#result').html(data);
                $("#div-loading").hide();
                $("#account-all").html(result);

							setTimeout(function(){
									var max = -1;
									$(".border-styling").each(function() {
											var h = $(this).height(); 
											max = h > max ? h : max;
									});
									$(".border-styling").each(function() {
											$(this).height(max); 
									});
									
									$( "body" ).on( "click", ".delete-button", function() {
										$("#id-setting").val($(this).attr("data-id"));
									});
							}, 1000);
								
            }
        })
        return false;
    }


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
                  $("#alert").removeClass('btn-danger');
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
									loadaccount();
                }
                else if(data.type=='error')
                {
                  $("#alert").addClass('btn-danger');
                  $("#alert").removeClass('alert-success');
                }
            }
        })
        return false;
    }


		$(document).click(function(e) {
				var target = e.target;

				if (!$(target).is('.glyphicon-question-sign') && !$(target).parents().is('.glyphicon-question-sign')) {
						$('.glyphicon-question-sign').find(".hint").hide();
				}
				if (!$(target).is('.glyphicon-menu-down') && !$(target).parents().is('.glyphicon-menu-down')) {
						$('.glyphicon-menu-down').find(".hint").hide();
				}
		});
		
  $(document).ready(function() {

		/*Terms and condition*/
		<?php if ($user->agree_term_condition==0) { ?>
		// $('#myModalTermsConditions').modal('show');
		<?php } ?>
		$('#button-ok-terms').click(function(e){
			if ( $('#checkbox-term').is(':checked') == false ) {
				alert("Anda belum setuju dengan terms and condition berikut");
			} else {

        $.ajax({
            type: 'get',
            url: "<?php echo url('agree-terms'); ?>",
            data: {},
            dataType: 'text',
            beforeSend: function()
            {
              $("#div-loading").show();
            },
            success: function(result) {
                // $('#result').html(data);
                $("#div-loading").hide();
                if(result=='success')
                {
									$('#myModalTermsConditions').modal('hide');
                }
            }
        });
			

			}
		});
		
		initializeClock('clockdiv', <?php echo $user->active_auto_manage ?>);

		/*Hint*/
		$('.tooltipPlugin').tooltipster({
				theme: 'tooltipster-noir',
				contentAsHTML: true,
				interactive:true,
		});
		
    $("#alert").hide();
    loadaccount();
		
    $( "body" ).on( "click", "#delete-setting", function() {
			//alert($(this).attr("data-id"));
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: "<?php echo url('delete-setting'); ?>",
            data: {
							id : $("#id-setting").val(),
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
                  $("#alert").addClass('btn-danger');
                  $("#alert").removeClass('alert-success');
                }
                else if(data.type=='error')
                {
                  $("#alert").addClass('alert-success');
                  $("#alert").removeClass('btn-danger');
                }
                $("#username").val("");
                $("#password").val("");
                $("#confirm_password").val("");
                loadaccount();
            }
        });
    });
    $( "body" ).on( "click", ".edit-cred", function() {
      $("#setting_id").val($(this).attr("data-id"));
      $("#edit_username").val($(this).attr("data-username"));
    });
    $( "body" ).on( "click", ".button-action", function() {
      action = "";
      if ($(this).val()=="Start") { action = "start"; }
      if ($(this).val()=="Stop") { action = "stop"; }
      call_action(action,$(this).attr("data-id"));
    });
    $('#button-start-all').click(function(e){
      call_action('start','all');
    });
    $('#button-stop-all').click(function(e){
      call_action('stop','all');
    });
    $('#button-edit-password').click(function(e){
      if ($("#edit_password").val() != $("#edit_confirm_password").val()) {
        $("#alert").addClass('btn-danger');
        $("#alert").removeClass('alert-success');
        $("#alert").show();
        $("#alert").html("password anda tidak sesuai");
      } else {
        $.ajax({
            headers: {  
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: "<?php echo url('process-edit-password'); ?>",
            data: $("#form-edit-password").serialize(),
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
                  $("#alert").removeClass('btn-danger');
                }
                else if(data.type=='error')
                {
                  $("#alert").addClass('btn-danger');
                  $("#alert").removeClass('alert-success');
                }
                $("#username").val("");
                $("#password").val("");
                loadaccount();
            }
        });
      }
    });
    $('#button-process').click(function(e){
      if ($("#password").val() != $("#confirm_password").val()) {
        $("#alert").addClass('btn-danger');
        $("#alert").removeClass('alert-success');
        $("#alert").show();
        $("#alert").html("password anda tidak sesuai");
      } else {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: "<?php echo url('process-save-credential'); ?>",
            data: $("#form-credential").serialize(),
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
                  $("#alert").removeClass('btn-danger');

									$("#username").val("");
									$("#password").val("");
									// $("#confirm_password").val("");
                }
                else if(data.type=='error')
                {
                  $("#alert").addClass('btn-danger');
                  $("#alert").removeClass('alert-success');
                }
                loadaccount();
            }
        });
      }
    });

		$( "body" ).on( "click", ".glyphicon-menu-down", function(e) {
			$(this).find('.hint').slideToggle();
		});

		$( "body" ).on( "click", ".glyphicon-question-sign", function(e) {
			$(this).find('.hint').slideToggle();
		});


  });
</script>



<!-- Modal -->
  <div class="modal fade" id="myModalTermsConditions" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
          <h4 class="modal-title">Term & Conditions
					</h4>
        </div>
        <div class="modal-body">
				
				
<h3 class="">Informasi Umum</h3>
<ul class=""><li class=""><p style="margin-bottom: 0px !important;">Celebgramme.com sebagai penyedia jasa peningkatan reputasi individual / brand melalui media cetak, radio, tv dan sosial media berusaha menyediakan berbagai fitur dan layanan untuk menjamin keamanan dan kenyamanan para penggunanya.</p></li><li><p style="margin-bottom: 0px !important;">Jasa yang ditawarkan Celebgramme.com bersifat as-is ( apa adanya ) dan bergantung kepada kebijaksanaan &amp; peraturan masing-masing media ( Instagram, media cetak, media online lainnya ) .</p></li><li><p style="margin-bottom: 0px !important;">Celebgramme.com tidak bertanggung jawab apabila akun pengguna di ban/block oleh media, rusaknya reputasi pihak lain, dan/atau segala bentuk perselisihan yang dapat terjadi pada akun pengguna situs.</p></li><li><p style="margin-bottom: 0px !important;">Celebgramme.com memiliki kewenangan untuk mengambil tindakan yang dianggap perlu terhadap akun yang diduga dan/atau terindikasi melakukan penyalahgunaan dan pelanggaran peraturan di Celebgramme.com, mulai dari melakukan moderasi, menghentikan layanan, maupun menutup akun tersebut tanpa memberikan pemberitahuan atau informasi terlebih dahulu kepada pemilik akun yang bersangkutan.</p></li><li><p style="margin-bottom: 0px !important;">Kerugian yang diakibatkan keterlibatan pihak lain di luar customer/klien terdaftar Celebgramme.com dan Celebgramme.com, tidak menjadi tanggung jawab Celebgramme.com.</p></li><li><p style="margin-bottom: 0px !important;">Silakan cek Berita &amp; Pengumuman terbaru tentang peraturan di Celebgramme.com.</p></li><li><p style="margin-bottom: 0px !important;">Hati-hati terhadap penipuan yang mengatasnamakan Celebgramme.com.</p></li><li class=""><p style="margin-bottom: 35px !important;">Celebgramme.com tidak berasosiasi dengan website/app Instagram, Facebook, Soundcloud, Youtube &amp; Twitter dalam hal apapun juga tanpa terkecuali .</p></li></ul>

<h3 class="">Pembatasan Tanggung Jawab</h3>				
<ul class=""><li><p style="margin-bottom: 0px !important;">Celebgramme.com tidak bertanggung jawab atas segala resiko dan kerugian yang timbul dari dan dalam kaitannya dengan informasi yang dituliskan oleh pengguna Celebgramme.com.</p></li><li><p style="margin-bottom: 0px !important;">Celebgramme.com tidak bertanggungjawab atas segala pelanggaran hak cipta, merek, desain industri, desain tata letak sirkuit, hak paten atau hak-hak pribadi lain yang melekat atas suatu barang, berkenaan dengan segala informasi yang dibuat oleh Pelapak.</p></li><li class=""><p style="margin-bottom: 0px !important;">Celebgramme.com tidak bertanggungjawab atas segala resiko dan kerugian yang timbul berkenaan dengan penggunaan barang yang dibeli melalui Celebgramme.com, dalam hal terjadi pelanggaran ketentuan perundang-undangan.</p></li><li><p style="margin-bottom: 0px !important;">Celebgramme.com tidak bertanggungjawab atas segala resiko dan kerugian yang timbul berkenaan dengan peretasan terhadap akun pengguna yang dilakukan oleh pihak ketiga.</p></li><li class=""><p style="margin-bottom: 0px !important;">Celebgramme.com tidak bertanggungjawab atas segala resiko dan kerugian yang timbul akibat kesalahan atau perbedaan nominal yang seharusnya ditransfer ke Celebgramme.</p></li><li class="" style="margin-bottom: 35px !important;">Celebgramme.com tidak bertanggungjawab atas segala resiko dan kerugian yang timbul apabila transaksi telah dianggap selesai (dana telah masuk ke akun admin Celebgramme ataupun Pembeli).</li></ul>

<h3 class="">Sanksi</h3>
<p>Segala tindakan yang melanggar peraturan di Celebgramme.com akan dikenakan sanksi berupa:</p>
<ul class="">
<li><p style="">Akun dibekukan atau dinonaktifkan.</p></li>
<li><p style="">Pelaporan ke pihak terkait (Kepolisian, dll).</p></li>
</ul>

<h3 class="">Pengguna</h3>
<ul class=""><li><p style="margin-bottom: 0px !important;">Pengguna situs Celebgramme.com wajib mengisi data pribadi secara lengkap dan jujur di halaman akun (profil).</p></li><li><p style="margin-bottom: 0px !important;">Pengguna situs Celebgramme.com bertanggung jawab sendiri atas keamanan dari informasi akses akun termasuk penggunaan e-mail dan password.</p></li><li><p style="margin-bottom: 0px !important;">Pengguna situs Celebgramme.com wajib membayar terlebih dahulu sebelum menggunakan jasa / service Celebgramme.com</p></li><li><p style="margin-bottom: 0px !important;">Penggunaan fasilitas apapun yang disediakan oleh Celebgramme.com mengindikasikan bahwa pengguna telah memahami dan menyetujui segala aturan yang diberlakukan oleh Celebgramme.com.</p></li><li><p style="margin-bottom: 0px !important;">Selama menggunakan&nbsp;service/layanan Celebgramme.com, pengguna dilarang keras menyampaikan setiap jenis konten apapun ( baik di profile, comments, foto dan video ) yang mengandung / bersinggungan dengan unsur SARA, pornografi, diskriminasi, dan/atau menghina / merusak nama baik / menyudutkan pihak lain.</p></li><li><p style="margin-bottom: 0px !important;">Pengguna situs Celebgramme.com tidak diperbolehkan untuk menggunakan situs ini untuk melanggar peraturan yang ditetapkan oleh hukum di Indonesia maupun di negara lainnya.</p></li><li><p style="margin-bottom: 0px !important;">Pengguna situs Celebgramme.com bertanggung jawab atas segala resiko yang timbul di kemudian hari atas informasi yang diberikannya ke dalam situs ini, termasuk namun tidak terbatas pada hal-hal yang berkaitan dengan hak cipta, merek, desain industri, desain tata letak industri dan hak paten atas suatu produk.</p></li><li><p style="margin-bottom: 0px !important;">Pengguna situs Celebgramme.com diwajibkan menghargai hak-hak pengguna lainnya dengan tidak memberikan informasi pribadi ke pihak lain tanpa izin pihak yang bersangkutan.</p></li><li><p style="margin-bottom: 0px !important;">Pengguna situs Celebgramme.com tidak diperkenankan membuat link atau mengirimkan e-mail spam dengan merujuk ke bagian apapun dari situs ini, tanpa seijin admin.</p></li><li><p style="margin-bottom: 0px !important;">Celebgramme.com memiliki hak untuk memblokir / menutup penggunaan sistem terhadap pengguna situs yang dianggap melanggar peraturan dan ketetapan yang berlaku di dalam halaman terms &amp; conditions ini. Sisa pembayaran langganan service Celebgramme akan dianggap hangus dan tidak dapat dikembalikan dengan alasan apapun. Keputusan ini sifatnya prerogratif admin Celebgramme.com</p></li><li><p>Pengguna situs Celebgramme.com akan mendapatkan beragam informasi promo terbaru dan penawaran eksklusif dari email newsletter. Namun, pengguna situs Celebgramme.com dapat berhenti berlangganan (unsubscribe) email newsletter jika tidak ingin menerima informasi tersebut.</p></li></ul>				
				
				
				
					<input type="checkbox" id="checkbox-term"> <label for="checkbox-term">Saya setuju dengan Term & Conditions diatas</label>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" id="button-ok-terms">OK</button>
        </div>
      </div>
    </div>
  </div>



<!-- Modal -->
  <div class="modal fade" id="myModalTutorialVideo" role="dialog" >
    <div class="modal-dialog" style="width:70%;">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
          <h4 class="modal-title">Tutorial Video
					</h4>
        </div>
        <div class="modal-body">
					<div class="embed-responsive embed-responsive-16by9">
							<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/QoWlmcNIbik"></iframe>
					</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" id="">Close</button>
        </div>
      </div>
    </div>
  </div>


<?php   if ($user->type<>"not-confirmed") { ?>
<div class="row">
              <div class="col-md-10 col-sm-10">
                <div class="panel panel-info ">
                  <div class="panel-heading">
                    <h3 class="panel-title">Hal Yang Perlu Diperhatikan Sebelum Anda Memulai</h3>
                  </div>
                  <div class="panel-body">
                    <!--
										<strong>===== AKSES TUTORIAL =====</strong><br>
										1. Click TUTORIAL VIDEO ► <a href="#" data-toggle="modal" data-target="#myModalTutorialVideo" >disini</a> ◄<br>
										2. Baca TUTORIAL PDF ► <a href="https://docs.google.com/document/d/1-gOSIrsoXj7Mdwj4Nph-vwPIxpKB8JScjb_D-GUYmUE" target="_blank">disini</a> ◄<br>
										3. Akses FAQ & SUPPORT ► <a href="https://celebgramme.freshdesk.com" target="_blank">disini</a> ◄<br>
                    <br>
										<strong>===== HARAP DIBACA =====</strong><br>
										<p style="color:#a94442;">
										1. SANGAT PENTING untuk AKUN BARU membaca TUTORIAL terlebih dahulu<br>
										2. DILARANG KERAS menggunakan sistem auto manage / instagram bot yang lain saat anda menggunakan Celebgramme.com <br>
										3. DILARANG MENGGANTI username/password selama menggunakan Celebgramme.com <br>
										4. DILARANG MEMBELI FOLLOWERS - selama menggunakan Celebgramme.com <br>
										5. Celebgramme otomatis melakukan Unfollow apabila akun anda mendekati batas following Instagram (7500 following)<br>
										</p>
										<br>
										<strong>===== CARA PENGGUNAAN =====</strong><br>
										1. Add Account Instagram anda<br>
                    2. Click Setting di setiap Account Instagram anda<br>
                    3. Tetapkan Setting yang anda inginkan <br>
										4. TIDAK PERLU menambah tanda # atau tanda @ di input hashtags & usernames <br>
										5. Setelah selesai Click START<br>
-->
										<?php echo $content; ?>
                  </div>
                </div>
              </div>
</div>
<div class="row">
              <div class="col-md-10 col-sm-10">
                <div class="panel panel-info ">
                  <div class="panel-heading">
                    <h3 class="panel-title">Dashboard</h3>
                  </div>
                  <div class="panel-body">
                    <div class="col-md-4 col-sm-8">
                      <input type="button" value="Add Account" class="btn btn-primary col-md-8 col-sm-12" data-toggle="modal" data-target="#myModal" id="btn-add-account">
                    </div>                        
                    <div class="col-md-4 col-sm-8">
                      <input type="button" value="Start All" class="btn btn-success col-md-8 col-sm-12" id="button-start-all">
                    </div>                        
                    <div class="col-md-4 col-sm-8">
                      <input type="button" value="Stop All" class="btn btn-danger col-md-8 col-sm-12" id="button-stop-all">
                    </div>                        
                  </div>
                </div>
              </div>  
</div>                        
<?php } ?>

<div class="row">

  <?php if ($user->type=="not-confirmed") { ?> 
    <div class="col-sm-10 col-md-10">            
      <div class="alert alert-danger col-sm-18 col-md-18">
        Silahkan konfirmasi email terlebih dahulu. Klik <a href="" id="link-activation">disini</a> untuk kirim email konfirmasi ulang.
      </div>  
    </div>          
  <?php } ?>
  <?php if (!is_null($order)) { ?> 
    <div class="col-sm-10 col-md-10">            
      <div class="alert alert-danger col-sm-18 col-md-18">
        Anda belum melakukan konfirmasi pembayaran. silahkan klik <a href="{{url('confirm-payment')}}">disini</a> untuk melakukan konfirmasi pembayaran
      </div>  
    </div>          
  <?php } ?>
  <div class="col-sm-10 col-md-10">            
    <div class="alert btn-danger col-sm-18 col-md-18" id="alert">
    </div>  
  </div>          
  @if (session('error'))
    <div class="col-sm-10 col-md-10">            
      <div class="alert alert-danger col-sm-18 col-md-18" >
        {{ session('error') }}
      </div>  
    </div>          
  @endif
</div>                        

<div class="row">
  <div class="col-sm-10 col-md-10">
			<h3>Total Waktu Berlangganan</h3>
      <div id="clockdiv" class="fl">
        <div class="fl">
          <span class="days"></span>
          <div class="smalltext">Days</div>
        </div>
        <div class="fl">
          <span class="hours"></span>
          <div class="smalltext">Hours</div>
        </div>
        <div class="fl">
          <span class="minutes"></span>
          <div class="smalltext">Minutes</div>
        </div>
        <div class="fl">
          <span class="seconds"></span>
          <div class="smalltext">Seconds</div>
        </div>
        <i class="fn">
        </i>
      </div>
			<div class="fl" style="margin-left:10px;">
				<span class="server-status"></span><label style="font-size:11px;"> &nbsp Server Status : </label>
				<span class="glyphicon glyphicon-question-sign hint-button tooltipPlugin" title="<div class='panel-heading'>Server Status</div><div class='panel-content'><strong>Normal</strong> - Server in Normal Traffic, Perubahan Settings akan berjalan sesuai dengan load server  <br><strong>High</strong> - Server in High Traffic, Perubahan Settings akan berjalan sesuai dengan load server <br><strong>Maintenance</strong> - Server under maintenance, Perubahan settings akan berjalan saat Status Server Normal/High</div>"></span>
				<span style="font-size:11px;color:#5abe5a;" >{{$status_server}}</span> <br>
				
				<label style="font-size:11px;"> Total waktu per akun : </label>
					<span class="glyphicon glyphicon-question-sign hint-button tooltipPlugin" title="<div class='panel-heading'>Total Waktu Per Akun</div><div class='panel-content'><strong>Total waktu per akun start </strong>= Total waktu pembelian / total akun start <br><strong>hanya akun yang di start saja </strong>yang dikurangi waktunya dari total waktu pembelian</div>"></span>
				<span style="font-size:11px;color:#5abe5a;" id="time-account-start"></span> <br>
				
				<label style="font-size:11px;"> Maksimal akun : {{$user->max_account}}</label>
				
			</div>
			<div class="fn">
			</div>
  </div>
</div>
<!--
<div class="row">
	<div class="col-sm-10 col-md-10">
		<h3>Total Account start = <span id="total-account-start"></span> </h3>
	</div>
</div>

<div class="row">
  <div class="col-sm-10 col-md-10">
			<h3>Total Waktu per account start : <span id="time-account-start"></span></h3>
  </div>
</div>

<div class="row">
	<div class="col-sm-10 col-md-10">
		<p>* Total waktu per akun start = Total waktu pembelian / total akun start <br>
hanya akun yang di start saja yang dikurangi waktunya dari total waktu pembelian</p>
	</div>
</div>
-->



<?php if ($user->type<>"not-confirmed") { ?>
<div class="row">
  <ul class="col-md-10" id="account-all">
<!--
    <div class="col-md-5 border-styling">
      <div class="row"> <img src="#" class=""> </div>
      <div class="row"> <label>nama</label></div>
      <div class="row"> <p> Status activity : Stopped</p></div>
      <div class="row"> 
        <div class="im-centered">
        <input type="button" value="Start" class="btn btn-info">
        <input type="button" value="Setting" class="btn btn-primary">
        </div>
      </div>
    </div>
-->
  </ul>                        
</div>      
<?php }?>


  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Instagram Login</h4>
        </div>
        <div class="modal-body">
          <form enctype="multipart/form-data" id="form-credential">
            <div class="form-group form-group-sm row">
              <label class="col-xs-8 col-sm-2 control-label" for="formGroupInputSmall">Username</label>
              <div class="col-sm-8 col-md-6">
                <input type="text" class="form-control" placeholder="Your username" name="username" id="username">
              </div>
            </div>  
            <div class="form-group form-group-sm row">
              <label class="col-xs-8 col-sm-2 control-label" for="formGroupInputSmall">Password</label>
              <div class="col-sm-8 col-md-6">
                <input type="password" class="form-control" placeholder="Your password" name="password" id="password">
              </div>
            </div>  
            <div class="form-group form-group-sm row">
              <label class="col-xs-8 col-sm-2 control-label" for="formGroupInputSmall">Confirm Password</label>
              <div class="col-sm-8 col-md-6">
                <input type="password" class="form-control" placeholder="Confirm your password" name="confirm_password" id="confirm_password">
              </div>
            </div>  
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" id="button-process">Submit</button>
        </div>
      </div>
      
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="myModal-edit-password" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit Password</h4>
        </div>
        <div class="modal-body">
          <form enctype="multipart/form-data" id="form-edit-password">
            <div class="form-group form-group-sm row">
              <label class="col-xs-8 col-sm-2 control-label" for="formGroupInputSmall">Username</label>
              <div class="col-sm-8 col-md-6">
                <input type="text" class="form-control" placeholder="Your username" name="edit_username" id="edit_username" disabled>
              </div>
            </div>  
            <div class="form-group form-group-sm row">
              <label class="col-xs-8 col-sm-2 control-label" for="formGroupInputSmall">Password</label>
              <div class="col-sm-8 col-md-6">
                <input type="password" class="form-control" placeholder="Your password" name="edit_password" id="edit_password">
              </div>
            </div>  
            <div class="form-group form-group-sm row">
              <label class="col-xs-8 col-sm-2 control-label" for="formGroupInputSmall">Confirm Password</label>
              <div class="col-sm-8 col-md-6">
                <input type="password" class="form-control" placeholder="Confirm your password" name="edit_confirm_password" id="edit_confirm_password">
              </div>
            </div>  
            <input type="hidden" name="setting_id" id="setting_id">
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" id="button-edit-password">Submit</button>
        </div>
      </div>
      
    </div>
  </div>

	
  <!-- Modal confirm delete-->
	<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
					<div class="modal-content">
							<div class="modal-header">
									Delete Account
							</div>
							<div class="modal-body">
									Are you sure want to delete ?
							</div>
							<input type="hidden" id="id-setting">
							<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
									<button type="button" class="btn btn-danger btn-ok" id="delete-setting" data-dismiss="modal">Delete</button>
							</div>
					</div>
			</div>
	</div>	
@endsection
