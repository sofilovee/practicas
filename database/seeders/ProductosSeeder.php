<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductosSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [];
        foreach (['Electrónica', 'Papelería', 'Hogar'] as $nombre) {
            $categorias[$nombre] = Categoria::firstOrCreate(['nombre' => $nombre]);
        }

        $productos = [
            ['Audífonos Bluetooth', 'Audífonos inalámbricos con micrófono y 20 horas de batería', 'Electrónica', 599, 25],
            ['Mouse inalámbrico', 'Mouse óptico con receptor USB y pilas incluidas', 'Electrónica', 199, 40],
            ['Teclado mecánico', 'Teclado con luz LED y switches azules', 'Electrónica', 849, 12],
            ['Cargador USB-C', 'Cargador rápido de 20 W para celular y tableta', 'Electrónica', 249, 0],
            ['Memoria USB 64 GB', 'Memoria USB 3.0 de alta velocidad', 'Electrónica', 159, 60],
            ['Bocina portátil', 'Bocina Bluetooth resistente al agua', 'Electrónica', 729, 8],
            ['Cable HDMI', 'Cable HDMI de 2 metros compatible con 4K', 'Electrónica', 129, 35],
            ['Webcam HD', 'Cámara web 1080p con micrófono integrado', 'Electrónica', 459, 0],
            ['Lámpara LED de escritorio', 'Lámpara con brazo flexible y tres niveles de brillo', 'Electrónica', 319, 18],
            ['Soporte para laptop', 'Base de aluminio ajustable en altura', 'Electrónica', 389, 22],

            ['Cuaderno profesional', 'Cuaderno de 100 hojas con raya', 'Papelería', 45, 120],
            ['Caja de bolígrafos', 'Caja con 12 bolígrafos de tinta azul', 'Papelería', 62, 80],
            ['Marcatextos', 'Paquete de 4 marcatextos de colores', 'Papelería', 58, 0],
            ['Carpeta tamaño carta', 'Carpeta de argollas con tapa dura', 'Papelería', 89, 30],
            ['Calculadora científica', 'Calculadora con 240 funciones y pantalla de dos líneas', 'Papelería', 275, 15],
            ['Notas adhesivas', 'Block de notas adhesivas de colores', 'Papelería', 35, 90],
            ['Juego de geometría', 'Regla, escuadras, transportador y compás', 'Papelería', 70, 0],
            ['Engrapadora', 'Engrapadora de escritorio para 25 hojas', 'Papelería', 110, 20],
            ['Tijeras escolares', 'Tijeras de acero con mango ergonómico', 'Papelería', 40, 55],
            ['Folders manila', 'Paquete de 50 folders tamaño carta', 'Papelería', 95, 45],

            ['Escoba de cerdas suaves', 'Escoba con mango de aluminio para interiores', 'Hogar', 99, 28],
            ['Trapeador de microfibra', 'Trapeador con cubeta y escurridor', 'Hogar', 349, 14],
            ['Juego de sartenes', 'Set de tres sartenes antiadherentes', 'Hogar', 899, 0],
            ['Set de toallas', 'Cuatro toallas de algodón de colores', 'Hogar', 420, 20],
            ['Almohada de fibra', 'Almohada suave con funda de algodón', 'Hogar', 185, 33],
            ['Cafetera eléctrica', 'Cafetera de 12 tazas con filtro permanente', 'Hogar', 650, 9],
            ['Licuadora', 'Licuadora de 600 W con vaso de vidrio', 'Hogar', 780, 0],
            ['Organizador de ropa', 'Organizador de tela con tres compartimentos', 'Hogar', 210, 26],
            ['Cobija matrimonial', 'Cobija afelpada para clima frío', 'Hogar', 520, 17],
            ['Vaso térmico', 'Vaso de acero inoxidable de 600 ml', 'Hogar', 175, 50],
        ];

        foreach ($productos as $i => $p) {
            $producto = new Producto([
                'nombre' => $p[0],
                'descripcion' => $p[1],
                'categoria_id' => $categorias[$p[2]]->id,
                'precio' => $p[3],
                'existencias' => $p[4],
                'estado' => $p[4] == 0 ? 'agotado' : 'disponible',
            ]);

            // Fechas distintas para poder probar el orden por fecha de registro
            $producto->created_at = now()->subDays($i + 1);
            $producto->save();
        }
    }
}