  <!DOCTYPE html>
<html>
    <head>
      <title>Celebgramme</title>
      <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/images/celebgramme-favicon.png') }}">
      <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
      <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
      <link href="{{ asset('/css/bootstrap-theme.min.css') }}" rel="stylesheet">
      <link href="{{ asset('/css/package.css') }}" rel="stylesheet">
    </head>
    <body>
      <div class="header-package row container">
        <div class="div-black">
          <div class="div-logo">
            <a href="http://celebgramme.com"><div class="logo"></div></a>
          </div>
          <h1 class="h1-package">Pilih Paket <strong>Auto Manage</strong></h1>
        </div>
      </div>

      <div class="row content-all">
        <div class="col-sm-2 col-md-2">
        </div>
        <div class="content-package container col-sm-8 col-md-8">  

          <h3 class="price-list"> Price List Auto Manage </h3>
          <div class="price-auto-manage col-sm-12 col-md-12">
            <div class="row">
							<!--
              <img src="{{url('images/package price-01.png')}}" class="img-responsive" width="1109" height="437">
							-->
							<!--
							<div class="col-sm-2 col-md-2" style="padding-right:8px;padding-left:8px;">
								<img src="{{url('images/price/prices-02.png')}}" class="img-responsive">
								<a href="{{url('checkout')}}/16"><button class="btn form-control btn-price">Buy Package</button></a>
							</div>
							<div class="col-sm-2 col-md-2" style="padding-right:8px;padding-left:8px;">
								<img src="{{url('images/price/prices-03.png')}}" class="img-responsive">
								<a href="{{url('checkout')}}/17"><button class="btn btn-price form-control">Buy Package</button></a>
							</div>
							<div class="col-sm-2 col-md-2" style="padding-right:8px;padding-left:8px;">
								<img src="{{url('images/price/prices-04.png')}}" class="img-responsive">
								<a href="{{url('checkout')}}/18"><button class="btn btn-price-popular form-control">Buy Package</button></a>
							</div>
							<div class="col-sm-2 col-md-2" style="padding-right:8px;padding-left:8px;">
								<img src="{{url('images/price/prices-05.png')}}" class="img-responsive">
								<a href="{{url('checkout')}}/19"><button class="btn btn-price form-control">Buy Package</button></a>
							</div>
							<div class="col-sm-2 col-md-2" style="padding-right:8px;padding-left:8px;">
								<img src="{{url('images/price/prices-06.png')}}" class="img-responsive">
								<a href="{{url('checkout')}}/25"><button class="btn btn-price form-control">Buy Package</button></a>
							</div>
							<div class="col-sm-2 col-md-2" style="padding-right:8px;padding-left:8px;">
								<img src="{{url('images/price/prices-07.png')}}" class="img-responsive">
								<a href="{{url('checkout')}}/20"><button class="btn btn-price form-control">Buy Package</button></a>
							</div>
							-->
							<div class="col-sm-1 col-md-1" style="padding-right:8px;padding-left:8px;">
							</div>
							<div class="col-sm-3 col-md-3" style="padding-right:8px;padding-left:8px;margin-top:50px;">
								<a href="{{url('checkout')}}/16">
									<img src="{{url('images/price/prices-07.png')}}" class="img-responsive">
								</a>
							</div>
							<div class="col-sm-4 col-md-4" style="padding-right:8px;padding-left:8px;">
								<a href="{{url('checkout')}}/27">
									<img src="{{url('images/price/prices-06.png')}}" class="img-responsive">
								</a>
							</div>
							<div class="col-sm-3 col-md-3" style="padding-right:8px;padding-left:8px;margin-top:50px;">
								<a href="{{url('checkout')}}/17">
									<img src="{{url('images/price/prices-05.png')}}" class="img-responsive">
								</a>
							</div>
							<div class="col-sm-1 col-md-1" style="padding-right:8px;padding-left:8px;">
							</div>
            </div>
            <div class="row">
							<div class="col-sm-1 col-md-1" style="padding-right:8px;padding-left:8px;">
							</div>
							<div class="col-sm-3 col-md-3" style="padding-right:8px;padding-left:8px;margin-top:10px;">
								<a href="{{url('checkout')}}/18">
									<img src="{{url('images/price/prices-02.png')}}" class="img-responsive">
								</a>
							</div>
							<div class="col-sm-3 col-md-3" style="padding-right:8px;padding-left:8px;margin-top:10px;">
								<a href="{{url('checkout')}}/19">
									<img src="{{url('images/price/prices-03.png')}}" class="img-responsive">
								</a>
							</div>
							<div class="col-sm-3 col-md-3" style="padding-right:8px;padding-left:8px;margin-top:10px;">
								<a href="{{url('checkout')}}/20">
									<img src="{{url('images/price/prices-04.png')}}" class="img-responsive">
								</a>
							</div>
							<div class="col-sm-1 col-md-1" style="padding-right:8px;padding-left:8px;">
							</div>
            </div>
						
            <div class="row" style="text-align:center">
                <p>**bisa digunakan max.total 3 account Instagram</p>
            </div>
          </div>
