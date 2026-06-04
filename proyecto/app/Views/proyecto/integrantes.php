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
            <h1 class="h3">Integrantes del proyecto</h1>
            <p class="text-muted mb-0">Proyecto: <?= htmlspecialchars($proyecto['titulo']) ?></p>
        </div>
        <div class="d-flex gap-2">
            <a href="?url=proyecto/index" class="btn btn-outline-secondary">Volver a proyectos</a>
            <a href="?url=proyecto/crearIntegrante/<?= $proyecto['idProyecto'] ?>" class="btn btn-primary">Nuevo integrante</a>
        </div>
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
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($integrantes)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">No hay integrantes registrados para este proyecto.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($integrantes as $integrante): ?>
                                <tr>
                                    <td><?= htmlspecialchars($integrante['idProyectista']) ?></td>
                                    <td><?= htmlspecialchars($integrante['cedula']) ?></td>
                                    <td><?= htmlspecialchars($integrante['nombre']) ?></td>
                                    <td><?= htmlspecialchars($integrante['apellido']) ?></td>
                                    <td><?= htmlspecialchars($integrante['correo']) ?></td>
                                    <td><?= htmlspecialchars($integrante['telefono']) ?></td>
                                    <td class="text-center">
                                        <a href="?url=proyecto/editarIntegrante/<?= $proyecto['idProyecto'] ?>/<?= $integrante['idProyectista'] ?>" class="btn btn-sm btn-outline-primary me-1">Editar</a>
                                        <a href="?url=proyecto/eliminarIntegrante/<?= $proyecto['idProyecto'] ?>/<?= $integrante['idProyectista'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar este integrante?');">Eliminar</a>
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
