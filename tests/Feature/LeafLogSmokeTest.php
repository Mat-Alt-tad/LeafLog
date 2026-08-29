<?php

use App\Models\User;

beforeEach(function () {
    $this->seed();
});

it('renders all public pages', function () {
    $planta = App\Models\Planta::first();

    $this->get('/')->assertOk();
    $this->get('/plantas')->assertOk();
    $this->get('/plantas/' . $planta->id)->assertOk();
    $this->get('/plantas/' . $planta->id . '/visor')->assertOk();
    $this->get('/recetas')->assertOk();
    $this->get('/animales')->assertOk();
    $this->get('/tratamientos')->assertOk();
    $this->get('/buscar?q=menta')->assertOk();
});

it('renders public show pages', function () {
    $this->get('/recetas/' . App\Models\Receta::first()->id)->assertOk();
    $this->get('/animales/' . App\Models\Animal::first()->id)->assertOk();
    $this->get('/tratamientos/' . App\Models\Tratamiento::first()->id)->assertOk();
});

it('hides admin behind auth and role', function () {
    $this->get('/admin')->assertRedirect('/login');

    $lector = User::where('email', 'lector@leaflog.test')->first();
    $this->actingAs($lector)->get('/admin')->assertForbidden();

    $admin = User::where('email', 'admin@leaflog.test')->first();
    $this->actingAs($admin)->get('/admin')->assertOk();
});

it('renders all admin pages for an admin user', function () {
    $admin = User::where('email', 'admin@leaflog.test')->first();
    $this->actingAs($admin);

    $planta = App\Models\Planta::first();
    $receta = App\Models\Receta::first();
    $animal = App\Models\Animal::first();
    $tratamiento = App\Models\Tratamiento::first();

    $this->get('/admin')->assertOk();
    $this->get('/admin/plantas')->assertOk();
    $this->get('/admin/plantas/crear')->assertOk();
    $this->get('/admin/plantas/' . $planta->id . '/editar')->assertOk();
    $this->get('/admin/recetas')->assertOk();
    $this->get('/admin/recetas/crear')->assertOk();
    $this->get('/admin/recetas/' . $receta->id . '/editar')->assertOk();
    $this->get('/admin/animales')->assertOk();
    $this->get('/admin/animales/crear')->assertOk();
    $this->get('/admin/animales/' . $animal->id . '/editar')->assertOk();
    $this->get('/admin/tratamientos')->assertOk();
    $this->get('/admin/tratamientos/crear')->assertOk();
    $this->get('/admin/tratamientos/' . $tratamiento->id . '/editar')->assertOk();
    $this->get('/admin/usuarios')->assertOk();
    $this->get('/admin/categorias')->assertOk();
});

it('renders aportes pages for lector and moderador', function () {
    $lector = User::where('email', 'lector@leaflog.test')->first();
    $this->actingAs($lector);

    $this->get('/aportes')->assertOk();
    $this->get('/aportes/crear')->assertOk();

    $moderador = User::where('email', 'moderador@leaflog.test')->first();
    $this->actingAs($moderador);
    $this->get('/aportes')->assertOk();
});
