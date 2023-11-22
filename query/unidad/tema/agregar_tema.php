<?php
include('../../../conn.php'); // Incluir archivo de conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $unidad = $_POST["unidad"];
    $nombre_tema = $_POST["nombre_tema"];

    try {
        // Preparar la consulta SQL
        $sql = "INSERT INTO tema (id_unidad, nombre) VALUES (:unidad, :nombre_tema)";
        $stmt = $conn->prepare($sql);

        // Vincular parámetros
        $stmt->bindParam(':unidad', $unidad, PDO::PARAM_INT);
        $stmt->bindParam(':nombre_tema', $nombre_tema, PDO::PARAM_STR);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo "Tema agregado con éxito";
        } else {
            echo "Error al agregar el tema";
        }
    } catch (PDOException $e) {
        echo "Error de base de datos: " . $e->getMessage();
    }
} else {
    echo "Acceso no permitido";
}
?>
