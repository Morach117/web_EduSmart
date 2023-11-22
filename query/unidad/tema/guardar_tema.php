<?php
// guardar_tema.php

include('../../../conn.php'); // Incluir archivo de conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idTema = $_POST["id_tema"];
    $nombreTema = $_POST["nombre_tema"];

    // Realiza la inserción en la base de datos
    $sql = "INSERT INTO subtemas (id_tema, nombre) VALUES (:id_tema, :nombre_tema)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_tema', $idTema, PDO::PARAM_INT);
    $stmt->bindParam(':nombre_tema', $nombreTema, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo "Tema guardado exitosamente.";
    } else {
        echo "Error al guardar el tema.";
    }
} else {
    echo "Acceso no permitido.";
}
?>
