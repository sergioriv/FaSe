<?php include'config.php'; $doctorID = $_POST['id']; 
$doctorRow = $con->query("SELECT * FROM doctores WHERE IDDoctor = '$doctorID'")->fetch_assoc();
?>
	<link href="css/calendar.min.css" rel="stylesheet">

<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title">Nuevo horario Doctor: <?php echo $doctorRow['dc_nombres'] ?></h4>
</div>

	<div class="modal-body">	
		<div class="contenedorAgenda">
			<div class="agendaCalendario">
				<div class="calendar" data-color="normal"></div>
			</div>
			<div id="horasCalendario"></div>
		</div>

	</div>

	<div id="consultoriosModal" class="modal fade">  
      <div class="modal-dialog">  
        <div class="modal-content" id="consultoriosDetails"></div>  
      </div>  
 	</div>

	<?php include'footer.php'; ?>

	<script src="js/calendar.min.js"></script>
	<script>
	var yy;
	var calendarArray =[];
	var monthOffset = [6,7,8,9,10,11,0,1,2,3,4,5];
	var monthArray = [["ENE","Enero"],["FEB","Febrero"],["MAR","Marzo"],["ABR","Abril"],["MAY","Mayo"],["JUN","Junio"],["JUL","Julio"],["AGO","Agosto"],["SEP","Septiembre"],["OCT","Octubre"],["NOV","Noviembre"],["DIC","Diciembre"]];
	var letrasArray = ["D","L","M","M","J","V","S"];
	var dayArray = ["1","2","3","4","5","6","7"];
	$(document).ready(function() {
		//$(document).on('click','.calendar-day.have-events',activateDay);
		$(document).on('click','.specific-day',activatecalendar);
		$(document).on('click','.calendar-month-view-arrow',offsetcalendar);
		$(window).resize(calendarScale);
		
		calendarSet();
		calendarScale();

		
		$(document).on('click', '.diaSelected', function(){

			var diasActivos = document.querySelectorAll(".diaSelectedActive");
			for (var i = diasActivos.length - 1; i >= 0; i--) {
				diasActivos[i].classList.remove("diaSelectedActive");
			}

			$(this).addClass("diaSelectedActive");

			var doctorForm = <?php echo $doctorID ?>;			

			var consultoriosId = $(this).attr("id");
		    if(consultoriosId != '' && doctorForm != '')
		    {
		    	$.ajax({
		        	url:"doctor-horario-range.php",
		            method:"POST",  
		            data:{
		            	id:consultoriosId,
		            	doctor:doctorForm
		            },  
		            cache: false,
					success:function(data){  
						$('#horasCalendario').html(data);
					}
		    	});  
			}            
		});
	});

	</script>