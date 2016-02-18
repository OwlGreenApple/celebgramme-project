<!DOCTYPE html>
<html>
    <head>
      <title>Celebgramme</title>
      <meta name="csrf-token" content="{{ csrf_token() }}" />
      <link href="{{ asset('/css/main.css') }}" rel="stylesheet">
      <script type="text/javascript" src="{{ asset('/js/jquery-1.11.3.js') }}"></script>
      <script>
        $(document).ready(function(){
					// $("#button-submit").trigger("click");
        });
        
      </script>
    </head>
    <body>
		
		
		
<FORM NAME="order" METHOD="Post" ACTION="https://apps.myshortcart.com/payment/request-payment/" >
<input type=hidden name="BASKET" value="Gold,70000.00,1,70000.00;Administration fee,5000.00,1,5000.00">
<input type=hidden name="STOREID" value="00302583">
<input type=hidden name="TRANSIDMERCHANT" value="CLB001">
<input type=hidden name="AMOUNT" value="75000.00">
<input type=hidden name="URL" value="https://celebgramme.com/celebgramme/doku-page/verify">
<input type=hidden name="WORDS" value="{{sha1('75000.00'.'c5j7w5C7r7P6'.'CLB001')}}">
<input type=hidden name="CNAME" value="Ismail Danuarta">
<input type=hidden name="CEMAIL" value="ismail@gmail.com">
<input type=hidden name="CWPHONE" value="0210000011">
<input type=hidden name="CHPHONE" value="0210980901">
<input type=hidden name="CMPHONE" value="081298098090">

<input type=hidden name="CCAPHONE" value="02109808009">
<input type=hidden name="CADDRESS" value="Jl. Jendral Sudirman Plaza Asia Office Park Unit 3">
<input type=hidden name="CZIPCODE" value="12345">
<input type=hidden name="SADDRESS" value="Pengadegan Barat V no 17F”>
<input type=hidden name="SZIPCODE" value="12217">
<input type=hidden name="SCITY" value="JAKARTA">
<input type=hidden name="SSTATE" value="DKI">
<input type=hidden name="SCOUNTRY" value="784">
<input type=hidden name="BIRTHDATE" value="1988-06-16">
<input type="submit" id="button-submit">
</FORM>		
		
		
    </body>
</html>
