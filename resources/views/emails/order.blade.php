No Order : {{$no_order}} <br>
Nama : {{$user->fullname}} <br>
Status Order : {{$status}} <br>
Anda telah memesan paket {{$package->package_name}} dengan harga Rp. {{number_format($package->price,0,'','.')}} <br>

<?php if ( $status == "Belum lunas" ) { ?>
	Silahkan melakukan pembayaran, dan mengkonfirmasi order anda <a href="{{url('confirm-payment')}}"> disini </a>. <br>
<?php } ?>

<br> Salam hangat, Celebgramme.com