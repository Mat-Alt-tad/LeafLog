<?php

namespace Database\Seeders;

use App\Models\Animal;
use App\Models\Aporte;
use App\Models\Categoria;
use App\Models\Comentario;
use App\Models\Planta;
use App\Models\Receta;
use App\Models\Subtema;
use App\Models\Tratamiento;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->seedRoles();
        $this->seedUsers();
        $this->seedCatalogo();
        $this->seedAportesYComentarios();
    }

    protected function seedRoles(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $roleLector = Role::firstOrCreate(['name' => 'lector']);
        $roleModerador = Role::firstOrCreate(['name' => 'moderador']);
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
    }

    protected function seedUsers(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@leaflog.test'],
            ['name' => 'Administrador', 'password' => bcrypt('password')]
        );
        $admin->assignRole('admin');

        $moderador = User::firstOrCreate(
            ['email' => 'moderador@leaflog.test'],
            ['name' => 'Moderadora Comunitaria', 'password' => bcrypt('password')]
        );
        $moderador->assignRole('moderador');

        $lector = User::firstOrCreate(
            ['email' => 'lector@leaflog.test'],
            ['name' => 'Lector', 'password' => bcrypt('password')]
        );
        $lector->assignRole('lector');
    }

    protected function seedCatalogo(): void
    {
        // ── Categorías y subtemas ─────────────────────────────────────────────
        $medicinal = Categoria::create(['nombre' => 'Medicinal', 'icono' => 'fas fa-heartbeat', 'orden' => 1, 'descripcion' => 'Plantas de uso medicinal tradicional.']);
        $alimenticia = Categoria::create(['nombre' => 'Alimenticia', 'icono' => 'fas fa-utensils', 'orden' => 2, 'descripcion' => 'Plantas de uso alimenticio.']);
        $ritual = Categoria::create(['nombre' => 'Ritual y Cultural', 'icono' => 'fas fa-dove', 'orden' => 3, 'descripcion' => 'Plantas con valor cultural y ceremonial.']);
        $forraje = Categoria::create(['nombre' => 'Forraje y Animal', 'icono' => 'fas fa-paw', 'orden' => 4, 'descripcion' => 'Plantas usadas en alimentación y cuidado animal.']);

        $subMed1 = Subtema::create(['categoria_id' => $medicinal->id, 'nombre' => 'Dolores y fiebre']);
        $subMed2 = Subtema::create(['categoria_id' => $medicinal->id, 'nombre' => 'Digestión']);
        $subAli1 = Subtema::create(['categoria_id' => $alimenticia->id, 'nombre' => 'Infusiones']);
        $subRit1 = Subtema::create(['categoria_id' => $ritual->id, 'nombre' => 'Limpieza energética']);
        $subFor1 = Subtema::create(['categoria_id' => $forraje->id, 'nombre' => 'Soporte nutricional animal']);

        // ── Plantas ───────────────────────────────────────────────────────────
        $plantas = [
            [
                'nombre' => 'Sábila',
                'cientifico' => 'Aloe vera',
                'categorias' => [$medicinal->id],
                'subtema' => $subMed1->id,
                'tags' => 'aloe, quemaduras, piel, cicatrizante',
                'verificada' => true,
                'descripcion' => 'Planta suculenta ampliamente usada en la región para tratar quemaduras, heridas y afecciones de la piel gracias a su gel refrescante.',
                'contexto' => 'Presente en casi todos los solares del municipio. Su cultivo se transmite de generación en generación y siempre hay una planta a la mano para el botiquín casero.',
                'instrucciones' => 'Cortar una hoja madura, abrirla y aplicar el gel directamente sobre la zona afectada. No ingerir sin preparación adecuada.',
                'relato' => 'Las abuelas del territorio recomiendan tener la sábila en la ventana "para que espante las malas vibras y alivie lo que se enferma".',
            ],
            [
                'nombre' => 'Menta',
                'cientifico' => 'Mentha spicata',
                'categorias' => [$medicinal->id, $alimenticia->id],
                'subtema' => $subMed2->id,
                'tags' => 'hierbabuena, agüita, digestión, infusión',
                'verificada' => true,
                'descripcion' => 'Hierba aromática usada en infusiones para aliviar molestias digestivas, cólicos y la sensación de pesadez.',
                'contexto' => 'Abunda en las huertas caseras. Es la base de la tradicional "agüita" que se toma después de las comidas.',
                'instrucciones' => 'Verter agua caliente sobre hojas frescas, dejar reposar 5 minutos y beber tibia.',
                'relato' => 'Se cuenta que el aroma de la menta "abre el camino" y calma el estómago de los que llegan de largos caminos a Fusagasugá.',
            ],
            [
                'nombre' => 'Manzanilla',
                'cientifico' => 'Matricaria chamomilla',
                'categorias' => [$medicinal->id],
                'subtema' => $subMed2->id,
                'tags' => 'camomila, sueño, digestión, calmante',
                'verificada' => true,
                'descripcion' => 'Flor reconocida por sus propiedades calmantes. Se usa en infusiones para dormir mejor y aliviar cólicos estomacales.',
                'contexto' => 'Sus flores blancas y amarillas son parte del paisaje de los mercados campesinos locales.',
                'instrucciones' => 'Preparar infusión con flores secas y beber en la noche para un sueño reparador.',
            ],
            [
                'nombre' => 'Eucalipto',
                'cientifico' => 'Eucalyptus globulus',
                'categorias' => [$medicinal->id],
                'subtema' => $subMed1->id,
                'tags' => 'gripa, vapor, resfriado, vías respiratorias',
                'verificada' => true,
                'descripcion' => 'Árbol cuyas hojas se usan en vaporizaciones y baños de vapor para aliviar la congestión respiratoria y los resfriados.',
                'contexto' => 'Plantado en los bordes de caminos y fincas; su aceite se considera purificador del ambiente.',
                'instrucciones' => 'Hervir hojas y respirar el vapor con precaución, cubriendo la cabeza con un paño.',
            ],
            [
                'nombre' => 'Limoncillo',
                'cientifico' => 'Cymbopogon citratus',
                'categorias' => [$alimenticia->id],
                'subtema' => $subAli1->id,
                'tags' => 'hierba de limón, infusión, relajante, té',
                'verificada' => true,
                'descripcion' => 'Hierba de aroma cítrico usada en infusiones relajantes y como bebida cotidiana en la región.',
                'contexto' => 'El "agüita de limoncillo" es una bebida emblemática de la provincia, servida en fiestas y ferias.',
            ],
            [
                'nombre' => 'Ruda',
                'cientifico' => 'Ruta graveolens',
                'categorias' => [$ritual->id, $medicinal->id],
                'subtema' => $subRit1->id,
                'tags' => 'protección, limpia, mal de ojo, planta sagrada',
                'verificada' => true,
                'descripcion' => 'Planta de uso ritual tradicional para limpia energética y protección del hogar, además de usos medicinales puntuales.',
                'contexto' => 'Se planta junto a las puertas para "cuidar" la casa. Su uso está ligado a costumbres del territorio.',
                'relato' => 'Los mayores dicen que quemar una rama de ruda al amanecer limpia el hogar de todo lo negativo que llegó durante la noche.',
            ],
            [
                'nombre' => 'Albahaca',
                'cientifico' => 'Ocimum basilicum',
                'categorias' => [$alimenticia->id, $ritual->id],
                'subtema' => $subAli1->id,
                'tags' => 'condimento, aroma, guisos, ahuyenta mosquitos',
                'verificada' => true,
                'descripcion' => 'Planta aromática usada como condimento en la cocina y en ramitos para aromatizar espacios.',
            ],
            [
                'nombre' => 'Botoncillo',
                'cientifico' => 'Spilanthes oleracea',
                'categorias' => [$medicinal->id],
                'subtema' => $subMed2->id,
                'tags' => 'dolor de muelas, anestésico, paludismo',
                'verificada' => true,
                'descripcion' => 'Planta de flores amarillas que produce una sensación de hormigueo; se usa tradicionalmente para el dolor de muelas.',
            ],
            [
                'nombre' => 'Ortiga',
                'cientifico' => 'Urtica dioica',
                'categorias' => [$forraje->id, $medicinal->id],
                'subtema' => $subFor1->id,
                'tags' => 'forraje, animales, purificar sangre, ortigazo',
                'verificada' => true,
                'descripcion' => 'Planta urticante usada en la alimentación de aves y como refuerzo nutricional, con usos tradicionales en circulación.',
                'contexto' => 'En las fincas se da a las gallinas como complemento alimenticio. Se manipula con guantes.',
            ],
            [
                'nombre' => 'Borraja',
                'cientifico' => 'Borago officinalis',
                'categorias' => [$medicinal->id],
                'subtema' => $subMed1->id,
                'tags' => 'tos, gripa, sudorífico, fiebre',
                'verificada' => true,
                'descripcion' => 'Planta de flor azul usada en infusiones para la tos y para inducir la sudoración durante la fiebre.',
            ],
            [
                'nombre' => 'Laurel',
                'cientifico' => 'Laurus nobilis',
                'categorias' => [$alimenticia->id],
                'subtema' => $subAli1->id,
                'tags' => 'condimento, guisos, cocina',
                'verificada' => true,
                'descripcion' => 'Hoja aromática que da sabor a guisos y sopas tradicionales de la región.',
            ],
        ];

        $created = [];
        foreach ($plantas as $i => $data) {
            $categorias = $data['categorias'] ?? [];
            $subtemaId = $data['subtema'] ?? null;
            unset($data['categorias'], $data['subtema']);

            $planta = Planta::create(array_merge(['subtema_id' => $subtemaId], $data));
            $planta->categorias()->sync($categorias);
            $created[] = $planta;
        }

        // ── Recetas ───────────────────────────────────────────────────────────
        $receta1 = Receta::create([
            'titulo' => 'Agüita de limoncillo caliente',
            'instrucciones' => "1. Lavar y cortar un puñado de hojas de limoncillo.\n2. Hervir dos tazas de agua.\n3. Agregar las hojas y dejar hervir 5 minutos a fuego lento.\n4. Colar y endulzar con panela al gusto.\n5. Servir tibia.",
            'tiempo_preparacion' => 10,
            'porciones' => 2,
        ]);
        $receta1->plantas()->attach($created[4]->id, ['cantidad' => '1 puñado', 'parte_usada' => 'Hojas']);

        $receta2 = Receta::create([
            'titulo' => 'Infusión de manzanilla para dormir',
            'instrucciones' => "1. Colocar flores secas de manzanilla en una taza.\n2. Verter agua caliente y tapar.\n3. Dejar reposar 5 minutos.\n4. Colar y beber tibia antes de acostarse.",
            'tiempo_preparacion' => 7,
            'porciones' => 1,
        ]);
        $receta2->plantas()->attach($created[2]->id, ['cantidad' => '2 cucharadas', 'parte_usada' => 'Flores']);

        $receta3 = Receta::create([
            'titulo' => 'Vapor de eucalipto para el resfriado',
            'instrucciones' => "1. Hervir hojas de eucalipto en una olla con agua.\n2. Retirar del fuego y colocar sobre una superficie estable.\n3. Cubrir la cabeza con un paño e inhalar el vapor con cuidado.\n4. Repetir dos veces al día.",
            'tiempo_preparacion' => 15,
            'porciones' => 1,
        ]);
        $receta3->plantas()->attach($created[3]->id, ['cantidad' => '1 puñado', 'parte_usada' => 'Hojas']);

        // ── Animales ──────────────────────────────────────────────────────────
        $gallina = Animal::create(['nombre' => 'Gallina criolla', 'tipo' => 'domestico', 'descripcion' => 'Aves de corral de las fincas; reciben plantas como refuerzo alimenticio natural.']);
        $gallina->plantas()->attach($created[8]->id, ['tipo_consumo' => 'alimento']);

        $vaca = Animal::create(['nombre' => 'Vaca lechera', 'tipo' => 'domestico', 'descripcion' => 'Ganado que pasta forrajes y hierbas del potrero.']);
        $vaca->plantas()->attach($created[8]->id, ['tipo_consumo' => 'complemento']);

        // ── Tratamientos ───────────────────────────────────────────────────────
        $t1 = Tratamiento::create(['sintoma' => 'Dolor de estómago', 'gravedad' => 'baja', 'descripcion' => 'Infusión de menta o manzanilla para calmar la pesadez y los cólicos.']);
        $t1->plantas()->attach($created[1]->id, ['parte_usada' => 'Hojas', 'preparacion' => 'Infusión']);

        $t2 = Tratamiento::create(['sintoma' => 'Resfriado y congestión', 'gravedad' => 'media', 'descripcion' => 'Vaporizaciones de eucalipto para descongestionar las vías respiratorias.']);
        $t2->plantas()->attach($created[3]->id, ['parte_usada' => 'Hojas', 'preparacion' => 'Vapor']);

        $t3 = Tratamiento::create(['sintoma' => 'Quemaduras superficiales', 'gravedad' => 'media', 'descripcion' => 'Aplicación de gel de sábila fresca sobre la zona afectada.']);
        $t3->plantas()->attach($created[0]->id, ['parte_usada' => 'Gel de la hoja', 'preparacion' => 'Aplicación directa']);

        $t4 = Tratamiento::create(['sintoma' => 'Insomnio', 'gravedad' => 'baja', 'descripcion' => 'Infusión de manzanilla antes de dormir para favorecer el descanso.']);
        $t4->plantas()->attach($created[2]->id, ['parte_usada' => 'Flores', 'preparacion' => 'Infusión']);
    }

    protected function seedAportesYComentarios(): void
    {
        $lector = User::where('email', 'lector@leaflog.test')->first();
        $moderador = User::where('email', 'moderador@leaflog.test')->first();
        $sabila = Planta::where('nombre', 'Sábila')->first();

        if ($lector && $sabila) {
            Aporte::create([
                'user_id' => $lector->id,
                'planta_id' => $sabila->id,
                'estado' => 'aprobado',
                'contenido' => 'Mi abuela añadía un poco de miel al gel de sábila para hacer la aplicación más tolerable en niños.',
            ]);

            Comentario::create([
                'user_id' => $moderador->id,
                'planta_id' => $sabila->id,
                'cuerpo' => 'Gracias por el aporte, lo incorporaremos a la ficha.',
                'likes' => 2,
            ]);
        }
    }
}
