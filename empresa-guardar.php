<?php include'config.php';

$nombre = $_POST['nombreEmp'];
$nit = $_POST['nitEmp'];
$codigo = $_POST['codigoEmp'];
$ciudadEmp = $_POST['ciudadEmp'];
$direccion = $_POST['direccionEmp'];
$telefono = $_POST['telefonoEmp'];
$web = $_POST['webEmp'];

$query = $con->query("UPDATE clinicas SET cl_nombre='$nombre', cl_nit='$nit', cl_codigo='$codigo', cl_idCiudad='$ciudadEmp', cl_direccion='$direccion', cl_telefono='$telefono', cl_web='$web' WHERE IDClinica = '$sessionClinica'");

$updateUser = $con->query("UPDATE usuarios SET us_nombre = '$nombre' WHERE us_idRol = 1 AND us_id = '$sessionClinica'");

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

if($query){ $_SESSION['consultoriosExito']=3; } else { $_SESSION['consultoriosExito']=1; }

header("Location:$_SESSION[concultoriosAntes]");
?>