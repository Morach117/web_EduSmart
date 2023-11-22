<?php
session_start(); // inicia la sesion

if (!isset($_SESSION['admin']['adminnakalogin']) == true) {
    header("location:index.php"); // si el usuario no inicio sesion, lo redirige a la pagina de login
}

include('conn.php'); // Incluir archivo de conexión a la base de datos
include('includes/navbar.php'); // Incluir archivo de barra de navegación

$id = isset($_GET['id']) ? $_GET['id'] : null;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Contenido</title>
</head>

<body>

    <div class="page-wrapper">
        <div class="page-body">
            <div class="cotainer-xl">
                <div class="container">
                    <div class="card">
                        <div class="card-body">
                            <fieldset class="form-fieldset">
                                <div class="col">
                                    <h2 class="page-title">
                                        Modulo para la gestión de temas (
                                        <?php
                                        // Realiza la consulta para obtener el nombre de la unidad con PDO
                                        $sqlUnidad = "SELECT nombre_unidad FROM unidades_tematicas WHERE id_unidad = :unidad_id";
                                        $stmtUnidad = $conn->prepare($sqlUnidad);
                                        $stmtUnidad->bindParam(':unidad_id', $id, PDO::PARAM_INT);
                                        $stmtUnidad->execute();

                                        // Maneja el resultado de la consulta con PDO
                                        if ($stmtUnidad) {
                                            $unidadRow = $stmtUnidad->fetch(PDO::FETCH_ASSOC);
                                            $nombreUnidad = $unidadRow['nombre_unidad'];

                                            // Muestra el nombre de la unidad
                                            echo "$nombreUnidad";
                                        } else {
                                            // Manejar el error si la consulta no tiene éxito
                                            echo "Error al obtener la unidad";
                                        }
                                        ?>
                                        )
                                    </h2>
                                    <h2 class="page-pretitle">
                                    </h2>
                                    <hr class="m-0" />
                                    <div class="row g-2">
                                        <div class="container my-3 card p-3 col">
                                            <div class="row">
                                                <h2 class="page-title col-8">
                                                    Temas actuales
                                                </h2>
                                                <div class="col-4">
                                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#modalAgregarTema">
                                                        Agregar Tema
                                                    </button>
                                                    <a href="direcciones.php?page=unidades" class="btn btn-secondary">
                            Regresar
                        </a>
                                                </div>
                                            </div>
                                            <hr class="m-3" />
                                            <div class="table-responsive">
                                                <table id="temas-table"
                                                    class="table table-striped table-hover text-center"
                                                    style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Tema</th>
                                                            <th scope="col">Acción</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $selTema = $conn->query("SELECT * FROM tema WHERE id_unidad = $id");
                                                        if ($selTema->rowCount() > 0) {
                                                            while ($selTemadRow = $selTema->fetch(PDO::FETCH_ASSOC)) {
                                                                ?>
                                                                <tr>
                                                                    <td>
                                                                        <?php echo $selTemadRow['nombre'] ?>
                                                                    </td>
                                                                    <td>
                                                                        <a href="subtema.php?id=<?php echo $selTemadRow['id_tema'] ?>"
                                                                            class="btn btn-primary">Agregar subtema</a>

                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                        } else {
                                                            echo "<tr><td colspan='3'>No hay temas registrados.</td></tr>";
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Body-->
    <div class="modal fade" id="modalAgregarTema" tabindex="-1" role="dialog" aria-labelledby="modalTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">Agregar Nuevo Tema</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-tema" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="unidad" class="form-label">Unidad:</label>
                        <select class="form-select" id="unidad" name="unidad" required>
                            <!-- Agrega una opción seleccionada automáticamente -->
                            <option value="<?php echo $id; ?>" selected><?php
                                // Realiza la consulta para obtener el nombre de la unidad con PDO
                                $sqlUnidad = "SELECT nombre_unidad FROM unidades_tematicas WHERE id_unidad = :unidad_id";
                                $stmtUnidad = $conn->prepare($sqlUnidad);
                                $stmtUnidad->bindParam(':unidad_id', $id, PDO::PARAM_INT);
                                $stmtUnidad->execute();

                                // Muestra el nombre de la unidad si la consulta fue exitosa
                                if ($stmtUnidad) {
                                    $unidadRow = $stmtUnidad->fetch(PDO::FETCH_ASSOC);
                                    echo $unidadRow['nombre_unidad'];
                                } else {
                                    echo "Error al obtener la unidad";
                                }
                                ?>
                            </option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nombre_tema" class="form-label">Nombre del Tema:</label>
                        <input type="text" class="form-control" id="nombre_tema" name="nombre_tema" required>
                    </div>
                    <input type="hidden" name="operacion" id="operacion">
                    <input type="hidden" name="id_tema" id="id_tema">
                    <div class="text-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="btnGuardarTema">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>





    <script>
        $(document).ready(function () {
            $('#temas-table').DataTable();
        });
        $(document).ready(function () {
            $('#subtemas-table').DataTable();
        });
        $(document).ready(function () {
            $('#contenido-table').DataTable();
        });
    </script>

    <!-- Agrega este script al final del cuerpo de tu archivo HTML -->
    <script>
    $(document).ready(function () {
        // Función para manejar el evento de clic en el botón Guardar
        $("#btnGuardarTema").click(function () {
            // Obtener los valores del formulario
            var unidad = $("#unidad").val();
            var nombre_tema = $("#nombre_tema").val();

            // Validar que los campos no estén vacíos
            if (unidad !== '' && nombre_tema !== '') {
                // Realizar la solicitud Ajax
                $.ajax({
                    url: "query/unidad/tema/agregar_tema.php", // Reemplaza con la ruta correcta
                    method: "POST",
                    data: {
                        unidad: unidad,
                        nombre_tema: nombre_tema
                    },
                    success: function (data) {
                        // Muestra un mensaje de éxito con SweetAlert
                        Swal.fire({
                            icon: 'success',
                            title: 'Tema agregado exitosamente',
                        }).then((result) => {
                            // Cierra el modal después de guardar
                            $('#modalAgregarTema').modal('hide');
                            // Recargar la página para actualizar la tabla
                            location.reload(true);
                        });
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                        // Muestra un mensaje de error con SweetAlert
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al agregar el tema',
                            text: 'Por favor, inténtalo de nuevo',
                        });
                    }
                });
            } else {
                // Muestra un mensaje de advertencia si algún campo está vacío
                Swal.fire({
                    icon: 'warning',
                    title: 'Completa todos los campos',
                });
            }
        });
    });
</script>


</body>

</html>