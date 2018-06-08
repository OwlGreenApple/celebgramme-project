Terima kasih, anda telah melakukan pemesanan Celebgramme service.<br>
Info Order anda adalah sebagai berikut <br>
<br>
<strong>No Order :</strong> {{$no_order}} <br>
<strong>Nama :</strong> {{$user->fullname}} <br>
<strong>Status Order :</strong> {{$status}} <br>
Anda telah memesan paket 
<?php 
	if ($order->type=="daily-activity") {
?>

{{$package->package_name}} = <strong>Rp. {{number_format($order->total - $order->discount,0,'','.')}} </strong><br>

<?php 		
	}
	else if ($order->type=="max-account") {
?>

{{$order->added_account}} akun = <strong>Rp. {{number_format($order->total - $order->discount,0,'','.')}} </strong><br>

<?php 		
	}
?>
<br>
<?php if ( $status == "Belum lunas" ) { ?>
	Harap SEGERA melakukan pembayaran,<br> 
	<strong>TRANSFER Melalui :</strong><br>
	<br>
	<strong>Bank BCA</strong><br>
	4800-227-122<br>
	Sugiarto Lasjim<br>
	<br>
	<!--
	<strong>Bank Mandiri</strong><br>
	121-00-3592712-2<br>
	Sugiarto Lasjim<br>
	-->
	<br>
	
	
	dan setelah selesai membayar<br>
	silahkan KLIK <a href="{{url('confirm-payment')}}"> --> KONFIRMASI PEMBAYARAN <-- </a> disini . <br>
	NB : No Rekening Account BCA untuk celebgramme & Celebpost berbeda, untuk mempercepat proses administrasi silahkan transfer pada rekening BCA service yang dibeli.
<?php } ?>

<br> Salam hangat, 
<br>
Celebgramme.com
