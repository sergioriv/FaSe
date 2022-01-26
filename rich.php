<!DOCTYPE html>
<html lang="es">

<head>
    <?php include'header.php';
			include'footer.php'; ?>
    <script src="js/jquery.richtext.min.js"></script>
    <link rel="stylesheet" href="css/richtext.min.css">
</head>

<body>

    <div class="contenedorPrincipal">


        <input type="text" id="RichText">

        <button id="enviar">Guardar</button>

        <div id="ver"></div>

        <script>
        $(document).ready(function() {
            $('#RichText').richText();
            $('#enviar').click(function() {
                console.log($('#RichText').val());
                $('#ver').html($('#RichText').val());
            });
        })
        </script>
    </div>

</body>

</html>