<!--
          <h3 class="price-list"> Price List Daily Likes </h3>
          <!--<form action="{{url('process-package')}}" method="POST" class="form-signin">-->
<!--
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
                    <label for="a1">10.000</label>
                  </td>
                  <td>
                    <label for="a2">60.000</label>
                  </td>
                  <td>
                    <label for="a3">180.000</label>
                  </td>
                </tr>
                <tr>
                  <td>
                    500
                  </td>
                  <td>
                    <label for="b1">15.000</label>
                  </td>
                  <td>
                    <label for="b2">90.000</label>
                  </td>
                  <td>
                    <label for="b3">270.000</label>
                  </td>
                </tr>
                <tr>
                  <td>
                    1000
                  </td>
                  <td>
                    <label for="c1">20.000</label>
                  </td>
                  <td>
                    <label for="c2">120.000</label>
                  </td>
                  <td>
                    <label for="c3">360.000</label>
                  </td>
                </tr>
                <tr>
                  <td>
                    2000
                  </td>
                  <td>
                    <label for="d1">30.000</label>
                  </td>
                  <td>
                    <label for="d2">180.000</label>
                  </td>
                  <td>
                    <label for="d3">540.000</label>
                  </td>
                </tr>
                <tr>
                  <td>
                    3000
                  </td>
                  <td>
                    <label for="e1">40.000</label>
                  </td>
                  <td>
                    <label for="e2">240.000</label>
                  </td>
                  <td>
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

            <div class="row col-sm-12 col-md-12" style="text-align:center;">
              <a href="{{url('checkout')}}"><div class="order-now" ></div></a>
            </div>

                <div class="description">
                  <div class="col-md-6 col-xs-6">
                    <!--
                    <div class="image-description center-block img-responsive">
                    </div>
                  -->
                    <img src="{{url('images/laptop-landingpage.png')}}" width="525" height="275" class="img-responsive">
                  </div>
                  <div class="content-description col-md-6 col-xs-6">
                    <h3>Cara Pembayaran</h3>
                    <p> 
                      1. Silahkan cek harga paket <!--Daily Likes & -->Auto Manage yang telah tersedia di halaman prices, klik lanjutkan <br>
                      2. Anda akan masuk ke halaman checkout, pilih paket yang anda inginkan. (jika anda tidak memilih salah satu paket, silahkan biarkan default) <br>
                      3. Masukkan kode kupon potongan harga jika ada <br>
                      4. Pilih opsi pembayaran anda kemudian klik order <br>
                      5. Silahkan lakukan pembayaran <br>
                      6. Untuk proses pemesanan selanjutnya, mohon konfirmasi pembayaran anda dengan mengisi form konfirmasi pembayaran. Silahkan cek email dari kami, kemudian klik link konfirmasi pembayaran <br>
                      7. Silahkan log in, paket anda telah aktif <br>
                      <strong>Selamat menggunakan Celebgramme!</strong>
                    </p>
                  </div>
                </div>


            <!--<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">-->
          </form>
          <p class="keterangan">
            <!--Jika anda mempunyai pertanyaan seputar Celebgramme, silahkan baca FAQ ( Frequently Asked Question ) <a href="http://celebgramme.com/faq">disini</a> atau silahkan hubungi kami <a href="http://celebgramme.com/support">disini</a>
          --></p>
        </div>
        <div class="col-sm-2 col-md-2">
        </div>
      </div>


      <div class="footer-package row container">
        <div class="footer-center container row">
          <div class="fl copyright col-md-6 col-sm-3">
            Celebgramme.com is NOT affiliated with Instagram.com in anyway
          </div>
          <div class="col-md-6 col-sm-5 fl footer-helper ">
            <a href="http://celebgramme.com/our-products/auto-manage">Our Products </a>| 
            <a href="http://celebgramme.com/auto-manage">How It Works </a>  | 
            <a href="http://celebgramme.com/prices">Prices </a>| 
            <a href="http://celebgramme.com/blog">Blog </a>| 
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
