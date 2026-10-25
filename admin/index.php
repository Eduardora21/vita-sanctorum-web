<?php
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Santo.php';

$database = new Database();
$db = $database->getConnection();
$santoModel = new Santo($db);

$mensaje = '';
$tipo_mensaje = '';

// Directorio físico de destino para subidas
$uploadDir = __DIR__ . '/../public/img/';

// Función auxiliar para procesar imagen subida
function procesarSubidaImagen(array $file, string $slug, string $uploadDir): ?string {
    if (isset($file['error']) && $file['error'] === UPLOAD_ERR_OK) {
        $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];
        $fileInfo = pathinfo($file['name']);
        $ext = strtolower($fileInfo['extension'] ?? '');

        if (in_array($ext, $allowedExts)) {
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $filename = 'santo_' . $slug . '_' . time() . '.' . $ext;
            $destination = $uploadDir . $filename;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                return 'public/img/' . $filename;
            }
        }
    }
    return null;
}

// 1. Procesar Eliminación
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $idToDelete = (int)$_GET['id'];
    if ($santoModel->delete($idToDelete)) {
        header('Location: index.php?msg=deleted');
        exit;
    }
}

// 2. Procesar Creación y Actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $nombre = trim($_POST['nombre'] ?? '');
    $titulo = trim($_POST['titulo'] ?? '');
    $festividad = trim($_POST['festividad'] ?? '');
    $mes_liturgico = (int)($_POST['mes_liturgico'] ?? 10);
    $dia_liturgico = str_pad(trim($_POST['dia_liturgico'] ?? '01'), 2, '0', STR_PAD_LEFT);
    $siglo = trim($_POST['siglo'] ?? '');
    $lugar = trim($_POST['lugar_origen'] ?? '');
    $patronazgo = trim($_POST['patronazgo'] ?? '');
    $atributos = trim($_POST['atributos'] ?? '');
    $frase = trim($_POST['frase_celebre'] ?? '');
    $biografia = trim($_POST['biografia'] ?? '');
    $santidad = trim($_POST['santidad'] ?? '');
    $oracion = trim($_POST['oracion'] ?? '');

    // Generar slug limpio
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $nombre), '-'));

    // Subir imagen si viene archivo
    $imagenSubida = null;
    if (isset($_FILES['imagen_archivo']) && $_FILES['imagen_archivo']['error'] !== UPLOAD_ERR_NO_FILE) {
        $imagenSubida = procesarSubidaImagen($_FILES['imagen_archivo'], $slug, $uploadDir);
    }

    if (!empty($nombre) && !empty($titulo) && !empty($festividad) && !empty($biografia)) {
        if ($action === 'create') {
            $datos = [
                'slug'            => $slug,
                'nombre'          => $nombre,
                'titulo'          => $titulo,
                'festividad'      => $festividad,
                'mes_liturgico'   => $mes_liturgico,
                'dia_liturgico'   => $dia_liturgico,
                'grado_liturgico' => 'Memoria obligatoria',
                'siglo'           => $siglo,
                'lugar_origen'    => $lugar,
                'patronazgo'      => $patronazgo,
                'atributos'       => $atributos,
                'frase_celebre'   => $frase,
                'biografia'       => $biografia,
                'santidad'        => $santidad,
                'oracion'         => $oracion,
                'imagen'          => $imagenSubida ?? 'public/img/san-francisco.jpg',
                'destacado'       => 1
            ];

            if ($santoModel->create($datos)) {
                header('Location: index.php?msg=created');
                exit;
            } else {
                $mensaje = 'Error al registrar el santo.';
                $tipo_mensaje = 'error';
            }
        } elseif ($action === 'edit') {
            $id = (int)$_POST['id'];
            $datos = [
                'slug'            => $slug,
                'nombre'          => $nombre,
                'titulo'          => $titulo,
                'festividad'      => $festividad,
                'mes_liturgico'   => $mes_liturgico,
                'dia_liturgico'   => $dia_liturgico,
                'siglo'           => $siglo,
                'lugar_origen'    => $lugar,
                'patronazgo'      => $patronazgo,
                'atributos'       => $atributos,
                'frase_celebre'   => $frase,
                'biografia'       => $biografia,
                'santidad'        => $santidad,
                'oracion'         => $oracion
            ];

            if ($imagenSubida) {
                $datos['imagen'] = $imagenSubida;
            }

            if ($santoModel->update($id, $datos)) {
                header('Location: index.php?msg=updated');
                exit;
            } else {
                $mensaje = 'Error al actualizar el registro.';
                $tipo_mensaje = 'error';
            }
        }
    } else {
        $mensaje = 'Por favor complete todos los campos obligatorios.';
        $tipo_mensaje = 'error';
    }
}

