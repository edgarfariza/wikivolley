<?php 
// iniciamos sesion para saber si el usuario esta logueado o no
session_start();
include 'conexion.php'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WIKIVOLLEY</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header class="linea-superior">
    <div class="logo-y-titulo">
        <a href="index.php">
            <img src="img/logodef.png" alt="Logo WikiVolley" class="logo-principal">
        </a>
        <h2>TU PORTAL DE VOLEIBOL FAVORITO</h2>
    </div>

    <nav class="menu-navegacion">
        <ul style="display: flex; align-items: center; list-style: none; margin: 0; padding: 0;">
            <li style="margin-right: 20px;"><a href="#seccion-noticias">NOTICIAS</a></li>
            <li style="margin-right: 20px;"><a href="#seccion-empleo">EMPLEO</a></li>
            <li style="margin-right: 20px;"><a href="#seccion-cursos">CURSOS</a></li>
            
            <li style="margin-left: 20px; padding-left: 20px; border-left: 1px solid #ccc;">
                <?php if (isset($_SESSION['usuario_nombre'])): ?>
                    <span style="color: #ccc; font-size: 0.9rem; margin-right: 15px;">
                        Hola, <strong><?php echo $_SESSION['usuario_nombre']; ?></strong>
                    </span>
                    <a href="logout.php" style="background-color: red; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 0.85rem;">Cerrar Sesion</a>
                <?php else: ?>
                    <a href="login.php" style="background-color: #2563eb; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 0.85rem;">Entrar</a>
                <?php endif; ?>
            </li>
        </ul>
    </nav>
</header>

<!-- SECCION NOTICIAS - los datos vienen del json que genera el script python -->
<section id="seccion-noticias">
    <div class="cabecera-noticias">
        <img src="img/logo-noticias.png" alt="Noticias Voleibol" class="logo-seccion">
    </div>
    
    <div class="contenedor-grid">
        <?php
        $ruta_archivo = __DIR__ . '/noticias.json';

        if (file_exists($ruta_archivo)) {
            $contenido_json = file_get_contents($ruta_archivo);
            $noticias = json_decode($contenido_json, true);

            if (!empty($noticias)) {
                foreach ($noticias as $fila) {
                    echo "<article class='tarjeta-noticia'>";
                    echo "  <div class='imagen-wrapper'>";
                    echo "    <img src='" . $fila['imagen'] . "' alt='Noticia'>";
                    echo "  </div>";
                    echo "  <div class='contenido-noticia'>";
                    echo "    <h3>" . htmlspecialchars($fila['titulo']) . "</h3>";
                    echo "    <p>" . substr($fila['contenido'], 0, 100) . "...</p>";
                    
                    if (!empty($fila['enlace'])) {
                        echo "<a href='" . $fila['enlace'] . "' class='boton-leer-mas' target='_blank'>Leer más</a>";
                    }
                    
                    echo "  </div>";
                    echo "</article>";
                }
            } else {
                echo "<p>No hay noticias por ahora.</p>";
            }
        } else {
            // si no existe el json hay que ejecutar el script python primero
            echo "<p>Error: no se encuentra noticias.json. Ejecuta el script primero.</p>";
        }
        ?>
    </div>
</section>

