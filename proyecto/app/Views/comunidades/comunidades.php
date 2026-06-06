<div class="container-fluid px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-gray-800"><i class="fa-solid fa-users-gear me-2"></i>Gestión de Comunidades</h2>
        <div class="d-flex gap-2">
            <a href="?url=proyecto/crear" class="btn btn-success shadow-sm">
                <i class="fa-solid fa-folder-plus me-2"></i>Registrar Proyecto
            </a>
            <a href="?url=proyecto/comunidades_crear" class="btn btn-primary shadow-sm">
                <i class="fa-solid fa-plus me-2"></i>Nueva Comunidad
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i><?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle" width="100%" cellspacing="0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre de la Comunidad</th>
                            <th>Tipo</th>
                            <th>Dirección / Ubicación</th>
                            <th class="text-center" style="width: 150px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($comunidades) && is_array($comunidades)): ?>
                            <?php foreach ($comunidades as $comunidad): ?>
                                <?php 
                                    // Truco de compatibilidad: detectamos si tu BD usa 'id' o 'id_comunidad'
                                    $id_actual = $comunidad['id_comunidad'] ?? $comunidad['id'] ?? null; 
                                ?>
                                <tr>
                                    <td><strong><?= $id_actual; ?></strong></td>
                                    <td><?= htmlspecialchars($comunidad['nombre']); ?></td>
                                    <td>
                                        <span class="badge bg-info text-dark">
                                            <?= htmlspecialchars($comunidad['tipo_comunidad'] ?? 'Urbana'); ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($comunidad['direccion']); ?></td>
                                    <td class="text-center">
                                        <div class="d-inline-flex gap-1">
                                            <a href="?url=proyecto/comunidades_editar/<?= $id_actual; ?>" class="btn btn-warning btn-sm" title="Editar">
                                                <i class="fa-solid fa-pen-to-square"></i> Editar
                                            </a>
                                            <a href="?url=proyecto/comunidades_eliminar/<?= $id_actual; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar esta comunidad?');" title="Eliminar">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="fa-solid fa-folder-open display-4 d-block mb-2"></i>
                                    No hay comunidades registradas actualmente.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>