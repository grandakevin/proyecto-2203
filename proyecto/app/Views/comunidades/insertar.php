<div class="container-fluid px-4 mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="m-0 font-weight-bold"><i class="fa-solid fa-building me-2"></i>Registrar Nueva Comunidad</h5>
                    <a href="?url=proyecto/comunidades_index" class="btn btn-light btn-sm text-primary">
                        <i class="fa-solid fa-arrow-left me-1"></i> Volver a la Lista
                    </a>
                </div>
                <div class="card-body">
                    
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i><?= $_SESSION['error']; unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="?url=proyecto/comunidades_guardar" method="POST">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label font-weight-bold">Nombre de la Comunidad <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej. Urbanización Central" required>
                            </div>
                            <div class="col-md-6">
                                <label for="tipo_comunidad" class="form-label font-weight-bold">Tipo de Comunidad</label>
                                <select class="form-select" id="tipo_comunidad" name="tipo_comunidad">
                                    <option value="Urbana" selected>Urbana</option>
                                    <option value="Rural">Rural</option>
                                    <option value="Indígena">Indígena</option>
                                    <option value="Consejo Comunal">Consejo Comunal</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="direccion" class="form-label font-weight-bold">Dirección / Ubicación Exacta <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="direccion" name="direccion" rows="3" placeholder="Indique calles, avenidas o puntos de referencia..." required></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="?url=proyecto/comunidades_index" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Guardar Comunidad
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>