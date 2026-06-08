<div class="sidebar">
    <div>
        <div class="sidebar-brand">
            <i class="fa-solid fa-layer-group"></i> Sistema PSI - MLK
        </div>
        <ul class="sidebar-menu">
            <li class="<?php echo (!isset($_GET['url']) || $_GET['url'] == 'home/index') ? 'active' : ''; ?>">
                <a href="?url=home/index"><i class="fa-solid fa-house"></i> Inicio</a>
            </li>
           
            <li><a href="#"><i class="fa-solid fa-file-invoice"></i> Diagnósticos</a></li>
            
            <li class="nav-item <?= (isset($_GET['url']) && ($_GET['url'] === 'proyecto/index' || $_GET['url'] === 'proyecto/crear' || $_GET['url'] === 'proyecto/editar')) ? 'active' : ''; ?>">
    <a class="nav-link" href="?url=proyecto/index">
        <i class="fa-solid fa-folder me-2"></i>
        <span>Proyectos</span>
    </a>
</li>

<li class="nav-item <?= (isset($_GET['url']) && strpos($_GET['url'], 'proyecto/comunidades_') !== false) ? 'active' : ''; ?>">
    <a class="nav-link" href="?url=proyecto/comunidades_index">
        <i class="fa-solid fa-building me-2"></i>
        <span>Comunidades</span>
    </a>
</li>
            
            <li><a href="#"><i class="fa-solid fa-chart-line"></i> Avances</a></li>
            <li><a href="#"><i class="fa-solid fa-star"></i> Evaluaciones</a></li>
            <li><a href="#"><i class="fa-solid fa-graduation-cap"></i> Líneas de Investigación</a></li>
            <li><a href="#"><i class="fa-solid fa-user-shield"></i> Usuarios</a></li>
            <li><a href="#"><i class="fa-solid fa-file-pdf"></i> Reportes</a></li>
            <!-- Importación de Datos eliminado temporalmente -->
            <li><a href="#"><i class="fa-solid fa-sliders"></i> Configuración</a></li>
        </ul>
    </div>
    <div class="sidebar-logout">
        <a href="#"><i class="fa-solid fa-power-off"></i> Salir</a>
    </div>
</div>

<div class="main-wrapper">
    <div class="navbar">
        <div class="user-profile">
            <i class="fa-regular fa-bell" style="margin-right: 15px; color: #64748b; cursor:pointer;"></i>
            <i class="fa-regular fa-circle-user" style="font-size: 16px;"></i>
            <span>Coordinador PSI</span>
            <i class="fa-solid fa-chevron-down" style="font-size: 10px; color: #94a3b8;"></i>
        </div>
    </div>
    <div class="content-body">