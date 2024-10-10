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

$email = $data->email;
$password = $data->password;

// Consulta SQL
$sql = "SELECT * FROM usuarios WHERE correo = ? AND clave = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(array("success" => true, "message" => "Login exitoso"));
} else {
    echo json_encode(array("success" => false, "message" => "Credenciales inválidas"));
}

$stmt->close();
$conn->close();
?>
