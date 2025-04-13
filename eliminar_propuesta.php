<?php
require_once 'database.php';

// Capturar los datos del formulario
$id = $_POST['id'];
$idpropuesta = $_POST["ideliminar"];

// Obtener la conexión a la base de datos
$conexion = Database::obtenerConexion();

//Verificar si hay una asamblea activa
$consul = "SELECT idasamblea FROM asambleas WHERE estado='activa'";
$resultado = $conexion->query($consul);

// Validar votos y actualizar la propuesta
function validarVotos($conexion, $id, $idpropuesta){
    $sql = "SELECT votos FROM propuestas WHERE idpropuesta='$idpropuesta' AND idusuario='$id'";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $votos = $fila['votos'];

        if ($votos == 0) {
            // Verificar si el ID del usuario ya existe en la base de datos
            $query = "DELETE FROM propuestas WHERE idpropuesta= '$idpropuesta' and idusuario='$id' and votos= 0";
            
            if ($conexion->query($query) === TRUE) {
                
                ?>
                <script>
                    alert("Propuesta eliminada correctamente.");
                    window.history.back();
                </script>
                <?php                
            } else {
                
                ?>
                <script>
                    alert("Error al eliminar Propuesta: " . $conexion->error);
                    window.history.back();
                </script>
                <?php                
            }
        } else {
            
            ?>
            <script>
                alert("No se puede eliminar la propuesta ya que tiene: " . $votos . " votos");
                window.history.back();
            </script>
            <?php            
        }
    } else {
        
        ?>
        <script>
            alert("No se encontró ninguna propuesta con el ID especificado.");
            window.history.back();
        </script>
        <?php        
    }
}

// Llamar a la función para validar votos y actualizar la propuesta
if ($resultado->num_rows > 0) {
    validarVotos($conexion, $id, $idpropuesta);
}else{
    ?>
    <script>
        alert("Aun no hay asambleas Activas, por tanto no hay propuestas que eliminar");
        window.history.back();
    </script>
    <?php
}
// Cerrar la conexión a la base de datos
$conexion->close();
?>