<?php
// seeds/TourSeeder.php

class TourSeeder
{
    private $pdo;
    private $agencyId;

    public function __construct($pdo, $agencyId)
    {
        $this->pdo = $pdo;
        $this->agencyId = $agencyId;
    }

    public function run()
    {
        // Limpiar datos previos de esta agencia para evitar duplicidad
        $stmtDeleteIti = $this->pdo->prepare("DELETE FROM itinerarios WHERE tour_id IN (SELECT id FROM tours WHERE agencia_id = ?)");
        $stmtDeleteIti->execute([$this->agencyId]);

        $stmtDeleteTours = $this->pdo->prepare("DELETE FROM tours WHERE agencia_id = ?");
        $stmtDeleteTours->execute([$this->agencyId]);

        $tours = [
            [
                'nombre' => 'Villa Rica y la Ruta del Café Premium',
                'descripcion' => 'Full Day sumergido en aromas de café, cataratas y la laguna El Oconal.',
                'duracion' => 1,
                'precio' => 110.00,
                'tags' => 'Villa Rica, Gourmet, Naturaleza',
                'nivel_dificultad' => 'facil',
                'ubicacion' => 'Villa Rica',
                'itinerario' => [
                    [
                        'dia' => 1,
                        'titulo' => 'Cultura Cafetalera',
                        'descripcion' => 'Viaje a la ciudad cafetalera por excelencia.',
                        'actividades' => 'Catarata El León, Finca de Café, Laguna El Oconal',
                        'hora_inicio' => '08:00:00',
                        'hora_fin' => '17:30:00',
                        'ubicacion' => 'Villa Rica'
                    ]
                ]
            ],
            [
                'nombre' => 'Pozuzo: Maravilla Austro-Alemana',
                'descripcion' => 'Conoce el único pueblo alemán en plena selva amazónica.',
                'duracion' => 1,
                'precio' => 140.00,
                'tags' => 'Pozuzo, Historia, Arquitectura',
                'nivel_dificultad' => 'medio',
                'ubicacion' => 'Pozuzo',
                'itinerario' => [
                    [
                        'dia' => 1,
                        'titulo' => 'Colonia Pozuzo',
                        'descripcion' => 'Historia de los colonos y gastronomía típica.',
                        'actividades' => 'Puente Emperador Guillermo, Catarata del Delfín, Almuerzo local',
                        'hora_inicio' => '07:00:00',
                        'hora_fin' => '19:00:00',
                        'ubicacion' => 'Pozuzo'
                    ]
                ]
            ],
            [
                'nombre' => 'Wharapo y Planta Láctea Floralp',
                'descripcion' => 'Visita a la destilería de aguardiente Wharapo y la reconocida planta de quesos Floralp.',
                'duracion' => 1,
                'precio' => 45.00,
                'tags' => 'Oxapampa, Gastronomía, Lácteos',
                'nivel_dificultad' => 'facil',
                'ubicacion' => 'Oxapampa',
                'itinerario' => [
                    [
                        'dia' => 1,
                        'titulo' => 'Ruta de Sabores Oxapampinos',
                        'descripcion' => 'Conoce los procesos artesanales de la zona.',
                        'actividades' => 'Degustación de aguardiente, Proceso del queso, Compras locales',
                        'hora_inicio' => '09:00:00',
                        'hora_fin' => '13:00:00',
                        'ubicacion' => 'Oxapampa'
                    ]
                ]
            ],
            [
                'nombre' => 'Tunqui Cueva y Chontabamba Recreativa',
                'descripcion' => 'Explora las estalactitas de Tunqui Cueva y diviértete en el valle de Chontabamba.',
                'duracion' => 1,
                'precio' => 55.00,
                'tags' => 'Chontabamba, Aventura, Cuevas',
                'nivel_dificultad' => 'facil',
                'ubicacion' => 'Chontabamba',
                'itinerario' => [
                    [
                        'dia' => 1,
                        'titulo' => 'Aventura en el Valle',
                        'descripcion' => 'Un recorrido divertido para toda la familia.',
                        'actividades' => 'Exploración de cueva, Puente colgante, Canotaje suave',
                        'hora_inicio' => '10:00:00',
                        'hora_fin' => '16:00:00',
                        'ubicacion' => 'Chontabamba'
                    ]
                ]
            ],
            [
                'nombre' => 'Catarata del Río Tigre: Oasis Natural',
                'descripcion' => 'Una de las caídas de agua más bellas de Oxapampa, ideal para un baño refrescante.',
                'duracion' => 1,
                'precio' => 50.00,
                'tags' => 'Oxapampa, Naturaleza, Agua',
                'nivel_dificultad' => 'medio',
                'ubicacion' => 'Oxapampa',
                'itinerario' => [
                    [
                        'dia' => 1,
                        'titulo' => 'Conexión con el Agua',
                        'descripcion' => 'Caminata corta hacia la catarata.',
                        'actividades' => 'Trekking liviano, Tiempo de baño, Fotografía de paisaje',
                        'hora_inicio' => '09:00:00',
                        'hora_fin' => '14:00:00',
                        'ubicacion' => 'Río Tigre'
                    ]
                ]
            ],
            [
                'nombre' => 'Bosque Shollet: Biodiversidad Infinita',
                'descripcion' => 'Descubre la flora y fauna única del bosque de neblina Shollet.',
                'duracion' => 1,
                'precio' => 85.00,
                'tags' => 'Villa Rica, Bio, Trekking',
                'nivel_dificultad' => 'alto',
                'ubicacion' => 'Villa Rica',
                'itinerario' => [
                    [
                        'dia' => 1,
                        'titulo' => 'Caminata por las Nubes',
                        'descripcion' => 'Exploración profunda del área protegida.',
                        'actividades' => 'Observación de aves, Identificación de orquídeas, Mirador panorámico',
                        'hora_inicio' => '08:00:00',
                        'hora_fin' => '16:00:00',
                        'ubicacion' => 'Bosque Shollet'
                    ]
                ]
            ],
            [
                'nombre' => 'Catarata del Delfín y Barrio Alemán',
                'descripcion' => 'Visita la impresionante Catarata del Delfín en Pozuzo y conoce la arquitectura alemana.',
                'duracion' => 1,
                'precio' => 130.00,
                'tags' => 'Pozuzo, Tradición, Cascada',
                'nivel_dificultad' => 'medio',
                'ubicacion' => 'Pozuzo',
                'itinerario' => [
                    [
                        'dia' => 1,
                        'titulo' => 'Pozuzo Cultural',
                        'descripcion' => 'Una inmersión en la colonia.',
                        'actividades' => 'Catarata del Delfín, Museo Schafferer, Planta de Cerveza Artesanal',
                        'hora_inicio' => '07:30:00',
                        'hora_fin' => '18:30:00',
                        'ubicacion' => 'Pozuzo'
                    ]
                ]
            ],
            [
                'nombre' => 'Huancabamba: Catarata Anana',
                'descripcion' => 'Viaja al distrito de Huancabamba para conocer la imponente caída de Anana.',
                'duracion' => 1,
                'precio' => 90.00,
                'tags' => 'Huancabamba, Aventura, Selva Alta',
                'nivel_dificultad' => 'alto',
                'ubicacion' => 'Huancabamba',
                'itinerario' => [
                    [
                        'dia' => 1,
                        'titulo' => 'Ruta Anana',
                        'descripcion' => 'Trekking exigente hacia la catarata.',
                        'actividades' => 'Caminata en selva virgen, Almuerzo en Huancabamba, Baño natural',
                        'hora_inicio' => '06:00:00',
                        'hora_fin' => '18:00:00',
                        'ubicacion' => 'Huancabamba'
                    ]
                ]
            ],
            [
                'nombre' => 'Comunidad Nativa Tsachopen',
                'descripcion' => 'Conoce las costumbres, artesanías y modo de vida de la comunidad Yanesha de Tsachopen.',
                'duracion' => 1,
                'precio' => 40.00,
                'tags' => 'Oxapampa, Cultura, Yanesha',
                'nivel_dificultad' => 'facil',
                'ubicacion' => 'Oxapampa',
                'itinerario' => [
                    [
                        'dia' => 1,
                        'titulo' => 'Inmersión Yanesha',
                        'descripcion' => 'Intercambio cultural con la comunidad.',
                        'actividades' => 'Demostración de cerámica, Danzas típicas, Taller de flechas',
                        'hora_inicio' => '14:00:00',
                        'hora_fin' => '17:30:00',
                        'ubicacion' => 'Tsachopen'
                    ]
                ]
            ],
            [
                'nombre' => 'Oxapampa City Tour: Paseo de los Colonos',
                'descripcion' => 'Recorrido por los puntos históricos y emblemáticos de la ciudad de Oxapampa.',
                'duracion' => 1,
                'precio' => 30.00,
                'tags' => 'Oxapampa, City Tour, Historia',
                'nivel_dificultad' => 'facil',
                'ubicacion' => 'Oxapampa',
                'itinerario' => [
                    [
                        'dia' => 1,
                        'titulo' => 'Centro Histórico',
                        'descripcion' => 'Caminata guiada por la plaza y alrededores.',
                        'actividades' => 'Iglesia Santa Rosa, Mercado Central, Paseo de los Colonos',
                        'hora_inicio' => '09:00:00',
                        'hora_fin' => '12:30:00',
                        'ubicacion' => 'Oxapampa Centro'
                    ]
                ]
            ],
            [
                'nombre' => 'Fundo Hassinger: Bosque de Pinos',
                'descripcion' => 'Un rincón europeo en Oxapampa conocido por sus plantaciones de pino y hortensias.',
                'duracion' => 1,
                'precio' => 35.00,
                'tags' => 'Oxapampa, Paisajismo, Relax',
                'nivel_dificultad' => 'facil',
                'ubicacion' => 'Oxapampa',
                'itinerario' => [
                    [
                        'dia' => 1,
                        'titulo' => 'Relajo en el Fundo',
                        'descripcion' => 'Tarde de fotografía y naturaleza.',
                        'actividades' => 'Paseo por senderos de pinos, Fotografía artística, Compra de miel y derivados',
                        'hora_inicio' => '15:00:00',
                        'hora_fin' => '18:00:00',
                        'ubicacion' => 'Fundo Hassinger'
                    ]
                ]
            ],
            [
                'nombre' => 'Pozuzo: Catarata Tres Caídas',
                'descripcion' => 'Una de las rutas más divertidas de Pozuzo con múltiples caídas de agua cristalina.',
                'duracion' => 1,
                'precio' => 125.00,
                'tags' => 'Pozuzo, Agua, Aventura',
                'nivel_dificultad' => 'medio',
                'ubicacion' => 'Pozuzo',
                'itinerario' => [
                    [
                        'dia' => 1,
                        'titulo' => 'Tres Caídas en la Selva',
                        'descripcion' => 'Travesía hacia las cascadas.',
                        'actividades' => 'Ruta en camioneta, Trekking de agua, Picnic en la catarata',
                        'hora_inicio' => '08:00:00',
                        'hora_fin' => '17:00:00',
                        'ubicacion' => 'Pozuzo'
                    ]
                ]
            ],
            [
                'nombre' => 'Selva Central Mágica (4 Días / 3 Noches)',
                'descripcion' => 'El paquete perfecto para conocer Oxapampa, Villa Rica y Pozuzo sin prisas.',
                'duracion' => 4,
                'precio' => 550.00,
                'tags' => 'Paquete Masivo, Completo, Oxapampa',
                'nivel_dificultad' => 'medio',
                'ubicacion' => 'Oxapampa',
                'itinerario' => [
                    [
                        'dia' => 1,
                        'titulo' => 'Llegada y Oxapampa Tradicional',
                        'descripcion' => 'Recepción y recorrido por el valle.',
                        'actividades' => 'City Tour, Iglesia Santa Rosa, Planta Láctea Floralp',
                        'hora_inicio' => '09:00:00',
                        'hora_fin' => '17:00:00',
                        'ubicacion' => 'Oxapampa'
                    ],
                    [
                        'dia' => 2,
                        'titulo' => 'Pozuzo: Un Pedazo de Europa en Selva',
                        'descripcion' => 'Viaje a la colonia de colonos alemanes.',
                        'actividades' => 'Puente Emperador, Catarata Delfín, Baile Típico',
                        'hora_inicio' => '07:30:00',
                        'hora_fin' => '18:30:00',
                        'ubicacion' => 'Pozuzo'
                    ],
                    [
                        'dia' => 3,
                        'titulo' => 'Villa Rica: Corazón Cafetalero',
                        'descripcion' => 'Visita a la cascada y la ruta del café.',
                        'actividades' => 'Laguna El Oconal, Cascada El León, Finca cafetalera',
                        'hora_inicio' => '08:30:00',
                        'hora_fin' => '17:30:00',
                        'ubicacion' => 'Villa Rica'
                    ],
                    [
                        'dia' => 4,
                        'titulo' => 'Chontabamba y Despedida',
                        'descripcion' => 'Cierre del viaje en el distrito recreativo.',
                        'actividades' => 'Tunqui Cueva, Destilería Wharapo, Compra de artesanías',
                        'hora_inicio' => '10:00:00',
                        'hora_fin' => '15:00:00',
                        'ubicacion' => 'Chontabamba'
                    ]
                ]
            ],
            [
                'nombre' => 'Expedición Oxapampa Profunda (5 Días / 4 Noches)',
                'descripcion' => 'Incluye todas las rutas clásicas más una expedición a cataratas de difícil acceso.',
                'duracion' => 5,
                'precio' => 720.00,
                'tags' => 'Aventura Total, VIP, Largo Alcance',
                'nivel_dificultad' => 'alto',
                'ubicacion' => 'Oxapampa',
                'itinerario' => [
                    [
                        'dia' => 1,
                        'titulo' => 'Bienvenida y Valle de Ensueño',
                        'descripcion' => 'Traslado y primeros puntos turísticos.',
                        'actividades' => 'Check-in, Mirador Florida, Wharapo',
                        'hora_inicio' => '10:00:00',
                        'hora_fin' => '18:00:00',
                        'ubicacion' => 'Oxapampa'
                    ],
                    [
                        'dia' => 2,
                        'titulo' => 'Pozuzo Místico',
                        'descripcion' => 'Full Day en la colonia Austro-Alemana.',
                        'actividades' => 'Puente Emperador, Pozas de Agua Salada, Museo Schafferer',
                        'hora_inicio' => '07:00:00',
                        'hora_fin' => '19:00:00',
                        'ubicacion' => 'Pozuzo'
                    ],
                    [
                        'dia' => 3,
                        'titulo' => 'Villa Rica y Bosque Shollet',
                        'descripcion' => 'Inmersión en biodiversidad y café.',
                        'actividades' => 'Caminata en Shollet, Laguna El Oconal, Planta de proceso de café',
                        'hora_inicio' => '08:00:00',
                        'hora_fin' => '17:30:00',
                        'ubicacion' => 'Villa Rica'
                    ],
                    [
                        'dia' => 4,
                        'titulo' => 'Aventura en Catarata Río Tigre',
                        'descripcion' => 'Trekking y conexión con la naturaleza.',
                        'actividades' => 'Trekking por el río, Baño bajo la catarata, Almuerzo campestre',
                        'hora_inicio' => '08:30:00',
                        'hora_fin' => '16:00:00',
                        'ubicacion' => 'Río Tigre'
                    ],
                    [
                        'dia' => 5,
                        'titulo' => 'Chontabamba Recreativa',
                        'descripcion' => 'Mañana de campo y retorno.',
                        'actividades' => 'Tunqui Cueva, Floralp, Traslado aeropuerto/estación',
                        'hora_inicio' => '09:30:00',
                        'hora_fin' => '15:30:00',
                        'ubicacion' => 'Chontabamba'
                    ]
                ]
            ]
        ];

        foreach ($tours as $t) {
            $stmt = $this->pdo->prepare("INSERT INTO tours (agencia_id, nombre, descripcion, duracion, precio, tags, nivel_dificultad, ubicacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $this->agencyId,
                $t['nombre'],
                $t['descripcion'],
                $t['duracion'],
                $t['precio'],
                $t['tags'],
                $t['nivel_dificultad'],
                $t['ubicacion']
            ]);
            $tourId = $this->pdo->lastInsertId();

            if (isset($t['itinerario'])) {
                foreach ($t['itinerario'] as $iti) {
                    $stmtIti = $this->pdo->prepare("INSERT INTO itinerarios (tour_id, dia_numero, titulo, descripcion, actividades, hora_inicio, hora_fin, ubicacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmtIti->execute([
                        $tourId,
                        $iti['dia'],
                        $iti['titulo'],
                        $iti['descripcion'],
                        $iti['actividades'],
                        $iti['hora_inicio'],
                        $iti['hora_fin'],
                        $iti['ubicacion']
                    ]);
                }
            }
        }
    }
}
