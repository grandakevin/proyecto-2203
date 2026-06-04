<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Panel MVC</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: #f4f6f9; 
            padding: 40px; 
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        h1 { color: #2c3e50; margin-bottom: 20px; font-size: 24px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px 15px; border-bottom: 1px solid #e0e0e0; text-align: left; }
        th { background-color: #34495e; color: white; font-weight: 600; }
        tr:hover { background-color: #f8f9fa; }
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            background: #e1f5fe;
            color: #0288d1;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Lista de Usuarios</h1>
       <center> <p style="color: #7f8c8d;">Por fin me salio esta vaina </p></center>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Rol de Usuario</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($datosUsuarios) && is_array($datosUsuarios)): ?>
                    <?php foreach ($datosUsuarios as $usuario): ?>
                        <tr>
                            <td><strong><?php echo $usuario['id']; ?></strong></td>
                            <td><?php echo $usuario['nombre']; ?></td>
                            <td><span class="badge"><?php echo $usuario['rol']; ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="text-align: center; color: red;">
                            No se encontraron datos de usuarios o la variable no está definida.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>