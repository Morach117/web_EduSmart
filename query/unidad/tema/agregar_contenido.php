<?php
include '../../../conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_subtema = $_POST["id_subtema"];
    $titulo = $_POST["titulo"];
    $descripcion = $_POST["descripcion"];
    $tipo = $_POST["tipo"];

    $archivo = null;

    if ($tipo === "video") {
        // Maneja el enlace de video
        $archivo = isset($_POST["enlace"]) ? $_POST["enlace"] : null;
    } else {
        // Maneja el archivo si se subió
        if (isset($_FILES["nombre_archivo"])) {
            $archivo_temp = $_FILES["nombre_archivo"]["tmp_name"];

            // Verifica si se subió el archivo correctamente
            if ($_FILES["nombre_archivo"]["error"] == 0) {
                $archivo_nombre = $_FILES["nombre_archivo"]["name"];
                $ruta_archivo = "../../../multimedia/" . $archivo_nombre;

                // Añade mensajes de depuración
                echo "Ruta del archivo temporal: $archivo_temp<br>";
                echo "Ruta del archivo destino: $ruta_archivo<br>";

                // Verifica si el archivo ya existe
                if (file_exists($ruta_archivo)) {
                    echo "Error: El archivo ya existe.<br>";
                    exit();
                }

                // Intenta mover el archivo
                if (move_uploaded_file($archivo_temp, $ruta_archivo)) {
                    $archivo = $archivo_nombre;
                    echo "Archivo movido correctamente.<br>";
                } else {
                    echo "Error al mover el archivo.<br>";
                    exit();
                }
            } else {
                // Maneja el error si no se subió el archivo correctamente
                echo "Error al subir el archivo. Código de error: " . $_FILES["nombre_archivo"]["error"] . "<br>";
                exit();
            }
        } else {
            // Maneja el error si no se recibió el archivo
            echo "Error: No se recibió el archivo.<br>";
            exit();
        }
    }

    // Resto del código de inserción en la base de datos

    $sql = "INSERT INTO contenido_tematico (id_subtema, titulo, descripcion, nombre_archivo, tipo)
            VALUES (:id_subtema, :titulo, :descripcion, :archivo, :tipo)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_subtema', $id_subtema, PDO::PARAM_INT);
    $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
    $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
    $stmt->bindParam(':archivo', $archivo, PDO::PARAM_STR);
    $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo "Contenido guardado exitosamente.<br>";
    } else {
        echo "Error al guardar el contenido. Detalles: " . print_r($stmt->errorInfo(), true) . "<br>";
    }
} else {
    echo "Acceso no permitido.<br>";
}
?>
