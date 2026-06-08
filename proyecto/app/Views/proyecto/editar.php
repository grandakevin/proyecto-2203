<?php
include __DIR__ . '/../layouts/header.php';
include __DIR__ . '/../layouts/sidebar.php';

$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);

$old = $old ?? [];
$proyecto = $proyecto ?? [];
$lineas = $lineas ?? [];
$integrantesActuales = $integrantesActuales ?? [];
$integrantesOld = $old['integrantes'] ?? [];

$values = [
    'titulo' => htmlspecialchars($old['titulo'] ?? ($proyecto['titulo'] ?? '')),
    'objetivoGeneral' => htmlspecialchars($old['objetivoGeneral'] ?? ($proyecto['objetivoGeneral'] ?? '')),
    'descripcion' => htmlspecialchars($old['descripcion'] ?? ($proyecto['descripcion'] ?? '')),
    'comunidad' => htmlspecialchars($old['comunidad'] ?? ($proyecto['comunidad'] ?? '')),
    'idLineaInvestigacion' => htmlspecialchars($old['idLineaInvestigacion'] ?? ($proyecto['idLineaInvestigacion'] ?? '')),
    'trayecto' => htmlspecialchars($old['trayecto'] ?? ($proyecto['trayecto'] ?? '')),
    'tutor' => htmlspecialchars($old['tutor'] ?? ($proyecto['tutor'] ?? '')),
    'docenteFormador' => htmlspecialchars($old['docenteFormador'] ?? ($proyecto['docenteFormador'] ?? '')),
    'fechaInicio' => htmlspecialchars($old['fechaInicio'] ?? ($proyecto['fechaInicio'] ?? '')),
    'fechaFin' => htmlspecialchars($old['fechaFin'] ?? ($proyecto['fechaFin'] ?? '')),
    'estado' => htmlspecialchars($old['estado'] ?? ($proyecto['estado'] ?? '')),
];
?>

<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h1 class="h3">Editar proyecto</h1>
            <p class="text-muted mb-0">Modifica los datos del proyecto seleccionado.</p>
            <?php if (!empty($proyecto['cantidadIntegrantes'])): ?>
                <p class="text-muted">Integrantes registrados: <?= intval($proyecto['cantidadIntegrantes']) ?></p>
            <?php endif; ?>
        </div>
        <a href="?url=proyecto/index" class="btn btn-outline-secondary">Volver al listado</a>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($integrantesActuales)): ?>
        <div class="card mb-4 border-secondary">
            <div class="card-header bg-secondary text-white">
                Integrantes actuales
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0">
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
                            <?php foreach ($integrantesActuales as $integrante): ?>
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="?url=proyecto/actualizar/<?= $proyecto['idProyecto'] ?>" method="post">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Título <span class="text-danger">*</span></label>
                        <input type="text" name="titulo" class="form-control" value="<?= $values['titulo'] ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Objetivo General <span class="text-danger">*</span></label>
                        <textarea name="objetivoGeneral" rows="4" class="form-control" required><?= $values['objetivoGeneral'] ?></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" rows="4" class="form-control"><?= $values['descripcion'] ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Comunidad <span class="text-danger">*</span></label>
                        <select name="comunidad" class="form-select" required>
                            <option value="">Seleccione una comunidad</option>
                            <?php if (!empty($comunidades) && is_array($comunidades)): ?>
                                <?php foreach ($comunidades as $c): ?>
                                    <option value="<?= htmlspecialchars($c['nombre']) ?>" <?= $values['comunidad'] === $c['nombre'] ? 'selected' : '' ?>>
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
                                <option value="<?= $linea['idLineaInvestigacion'] ?>" <?= $values['idLineaInvestigacion'] == $linea['idLineaInvestigacion'] ? 'selected' : '' ?> >
                                    <?= htmlspecialchars($linea['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Trayecto <span class="text-danger">*</span></label>
                        <select name="trayecto" class="form-select" required>
                            <option value="">Seleccione un trayecto</option>
                            <option value="Trayecto I" <?= $values['trayecto'] === 'Trayecto I' ? 'selected' : '' ?>>Trayecto I</option>
                            <option value="Trayecto II" <?= $values['trayecto'] === 'Trayecto II' ? 'selected' : '' ?>>Trayecto II</option>
                            <option value="Trayecto III" <?= $values['trayecto'] === 'Trayecto III' ? 'selected' : '' ?>>Trayecto III</option>
                            <option value="Trayecto IV" <?= $values['trayecto'] === 'Trayecto IV' ? 'selected' : '' ?>>Trayecto IV</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tutor académico</label>
                        <input type="text" name="tutor" class="form-control" value="<?= $values['tutor'] ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Docente formador</label>
                        <input type="text" name="docenteFormador" class="form-control" value="<?= $values['docenteFormador'] ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha inicio <span class="text-danger">*</span></label>
                        <input type="date" name="fechaInicio" class="form-control" value="<?= $values['fechaInicio'] ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha fin</label>
                        <input type="date" name="fechaFin" class="form-control" value="<?= $values['fechaFin'] ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="Activo" <?= $values['estado'] === 'Activo' ? 'selected' : '' ?>>Activo</option>
                            <option value="Finalizado" <?= $values['estado'] === 'Finalizado' ? 'selected' : '' ?>>Finalizado</option>
                            <option value="Suspendido" <?= $values['estado'] === 'Suspendido' ? 'selected' : '' ?>>Suspendido</option>
                            <option value="Aprobado" <?= $values['estado'] === 'Aprobado' ? 'selected' : '' ?>>Aprobado</option>
                            <option value="Rechazado" <?= $values['estado'] === 'Rechazado' ? 'selected' : '' ?>>Rechazado</option>
                        </select>
                    </div>
                </div>

                <hr class="my-4">
                <h5>Agregar integrantes adicionales</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Cédula</label>
                        <input type="text" name="integrantes[0][cedula]" class="form-control" value="<?= htmlspecialchars($integrantesOld[0]['cedula'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="integrantes[0][nombre]" class="form-control" value="<?= htmlspecialchars($integrantesOld[0]['nombre'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Apellido</label>
                        <input type="text" name="integrantes[0][apellido]" class="form-control" value="<?= htmlspecialchars($integrantesOld[0]['apellido'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Correo</label>
                        <input type="email" name="integrantes[0][correo]" class="form-control" value="<?= htmlspecialchars($integrantesOld[0]['correo'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="integrantes[0][telefono]" class="form-control" value="<?= htmlspecialchars($integrantesOld[0]['telefono'] ?? '') ?>">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Actualizar proyecto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>