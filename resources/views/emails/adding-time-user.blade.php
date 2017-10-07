Hi , {{ucfirst(strtolower($user->fullname))}} <br>
<br>
Terima kasih anda sudah membeli service Celebgramme.com <br>
<br>
Anda sudah tercatat sebagai Customer Celebgramme sebelumnya,<br>
Kredit Waktu yang dibeli akan ditambahkan langsung ke akun anda<br>
<br>
Berikut ini adalah Email yang anda daftarkan : <br>
<strong>Email :</strong> {{$user->email}}<br>

<br>
<strong>Link to login </strong><a href="https://celebgramme.com/celebgramme">-----> Click Link Login Disini <----- </a><br>
<br>
Silahkan klik Link di atas untuk Login <br>
dan masukkan Email & Password anda seperti biasa. <br>
<br> 
<strong>PS:</strong> Apabila anda Lupa Password anda, silahkan Click "Forgot Password" di halaman Login<br>
<br>

<?php 
if ($isi_form_kaos) {
		echo "Silahkan isi form untuk pemesanan kaos dengan mengklik link ini <a href='https://docs.google.com/forms/d/1M-IK4qcHv_0fobiwjaPQsXsnCZxjquZhgvTf4Bdgxlo/edit?usp=sharing' target='_blank'> >>FORM<< </a> <br>";
}
?>

Salam sukses selalu, <br>
<br>
Michael Sugiharto<br>
Celebgramme.com
