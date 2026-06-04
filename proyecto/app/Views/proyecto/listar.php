<?php
include __DIR__ . '/../layouts/header.php';
include __DIR__ . '/../layouts/sidebar.php';

$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);
?>

<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h1 class="h3">Proyectos</h1>
            <p class="text-muted mb-0">Administración de proyectos registrados en el sistema.</p>
        </div>
        <a href="?url=proyecto/crear" class="btn btn-primary">Nuevo proyecto</a>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Título</th>
                            <th>Objetivo General</th>
                            <th>Comunidad</th>
                            <th>Línea de Investigación</th>
                            <th>Trayecto</th>
                            <th>Tutor</th>
                            <th>Docente Formador</th>
                            <th>Inicio</th>
                            <th>Fin</th>
                            <th>Estado</th>
                            <th>Integrantes</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($proyectos)): ?>
                            <tr>
                                <td colspan="13" class="text-center py-4">No hay proyectos registrados.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($proyectos as $proyecto): ?>
                                <tr>
                                    <td><?= htmlspecialchars($proyecto['idProyecto']) ?></td>
                                    <td><?= htmlspecialchars($proyecto['titulo']) ?></td>
                                    <td><?= htmlspecialchars(substr($proyecto['objetivoGeneral'], 0, 70)) ?><?= strlen($proyecto['objetivoGeneral']) > 70 ? '...' : '' ?></td>
                                    <td><?= htmlspecialchars($proyecto['comunidad']) ?></td>
                                    <td><?= htmlspecialchars($proyecto['lineaInvestigacion']) ?></td>
                                    <td><?= htmlspecialchars($proyecto['trayecto']) ?></td>
                                    <td><?= htmlspecialchars($proyecto['tutor']) ?></td>
                                    <td><?= htmlspecialchars($proyecto['docenteFormador']) ?></td>
                                    <td><?= htmlspecialchars($proyecto['fechaInicio']) ?></td>
                                    <td><?= htmlspecialchars($proyecto['fechaFin']) ?></td>
                                    <td><span class="badge bg-secondary"><?= htmlspecialchars($proyecto['estado']) ?></span></td>
                                    <td class="text-center"><?= intval($proyecto['cantidadIntegrantes'] ?? 0) ?></td>
                                    <td class="text-center">
                                        <a href="?url=proyecto/editar/<?= $proyecto['idProyecto'] ?>" class="btn btn-sm btn-outline-primary me-1">Editar</a>
                                        <a href="?url=proyecto/eliminar/<?= $proyecto['idProyecto'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar este proyecto?');">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>