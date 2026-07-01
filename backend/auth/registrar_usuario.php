<?php
// Conexión a la base de datos GIA
$conexion = new mysqli("localhost:3310", "root", "", "GIA");

// Verificar la conexión, si algo falla detiene el programa y muestra el error
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Recibir datos del formulario, captura lo que el usuario escribio en el formulario (registro.html)
$usuario = $_POST['usuario'];
$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$password = $_POST['password'];

// Encriptar la contraseña, es muy importante Convierte la contraseña en un hash seguro, nunca guarda la contraseña real en la BD
$claveEncriptada = password_hash($password, PASSWORD_DEFAULT);

// Verificar si el usuario ya existe
$sql_verificar = "SELECT * FROM usuarios WHERE Usuario = ?";
$stmt = $conexion->prepare($sql_verificar);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$resultado = $stmt->get_result();

//condicion
if ($resultado->num_rows > 0) {
 //Verificar si el usuario ya existe   
    echo "<script>alert('El usuario ya existe'); window.location.href='registro.html';</script>";
    exit();
}

// Insertar nuevo usuario
$sql_insertar = "INSERT INTO usuarios (usuario, nombre, correo, contraseña) VALUES (?, ?, ?, ?)";
$stmt = $conexion->prepare($sql_insertar);
$stmt->bind_param("ssss", $usuario, $nombre, $correo, $claveEncriptada);

//ejecutar la insercion
if ($stmt->execute()) {

//si todo sale bien
    echo "<script>alert('Registro exitoso. Ahora puedes iniciar sesión.'); window.location.href='/GIA/index.html';</script>";
} else {
    //si falla
    echo "Error: " . $stmt->error;
}

//cerrar conexión
$stmt->close();
$conexion->close();
?>
