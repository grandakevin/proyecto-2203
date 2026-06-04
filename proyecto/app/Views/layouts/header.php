<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema PSI-MLK</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { display: flex; background-color: #f4f6f9; min-height: 100vh; }
        .sidebar { width: 260px; background-color: #0b2545; color: #fff; display: flex; flex-direction: column; justify-content: space-between; flex-shrink: 0; }
        .sidebar-brand { padding: 20px; font-size: 16px; font-weight: bold; border-bottom: 1px solid #134074; text-align: center; background: #081c33; letter-spacing: 0.5px; }
        .sidebar-menu { list-style: none; padding: 15px 0; flex-grow: 1; overflow-y: auto; }
        .sidebar-menu li a { display: flex; align-items: center; padding: 11px 22px; color: #b3c5d7; text-decoration: none; font-size: 13px; transition: all 0.2s; }
        .sidebar-menu li a i { margin-right: 12px; width: 20px; text-align: center; font-size: 14px; }
        .sidebar-menu li a:hover, .sidebar-menu li.active a { color: #fff; background-color: #134074; border-left: 4px solid #00b4d8; }
        .sidebar-logout { padding: 15px 20px; border-top: 1px solid #134074; background: #081c33; }
        .sidebar-logout a { color: #ff4d4d; text-decoration: none; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 500; }
        .main-wrapper { flex-grow: 1; display: flex; flex-direction: column; min-height: 100vh; overflow: hidden; }
        .navbar { height: 60px; background: #fff; display: flex; align-items: center; justify-content: flex-end; padding: 0 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.03); flex-shrink: 0; }
        .user-profile { display: flex; align-items: center; gap: 10px; font-size: 13px; color: #333; font-weight: 500; }
        .content-body { padding: 25px; flex-grow: 1; overflow-y: auto; }
        .sidebar-menu { margin-bottom: 0; }
    </style>
</head>
<body>