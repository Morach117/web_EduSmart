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
                                        Modulo para la gestión de contenido
                                    </h2>
                                    <h2 class="page-pretitle">
                                    </h2>
                                    <hr class="m-0" />
                                    <div class="row g-2">
                                        <div class="card container my- card p-3">
                                            <div class="row p-2">
                                                <h2 class="page-title col-6">
                                                    Contenidos actuales
                                                </h2>

                                                <div class="col-5 text-end">
                                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#modalContenido">
                                                        Agregar Contenido
                                                    </button>
                                                </div>
                                                <div class="col"></div>
                                            </div>
                                            <hr class="m-1" />
                                            <div class="table-responsive">
                                                <table id="contenido-table"
                                                    class="table table-striped table-hover text-center"
                                                    style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Titulo</th>
                                                            <th scope="col">Descripcion</th>
                                                            <th scope="col">Archivo</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $selContenido = $conn->query("SELECT * FROM contenido_tematico WHERE id_subtema = $id");
                                                        if ($selContenido->rowCount() > 0) {
                                                            while ($selContenidoRow = $selContenido->fetch(PDO::FETCH_ASSOC)) {
                                                                ?>
                                                                <tr>
                                                                    <td>
                                                                        <?php echo $selContenidoRow['titulo'] ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo $selContenidoRow['descripcion'] ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo $selContenidoRow['nombre_archivo'] ?>
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
<div class="modal fade " id="modalContenido" tabindex="-1" role="dialog" aria-labelledby="modalTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">Agregar Contenido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form id="form-contenido" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                    <input type="hidden" id="id_subtema" name="id_subtema" value="<?php echo $id; ?>">

                        <label for="titulo" class="form-label">Título:</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción:</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo:</label>
                        <select class="form-select" id="tipo" name="tipo" required>
                            <option value="infografia">Infografía</option>
                            <option value="video">Video</option>
                        </select>
                    </div>
                    <div class="mb-3" id="archivoInput">
                        <label for="nombre_archivo" class="form-label">Archivo:</label>
                        <input type="file" class="form-control" id="nombre_archivo" name="nombre_archivo" accept=".pdf, .png, .jpg, .jpeg, .doc, .docx" required>
                    </div>
                    <div class="mb-3" id="linkInput" style="display: none;">
                        <label for="enlace" class="form-label">Enlace de Video:</label>
                        <input type="text" class="form-control" id="enlace" name="enlace">
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="btnGuardarContenido">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    // Manejo del cambio en el campo de tipo
    $("#tipo").on("change", function () {
        var selectedTipo = $(this).val();
        if (selectedTipo === "video") {
            $("#archivoInput").hide();
            $("#linkInput").show();
        } else {
            $("#archivoInput").show();
            $("#linkInput").hide();
        }
    });

    // Manejo del clic en el botón Guardar
    $("#btnGuardarContenido").on("click", function () {
        // Obtén los valores del formulario
        var id_subtema = $("#id_subtema").val();
        var titulo = $("#titulo").val();
        var descripcion = $("#descripcion").val();
        var tipo = $("#tipo").val();
        var archivo = tipo === "video" ? $("#enlace").val() : $("#nombre_archivo")[0].files[0];

        // Crea un objeto FormData para manejar correctamente el envío de archivos
        var formData = new FormData();
        formData.append("id_subtema", id_subtema);
        formData.append("titulo", titulo);
        formData.append("descripcion", descripcion);
        formData.append("tipo", tipo);
        formData.append("archivo", archivo);

        // Realiza la petición Ajax
        $.ajax({
            url: "query/unidad/tema/agregar_contenido.php",
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                console.log(response);
                $("#modalContenido").modal("hide");
                // Recargar la tabla después de guardar para reflejar los cambios
            },
            error: function (xhr, textStatus, errorThrown) {
                console.error("Error al guardar el contenido:", textStatus, errorThrown);
            }
        });
    });
});
</script>






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
</body>

</html>