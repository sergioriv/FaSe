<?php include'config.php'; include'pagination-modal-params.php'; include'encrypt.php';

$egresosQuery = "SELECT * FROM ordenesabonos AS pra
			INNER JOIN usuarios AS us ON pra.pra_idUsuario = us.IDUsuario
			INNER JOIN ordenesentrada AS ore ON pra.pra_idOrden = ore.IDOrdenEntrada
			INNER JOIN proveedores AS pr ON ore.ore_idProveedor = pr.IDProveedor
			WHERE pra_idClinica = '$sessionClinica'
			ORDER BY IDOrdenAbono DESC";
//sucursales.sc_nombre ASC,
$rowCount = $con->query($egresosQuery)->num_rows;
	$pagConfig = array(
		'totalRows' => $rowCount,
	    'perPage' => $numeroResultados,
		'link_func' => 'paginationEgresos'
	);
    $pagination =  new Pagination($pagConfig);

$egresosSql = $con->query($egresosQuery." LIMIT $numeroResultados");
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<?php include'header.php';
			include'footer.php'; ?>
</head>
<body>
	<div class="contenedorPrincipal">

		<div id="msj-factura" class="contenedorAlerta"><?php include'mensajes.php'; ?></div>

		<div class="tituloBuscador">
			<div class="titulo tituloSecundario">Comprobantes de Egreso<a href="excel-comprobantes-egreso.php"><i class="fa fa-download"></i>Descargar reporte</a></div>
			<span>
				<i class="fa fa-search" aria-hidden="true"></i>
				<input type="text" id="searchComprobantesEgreso" list="abonos" class="buscador" placeholder="Buscar . . ." onkeyup="paginationEgresos();">
			</span>
		</div>

		<div id="showResults">
			<table class="tableList">
				<thead>
					<tr>
						<th class="estado">&nbsp</th>
						<th>#</th>
						<th class="columnaCorta">Fecha</th>
						<th>Usuario</th>
						<th>Proveedor</th>
						<th># Factura</th>
						<th>Forma Pago</th>
						<th align="right">Valor</th>
						<th>&nbsp</th>
					</tr>
				</thead>
				<tbody>
					<?php while($egresosRow = $egresosSql->fetch_assoc()){

							$nombreUsuarioEgreso = '';
					    	$IDusuarioEgreso = $egresosRow['us_id'];
					    	if($egresosRow['us_idRol']==1){
					   			$usuarioEgreso = $con->query("SELECT * FROM clinicas WHERE IDClinica='$IDusuarioEgreso'")
					   			->fetch_assoc();
					   			$nombreUsuarioEgreso = $usuarioEgreso['cl_nombre'];
					    	} elseif($egresosRow['us_idRol']==2){
					   			$usuarioEgreso = $con->query("SELECT * FROM sucursales WHERE IDSucursal='$IDusuarioEgreso'")
					   			->fetch_assoc();
					   			$nombreUsuarioEgreso = $usuarioEgreso['sc_nombre'];
					    	} elseif($egresosRow['us_idRol']==3){
					   			$usuarioEgreso = $con->query("SELECT * FROM doctores WHERE IDDoctor='$IDusuarioEgreso'")
					   			->fetch_assoc();
					   			$nombreUsuarioEgreso = $usuarioEgreso['dc_nombres'];
					    	}

					    	if($egresosRow['pra_estado']==1){
					    		$estadoEgreso = 'estadoNeutro';
					    	} else {
					    		$estadoEgreso = 'estadoCancelado';
					    	}

					    	if($egresosRow['us_estado']==0){ $iUS = $iconW; $cUS = 'elementoEliminado';
							} else { $iUS = ''; $cUS = ''; }

							$formaPago = $con->query("SELECT * FROM fomaspago WHERE IDFormaPago = '$egresosRow[pra_idFormaPago]'")->fetch_assoc();

						        	if($egresosRow['pra_idFormaPago']==2){
										$bancoEgreso = $con->query("SELECT * FROM bancos WHERE IDBanco = '$egresosRow[pra_idBanco]'")->fetch_assoc();
										$egresoFormaPago = $formaPago['fp_nombre'].'<br>'.$bancoEgreso['bnc_codigo'].' | '.$egresosRow['pra_cheque'];
									} else {
										$egresoFormaPago = $formaPago['fp_nombre'];
									}
					?>
					<tr>
					    <td class="estado <?php echo $estadoEgreso ?>"></td>
					    <td align="right"><?php echo $egresosRow['pra_consecutivo'] ?></td>
					    <td align="center"><?php echo $egresosRow['pra_fechaCreacion'] ?></td>
					    <td class="<?php echo $cUS ?>"><?php echo $iUS.$nombreUsuarioEgreso ?></td>
					    <td><?php echo $egresosRow['pr_nombre'] ?></td>
					    <td align="center"><?php echo $egresosRow['ore_numeroFactura'] ?></td>
					    <td align="center"><?= $egresoFormaPago ?></td>
					    <td align="right"><?php echo '$'.number_format($egresosRow['pra_abono'], 0, ".", ","); ?></td>
					    <td class="tableOption">
					    	<a title="Descargar" href="orden-abono-pdf.php?q=<?= encrypt( 'id='.$egresosRow['IDOrdenAbono'] ) ?>"><i class="fa fa-download"></i></a>
                            <?php if($egresosRow['pra_estado']==1){ ?>
                                <a title="Editar" class="consultorioEgresoEditar" id="<?php echo $egresosRow['IDOrdenAbono'] ?>"><?php echo $iconoEditar ?></a>
                                <a title="Anular" class="anularEgreso eliminar" id="<?php echo $egresosRow['IDOrdenAbono'] ?>"><?php echo $iconoEliminar ?></a>
                            <?php } ?>
						   </td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php echo $pagination->createLinks(); ?>
		</div>
	</div>

	<div id="consultoriosModal" class="modal fade">  
      <div class="modal-dialog">  
        <div class="modal-content" id="consultoriosDetails"></div>  
      </div>  
 	</div>

	<script type="text/javascript">
		function paginationEgresos(page_num) {
		    page_num = page_num?page_num:0;
		    var busqueda = $('#searchComprobantesEgreso').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/comprobantesEgresoData.php',
		        data:'page='+page_num+'&busqueda='+busqueda,
		        success: function (html) {
		            $('#showResults').html(html);
		        }
		    });
		}

		$(document).on('click', '.consultorioEgresoEditar', function(){
			var consultoriosId = $(this).attr("id");
			if(consultoriosId != '')
			{
			   	$.ajax({
			       	url:"orden-abono-factura.php",  
			        method:"POST", 
			        data:{egresoID:consultoriosId},
			        success:function(data){  
						$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show'); 
					}
			    });
			}
		});
		$(document).on('click', '.anularEgreso', function(){  
			var consultoriosId = $(this).attr("id");
		    if(consultoriosId != '')
		    {  
		    	$.ajax({
		        	url:"orden-abono-eliminar.php",  
		            method:"POST",  
		            data:{id:consultoriosId},
		            success:function(data){  
						$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show'); 
					}
		    	});  
			}            
		});
	</script>
</body>
</html>