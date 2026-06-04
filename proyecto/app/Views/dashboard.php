<?php
include __DIR__ . '/layouts/header.php';
include __DIR__ . '/layouts/sidebar.php';

$totalProyectos = $totalProyectos ?? 0;
$totalActivos = $totalActivos ?? 0;
$totalComunidades = $totalComunidades ?? 0;
$totalProyectosConIntegrantes = $totalProyectosConIntegrantes ?? 0;
$totalEvaluaciones = $totalEvaluaciones ?? 0;
$proyectosRecientes = $proyectosRecientes ?? [];
?>

<style>
.dashboard-top-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 18px;
    margin-bottom: 24px;
}
.dashboard-card {
    background: #ffffff;
    border-radius: 22px;
    padding: 24px;
    box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
    display: flex;
    justify-content: space-between;
    align-items: center;
    min-height: 120px;
}
.dashboard-card .card-info {
    max-width: 75%;
}
.dashboard-card .card-info h3 {
    font-size: 2.1rem;
    margin-bottom: 10px;
    color: #111827;
}
.dashboard-card .card-info p {
    margin: 0;
    color: #6b7280;
    font-size: 0.95rem;
}
.dashboard-card-icon {
    width: 54px;
    height: 54px;
    border-radius: 16px;
    display: grid;
    place-items: center;
    font-size: 1.35rem;
    color: #ffffff;
}
.dashboard-card.blue .dashboard-card-icon { background: #2563eb; }
.dashboard-card.green .dashboard-card-icon { background: #16a34a; }
.dashboard-card.orange .dashboard-card-icon { background: #f97316; }
.dashboard-card.purple .dashboard-card-icon { background: #7c3aed; }
.dashboard-card.pink .dashboard-card-icon { background: #ec4899; }
.dashboard-table-card {
    background: #ffffff;
    border-radius: 22px;
    padding: 24px;
    box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
}
.dashboard-table-card .table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
}
.dashboard-table-card .table-header h2 {
    font-size: 1.25rem;
    margin: 0;
    color: #111827;
}
.btn-view-all {
    color: #2563eb;
    font-weight: 600;
    text-decoration: none;
}
.table-dashboard {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 12px;
}
.table-dashboard th {
    color: #6b7280;
    font-weight: 600;
    padding: 14px 16px;
    text-align: left;
    font-size: 0.95rem;
}
.table-dashboard td {
    background: #f8fafc;
    border: none;
    padding: 18px 16px;
    color: #334155;
    vertical-align: middle;
}
.table-dashboard td strong {
    color: #0f172a;
}
.table-dashboard tr td:first-child {
    border-top-left-radius: 16px;
    border-bottom-left-radius: 16px;
}
.table-dashboard tr td:last-child {
    border-top-right-radius: 16px;
    border-bottom-right-radius: 16px;
}
.badge-status {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 8px 12px;
    border-radius: 999px;
    font-weight: 600;
    font-size: 0.78rem;
}
.badge-active { background: #dcfce7; color: #166534; }
.badge-pending { background: #fef3c7; color: #92400e; }
.badge-finalizado { background: #e0f2fe; color: #0369a1; }
.btn-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #eef2ff;
    color: #4338ca;
    border: none;
    text-decoration: none;
}
.btn-action:hover { background: #e0e7ff; }
</style>

<div class="dashboard-top-cards">
    <div class="dashboard-card blue">
        <div class="card-info">
            <h3><?= $totalProyectos ?></h3>
            <p>Proyectos registrados</p>
        </div>
        <div class="dashboard-card-icon"><i class="fa-solid fa-folder-tree"></i></div>
    </div>
    <div class="dashboard-card green">
        <div class="card-info">
            <h3><?= $totalActivos ?></h3>
            <p>Proyectos activos</p>
        </div>
        <div class="dashboard-card-icon"><i class="fa-solid fa-clipboard-check"></i></div>
    </div>
    <div class="dashboard-card orange">
        <div class="card-info">
            <h3><?= $totalComunidades ?></h3>
            <p>Comunidades activas</p>
        </div>
        <div class="dashboard-card-icon"><i class="fa-solid fa-house-chimney-window"></i></div>
    </div>
    <div class="dashboard-card purple">
        <div class="card-info">
            <h3><?= $totalProyectosConIntegrantes ?></h3>
            <p>Proyectos con integrantes</p>
        </div>
        <div class="dashboard-card-icon"><i class="fa-solid fa-users"></i></div>
    </div>
    <div class="dashboard-card pink">
        <div class="card-info">
            <h3><?= $totalEvaluaciones ?></h3>
            <p>Evaluaciones realizadas</p>
        </div>
        <div class="dashboard-card-icon"><i class="fa-solid fa-star"></i></div>
    </div>
</div>

<div class="dashboard-table-card">
    <div class="table-header">
        <h2>Últimos proyectos registrados</h2>
        <a href="?url=proyecto/index" class="btn-view-all">Ver todos los proyectos →</a>
    </div>
    <div class="table-responsive">
        <table class="table-dashboard">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Título del Proyecto</th>
                    <th>Comunidad</th>
                    <th>Estado</th>
                    <th>Fecha Inicio</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($proyectosRecientes)): ?>
                    <tr>
                        <td colspan="6" class="text-center">No hay proyectos recientes.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($proyectosRecientes as $proyecto): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars(sprintf('PSI%03d', $proyecto['idProyecto'])) ?></strong></td>
                            <td><?= htmlspecialchars($proyecto['titulo']) ?></td>
                            <td><?= htmlspecialchars($proyecto['comunidad']) ?></td>
                            <td>
                                <?php
                                $estado = htmlspecialchars($proyecto['estado']);
                                $badgeClass = $estado === 'Activo' ? 'badge-active' : ($estado === 'Finalizado' ? 'badge-finalizado' : 'badge-pending');
                                ?>
                                <span class="badge-status <?= $badgeClass ?>"><?= $estado ?></span>
                            </td>
                            <td><?= $proyecto['fechaInicio'] ? htmlspecialchars(date('d/m/Y', strtotime($proyecto['fechaInicio']))) : '-' ?></td>
                            <td><a href="?url=proyecto/editar/<?= $proyecto['idProyecto'] ?>" class="btn-action" title="Ver proyecto"><i class="fa-regular fa-eye"></i></a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/layouts/footer.php'; ?>