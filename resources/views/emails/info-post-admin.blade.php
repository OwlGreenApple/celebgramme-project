instagram user : {{$setting_temp->insta_username}} <br>
Update yang dilakukan user : <br>
<?php 
	$strings =  explode("~", substr($post->description,12));
	foreach ($strings as $string){
			$pieces = explode("=", $string );	
			if (count($pieces)>1) {
				echo "<strong>".$pieces[0].": </strong> ".$pieces[1]."<br>";
			}
	}
?>