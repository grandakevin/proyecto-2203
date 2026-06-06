<?php $id_actual = $comunidad['id_comunidad'] ?? $comunidad['id'] ?? 0; ?>

<form action="?url=proyecto/comunidades_editar/<?= $id_actual; ?>" method="POST">
    <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($comunidad['nombre']); ?>" required>
    
    <textarea class="form-control" name="direccion" required><?= htmlspecialchars($comunidad['direccion']); ?></textarea>
    
    <button type="submit" class="btn btn-primary">Actualizar Datos</button>
</form>