<?php
session_start();

if (!isset($_SESSION['admin']['adminnakalogin']) == true) {
    header("location:index.php");
}

include('conn.php');
include('includes/navbar.php');

$id_examen = isset($_GET['id']) ? $_GET['id'] : null;

if ($id_examen) {
    $stmt = $conn->prepare("SELECT * FROM examenes WHERE id_examen = :id_examen");
    $stmt->bindParam(':id_examen', $id_examen, PDO::PARAM_INT);
    $stmt->execute();

    $examen = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($examen) {
        $stmtMateria = $conn->prepare("SELECT nombre_materia FROM materias WHERE id_materia = :id_materia");
        $stmtMateria->bindParam(':id_materia', $examen['id_materia'], PDO::PARAM_INT);
        $stmtMateria->execute();
        $nombreMateria = $stmtMateria->fetchColumn();

        $stmtUnidad = $conn->prepare("SELECT nombre_unidad FROM unidades_tematicas WHERE id_unidad = :id_unidad");
        $stmtUnidad->bindParam(':id_unidad', $examen['id_unidad'], PDO::PARAM_INT);
        $stmtUnidad->execute();
        $nombreUnidad = $stmtUnidad->fetchColumn();

        $tipoExamen = $examen['tipo_examen'];

        // Obtener preguntas
        $pregunta1 = $examen['pregunta1'];
        $pregunta2 = $examen['pregunta2'];
        $pregunta3 = $examen['pregunta3'];

        // Crear un array de preguntas
        $preguntas = array($pregunta1, $pregunta2, $pregunta3);
    } else {
        echo "Examen no encontrado";
        exit;
    }
} else {
    echo "ID de examen no proporcionado";
    exit;
}

// Procesar el formulario para agregar preguntas
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Tu lógica para agregar preguntas
    // ...
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información del Examen</title>
</head>

<body>

    <div class="page-wrapper">
        <div class="page-body">
            <div class="container-xl">
                <div class="row justify-content-center align-items-center g-2">
                    <div class="col-4">
                        <div class="card">
                            <fieldset class="form-fieldset m-3">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="formId1" id="formId1"
                                        placeholder="" value="<?php echo $nombreMateria; ?>" readonly>
                                    <label for="formId1">Nombre de la Materia</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="formId2" id="formId2"
                                        placeholder="" value="<?php echo $nombreUnidad; ?>" readonly>
                                    <label for="formId2">Nombre de la Unidad</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="formId3" id="formId3"
                                        placeholder="" value="<?php echo $tipoExamen; ?>" readonly>
                                    <label for="formId3">Tipo de Examen</label>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="col-1"></div>
                    <div class="col-7">
                        <div class="card">
                            <fieldset class="form-fieldset m-3">
                                <div class="mb-3">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modalAgregarPreguntas">Agregar Preguntas</button>
                                </div>
                                <?php foreach ($preguntas as $index => $pregunta) : ?>
                                    <div class="mb-3">
                                        <div class="form-floating">
                                            <textarea class="form-control" placeholder="Pregunta <?php echo $index + 1; ?>"
                                                name="pregunta<?php echo $index + 1; ?>" readonly><?php echo $pregunta; ?></textarea>
                                            <label for="pregunta<?php echo $index + 1; ?>">Pregunta <?php echo $index + 1; ?></label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                <button type="submit" class="btn btn-primary">Guardar Preguntas</button>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar preguntas -->
    <div class="modal fade" id="modalAgregarPreguntas" tabindex="-1" aria-labelledby="modalAgregarPreguntasLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarPreguntasLabel">Agregar Preguntas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="preguntaNueva1" class="form-label">Pregunta 1</label>
                            <textarea class="form-control" id="preguntaNueva1" name="preguntaNueva1"
                                placeholder="Ingrese la pregunta 1"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="preguntaNueva2" class="form-label">Pregunta 2</label>
                            <textarea class="form-control" id="preguntaNueva2" name="preguntaNueva2"
                                placeholder="Ingrese la pregunta 2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="preguntaNueva3" class="form-label">Pregunta 3</label>
                            <textarea class="form-control" id="preguntaNueva3" name="preguntaNueva3"
                                placeholder="Ingrese la pregunta 3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Agregar Preguntas</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
