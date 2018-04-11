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
                <div id="alert">
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
                    <input id="kota" type="text" class="form-control" name="kota" >
                </div>
            </div>

            <div class="form-group">
                <label for="name" class="col-md-12 control-label" style="text-align:left;">Sebutkan 5 selebgram terpopuler versi anda?</label>

                <div class="col-md-12">
                    <input id="kota" type="text" class="form-control" name="selebgram" >
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
