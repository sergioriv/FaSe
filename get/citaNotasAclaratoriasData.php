<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $citaID = $_SESSION['FaSe_editID'];
    
    //get number of rows
    $aclaratoriasQuery = "SELECT usuarios.us_nombre, notaaclaratoria.* FROM notaaclaratoria INNER JOIN usuarios ON notaaclaratoria.na_idUsuario = usuarios.IDUsuario WHERE na_idCita = '$citaID' ORDER BY IDNotaAclaratoria DESC ";

    $rowCountCitaNotas = $con->query($aclaratoriasQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCountCitaNotas,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationCitaNotasAclaratorias'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $aclaratoriasSql = $con->query($aclaratoriasQuery." LIMIT $numeroResultados");
    
?>
                                <table class="tableList">
                                    <thead>
                                        <tr>
                                            <th class="columnaCorta">Fecha</th>
                                            <th>Usuario</th>
                                            <th>Nota</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($aclaratoriasRow = $aclaratoriasSql->fetch_assoc()) { ?>
                                                <tr>
                                                    <td><?= $aclaratoriasRow['na_fechaCreacion'] ?></td>
                                                    <td><?= $aclaratoriasRow['us_nombre'] ?></td>
                                                    <td><?= $aclaratoriasRow['na_nota'] ?></td>
                                                </tr>
                                        <?php
                                            }
                                        ?>
                                    </tbody>
                                </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>