// Alertas GET
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'created') {
        $mensaje = 'Santo registrado exitosamente con su imagen.';
        $tipo_mensaje = 'success';
    } elseif ($_GET['msg'] === 'updated') {
        $mensaje = 'Santo actualizado correctamente.';
        $tipo_mensaje = 'success';
    } elseif ($_GET['msg'] === 'deleted') {
        $mensaje = 'Registro eliminado correctamente.';
        $tipo_mensaje = 'success';
    }
}

// Traer registros completos para precarga en modal
$queryCompletos = "SELECT * FROM santos ORDER BY id DESC";
$stmt = $db->query($queryCompletos);
$santos_admin = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];

$totalSantos = count($santos_admin);
$mesActualNum = (int)date('n');
$festividadesEsteMes = count($santoModel->getByMesLiturgico($mesActualNum));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control | Vita Sanctorum</title>
    <link rel="icon" type="image/jpeg" href="../public/img/logo.jpg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body class="admin-body">

    <!-- Header del Panel -->
    <header class="admin-header">
        <div class="admin-nav-container">
            <div class="admin-brand">
                <span class="logo-wrapper logo-wrapper-sm">
                    <img src="../public/img/logo.jpg" alt="Logo" class="brand-logo">
                </span>
                <div>
                    <span class="admin-title">Vita Sanctorum</span>
                    <span class="admin-badge">Panel Administrativo</span>
                </div>
            </div>

            <div class="admin-user-nav">
                <a href="../index.php" class="btn-secondary-outline" target="_blank">&larr; Ver Sitio Web</a>
                <a href="logout.php" class="btn-secondary-outline" style="color: #f87171; border-color: rgba(248, 113, 113, 0.2); margin-left: 0.5rem;">Cerrar Sesión</a>
            </div>
        </div>
    </header>

    <!-- Contenedor Principal -->
    <main class="admin-main">
        
        <?php if (!empty($mensaje)): ?>
            <div style="padding: 1rem 1.25rem; border-radius: 8px; font-size: 0.9rem; <?= $tipo_mensaje === 'success' ? 'background: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34, 197, 94, 0.3); color: #4ade80;' : 'background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5;'; ?>">
                <?= htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <!-- Tarjetas de Métricas -->
        <section class="admin-stats-grid">
            <div class="stat-card">
                <span class="stat-label">Santos Registrados</span>
                <span class="stat-number"><?= $totalSantos; ?></span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Festividades del Mes Actual</span>
                <span class="stat-number"><?= $festividadesEsteMes; ?></span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Estado del Sistema</span>
                <span class="stat-status-connected">● Conectado a MySQL</span>
            </div>
        </section>

        <!-- Barra de Acciones y Búsqueda -->
        <section class="admin-action-bar">
            <div class="admin-search-wrapper">
                <input type="text" id="adminSearchInput" class="admin-search" placeholder="Filtrar registros por nombre o patronazgo...">
            </div>
            <button type="button" class="btn-gold" id="btnOpenModal">
                + Nuevo Santo
            </button>
        </section>

        <!-- Tabla de Registros -->
        <section class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Santo</th>
                        <th>Festividad</th>
                        <th>Siglo</th>
                        <th>Patronazgo</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody id="saintsTableBody">
                    <?php if (!empty($santos_admin)): ?>
                        <?php foreach ($santos_admin as $row): ?>
                            <tr>
                                <td class="cell-id">#<?= $row['id']; ?></td>
                                <td style="width: 50px;">
                                    <img src="../<?= htmlspecialchars($row['imagen']); ?>" alt="<?= htmlspecialchars($row['nombre']); ?>" style="width: 42px; height: 42px; object-fit: cover; border-radius: 6px; border: 1px solid rgba(255,255,255,0.1);">
                                </td>
                                <td class="cell-name">
                                    <strong><?= htmlspecialchars($row['nombre']); ?></strong>
                                </td>
                                <td><?= htmlspecialchars($row['festividad']); ?></td>
                                <td><?= htmlspecialchars($row['siglo'] ?? 'N/A'); ?></td>
                                <td><?= htmlspecialchars($row['patronazgo'] ?? 'General'); ?></td>
                                <td class="text-right cell-actions">
                                    <!-- Botón Editar con ícono de lápiz -->
                                    <button 
                                        type="button" 
                                        class="btn-action edit btn-edit-santo" 
                                        title="Editar Santo"
                                        data-json='<?= htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8'); ?>'
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 3px;">
                                            <path d="M12 20h9"></path>
                                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                        </svg>
                                        Editar
                                    </button>

                                    <!-- Botón Eliminar -->
                                    <a href="index.php?action=delete&id=<?= $row['id']; ?>" 
                                       class="btn-action delete" 
                                       title="Eliminar" 
                                       onclick="return confirm('¿Está seguro de eliminar a <?= addslashes($row['nombre']); ?>?');">
                                        Eliminar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                                No hay registros cargados en la base de datos.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>

    <!-- Modal: Formulario de Creación / Edición -->
    <div class="modal-overlay" id="saintModal">
        <div class="modal-window">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Registrar Nuevo Santo</h3>
                <button type="button" class="btn-close-modal" id="btnCloseModal">&times;</button>
            </div>

            <form action="index.php" method="POST" enctype="multipart/form-data" class="modal-form" id="saintForm">
                <input type="hidden" name="action" id="formAction" value="create">
                <input type="hidden" name="id" id="santoId" value="">

                <div class="form-row">
                    <div class="form-group">
                        <label for="inputNombre">Nombre Completo *</label>
                        <input type="text" id="inputNombre" name="nombre" placeholder="Ej: San Juan de la Cruz" required>
                    </div>
                    <div class="form-group">
                        <label for="inputTitulo">Título / Rango *</label>
                        <input type="text" id="inputTitulo" name="titulo" placeholder="Ej: Místico y Doctor de la Iglesia" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="inputFestividad">Festividad (Texto) *</label>
                        <input type="text" id="inputFestividad" name="festividad" placeholder="Ej: 14 de Diciembre" required>
                    </div>
                    <div class="form-group">
                        <label for="inputSiglo">Siglo / Época</label>
                        <input type="text" id="inputSiglo" name="siglo" placeholder="Ej: Siglo XVI (1542 - 1591)">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="inputMes">Mes Litúrgico (1-12) *</label>
                        <input type="number" id="inputMes" name="mes_liturgico" min="1" max="12" value="10" required>
                    </div>
                    <div class="form-group">
                        <label for="inputDia">Día Litúrgico (01-31) *</label>
                        <input type="text" id="inputDia" name="dia_liturgico" maxlength="2" placeholder="14" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="inputLugar">Lugar de Origen</label>
                        <input type="text" id="inputLugar" name="lugar_origen" placeholder="Ej: Fontiveros, España">
                    </div>
                    <div class="form-group">
                        <label for="inputPatronazgo">Patronazgo Principal</label>
                        <input type="text" id="inputPatronazgo" name="patronazgo" placeholder="Ej: Poetas y contemplativos">
                    </div>
                </div>

                <!-- Selector de Imagen desde Explorador de Archivos -->
                <div class="form-group">
                    <label for="inputImagenArchivo">Imagen del Santo (JPG, PNG, WEBP)</label>
                    <input type="file" id="inputImagenArchivo" name="imagen_archivo" accept="image/jpeg,image/png,image/webp">
                    <small id="imageHint" style="color: var(--text-muted); font-size: 0.78rem; margin-top: 0.25rem;">
                        Selecciona una imagen desde tu computadora para subirla automáticamente.
                    </small>
                </div>

                <div class="form-group">
                    <label for="inputAtributos">Atributos Tradicionales</label>
                    <input type="text" id="inputAtributos" name="atributos" placeholder="Ej: Hábito carmelita, pluma, crucifijo">
                </div>

                <div class="form-group">
                    <label for="inputFrase">Frase Célebre</label>
                    <input type="text" id="inputFrase" name="frase_celebre" placeholder="Ej: En el atardecer de la vida seremos juzgados por el amor.">
                </div>

                <div class="form-group">
                    <label for="inputBiografia">Biografía y Misión * (Usa dobles saltos de línea para párrafos)</label>
                    <textarea id="inputBiografia" name="biografia" rows="4" placeholder="Escribe aquí la historia de su vida..." required></textarea>
                </div>

                <div class="form-group">
                    <label for="inputSantidad">Reconocimiento / Santidad</label>
                    <textarea id="inputSantidad" name="santidad" rows="2" placeholder="Detalles de canonización o doctorado..."></textarea>
                </div>

                <div class="form-group">
                    <label for="inputOracion">Oración de Intercesión</label>
                    <textarea id="inputOracion" name="oracion" rows="2" placeholder="Oración tradicional..."></textarea>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary-outline" id="btnCancelModal">Cancelar</button>
                    <button type="submit" class="btn-gold" id="btnSubmitModal">Guardar Santo</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script de interactividad: Modal, Precarga de Edición y Filtro -->
    <script>
        const modal = document.getElementById('saintModal');
        const modalTitle = document.getElementById('modalTitle');
        const saintForm = document.getElementById('saintForm');
        const formAction = document.getElementById('formAction');
        const santoId = document.getElementById('santoId');
        const btnSubmitModal = document.getElementById('btnSubmitModal');
        const imageHint = document.getElementById('imageHint');

        const btnOpen = document.getElementById('btnOpenModal');
        const btnClose = document.getElementById('btnCloseModal');
        const btnCancel = document.getElementById('btnCancelModal');
        const searchInput = document.getElementById('adminSearchInput');
        const rows = document.querySelectorAll('#saintsTableBody tr');

        // Abrir modal para CREAR
        btnOpen.addEventListener('click', () => {
            saintForm.reset();
            formAction.value = 'create';
            santoId.value = '';
            modalTitle.textContent = 'Registrar Nuevo Santo';
            btnSubmitModal.textContent = 'Guardar Santo';
            imageHint.textContent = 'Selecciona una imagen desde tu computadora para subirla automáticamente.';
            modal.classList.add('modal-active');
        });

        // Abrir modal para EDITAR con precarga de datos
        document.querySelectorAll('.btn-edit-santo').forEach(btn => {
            btn.addEventListener('click', () => {
                const data = JSON.parse(btn.getAttribute('data-json'));

                formAction.value = 'edit';
                santoId.value = data.id;
                modalTitle.textContent = 'Editar: ' + data.nombre;
                btnSubmitModal.textContent = 'Actualizar Cambios';
                imageHint.textContent = 'Opcional: Si seleccionas un nuevo archivo, reemplazará la imagen actual (' + data.imagen + ').';

                // Rellenar campos del formulario
                document.getElementById('inputNombre').value = data.nombre || '';
                document.getElementById('inputTitulo').value = data.titulo || '';
                document.getElementById('inputFestividad').value = data.festividad || '';
                document.getElementById('inputSiglo').value = data.siglo || '';
                document.getElementById('inputMes').value = data.mes_liturgico || 10;
                document.getElementById('inputDia').value = data.dia_liturgico || '01';
                document.getElementById('inputLugar').value = data.lugar_origen || '';
                document.getElementById('inputPatronazgo').value = data.patronazgo || '';
                document.getElementById('inputAtributos').value = data.atributos || '';
                document.getElementById('inputFrase').value = data.frase_celebre || '';
                document.getElementById('inputBiografia').value = data.biografia || '';
                document.getElementById('inputSantidad').value = data.santidad || '';
                document.getElementById('inputOracion').value = data.oracion || '';

                modal.classList.add('modal-active');
            });
        });

        // Cerrar modal
        btnClose.addEventListener('click', () => modal.classList.remove('modal-active'));
        btnCancel.addEventListener('click', () => modal.classList.remove('modal-active'));

        window.addEventListener('click', (e) => {
            if (e.target === modal) modal.classList.remove('modal-active');
        });

        // Filtrado en tiempo real en la tabla
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase().trim();
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    </script>
</body>
</html>