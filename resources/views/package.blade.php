  <!DOCTYPE html>
<html>
    <head>
      <title>Celebgramme</title>
      <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/images/celebgramme-favicon.png') }}">
      <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
      <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
      <link href="{{ asset('/css/bootstrap-theme.min.css') }}" rel="stylesheet">
      <link href="{{ asset('/css/package.css') }}" rel="stylesheet">
      <script type="text/javascript">
        $(document).ready(function() {
          $("#alert").hide();
        });
      </script>
    </head>
    <body>
      <div class="header-package row container">
        <div class="div-black">
          <div class="div-logo">
            <a href="http://celebgramme.com"><div class="logo"></div></a>
          </div>
          <h1 class="h1-package">Pilih Paket <strong>Instagram Likes</strong></h1>
        </div>
      </div>

      <div class="content-package container row">  

        <h3 class="price-list"> Price List Auto Manage </h3>
        <div class="price-auto-manage">
          <p>**bisa digunakan max.total 3 account</p>
        </div>

        <h3 class="price-list"> Price List Daily Likes </h3>
        <form action="{{url('process-package')}}" method="POST" class="form-signin">

        <div class="table-daily-likes row">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>
                  Daily Likes 
                </th>
                <th>
                  Price 1 day
                </th>
                <th>
                  Price 7 day
                </th>
                <th>
                  Price 28 day
                </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  200
                </td>
                <td>
                  <!-- <input type="radio" name="package" value="1" id="a1" checked> -->
                  <label for="a1">10.000</label>
                </td>
                <td>
                  <!-- <input type="radio" name="package" value="2" id="a2"> -->
                  <label for="a2">60.000</label>
                </td>
                <td>
                  <!-- <input type="radio" name="package" value="3" id="a3"> -->
                  <label for="a3">180.000</label>
                </td>
              </tr>
              <tr>
                <td>
                  500
                </td>
                <td>
                  <!-- <input type="radio" name="package" value="4" id="b1"> -->
                  <label for="b1">15.000</label>
                </td>
                <td>
                  <!-- <input type="radio" name="package" value="5" id="b2"> -->
                  <label for="b2">90.000</label>
                </td>
                <td>
                  <!-- <input type="radio" name="package" value="6" id="b3"> -->
                  <label for="b3">270.000</label>
                </td>
              </tr>
              <tr>
                <td>
                  1000
                </td>
                <td>
                  <!-- <input type="radio" name="package" value="7" id="c1"> -->
                  <label for="c1">20.000</label>
                </td>
                <td>
                  <!-- <input type="radio" name="package" value="8" id="c2"> -->
                  <label for="c2">120.000</label>
                </td>
                <td>
                  <!-- <input type="radio" name="package" value="9" id="c3"> -->
                  <label for="c3">360.000</label>
                </td>
              </tr>
              <tr>
                <td>
                  2000
                </td>
                <td>
                  <!-- <input type="radio" name="package" value="10" id="d1"> -->
                  <label for="d1">30.000</label>
                </td>
                <td>
                  <!-- <input type="radio" name="package" value="11" id="d2"> -->
                  <label for="d2">180.000</label>
                </td>
                <td>
                  <!-- <input type="radio" name="package" value="12" id="d3"> -->
                  <label for="d3">540.000</label>
                </td>
              </tr>
              <tr>
                <td>
                  3000
                </td>
                <td>
                  <!-- <input type="radio" name="package" value="13" id="e1"> -->
                  <label for="e1">40.000</label>
                </td>
                <td>
                  <!-- <input type="radio" name="package" value="14" id="e2"> -->
                  <label for="e2">240.000</label>
                </td>
                <td>
                  <!-- <input type="radio" name="package" value="15" id="e3"> -->
                  <label for="e3">720.000</label>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
          <!--
          <div class="div-opsi-pembayaran">
            <label class="col-xs-6 col-sm-6 control-label" for="formGroupInputSmall">Pilih Opsi Pembayaran Anda</label>
            <div class="col-sm-2 col-md-2">
              <select class="form-control" name="payment-method">
                <option value="1">Bank transfer</option> -->
        <!--        <option value="2">Veritrans</option>-->
           <!--   </select>
            </div>
          </div>  
        -->



              <div class="description">
                <div class="image-description fl col-md-4 col-xs-2">
                </div>
                <div class="content-description fl col-md-8 col-xs-6">
                  <h3>Cara Pembayaran</h3>
                  <p> 
                    1. Silahkan cek harga paket Daily Likes & Auto Manage yang telah tersedia di halaman prices, klik lanjutkan <br>
                    2. Anda akan masuk ke halaman checkout, pilih paket yang anda inginkan. (jika anda tidak memilih salah satu paket, silahkan biarkan default) <br>
                    3. Masukkan kode kupon potongan harga jika ada <br>
                    4. Pilih opsi pembayaran anda kemudian klik order <br>
                    5. Silahkan lakukan pembayaran <br>
                    6. Untuk proses pemesanan selanjutnya, mohon konfirmasi pembayaran anda dengan mengisi form konfirmasi pembayaran. Silahkan cek email dari kami, kemudian klik link konfirmasi pembayaran <br>
                    7. Silahkan log in, paket anda telah aktif <br>
                    <strong>Selamat menggunakan Celebgramme!</strong>
                  </p>
                </div>
                <div class="fn"></div>
              </div>


          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
          <input class="btn-package" type="submit" value="Lanjutkan" id="button-process">
        </form>
        <p class="keterangan">Jika anda mempunyai pertanyaan seputar Celebgramme, silahkan baca FAQ ( Frequently Asked Question ) <a href="http://celebgramme.com/faq">disini</a> atau silahkan hubungi kami <a href="http://celebgramme.com/support">disini</a></p>
      </div>


      <div class="footer-package row container">
        <div class="footer-center container row">
          <div class="fl copyright col-md-7 col-sm-4">
            Celebgramme.com is NOT affiliated with Instagram.com & Facebook.com in anyway
          </div>
          <div class="col-md-5 col-sm-5 fl footer-helper ">
            <a href="http://celebgramme.com/about-us">About Us </a>| 
            <a href="http://celebgramme.com/about-us">Fitur </a>| 
            <a href="http://celebgramme.com/how-it-works">How It Works </a>  | 
            <a href="http://celebgramme.com/faq">FAQ  </a>| 
            <a href="http://celebgramme.com/support">Support  </a>|  
            <a href="{{url('login')}}">Log in</a>
          </div>
          <div class="fn">
          </div>
        </div>

      </div>
    </body>
</html>
