# Documentación del proyecto PSI-MLK

## 1. Descripción general

Este proyecto es un sistema web en PHP que gestiona proyectos sociotecnológicos, sus líneas de investigación y sus integrantes. Está construido con una arquitectura MVC ligera y sin un framework completo, usando:

- PHP con namespaces
- PDO para conexión con MySQL
- Bootstrap para estilos base
- Vistas PHP sencillas para renderizar HTML con datos dinámicos
- Enrutamiento básico a través de `public/index.php`

El objetivo principal es mantener la estructura MVC existente y adaptar el módulo de proyectos al registro académico requerido.

---

## 2. Estructura del proyecto

```
proyecto/
  composer.json
  index.php
  public/
    index.php
  app/
    Config/
      Database.php
    Controllers/
      HomeController.php
      ProyectoController.php
      ...otros controladores...
    Models/
      Proyecto.php
      LineaInvestigacion.php
      Proyectista.php
      ...otros modelos...
    Views/
      dashboard.php
      layouts/
        header.php
        sidebar.php
        footer.php
      proyecto/
        crear.php
        editar.php
        listar.php
        integrantes.php
        crear_integrante.php
        editar_integrante.php
  vendor/
  database/
    create_proyecto_table.sql
```

---

## 3. Enrutamiento y arranque

### 3.1 `public/index.php`

Este archivo es el punto de entrada principal de la aplicación para el servidor web.

Funciona así:

1. Inicia sesión con `session_start()`.
2. Carga `vendor/autoload.php` de Composer.
3. Lee la variable `$_GET['url']` y la divide en segmentos.
4. Construye el controlador a partir del primer segmento: `App\Controllers\{Nombre}Controller`.
5. Llama al método definido en el segundo segmento.
6. Pasa los parámetros restantes al método.

Ejemplo:

- `?url=proyecto/index` carga `ProyectoController::index()`.
- `?url=proyecto/crear` carga `ProyectoController::crear()`.
- `?url=proyecto/guardar` carga `ProyectoController::guardar()`.

### 3.2 `index.php` en la raíz

Solo incluye `public/index.php`, permitiendo usar la raíz del proyecto como alias sin cambiar el router.

---

## 4. Configuración de la base de datos

### 4.1 `app/Config/Database.php`

Este archivo maneja la conexión con MySQL y la creación automática de esquemas.

Comportamiento:

- Conecta a MySQL con PDO.
- Crea la base de datos `psi_mlk` si no existe.
- Crea tablas esenciales:
  - `linea_investigacion`
  - `proyecto`
  - `proyectista`
- Inserta líneas de investigación iniciales cuando la tabla está vacía.
- Añade columnas faltantes en `proyecto` si el esquema antiguo aún no las tiene.
- Elimina columnas antiguas `lineaInvestigacion` y `periodoAcademico` cuando existen.
- Asegura la clave foránea `fk_proyecto_linea_investigacion`.

### 4.2 Tablas principales

#### `linea_investigacion`
- `idLineaInvestigacion` (PK)
- `nombre`
- `descripcion`
- `created_at`

#### `proyecto`
- `idProyecto` (PK)
- `titulo`
- `objetivoGeneral`
- `descripcion`
- `idLineaInvestigacion` (FK a `linea_investigacion`)
- `trayecto`
- `tutor`
- `docenteFormador`
- `comunidad`
- `fechaInicio`
- `fechaFin`
- `estado`
- `created_at`

#### `proyectista`
- `idProyectista` (PK)
- `idProyecto` (FK a `proyecto`)
- `cedula`
- `nombre`
- `apellido`
- `correo`
- `telefono`
- `created_at`

---

## 5. Modelos

### 5.1 `app/Models/Proyecto.php`

Representa la entidad proyecto y contiene métodos de acceso a datos:

- `obtenerTodos()`
  - Devuelve todos los proyectos con:
    - nombre de línea de investigación (`lineaInvestigacion`)
    - cantidad de integrantes (`cantidadIntegrantes`)
  - Usa un `LEFT JOIN` con `linea_investigacion` y `proyectista`.
- `obtenerPorId($id)`
  - Devuelve un solo proyecto por su ID con la misma información extendida.
- `guardar($datos)`
  - Inserta un nuevo proyecto y devuelve el ID generado.
- `actualizar($id, $datos)`
  - Actualiza los campos del proyecto.
- `eliminar($id)`
  - Elimina el proyecto de la base de datos.

### 5.2 `app/Models/LineaInvestigacion.php`

Administra las líneas de investigación:

- `obtenerTodos()`
  - Devuelve todas las líneas ordenadas por nombre.
- `obtenerPorId($id)`
  - Busca la línea por su ID.

