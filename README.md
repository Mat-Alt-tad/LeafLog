# LeafLog

LeafLog es un prototipo de plataforma digital para catalogar plantas medicinales y preservar los saberes etnobotánicos de la zona rural de Fusagasugá. El sistema conecta plantas con recetas, medicina tradicional y alimentación animal, e incluye un visor 3D con efecto Pepper's Ghost en modos caja y pirámide.

## Requisitos

- PHP 8.3 o superior (probado con PHP 8.5).
- Composer.
- Node.js 20 o superior.
- MySQL/MariaDB o SQLite.
- Extensiones PHP habituales de Laravel (`pdo`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`).

## Instalación

```bash
git clone https://github.com/Mat-Alt-tad/LeafLog.git
cd LeafLog
composer install
cp .env.example .env
php artisan key:generate
# configurar DB_HOST, DB_DATABASE, DB_USERNAME y DB_PASSWORD en .env
php artisan migrate --seed
npm install
npm run build
php artisan storage:link
php artisan serve
```

La aplicación queda disponible en `http://127.0.0.1:8000`.

## Modelos 3D

El seeder copia a `storage/app/public/modelos/` los cinco modelos optimizados que están en `public/models/3d/`:

- `sabila.glb`
- `menta.glb`
- `manzanilla.glb`
- `eucalipto.glb`
- `limoncillo.glb`

Fueron generados con TRELLIS.2 de Microsoft Research a partir de imágenes, convertidos con `trimesh` y optimizados con `gltf-transform` (simplificación, cuantización y texturas WebP). Los archivos finales pesan entre 3,65 MB y 8,08 MB.

El visor usa Three.js y carga GLB estándar. Las versiones optimizadas conservan la extensión `KHR_mesh_quantization`, soportada por `GLTFLoader`.

Ruda y Albahaca tienen imagen de referencia, pero sus modelos 3D quedan pendientes.

## Pruebas

```bash
php artisan test
```

La suite cubre autenticación, perfil, rutas principales, catálogo, búsqueda y flujos críticos. La evidencia de la ejecución de sustentación está en `documentacion/evidencias/pruebas-2026-10-22.txt`.

## Estructura

- `app/Http/Controllers`: controladores de catálogo, búsqueda, administración y visor.
- `app/Models`: modelos Eloquent.
- `database/migrations`: esquema relacional.
- `database/seeders`: datos de demostración y asociación de los modelos 3D.
- `resources/js/visor.js`: visor Three.js.
- `resources/views`: vistas Blade.
- `public/models/3d`: GLB optimizados.
- `tests`: suite Pest.

## Versión evaluada

La versión de sustentación se identifica con la etiqueta `v1.0-sustentacion`. El README y los modelos forman parte de esa versión.

## Autoría

Trabajo de grado de Mateo Venegas Solano, Programa de Ingeniería de Sistemas y Computación, Universidad de Cundinamarca, Sede Fusagasugá.

Director: Daniel Alejandro Moncada Beltrán. Codirector: Gustavo Andrés Rodríguez Méndez.
