<?php include 'config.php';

if (isset($_FILES["filePhoto"]))
	{
		$file = $_FILES["filePhoto"];
		$nombreFoto = $file["name"];
		
		if($nombreFoto!=""){
		
			$tipo = $file["type"];
			$ruta_provisional = $file["tmp_name"];
			$size = $file["size"];
			$dimensiones = getimagesize($ruta_provisional);
			$width = $dimensiones[0];
			$height = $dimensiones[1];
			$carpeta = "img-users/";
			$inicial = 'C';
			
			if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png')
			{
			  echo '<div>no es una imagen</div>';
		  	  exit();
			}
			  else if ($size > 1024*2048)
			{
			  echo '<div>excede 2MB</div>';
		  	  exit();
			}
		/*   else if ($width > 400 || $height > 80)
			{
				echo '<div class="alert alert-danger" role="alert"><strong>Error!</strong> La imágen supera los 80px de alto ó los 400px de ancho permitidos.</div>';
			}
			else if($width < 60 || $height < 60)
			{
				echo "Error la anchura y la altura mínima permitida es 60px";
			}
		*/  else
			{ 
				$tipo_subir = str_replace('image/', '.', $tipo);
				$src = $carpeta.$inicial.$sessionClinica.".jpg";
				move_uploaded_file($ruta_provisional, $src);
				mysqli_query($con,"UPDATE clinicas SET cl_logo='$src' WHERE IDClinica = '$sessionClinica'");
			}
		}
	}

header("location:pasarela-sucursales")
?>