Se usa para llenar el selector de `Línea de investigación` en el formulario de proyectos.

### 5.3 `app/Models/Proyectista.php`

Gestiona los integrantes asociados a un proyecto:

- `obtenerPorProyecto($idProyecto)`
  - Lista integrantes de un proyecto.
- `contarPorProyecto($idProyecto)`
  - Cuenta integrantes de un proyecto.
- `obtenerPorId($idProyectista)`
  - Busca un integrante por su ID.
- `guardar($datos)`
  - Inserta un nuevo integrante.
- `actualizar($idProyectista, $datos)`
  - Actualiza los datos de un integrante.
- `eliminar($idProyectista)`
  - Borra el integrante.

---

## 6. Controladores

### 6.1 `app/Controllers/ProyectoController.php`

Es el controlador más importante del módulo de proyectos.

#### Métodos principales:

- `index()`
  - Obtiene todos los proyectos y muestra la vista `listar.php`.
- `crear()`
  - Carga líneas de investigación y valores anteriores de formulario.
  - Muestra `crear.php`.
- `guardar()`
  - Valida el formulario POST.
  - Crea el proyecto en BD.
  - Inserta integrantes opcionales.
  - Redirige a la lista o vuelve a la forma con errores.
- `editar($id)`
  - Carga proyecto, líneas y lista de integrantes actuales.
  - Muestra `editar.php`.
- `actualizar($id)`
  - Valida y actualiza el proyecto.
  - Agrega nuevos integrantes si se envían.
- `eliminar($id)`
  - Borra un proyecto.

#### Gestión de integrantes:
- `integrantes($idProyecto)`
  - Muestra la lista de integrantes de un proyecto.
- `crearIntegrante($idProyecto)`
  - Muestra formulario para agregar integrante.
- `guardarIntegrante($idProyecto)`
  - Valida y guarda el integrante.
- `editarIntegrante($idProyecto, $idProyectista)`
  - Muestra formulario de edición.
- `actualizarIntegrante($idProyecto, $idProyectista)`
  - Valida y actualiza el integrante.
- `eliminarIntegrante($idProyecto, $idProyectista)`
  - Elimina un integrante.

#### Validaciones importantes

En `guardar()` y `actualizar()` de proyecto se exigen:
- `titulo`
- `objetivoGeneral`
- `comunidad`
- `fechaInicio`
- `trayecto`
- `idLineaInvestigacion`

También se valida:
- que `trayecto` sea uno de los valores permitidos
- que `estado` sea uno de los valores permitidos
- que la línea de investigación exista en la tabla `linea_investigacion`

Para integrantes se exige:
- `cedula`
- `nombre`
- `apellido`
- `correo`

---

### 6.2 `app/Controllers/HomeController.php`

Genera el dashboard inicial.

Funciones principales:

- Crea un modelo `Proyecto`.
- Obtiene todos los proyectos.
- Calcula métricas:
  - proyectos totales
  - proyectos activos
  - comunidades distintas
  - proyectos con integrantes
- Carga `Views/dashboard.php`.

Esta vista no editada gestiona solo el tablero inicial.

---

## 7. Vistas y presentación

### 7.1 Layout general

Las vistas comparten una estructura común con:

- `app/Views/layouts/header.php`
  - Arranca el documento HTML.
  - Carga Bootstrap CSS desde CDN.
  - Define estilos globales y la barra lateral.
- `app/Views/layouts/sidebar.php`
  - Contiene el menú de navegación principal.
  - Incluye enlaces a Home, Proyectos y otros módulos.
- `app/Views/layouts/footer.php`
  - Cierra los contenedores HTML.
  - Carga Bootstrap JS desde CDN.

### 7.2 Bootstrap

Sí, el proyecto usa Bootstrap 5 para:

- rejillas (`row`, `col-*`)
- botones (`btn`, `btn-primary`, `btn-outline-secondary`)
- tarjetas (`card`, `card-body`)
- tablas (`table`, `table-responsive`)
- formularios (`form-control`, `form-select`)

Además existen estilos personalizados dentro de `header.php` y en `dashboard.php` para la apariencia del panel.

### 7.3 Vistas del módulo de proyectos

#### `app/Views/proyecto/crear.php`
- Formulario para registrar un nuevo proyecto.
- Incluye campos académicos y administrativos.
- Trae `idLineaInvestigacion` desde el modelo `LineaInvestigacion`.
- Permite agregar hasta dos integrantes desde el mismo formulario.

#### `app/Views/proyecto/editar.php`
- Similar a `crear.php` pero con datos precargados.
- Muestra integrantes ya asociados al proyecto.
- Permite agregar integrantes adicionales.

