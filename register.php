<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Conexión a la base de datos
$servername = "localhost";
$username = "tu_usuario";
$password = "tu_contraseña";
$dbname = "citas_DB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener datos del cuerpo de la solicitud
$data = json_decode(file_get_contents("php://input"));

$nombre = $data->nombre;
$correo = $data->correo;
$clave = $data->clave;

// Verificar si el correo ya existe
$check_sql = "SELECT * FROM usuarios WHERE correo = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("s", $correo);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    echo json_encode(array("success" => false, "message" => "El correo ya está registrado"));
} else {
    // Insertar nuevo usuario
    $insert_sql = "INSERT INTO usuarios (nombre, correo, clave) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("sss", $nombre, $correo, $clave);

    if ($insert_stmt->execute()) {
        echo json_encode(array("success" => true, "message" => "Usuario registrado exitosamente"));
    } else {
        echo json_encode(array("success" => false, "message" => "Error al registrar usuario"));
    }
}

$check_stmt->close();
$insert_stmt->close();
$conn->close();
?>