<!-- SECCION EMPLEO -->
<section id="seccion-empleo">
    <div class="cabecera-empleo">
        <img src="img/logo-empleo.png" alt="Bolsa de Empleo" class="logo-seccion">
    </div>
    <div class="contenedor-lista-empleo">
    <?php
    $sql_empleo = "SELECT * FROM bolsa_empleo ORDER BY id DESC";
    $resultado_empleo = $conexion->query($sql_empleo);

    if ($resultado_empleo && $resultado_empleo->num_rows > 0) {
        while($fila = $resultado_empleo->fetch_assoc()) {

            // recogemos los campos, si no existen ponemos un valor por defecto
            $puesto = isset($fila['PUESTO']) ? $fila['PUESTO'] : 'Sin puesto';
            $salario_db = isset($fila['SALARIO']) ? $fila['SALARIO'] : 0;
            $desc = isset($fila['DESCRIPCION']) ? $fila['DESCRIPCION'] : '';
            $contacto = isset($fila['CONTACTO']) ? $fila['CONTACTO'] : '#';

            if ($salario_db > 0) {
                $salario_mostrar = $salario_db . " €";
            } else {
                $salario_mostrar = "A convenir";
            }

            // comprobamos si el contacto es un email o una url
            if (strpos($contacto, '@') !== false) {
                $href = "mailto:" . $contacto;
            } else {
                $href = $contacto;
            }

            echo "<div class='item-empleo-lista'>";
                echo "<div class='col-info'>";
                    echo "<h3>" . htmlspecialchars($puesto) . "</h3>";
                    echo "<span class='tag-salario'>" . $salario_mostrar . "</span>";
                echo "</div>";

                echo "<div class='col-desc'>";
                    echo "<p>" . substr($desc, 0, 140) . "...</p>";
                echo "</div>";

                echo "<div class='col-boton'>";
                // si esta logueado puede aplicar, si no le mandamos al login
                if (isset($_SESSION['usuario_rol'])) {
                    echo "<a href='postular.php?id_empleo=" . $fila['id'] . "' class='btn-lista' target='_blank'>Aplicar</a>";
                } else {
                    echo "<a href='login.php' class='btn-lista' style='background-color: gray; white-space: nowrap;'>Iniciar sesion para aplicar</a>";
                }
                echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<p>No hay ofertas de empleo disponibles.</p>";
    }
    ?>
    </div>
</section>

<!-- SECCION CURSOS -->
<section id="seccion-cursos">
    <div class="overlay-cursos"></div>

    <div class="contenido-seccion-wrapper">
        <div class="cabecera-seccion-logo">
            <img src="img/logo-curso.png" alt="Cursos" class="logo-seccion">
        </div>

        <div class="contenedor-grid-cursos">
        <?php
        $sql_cursos = "SELECT * FROM cursos ORDER BY id DESC";
        $resultado_cursos = $conexion->query($sql_cursos);

        if ($resultado_cursos && $resultado_cursos->num_rows > 0) {
            while($fila = $resultado_cursos->fetch_assoc()) {
                $nombre = isset($fila['NOMBRE']) ? $fila['NOMBRE'] : 'Sin titulo';
                $descripcion = isset($fila['DESCRIPCION']) ? $fila['DESCRIPCION'] : '';
                $precio_db = isset($fila['PRECIO']) ? $fila['PRECIO'] : 0;
                $duracion = isset($fila['DURACION']) ? $fila['DURACION'] : 'Consultar';
                $imagen = isset($fila['IMAGEN']) ? $fila['IMAGEN'] : 'default-curso.jpg';
                $enlace = isset($fila['ENLACE']) ? $fila['ENLACE'] : '#';

                if ($precio_db > 0) {
                    $precio_mostrar = $precio_db . " €";
                } else {
                    $precio_mostrar = "Gratis";
                }

                if (strpos($enlace, '@') !== false) {
                    $href = "mailto:" . $enlace;
                } else {
                    $href = $enlace;
                }

                echo "<div class='tarjeta-curso-compacta'>";
                    echo "<div class='mini-imagen-wrapper'>";
                        echo "<img src='img/" . $imagen . "' alt='Curso'>";
                    echo "</div>";

                    echo "<div class='contenido-curso-compacto'>";
                        echo "<div class='cabecera-card'>";
                            echo "<h3>" . htmlspecialchars($nombre) . "</h3>";
                            echo "<span class='badge-precio-mini'>" . $precio_mostrar . "</span>";
                        echo "</div>";
                        
                        echo "<span class='duracion-txt'>Duracion: " . $duracion . "</span>";
                        echo "<p>" . substr($descripcion, 0, 90) . "...</p>";
                        
                        echo "<a href='" . $href . "' class='btn-curso-mini' target='_blank'>Inscribirse</a>";
                    echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>Proximamente nuevos cursos.</p>";
        }
        ?>
        </div>
    </div>
</section>

<!-- PANEL ADMIN - solo lo ve el administrador -->
<?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'Admin'): ?>

    <div class="separador-admin">
        <h2>PANEL DE ADMINISTRADOR</h2>
        <p>Solo visible para el admin.</p>
    </div>

    <!-- boton para lanzar el script python que actualiza las noticias -->
    <section style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
        <div style="background-color: #1e293b; padding: 25px; border-radius: 10px; border: 1px solid #334155;">
            <h4 style="color: white; margin-top: 0;">Scripts Python</h4>
            <p style="color: #ccc; margin-bottom: 20px;">
                Desde aqui puedes ejecutar el script de python que actualiza las noticias del portal.
                Cuando termina se actualiza el json y se ven en la web.
            </p>
            <form action="ejecutar_script.php" method="POST">
                <button type="submit" style="background-color: #2563eb; color: white; border: none; padding: 12px 24px; border-radius: 6px; cursor: pointer; font-weight: bold;">
                    Ejecutar Script de Noticias
                </button>
            </form>
        </div>
    </section>

    <!-- tabla con los usuarios que han aplicado a ofertas -->
    <section style="max-width: 1200px; margin: 0 auto 40px auto; padding: 0 20px;">
        <div style="background-color: #1e293b; padding: 25px; border-radius: 10px; border: 1px solid #334155; margin-top: 25px;">
            <h4 style="color: white; margin-top: 0;">Postulaciones de usuarios</h4>
            <p style="color: #ccc; margin-bottom: 20px;">
                Aqui se ve que usuarios han aplicado a que ofertas:
            </p>

            <?php
            // join para sacar el nombre del usuario y el nombre de la oferta
            $sql_logs = "SELECT u.nombre AS usuario, e.PUESTO AS puesto, p.fecha_postulacion AS fecha 
                         FROM postulaciones p
                         INNER JOIN usuarios u ON p.usuario_id = u.id
                         INNER JOIN bolsa_empleo e ON p.empleo_id = e.id
                         ORDER BY p.fecha_postulacion DESC";
            
            $res_logs = $conexion->query($sql_logs);

            if ($res_logs && $res_logs->num_rows > 0) {
                echo "<table style='width: 100%; border-collapse: collapse; color: white; font-size: 0.9rem;'>";
                echo "<tr style='background-color: #0f172a; text-align: left;'>";
                echo "<th style='padding: 10px; border: 1px solid #334155;'>Usuario</th>";
                echo "<th style='padding: 10px; border: 1px solid #334155;'>Oferta</th>";
                echo "<th style='padding: 10px; border: 1px solid #334155;'>Fecha</th>";
                echo "</tr>";

                while ($log = $res_logs->fetch_assoc()) {
                    echo "<tr style='border-bottom: 1px solid #334155;'>";
                    echo "<td style='padding: 10px;'>" . htmlspecialchars($log['usuario']) . "</td>";
                    echo "<td style='padding: 10px;'>" . htmlspecialchars($log['puesto']) . "</td>";
                    echo "<td style='padding: 10px;'>" . $log['fecha'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p style='color: #ccc;'>Todavia no hay postulaciones.</p>";
            }
            ?>
        </div>

        <!-- gestion de usuarios -->
        <div style="background-color: #1e293b; padding: 25px; border-radius: 10px; border: 1px solid #334155; margin-top: 25px;">
            <h4 style="color: white; margin-top: 0;">Usuarios registrados</h4>
            <p style="color: #ccc; margin-bottom: 20px;">
                Puedes cambiar el rol de los usuarios o eliminarlos:
            </p>

            <?php
            $sql_usuarios = "SELECT id, nombre, email, rol, fecha_registro FROM usuarios ORDER BY id ASC";
            $res_usuarios = $conexion->query($sql_usuarios);

            if ($res_usuarios && $res_usuarios->num_rows > 0) {
                echo "<table style='width: 100%; border-collapse: collapse; color: white; font-size: 0.9rem;'>";
                echo "<tr style='background-color: #0f172a; text-align: left;'>";
                echo "<th style='padding: 10px; border: 1px solid #334155;'>ID</th>";
                echo "<th style='padding: 10px; border: 1px solid #334155;'>Nombre</th>";
                echo "<th style='padding: 10px; border: 1px solid #334155;'>Email</th>";
                echo "<th style='padding: 10px; border: 1px solid #334155;'>Rol</th>";
                echo "<th style='padding: 10px; border: 1px solid #334155;'>Acciones</th>";
                echo "</tr>";

                while ($usr = $res_usuarios->fetch_assoc()) {
                    echo "<tr style='border-bottom: 1px solid #334155;'>";
                    echo "<td style='padding: 10px;'>" . $usr['id'] . "</td>";
                    echo "<td style='padding: 10px;'>" . htmlspecialchars($usr['nombre']) . "</td>";
                    echo "<td style='padding: 10px;'>" . $usr['email'] . "</td>";
                    echo "<td style='padding: 10px;'>" . $usr['rol'] . "</td>";
                    
                    echo "<td style='padding: 10px;'>";
                    // no dejamos que el admin se elimine a si mismo
                    if ($usr['id'] == $_SESSION['usuario_id']) {
                        echo "<span style='color: gray;'>Tu cuenta</span>";
                    } else {
                        if ($usr['rol'] === 'Admin') {
                            echo "<a href='modificar_usuario.php?accion=quitar_admin&id=" . $usr['id'] . "' style='background-color: orange; color: white; padding: 4px 8px; border-radius: 4px; text-decoration: none; font-size: 0.8rem; margin-right: 5px;'>Quitar Admin</a>";
                        } else {
                            echo "<a href='modificar_usuario.php?accion=hacer_admin&id=" . $usr['id'] . "' style='background-color: green; color: white; padding: 4px 8px; border-radius: 4px; text-decoration: none; font-size: 0.8rem; margin-right: 5px;'>Hacer Admin</a>";
                        }
                        echo "<a href='modificar_usuario.php?accion=eliminar&id=" . $usr['id'] . "' onclick='return confirm(\"Seguro que quieres eliminar este usuario?\");' style='background-color: red; color: white; padding: 4px 8px; border-radius: 4px; text-decoration: none; font-size: 0.8rem;'>Eliminar</a>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p style='color: #ccc;'>No hay usuarios.</p>";
            }
            ?>
        </div>
    </section>

<?php endif; ?>

<footer class="footer">
    <p>&copy; 2026 WIKIVOLLEY - Edgar Ariza</p>
</footer>

</body>
</html>