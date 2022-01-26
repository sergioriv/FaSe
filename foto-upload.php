<?php  include'config.php';

$pacienteID = $_POST['pacienteID'];
$titulo = $_POST['titulo'];
$descripcion = nl2br(trim($_POST['descripcion']));


 $output = '';  
 if(is_array($_FILES))  
 {  
      foreach($_FILES['images']['name'] as $name => $value)  
      {  
           $file_name = explode(".", $_FILES['images']['name'][$name]);  
           $allowed_extension = array("jpg", "jpeg", "png", "bmp");  
           if(in_array($file_name[1], $allowed_extension))
           {
              $queryFotos = $con->query("INSERT INTO pacientefotos SET pf_idUsuario='$sessionUsuario', pf_idPaciente='$pacienteID', pf_titulo='$titulo', pf_descripcion='$descripcion', pf_fechaCreacion='$fechaHoy'");
              $idQuery = $con->insert_id;
              $foto = 'P'.$pacienteID.'_'.$idQuery.'.jpg';
              $carpeta = 'foto-registro/';
              $updateFoto = $con->query("UPDATE pacientefotos SET pf_foto='$carpeta$foto' WHERE IDPacFoto = '$idQuery'");


                $new_name = $foto;  
                $sourcePath = $_FILES["images"]["tmp_name"][$name];  
                $targetPath = "foto-registro/".$new_name;  
                move_uploaded_file($sourcePath, $targetPath);  
           }  
      }  
 
      $output .= '
           <div class="contenedor-galeria">
            <div class="" id="slider-thumbs">
                <!-- Bottom switcher of slider -->
                <div class="hide-bullets">';
                $imagenesSql = $con->query("SELECT * FROM pacientefotos WHERE pf_idPaciente = '$pacienteID' ORDER BY IDPacFoto DESC");
                  while($imagenesRow = $imagenesSql->fetch_assoc()){                
      $output .='   <div class="miniatura">
                      <img src="'.$imagenesRow['pf_foto'].'">
                      <div class="optionImage">
                        <div title="Ver" class="ver-carousel-selector" data-img="'.$imagenesRow['IDPacFoto'].'"><i class="fa fa-eye"></i></div>
                        <div title="Eliminar" id="'.$imagenesRow['IDPacFoto'].'" t="pacientefotos" pc="'.$pacienteID.'" class="consultorioEliminarFoto eliminar">'.$iconoEliminar.'</div>
                      </div>
                    </div>';
                  }
      $output .='</div>
            </div>
            <div id="registro-fotografico" class="carousel slide carousel-fade" data-ride="carousel">
									<a class="close-fullscreen" onclick="closeFullscreen();" data-dismiss="modal">&times;</a>
									<!-- Indicators -->
								<!--    <ol class="carousel-indicators">';
										$imagenesSql = $con->query("SELECT * FROM pacientefotos WHERE pf_idPaciente = '$pacienteID' ORDER BY IDPacFoto DESC");
										$i=0;
											while($imagenesRow = $imagenesSql->fetch_assoc()){
			$output .='			<li data-target="#registro-fotografico" data-slide-to="'.$i.'"></li>';
										$i++; }
      $output .='				</ol>-->

									<!-- Wrapper for slides -->
									<div class="carousel-inner">';
										$imagenesSql = $con->query("SELECT * FROM pacientefotos WHERE pf_idPaciente = '$pacienteID' ORDER BY IDPacFoto DESC");
											while($imagenesRow = $imagenesSql->fetch_assoc()){
			$output .='			<div class="item" id="carousel-selector-'.$imagenesRow['IDPacFoto'].'">
												<img src="'.$imagenesRow['pf_foto'].'" alt="Error al cargar">
												<div class="carousel-caption">
													<h3>'.$imagenesRow['pf_titulo'].'</h3>
													<p>'.$imagenesRow['pf_descripcion'].'</p>
												</div>
											</div>';
										}

      $output .='	</div>

									<!-- Left and right controls -->
									<a class="left carousel-control" href="#registro-fotografico" data-slide="prev">
										<i class="fa fa-angle-left"></i>
									</a>
									<a class="right carousel-control" href="#registro-fotografico" data-slide="next">
										<i class="fa fa-angle-right"></i>
									</a>
								</div>
					        </div>
        </div>';
?>
        <script type="text/javascript">
            var carousel = document.getElementById("registro-fotografico");
            $(carousel).carousel({
              interval: 0
            })
            

              $(document).on('click', '.ver-carousel-selector', function(){  
                  var idRegFoto = $(this).attr("data-img");
                  $('.active').removeClass('active');
                  $('#carousel-selector-'+idRegFoto).addClass('active');
                  openFullscreen();
                      
              });
        </script>
        <script type="text/javascript">
          $("#consultoriosModal").modal('hide');
        </script>

<?php

      echo $output;  
 }  
 ?>