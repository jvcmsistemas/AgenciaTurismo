<?php
require_once __DIR__ . '/config/db.php';

$agencyId = 5;

try {
    echo "<h2>Insertando Tours de prueba para Agencia ID: $agencyId</h2>";

    $tours = [
        [
            'nombre' => 'City Tour Oxapampa',
            'descripcion' => 'Recorrido por la Plaza de Armas, Iglesia Santa Rosa, arquitectura típica de madera, Mirador La Florida, Manantial de la Virgen y Catarata Río Tigre.',
            'duracion' => 0.5,
            'precio' => 35.00,
            'tags' => 'Cultura, Naturaleza, Relax',
            'nivel_dificultad' => 'facil',
            'ubicacion' => 'Oxapampa Ciudad'
        ],
        [
            'nombre' => 'Chontabamba Express',
            'descripcion' => 'Visita al Mirador del Valle, parque temático, trapiche tradicional El Wharapo, tunki cueva y tiendas de artesanía local.',
            'duracion' => 0.5,
            'precio' => 30.00,
            'tags' => 'Tradición, Gastronomía, Cavernas',
            'nivel_dificultad' => 'facil',
            'ubicacion' => 'Chontabamba'
        ],
        [
            'nombre' => 'Aventura Río Tigre',
            'descripcion' => 'Caminata corta por sendero ecológico, visita a la impresionante Catarata Río Tigre y tiempo libre para baño en sus pozas naturales.',
            'duracion' => 0.5,
            'precio' => 30.00,
            'tags' => 'Naturaleza, Caminata, Agua',
            'nivel_dificultad' => 'facil',
            'ubicacion' => 'Río Tigre'
        ],
        [
            'nombre' => 'Full Day Pozuzo: Colonia Austro-Alemana',
            'descripcion' => 'Viaje histórico a la única colonia austro-alemana del mundo. Cruce del Parque Yanachaga, cataratas Rayantambo y Yulitunqui, Museo Schafferer y Puente Emperador Guillermo I.',
            'duracion' => 1,
            'precio' => 85.00,
            'tags' => 'Historia, Cultura, Naturaleza',
            'nivel_dificultad' => 'medio',
            'ubicacion' => 'Pozuzo'
        ],
        [
            'nombre' => 'Full Day Villa Rica: Ruta del Café',
            'descripcion' => 'Inmersión en la tierra del café más fino. Visita a la Plaza, miradores panorámicos, paseo en bote por la Laguna El Oconal (avistamiento de aves) y degustación en fincas cafetaleras.',
            'duracion' => 1,
            'precio' => 70.00,
            'tags' => 'Café, Laguna, Aves',
            'nivel_dificultad' => 'facil',
            'ubicacion' => 'Villa Rica'
        ],
        [
            'nombre' => 'Trekking Yanachaga-Chemillén',
            'descripcion' => 'Exploración del sector San Alberto. Caminata por senderos de bosque nuboso, avistamiento de fauna silvestre (gallito de las rocas) y miradores naturales.',
            'duracion' => 1,
            'precio' => 95.00,
            'tags' => 'Trekking, Bosque, Fauna',
            'nivel_dificultad' => 'medio',
            'ubicacion' => 'Parque Nacional Yanachaga'
        ],
        [
            'nombre' => 'Paquete 2D/1N: Esencia Oxapampina',
            'descripcion' => 'Día 1: Ruta de la Granadilla y Catarata Río Tigre. Día 2: Tour vivencial en Chontabamba y compras de artesanía.',
            'duracion' => 2,
            'precio' => 160.00,
            'tags' => 'Paquete, Vivencial, Relax',
            'nivel_dificultad' => 'facil',
            'ubicacion' => 'Oxapampa / Chontabamba'
        ],
        [
            'nombre' => 'Paquete 3D/2N: Café y Selva',
            'descripcion' => 'Día 1: City Tour Oxapampa. Día 2: Full Day Villa Rica (Laguna y Café). Día 3: Relax en Río Tigre y despedida.',
            'duracion' => 3,
            'precio' => 290.00,
            'tags' => 'Paquete, Café, Naturaleza',
            'nivel_dificultad' => 'medio',
            'ubicacion' => 'Oxapampa / Villa Rica'
        ],
        [
            'nombre' => 'Paquete 4D/3N: Aventura Total',
            'descripcion' => 'La experiencia completa: Oxapampa, Chontabamba, Full Day Villa Rica y el imperdible Full Day Pozuzo con sus cataratas.',
            'duracion' => 4,
            'precio' => 420.00,
            'tags' => 'Paquete, Aventura, Cultura',
            'nivel_dificultad' => 'medio',
            'ubicacion' => 'Multidestino'
        ],
        [
            'nombre' => 'Paquete 5D/4N: Gran Circuito Central',
            'descripcion' => 'Explora todo: Ruta de la Granadilla, Río Tigre, Chontabamba, Villa Rica y Pozuzo. Incluye actividades nocturnas y fogata de despedida.',
            'duracion' => 5,
            'precio' => 580.00,
            'tags' => 'Paquete, Completo, Exclusivo',
            'nivel_dificultad' => 'medio',
            'ubicacion' => 'Todo Oxapampa'
        ]
    ];

    $sql = "INSERT INTO tours (agencia_id, nombre, descripcion, duracion, precio, tags, nivel_dificultad, ubicacion) 
            VALUES (:agencia_id, :nombre, :descripcion, :duracion, :precio, :tags, :nivel_dificultad, :ubicacion)";

    $stmt = $pdo->prepare($sql);

    foreach ($tours as $tour) {
        $tour['agencia_id'] = $agencyId;
        $stmt->execute($tour);
        echo "Tour insertado: {$tour['nombre']}<br>";
    }

    echo "<h3>¡Carga de Tours completada exitosamente!</h3>";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
