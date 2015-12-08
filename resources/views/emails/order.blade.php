No Order : {{$no_order}} <br>
Nama : {{$user->fullname}} <br>
Status Order : {{$status}} <br>
Anda telah memesan paket {{$package->package_name}} dengan harga Rp. {{number_format($package->price,0,'','.')}} <br>

<?php if ($status=="Belum Lunas") { ?>
Silahkan melakukan pembayaran, dan mengkonfirmasi order anda. <br>
<?php } ?>

<br> Salam hangat, Celebgramme.com