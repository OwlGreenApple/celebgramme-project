Terima kasih, <br>
Admin telah MENERIMA KONFIRMASI PEMBAYARAN anda <br>
berikut ini adalah INVOICE PEMBAYARAN anda <br>
<br>
<strong>No Invoice : </strong> {{$no_invoice}}<br>
<strong>Anda membayar via : </strong> {{$order_type}} <br>
<strong>Paket : </strong> {{$package->package_name}} <br>
<strong>Harga Paket : </strong> {{$order->total}} <br>
<strong>Discount : </strong> {{$coupon_value}} <br>
<strong>Total : </strong> {{$invoice->total}} <br>
<br>
Silahkan akses ke user Dashboard<br>
<?php if(env('APP_PROJECT')='Celebgramme') { ?>
Login ke https://activfans.com/dashboard/<br>
<?php } else { ?>
Login ke https://activfans.com/amelia/<br>
<?php } ?>
<br>
Dan Setup Settings Instagram Auto Manage anda.<br>
<br>
Team kami selalu siap membantu anda,<br>
<br>
<br>
Salam hangat, <br>
<br>
<?php if(env('APP_PROJECT')=='Celebgramme') {?>
Activfans.com
<?php } else { ?>
Amelia
<?php } ?>

