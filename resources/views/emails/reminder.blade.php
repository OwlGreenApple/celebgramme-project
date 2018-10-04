Hi kak {{$user->fullname}},<br>
<br>
Kami masih menunggu pembayaran anda untuk :<br>
<br>
<strong>Order    : </strong>{{$no_order}} <br>
<strong>Tanggal : </strong>{{$tanggal_order}}<br>
<strong>Paket   : </strong><?php 
	if ($order->type=="daily-activity") {
?>

{{$package->package_name}} = Rp. {{number_format($order->total - $order->discount,0,'','.')}} </strong><br>

<?php 		
	}
	else if ($order->type=="max-account") {
?>

{{$order->added_account}} akun = Rp. {{number_format($order->total - $order->discount,0,'','.')}} <br>

<?php 		
	}
?>
<br>
<br>
<br>
Silahkan transfer ke :<br>
<br>
BCA  ( cab ITC Mangga Dua, Jakarta )<br>
4800-227-122<br>
Sugiarto Lasjim<br>
<br>
Jangan lupa konfirmasi di dalam dashboard<br>
setelah melakukan pembayaran <br>
<br>
Terima kasih sudah menjadi pelanggan Activfans,<br>
<br>
Apabila memerlukan bantuan<br>
Silahkan hubungi kami via email<br>
<br>
Activfans@gmail.com<br>
<br>
Salam hangat,<br>
<br>
<br>
Team Activfans