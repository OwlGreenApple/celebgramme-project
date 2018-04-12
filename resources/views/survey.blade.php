  <!DOCTYPE html>
<html>
    <head>
      <title>Celebgramme</title>
      <meta name="csrf-token" content="{{ csrf_token() }}" />
      <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/images/celebgramme-favicon.png') }}">
      <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
      <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
      <link href="{{ asset('/css/bootstrap-theme.min.css') }}" rel="stylesheet">
      <link href="{{ asset('/css/package.css') }}" rel="stylesheet">
      <style>
        .header-package{
          height:228px;
        }
      </style>
      <!-- Jquery Core Js -->
      <script src="{{ asset('js/jquery-1.11.3.js') }}"></script>
      <script>
        $(document).ready(function(){
          $('#button-submit').click(function(e){
            $(this).val("Please wait");
            $(this).prop('disabled', true);;
            $.ajax({                                      
              url: '<?php echo url('submit-survey'); ?>',
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              type: 'POST',
              data: $("#form-survey").serialize(),
              dataType: 'text',
              success: function(result)
              {
                var data = jQuery.parseJSON(result);
                $("#alert").show();
                $("#alert").html(data.message);
                if(data.type=='success') {
                  $("#alert").addClass("alert-success");
                  $("#alert").removeClass("alert-danger");
                  
                  $("#thank-you").show();
                  $("#no-undian").html(data.noundian);
                  $("#form-survey").hide();
                } else if (data.type=='error') {
                  $("#alert").addClass("alert-danger");
                  $("#alert").removeClass("alert-success");
                }
              }
            });
          });
        });
      </script>
    </head>
    <body>
      <div class="header-package row container">
        <div class="div-black">
          <div class="div-logo">
            <a href="http://celebgramme.com"><div class="logo"></div></a>
          </div>
          <!--<h1 class="h1-package">Silahkan Jawab Pertanyaan Dibawah</h1>-->
        </div>
      </div>

      <div class="row content-all">
        <div class="col-sm-2 col-md-2">
        </div>
        <div class="content-package container col-sm-8 col-md-8">  

          <h3 class="price-list"> Survey Singkat Berhadiah</h3>
          <form class="form-horizontal" id="form-survey">
            
            <div class="form-group">
              <div class="col-md-12">
                <div id="alert" class="alert">
                </div>
              </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-12 control-label" style="text-align:left;">Nama lengkap</label>

                <div class="col-md-12">
                    <input id="name" type="text" class="form-control" name="fullname">
                </div>
            </div>

            <div class="form-group">
                <label for="name" class="col-md-12 control-label" style="text-align:left;">Email</label>

                <div class="col-md-12">
                    <input id="email" type="text" class="form-control" name="email" >
                </div>
            </div>

            <div class="form-group">
                <label for="name" class="col-md-12 control-label" style="text-align:left;">Sebutkan kota tempat tinggal anda?</label>

                <div class="col-md-12">
                    <input id="kota" type="text" class="form-control" name="kota">
                </div>
            </div>

            <div class="form-group">
                <label for="name" class="col-md-12 control-label" style="text-align:left;">Apakah sudah punya bisnis online?</label>

                <div class="col-md-12">
                    <label><input type="radio" name="is_bisnis" value="1" checked="checked">Ya</label> &nbsp
                    <label><input type="radio" name="is_bisnis" value="0">Tidak</label>
                </div>
            </div>

            <div class="form-group">
                <label for="name" class="col-md-12 control-label" style="text-align:left;">Apakah anda ingin menjadi selebgram?</label>

                <div class="col-md-12">
                    <label><input type="radio" name="is_selebgram" value="1" checked="checked">Ya</label> &nbsp
                    <label><input type="radio" name="is_selebgram" value="0">Tidak</label>
                </div>
            </div>

            <div class="form-group">
                <label for="name" class="col-md-12 control-label" style="text-align:left;">Sebutkan 5 toko online IG terpopuler menurut anda?</label>

                <div class="col-md-12">
                    <textarea id="popular-olshop" class="form-control" name="popular_olshop" rows="3"></textarea>
                </div>
            </div>

            <div class="form-group">
                <label for="name" class="col-md-12 control-label" style="text-align:left;">Sebutkan 5 selebgram terpopuler versi anda?</label>

                <div class="col-md-12">
                    <textarea id="selebgram" class="form-control" name="selebgram" rows="3"></textarea>
                </div>
            </div>


            <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                </div>
            </div>
          
            <div class="form-group">
                <div class="col-md-2 col-xs-12 col-md-offset-5 col-xs-offset-0">
                    <input type="button" class="btn btn-primary form-control" value="Submit" id="button-submit">
                </div>
            </div>
          </form>
          
          <div id="thank-you" style="display:none;">
            Terima kasih sudah berpartisipasi dalam "Survey Berhadiah Celebgramme" <br>
            Ini adalah NO UNDIAN Anda: <br>
            <br>
            <b><span id="no-undian" style="font-size:100px;">101</span></b> <br>
            <br>
            Pastikan anda melihat Video Pengundian Pemenang pada hari: <br>
            <b>Senin, 16 April 2018, Jam 12 siang - Pengundian ke-1</b> <br>
            <b>Senin, 23 April 2018, Jam 12 siang - Pengundian ke-2</b> <br>
            <br>
            Selamat, Anda juga mendapatkan <b>KUPON DISKON CELEBGRAMME 15%</b> <br>
            Berlaku untuk semua paket Celebgramme <br>
            <br>
            Kupon Diskon Celebgramme : <b>xp7sr12</b> <br>
            <br>
            Order di ► <a href="https://celebgramme.com/celebgramme/prices">https://celebgramme.com/celebgramme/prices</a> <br>
            <br>
            Kami juga memberikan Potongan Rp 75,000 untuk Seminar Digimaru  <br>
            <br>
            "Online sales explosion" <br>
            Jakarta, 28 April 2018 <br>
            Aruba room, Kota Kasablanka Lt 4 <br>
            <br>
            Kupon Diskon Digimaru : <b>survey2018</b> <br>
            <br>
            Pesan Tiket ► <a href="http://digimaru.org">http://digimaru.org</a> <br>
            *Kupon BOLEH Digunakan berulang untuk pembelian lebih dari 1 tiket <br>
            <br>
            Gunakan Kupon sebaik-baiknya <br>
            Dan semoga anda terpilih menjadi salah satu pemenang Survey Berhadiah Celebgramme <br>
            <br>
            Salam hangat, <br>
            <br>
            <br>
            Michael Sugiharto <br>
            Celebgramme.com <br>
            <br>
            <br>
            *PS: Video pengundian dan pengumuman pemenang akan diupload di https://celebgramme.com/surveywinner
            <br>
            <br>
            
          </div>

        </div>
        <div class="col-sm-2 col-md-2">
        </div>
      </div>


      <div class="footer-package row container">
        <div class="footer-center container row">
          <div class="copyright col-md-6 col-sm-6">
            Celebgramme.com © 2018
          </div>
					<div class="col-md-2 col-sm-2">
          </div>
          <div class="col-md-4 col-sm-4 footer-helper ">
            <a href="http://celebgramme.com/blog">Blog </a>| 
            <a href="http://celebgramme.com/support">Support  </a>|  
            <a href="http://celebgramme.com/faq">FAQ  </a>| 
            <a href="http://celebgramme.com/prices">Prices </a>| 
            <a href="{{url('login')}}">Log in</a>
          </div>
          <div class="fn">
          </div>
        </div>

      </div>
    </body>
</html>
