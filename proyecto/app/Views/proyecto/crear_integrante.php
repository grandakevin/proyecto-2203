<?php
include __DIR__ . '/../layouts/header.php';
include __DIR__ . '/../layouts/sidebar.php';

$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);

$old = $old ?? [];
$values = [
    'cedula' => htmlspecialchars($old['cedula'] ?? ''),
    'nombre' => htmlspecialchars($old['nombre'] ?? ''),
    'apellido' => htmlspecialchars($old['apellido'] ?? ''),
    'correo' => htmlspecialchars($old['correo'] ?? ''),
    'telefono' => htmlspecialchars($old['telefono'] ?? ''),
];
?>

<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h1 class="h3">Agregar integrante</h1>
            <p class="text-muted mb-0">Proyecto: <?= htmlspecialchars($proyecto['titulo']) ?></p>
        </div>
        <a href="?url=proyecto/integrantes/<?= $proyecto['idProyecto'] ?>" class="btn btn-outline-secondary">Volver a integrantes</a>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="?url=proyecto/guardarIntegrante/<?= $proyecto['idProyecto'] ?>" method="post">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Cédula <span class="text-danger">*</span></label>
                        <input type="text" name="cedula" class="form-control" value="<?= $values['cedula'] ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control" value="<?= $values['nombre'] ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Apellido <span class="text-danger">*</span></label>
                        <input type="text" name="apellido" class="form-control" value="<?= $values['apellido'] ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Correo <span class="text-danger">*</span></label>
                        <input type="email" name="correo" class="form-control" value="<?= $values['correo'] ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" value="<?= $values['telefono'] ?>">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Guardar integrante</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
