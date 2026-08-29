<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\AporteController;
use App\Http\Controllers\BusquedaController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\PlantaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecetaController;
use App\Http\Controllers\TratamientoController;
use App\Http\Controllers\VisorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/plantas', [PlantaController::class, 'index'])->name('plantas.index');
Route::get('/plantas/{planta}', [PlantaController::class, 'show'])->name('plantas.show');

Route::get('/recetas', [RecetaController::class, 'index'])->name('recetas.index');
Route::get('/recetas/{receta}', [RecetaController::class, 'show'])->name('recetas.show');

Route::get('/animales', [AnimalController::class, 'index'])->name('animales.index');
Route::get('/animales/{animal}', [AnimalController::class, 'show'])->name('animales.show');

Route::get('/tratamientos', [TratamientoController::class, 'index'])->name('tratamientos.index');
Route::get('/tratamientos/{tratamiento}', [TratamientoController::class, 'show'])->name('tratamientos.show');

Route::get('/buscar', [BusquedaController::class, 'index'])->name('buscar');
Route::get('/buscar/autocompletar', [BusquedaController::class, 'autocompletar'])->name('buscar.autocompletar');

Route::get('/plantas/{planta}/visor', [VisorController::class, 'show'])->name('plantas.visor');

Route::middleware('auth')->group(function () {
    Route::post('/plantas/{planta}/comentarios', [ComentarioController::class, 'store'])->name('comentarios.store');
    Route::post('/comentarios/{comentario}/like', [ComentarioController::class, 'like'])->name('comentarios.like');
    Route::delete('/comentarios/{comentario}', [ComentarioController::class, 'destroy'])->name('comentarios.destroy');
});

Route::middleware(['auth', 'role:lector|moderador|admin'])->group(function () {
    Route::get('/aportes', [AporteController::class, 'index'])->name('aportes.index');
    Route::get('/aportes/crear', [AporteController::class, 'create'])->name('aportes.create');
    Route::post('/aportes', [AporteController::class, 'store'])->name('aportes.store');
});

Route::middleware(['auth', 'role:moderador|admin'])->group(function () {
    Route::post('/aportes/{aporte}/aprobar', [AporteController::class, 'aprobar'])->name('aportes.aprobar');
    Route::post('/aportes/{aporte}/rechazar', [AporteController::class, 'rechazar'])->name('aportes.rechazar');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/plantas', [AdminController::class, 'plantas'])->name('plantas.index');
    Route::get('/plantas/crear', [PlantaController::class, 'create'])->name('plantas.create');
    Route::post('/plantas', [PlantaController::class, 'store'])->name('plantas.store');
    Route::get('/plantas/{planta}/editar', [PlantaController::class, 'edit'])->name('plantas.edit');
    Route::put('/plantas/{planta}', [PlantaController::class, 'update'])->name('plantas.update');
    Route::delete('/plantas/{planta}', [PlantaController::class, 'destroy'])->name('plantas.destroy');

    Route::post('/plantas/{planta}/modelos', [VisorController::class, 'store'])->name('plantas.modelos.store');
    Route::delete('/modelos/{modelo}', [VisorController::class, 'destroy'])->name('modelos.destroy');

    Route::get('/recetas', [AdminController::class, 'recetas'])->name('recetas.index');
    Route::get('/recetas/crear', [RecetaController::class, 'create'])->name('recetas.create');
    Route::post('/recetas', [RecetaController::class, 'store'])->name('recetas.store');
    Route::get('/recetas/{receta}/editar', [RecetaController::class, 'edit'])->name('recetas.edit');
    Route::put('/recetas/{receta}', [RecetaController::class, 'update'])->name('recetas.update');
    Route::delete('/recetas/{receta}', [RecetaController::class, 'destroy'])->name('recetas.destroy');

    Route::get('/animales', [AdminController::class, 'animales'])->name('animales.index');
    Route::get('/animales/crear', [AnimalController::class, 'create'])->name('animales.create');
    Route::post('/animales', [AnimalController::class, 'store'])->name('animales.store');
    Route::get('/animales/{animal}/editar', [AnimalController::class, 'edit'])->name('animales.edit');
    Route::put('/animales/{animal}', [AnimalController::class, 'update'])->name('animales.update');
    Route::delete('/animales/{animal}', [AnimalController::class, 'destroy'])->name('animales.destroy');
    Route::post('/animales/{animal}/plantas', [AnimalController::class, 'attachPlantas'])->name('animales.plantas.store');

    Route::get('/tratamientos', [AdminController::class, 'tratamientos'])->name('tratamientos.index');
    Route::get('/tratamientos/crear', [TratamientoController::class, 'create'])->name('tratamientos.create');
    Route::post('/tratamientos', [TratamientoController::class, 'store'])->name('tratamientos.store');
    Route::get('/tratamientos/{tratamiento}/editar', [TratamientoController::class, 'edit'])->name('tratamientos.edit');
    Route::put('/tratamientos/{tratamiento}', [TratamientoController::class, 'update'])->name('tratamientos.update');
    Route::delete('/tratamientos/{tratamiento}', [TratamientoController::class, 'destroy'])->name('tratamientos.destroy');

    Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('usuarios.index');
    Route::get('/categorias', [AdminController::class, 'categorias'])->name('categorias.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
