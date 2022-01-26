<?php include'../config.php'; include'../functions.php';

$odontogramaID = $_POST['odontogramaID'];
$pacienteID = $_POST['pacienteID'];

if($odontogramaID > 0 && $pacienteID > 0) {
    $odontoPaciente = $con->query("SELECT * FROM pacienteodontograma WHERE IDOdontograma = '$odontogramaID' AND pod_idPaciente = '$pacienteID'")->fetch_assoc();
}


if($odontoPaciente)
{
    echo "<img src='$odontoPaciente[pod_odontoImage]'>";
}
else {
?>

<table id="table-odontograma" style="text-align: center; margin: 0 auto; border-collapse: collapse;" border="0">
    <tr id="odontograma-fila1">
        <td style="border: solid white 5px;">
            <b>18</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="18" id="diente18" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>17</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="17" id="diente17" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>16</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="16" id="diente16" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>15</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="15" id="diente15" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>14</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="14" id="diente14" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>13</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="13" id="diente13" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>12</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="12" id="diente12" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>11</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="11" id="diente11" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>21</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="21" id="diente21" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>22</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="22" id="diente22" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>23</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="23" id="diente23" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>24</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="24" id="diente24" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>25</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="25" id="diente25" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>26</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="26" id="diente26" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>27</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="27" id="diente27" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>28</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="28" id="diente28" viewBox="0 0 142.57 203"></svg>
        </td>
    </tr>
    <tr id="odontograma-fila2">
        <td style="border: solid white 5px;"></td>
        <td style="border: solid white 5px;"></td>
        <td style="border: solid white 5px;"></td>
        <td style="border: solid white 5px;">
            <b>55</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="55" id="diente55" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>54</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="54" id="diente54" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>53</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="53" id="diente53" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>52</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="52" id="diente52" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>51</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="51" id="diente51" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>61</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="61" id="diente61" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>62</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="62" id="diente62" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>63</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="63" id="diente63" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>64</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="64" id="diente64" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>65</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="65" id="diente65" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;"></td>
        <td style="border: solid white 5px;"></td>
        <td style="border: solid white 5px;"></td>
    </tr>
    <tr id="odontograma-fila3">
        <td style="border: solid white 5px;"></td>
        <td style="border: solid white 5px;"></td>
        <td style="border: solid white 5px;"></td>
        <td style="border: solid white 5px;">
            <b>85</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="85" id="diente85" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>84</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="84" id="diente84" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>83</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="83" id="diente83" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>82</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="82" id="diente82" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>81</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="81" id="diente81" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>71</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="71" id="diente71" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>72</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="72" id="diente72" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>73</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="73" id="diente73" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>74</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="74" id="diente74" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>75</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="75" id="diente75" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;"></td>
        <td style="border: solid white 5px;"></td>
        <td style="border: solid white 5px;"></td>
    </tr>
    <tr id="odontograma-fila4">
        <td style="border: solid white 5px;">
            <b>48</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="48" id="diente48" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>47</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="47" id="diente47" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>46</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="46" id="diente46" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>45</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="45" id="diente45" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>44</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="44" id="diente44" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>43</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="43" id="diente43" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>42</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="42" id="diente42" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>41</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="41" id="diente41" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>31</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="31" id="diente31" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>32</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="32" id="diente32" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>33</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="33" id="diente33" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>34</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="34" id="diente34" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>35</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="35" id="diente35" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>36</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="36" id="diente36" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>37</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="37" id="diente37" viewBox="0 0 142.57 203"></svg>
        </td>
        <td style="border: solid white 5px;">
            <b>38</b>
            <svg fill="#fff" stroke="#3e3e3e" width="50" class="diente" diente="38" id="diente38" viewBox="0 0 142.57 203"></svg>
        </td>
    </tr>
</table>

<script type="text/javascript">
var dienteDefault = `
                        <path class="sector-default" onclick="cambiarConvencion(this)" d="M23.79,53.32c12.6-12.6,29.68-19.67,47.5-19.67c17.82,0,34.9,7.08,47.5,19.67c7.93-7.93,15.86-15.86,23.79-23.79C123.67,10.62,98.02,0,71.29,0C44.55,0,18.91,10.62,0,29.53C7.93,37.46,15.86,45.39,23.79,53.32z"
                            transform="translate(0.5 0.5)" />

                        <path class="sector-default" onclick="cambiarConvencion(this)" d="M47.54,77.06c6.3-6.3,14.84-9.84,23.75-9.84c8.91,0,17.45,3.54,23.75,9.84c7.92-7.92,15.83-15.83,23.75-23.75c-12.6-12.6-29.68-19.67-47.5-19.67c-17.82,0-34.9,7.08-47.5,19.67C31.7,61.23,39.62,69.15,47.54,77.06z"
                            transform="translate(0.5 0.5)" />

                        <path class="sector-default" onclick="cambiarConvencion(this)" d="M104.87,100.81c0-4.24-0.8-8.42-2.36-12.36c-1.69-4.27-4.23-8.14-7.48-11.39c-3.24-3.25-7.12-5.79-11.39-7.48c-3.94-1.56-8.13-2.36-12.36-2.36c-4.24,0-8.42,0.8-12.36,2.36c-4.27,1.69-8.14,4.23-11.39,7.48c-3.24,3.24-5.79,7.12-7.48,11.39c-1.56,3.94-2.36,8.13-2.36,12.36c0,4.24,0.8,8.42,2.36,12.36c1.69,4.27,4.23,8.14,7.48,11.39c3.24,3.25,7.12,5.79,11.39,7.48c3.94,1.56,8.13,2.36,12.36,2.36c4.24,0,8.42-0.8,12.36-2.36c4.27-1.69,8.14-4.23,11.39-7.48c3.25-3.24,5.79-7.12,7.48-11.39C104.07,109.24,104.87,105.05,104.87,100.81z"
                            transform="translate(0.5 0.5)" />

						<path class="sector-default" onclick="cambiarConvencion(this)" d="M23.79,148.31c12.6,12.6,29.68,19.67,47.5,19.67c17.82,0,34.9-7.08,47.5-19.67c-7.92-7.92-15.83-15.83-23.75-23.75c-6.3,6.3-14.84,9.84-23.75,9.84c-8.91,0-17.45-3.54-23.75-9.84C39.62,132.48,31.7,140.39,23.79,148.31z"
							transform="translate(0.5 0.5)" />

						<path class="sector-default" onclick="cambiarConvencion(this)" d="M118.78,148.31c-12.6,12.6-29.68,19.67-47.5,19.67c-17.82,0-34.9-7.08-47.5-19.67C15.86,156.24,7.93,164.17,0,172.1c18.91,18.91,44.55,29.53,71.29,29.53c26.74,0,52.38-10.62,71.29-29.53C134.64,164.17,126.71,156.24,118.78,148.31z"
							transform="translate(0.5 0.5)" />

						<path class="sector-default" onclick="cambiarConvencion(this)" d="M23.79,53.32C11.19,65.91,4.11,83,4.11,100.81c0,17.82,7.08,34.9,19.67,47.5c7.92-7.92,15.83-15.83,23.75-23.75c-6.3-6.3-9.84-14.84-9.84-23.75c0-8.91,3.54-17.45,9.84-23.75C39.62,69.15,31.7,61.23,23.79,53.32z"
							transform="translate(0.5 0.5)" />

						<path class="sector-default" onclick="cambiarConvencion(this)" d="M95.03,77.06c6.3,6.3,9.84,14.84,9.84,23.75c0,8.91-3.54,17.45-9.84,23.75c7.92,7.92,15.83,15.83,23.75,23.75c12.6-12.6,19.67-29.68,19.67-47.5c0-17.82-7.08-34.9-19.67-47.5C110.87,61.23,102.95,69.15,95.03,77.06z"
							transform="translate(0.5 0.5)" />
					`;


cargarDiente('18');
cargarDiente('17');
cargarDiente('16');
cargarDiente('15');
cargarDiente('14');
cargarDiente('13');
cargarDiente('12');
cargarDiente('11');

cargarDiente('21');
cargarDiente('22');
cargarDiente('23');
cargarDiente('24');
cargarDiente('25');
cargarDiente('26');
cargarDiente('27');
cargarDiente('28');

cargarDiente('55');
cargarDiente('54');
cargarDiente('53');
cargarDiente('52');
cargarDiente('51');

cargarDiente('61');
cargarDiente('62');
cargarDiente('63');
cargarDiente('64');
cargarDiente('65');

cargarDiente('85');
cargarDiente('84');
cargarDiente('83');
cargarDiente('82');
cargarDiente('81');

cargarDiente('71');
cargarDiente('72');
cargarDiente('73');
cargarDiente('74');
cargarDiente('75');

cargarDiente('48');
cargarDiente('47');
cargarDiente('46');
cargarDiente('45');
cargarDiente('44');
cargarDiente('43');
cargarDiente('42');
cargarDiente('41');

cargarDiente('31');
cargarDiente('32');
cargarDiente('33');
cargarDiente('34');
cargarDiente('35');
cargarDiente('36');
cargarDiente('37');
cargarDiente('38');

function cargarDiente(diente) {
    $('#diente' + diente).html(dienteDefault);
}

$('.diente').click(function() {
    var convencion = $('#convencion').val();
    if (convencion == 'dientedefault') {
        $(this).removeClass();
        $(this).addClass('diente')
        $(this).html(dienteDefault);
    }
});

function cambiarConvencion(e) {
    // var dienteafectado = '';
    // var convencion = '';
    $(e).removeClass();
    var convencion = $('#convencion').val();

    // dienteafectado = $(e).parent().attr("diente");

    if (convencion == 'dientedefault') {
        $(e).siblings().removeClass();
        $(e).siblings().addClass('sector-default');
        $(e).addClass('sector-default');
    }
    if (convencion == 'sectordefault') {
        $(e).addClass('sector-default');

        if (!$(e).siblings().hasClass('sector-caries'))
            $(e).parent().removeClass('diente-caries');

        if (!$(e).siblings().hasClass('sector-obturado'))
            $(e).parent().removeClass('diente-obturado');

    }
    if (convencion == 1) {
        $(e).parent().addClass('diente-caries');
        $(e).addClass('sector-default sector-caries');
    }
    if (convencion == 2) {
        $(e).parent().addClass('diente-obturado');
        $(e).addClass('sector-default sector-obturado');
    }
    if (convencion == 3) {
        $(e).parent().addClass('diente-ausente').html('');
        // .contents().unwrap().wrap('<div/>');
    }
    if (convencion == 4) {
        $(e).parent().addClass('diente-coronaBuenEstado').html('');
    }
    if (convencion == 5) {
        $(e).parent().addClass('diente-coronaMalEstado').html('');
    }
    if (convencion == 6) {
        $(e).parent().addClass('diente-edentulo').html('');
    }
    if (convencion == 7) {
        $(e).parent().addClass('diente-endodonciaBuenEstado').html('');
    }
    if (convencion == 8) {
        $(e).parent().addClass('diente-endodonciaNecesita').html('');
    }
    if (convencion == 9) {
        $(e).parent().addClass('diente-exodoncia').html('');
    }
    if (convencion == 10) {
        $(e).parent().addClass('diente-implante').html('');
    }
    if (convencion == 11) {
        $(e).parent().addClass('diente-necesitaSellante').html('');
    }
    if (convencion == 12) {
        $(e).parent().addClass('diente-obturadoConCaries').html('');
    }
    if (convencion == 13) {
        $(e).parent().addClass('diente-obturadoEnResina').html('');
    }
    if (convencion == 14) {
        $(e).parent().addClass('diente-protesisFijaTotal').html('');
    }
    if (convencion == 15) {
        $(e).parent().addClass('diente-protesisParcial').html('');
    }
    if (convencion == 16) {
        $(e).parent().addClass('diente-resinaPreventiva').html('');
    }
    if (convencion == 17) {
        $(e).parent().addClass('diente-sano').html('');
    }
    if (convencion == 18) {
        $(e).parent().addClass('diente-sellante').html('');
    }


    html2canvas(document.getElementById('table-odontograma')).then(function(canvas) {
		$('#imageOdontograma').val(canvas.toDataURL());
	});
}
</script>
<?php } ?>