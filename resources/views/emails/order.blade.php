Terima kasih, anda telah melakukan pemesanan Celebgramme service.
Info Order anda adalah sebagai berikut <br>

No Order : {{$no_order}} <br>
Nama : {{$user->fullname}} <br>
Status Order : {{$status}} <br>
Anda telah memesan paket {{$package->package_name}} dengan harga Rp. {{number_format($order->total,0,'','.')}} <br>

<?php if ( $status == "Belum lunas" ) { ?>
	Harap SEGERA melakukan pembayaran, 
	dan setelah selesai membayar
	silahkan KLIK <a href="{{url('confirm-payment')}}"> --> KONFIRMASI PEMBAYARAN <-- </a> disini . <br>
<?php } ?>

<br> Salam hangat, 

Celebgramme.com