#### `app/Views/proyecto/listar.php`
- Lista todos los proyectos en una tabla.
- Muestra columnas como `Línea de Investigación`, `Trayecto`, `Estado`, `Integrantes`.
- Incluye botones de `Editar` y `Eliminar`.

#### `app/Views/proyecto/integrantes.php`
- Lista integrantes de un solo proyecto.
- Permite crear, editar y eliminar integrantes.

#### `app/Views/proyecto/crear_integrante.php` y `editar_integrante.php`
- Formularios para crear o actualizar un integrante.
- Campos obligatorios: cédula, nombre, apellido, correo.

### 7.4 Vista del Home (`dashboard.php`)

- Muestra métricas clave extraídas de proyectos.
- Usa tarjetas visuales y una tabla de proyectos recientes.
- Carga parte del CSS en el propio archivo para personalizar el dashboard.
- Depende de `HomeController` para recibir datos.

---

## 8. Flujo de un caso de uso

### Crear un proyecto

1. Se ingresa a `?url=proyecto/crear`.
2. `public/index.php` invoca `ProyectoController::crear()`.
3. El controlador carga líneas con `LineaInvestigacion::obtenerTodos()`.
4. Se muestra el formulario en `crear.php`.
5. El usuario envía datos a `?url=proyecto/guardar`.
6. `ProyectoController::guardar()` valida los datos.
7. Si pasa validación, guarda el proyecto y los integrantes.
8. Redirige a `?url=proyecto/index` con mensaje de éxito.

### Editar un proyecto

1. Se ingresa a `?url=proyecto/editar/{id}`.
2. El controlador carga el proyecto y sus integrantes.
3. Renderiza `editar.php` con los datos.
4. Al enviar, `actualizar()` valida y actualiza.
5. Puede guardar nuevos integrantes opcionales.

### Manejar integrantes

- `proyecto/integrantes/{id}` lista los integrantes.
- `proyecto/crearIntegrante/{id}` muestra la forma de creación.
- `proyecto/guardarIntegrante/{id}` guarda el integrante.
- `proyecto/editarIntegrante/{idProyecto}/{idProyectista}` actualiza el registro.

---

## 9. Puntos clave para la defensa

### 9.1 Arquitectura MVC

- `Controllers` gestionan el flujo y la validación.
- `Models` ejecutan consultas SQL y devuelven datos.
- `Views` solo muestran HTML con variables inyectadas.

### 9.2 Normalización de datos

- La línea de investigación se guarda como `idLineaInvestigacion` en `proyecto`.
- Esto evita guardar texto libre y permite reutilizar líneas predefinidas.
- `Proyectista` es tabla separada para manejar varios integrantes por proyecto.

### 9.3 Uso de Bootstrap y CSS

- Bootstrap se usa para diseño responsivo y componentes rápidos.
- El CSS propio define el layout principal del panel.
- Esto combina velocidad de desarrollo con una apariencia personalizada.

### 9.4 Validación y mensajes

- El controlador valida datos obligatorios.
- Los errores se guardan en `$_SESSION['error']`.
- Si falla la validación, el usuario vuelve al formulario con los datos ingresados.

### 9.5 Escalabilidad

- La estructura permite agregar nuevos módulos con más controladores, modelos y vistas.
- El router básico puede recibir nuevas rutas sin cambios grandes.

---

## 10. Recomendaciones rápidas

Si quieres algo para leer en la defensa, di:

- “Este proyecto es un MVC ligero en PHP con acceso a base de datos mediante PDO.
- “El punto de entrada es `public/index.php`, que convierte la URL en controlador y método.
- “El módulo `Proyecto` maneja los proyectos y sus integrantes; el módulo `Home` muestra un dashboard resumido.
- “Usamos Bootstrap para los estilos base, pero agregamos CSS propio para el sidebar y el panel principal.
- “La base de datos está normalizada: `proyecto` apunta a `linea_investigacion` y `proyectista` almacena los integrantes.”

---

## 11. Archivos clave para revisar antes de la defensa

- `public/index.php`
- `app/Config/Database.php`
- `app/Controllers/ProyectoController.php`
- `app/Controllers/HomeController.php`
- `app/Models/Proyecto.php`
- `app/Models/LineaInvestigacion.php`
- `app/Models/Proyectista.php`
- `app/Views/layouts/header.php`
- `app/Views/proyecto/crear.php`
- `app/Views/proyecto/editar.php`
- `app/Views/proyecto/listar.php`
- `app/Views/dashboard.php`

---

## 12. Nota adicional

La aplicación está diseñada para que el propio servidor construya el esquema de base de datos al iniciar, lo que facilita la puesta en marcha y evita dependencias de migraciones manuales.
