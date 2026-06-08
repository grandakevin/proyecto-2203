<?php
include __DIR__ . '/../layouts/header.php';
include __DIR__ . '/../layouts/sidebar.php';

$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);

$old = $old ?? [];
$lineas = $lineas ?? [];
$integrantesOld = $old['integrantes'] ?? [[], []];
?>

<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h1 class="h3">Crear proyecto</h1>
            <p class="text-muted mb-0">Completa el formulario para registrar un nuevo proyecto.</p>
        </div>
        <a href="?url=proyecto/index" class="btn btn-outline-secondary">Volver al listado</a>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="?url=proyecto/guardar" method="post">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Título <span class="text-danger">*</span></label>
                        <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($old['titulo'] ?? '') ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Objetivo General <span class="text-danger">*</span></label>
                        <textarea name="objetivoGeneral" rows="4" class="form-control" required><?= htmlspecialchars($old['objetivoGeneral'] ?? '') ?></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" rows="4" class="form-control"><?= htmlspecialchars($old['descripcion'] ?? '') ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Comunidad <span class="text-danger">*</span></label>
                        <select name="comunidad" class="form-select" required>
                            <option value="">Seleccione una comunidad</option>
                            <?php if (!empty($comunidades) && is_array($comunidades)): ?>
                                <?php foreach ($comunidades as $c): ?>
                                    <option value="<?= htmlspecialchars($c['nombre']) ?>" <?= isset($old['comunidad']) && $old['comunidad'] === $c['nombre'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($c['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Línea de investigación <span class="text-danger">*</span></label>
                        <select name="idLineaInvestigacion" class="form-select" required>
                            <option value="">Seleccione una línea</option>
                            <?php foreach ($lineas as $linea): ?>
                                <option value="<?= $linea['idLineaInvestigacion'] ?>" <?= isset($old['idLineaInvestigacion']) && $old['idLineaInvestigacion'] == $linea['idLineaInvestigacion'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($linea['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Trayecto <span class="text-danger">*</span></label>
                        <select name="trayecto" class="form-select" required>
                            <?php $trayectoOld = $old['trayecto'] ?? ''; ?>
                            <option value="">Seleccione un trayecto</option>
                            <option value="Trayecto I" <?= $trayectoOld === 'Trayecto I' ? 'selected' : '' ?>>Trayecto I</option>
                            <option value="Trayecto II" <?= $trayectoOld === 'Trayecto II' ? 'selected' : '' ?>>Trayecto II</option>
                            <option value="Trayecto III" <?= $trayectoOld === 'Trayecto III' ? 'selected' : '' ?>>Trayecto III</option>
                            <option value="Trayecto IV" <?= $trayectoOld === 'Trayecto IV' ? 'selected' : '' ?>>Trayecto IV</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tutor académico</label>
                        <input type="text" name="tutor" class="form-control" value="<?= htmlspecialchars($old['tutor'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Docente formador</label>
                        <input type="text" name="docenteFormador" class="form-control" value="<?= htmlspecialchars($old['docenteFormador'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha inicio <span class="text-danger">*</span></label>
                        <input type="date" name="fechaInicio" class="form-control" value="<?= htmlspecialchars($old['fechaInicio'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha fin</label>
                        <input type="date" name="fechaFin" class="form-control" value="<?= htmlspecialchars($old['fechaFin'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select">
                            <?php $estadoOld = $old['estado'] ?? 'Activo'; ?>
                            <option value="Activo" <?= $estadoOld === 'Activo' ? 'selected' : '' ?>>Activo</option>
                            <option value="Finalizado" <?= $estadoOld === 'Finalizado' ? 'selected' : '' ?>>Finalizado</option>
                            <option value="Suspendido" <?= $estadoOld === 'Suspendido' ? 'selected' : '' ?>>Suspendido</option>
                            <option value="Aprobado" <?= $estadoOld === 'Aprobado' ? 'selected' : '' ?>>Aprobado</option>
                            <option value="Rechazado" <?= $estadoOld === 'Rechazado' ? 'selected' : '' ?>>Rechazado</option>
                        </select>
                    </div>
                </div>

                <hr class="my-4">
                <h5>Integrantes del equipo (opcional)</h5>
                <div class="row g-3">
                    <?php for ($i = 0; $i < 2; $i++):
                        $integrante = $integrantesOld[$i] ?? [];
                    ?>
                        <div class="col-md-4">
                            <label class="form-label">Cédula</label>
                            <input type="text" name="integrantes[<?= $i ?>][cedula]" class="form-control" value="<?= htmlspecialchars($integrante['cedula'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="integrantes[<?= $i ?>][nombre]" class="form-control" value="<?= htmlspecialchars($integrante['nombre'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Apellido</label>
                            <input type="text" name="integrantes[<?= $i ?>][apellido]" class="form-control" value="<?= htmlspecialchars($integrante['apellido'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Correo</label>
                            <input type="email" name="integrantes[<?= $i ?>][correo]" class="form-control" value="<?= htmlspecialchars($integrante['correo'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="integrantes[<?= $i ?>][telefono]" class="form-control" value="<?= htmlspecialchars($integrante['telefono'] ?? '') ?>">
                        </div>
                    <?php endfor; ?>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Guardar proyecto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>