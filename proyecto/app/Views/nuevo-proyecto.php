<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Proyecto - PSI-MLK</title>
    <link href="https://fonts.googleapis.com/css2 family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-color: #f4f6f9; padding: 40px; display: flex; justify-content: center; }
        .form-container { background: #fff; width: 100%; max-width: 600px; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .form-header { border-bottom: 2px solid #edf2f7; padding-bottom: 15px; margin-bottom: 20px; }
        .form-header h2 { color: #0b2545; font-size: 20px; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-size: 13px; color: #64748b; font-weight: 600; margin-bottom: 6px; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; color: #334155; outline: none; }
        .form-group input:focus, .form-group textarea:focus { border-color: #00b4d8; }
        .btn-submit { background: #0b2545; color: #fff; border: none; padding: 12px 20px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; width: 100%; transition: background 0.2s; }
        .btn-submit:hover { background: #134074; }
        .btn-back { display: block; text-align: center; margin-top: 15px; font-size: 13px; color: #64748b; text-decoration: none; }
    </style>
</head>
<body>

<div class="form-container">
    <div class="form-header">
        <h2><i class="fa-solid fa-folder-plus"></i> Registrar Proyecto (CUS-02)</h2>
    </div>

    <form action="/proyecto/public/proyectos/guardar" method="POST">
        
        <div class="form-group">
            <label for="titulo">Título del Proyecto</label>
            <input type="text" id="titulo" name="titulo" placeholder="Ej. Automatización del Sistema de Aguas" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" rows="4" placeholder="Breve resumen del alcance del proyecto..." required></textarea>
        </div>

        <div class="form-group">
            <label for="fechaFinalizacion">Fecha Estimada de Finalización</label>
            <input type="date" id="fechaFinalizacion" name="fechaFinalizacion" required>
        </div>

        <div class="form-group">
            <label for="estado">Estado Inicial del Proyecto</label>
            <select id="estado" name="estado">
                <option value="Registrado">Registrado</option>
                <option value="EnEjecucion">En Ejecución</option>
            </select>
        </div>

        <button type="submit" class="btn-submit">Guardar Proyecto</button>
        <a href="/proyecto/public/" class="btn-back">← Volver al Inicio</a>
    </form>
</div>

</body>
</html>