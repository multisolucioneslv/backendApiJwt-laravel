<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cotizacion;
use App\Models\CotizacionItem;
use App\Models\Customer;
use App\Models\User;
use App\Models\Producto;

class CotizacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar que existan clientes, usuarios y productos
        $customers = Customer::all();
        $users = User::all();
        $productos = Producto::all();

        if ($customers->isEmpty() || $users->isEmpty() || $productos->isEmpty()) {
            $this->command->warn('⚠️  Debes ejecutar primero los seeders de Customers, Users y Productos');
            return;
        }

        $this->command->info('🚀 Creando cotizaciones de prueba...');

        $estados = ['generada', 'entregada', 'vendida', 'rechazada'];

        // Crear 15 cotizaciones de prueba
        for ($i = 1; $i <= 15; $i++) {
            $customer = $customers->random();
            $user = $users->random();
            $estado = $estados[array_rand($estados)];

            $fechaCotizacion = now()->subDays(rand(1, 60));
            $fechaVencimiento = $fechaCotizacion->copy()->addDays(rand(7, 30));

            // Crear cotización con número único manual
            $cotizacion = Cotizacion::create([
                'numero_cotizacion' => 'COT-2025-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'customer_id' => $customer->id,
                'user_id' => $user->id,
                'fecha_cotizacion' => $fechaCotizacion,
                'fecha_vencimiento' => $fechaVencimiento,
                'estado' => $estado,
                'impuesto' => rand(0, 20),
                'descuento' => rand(0, 100),
                'notas' => $this->obtenerNotaAleatoria(),
                'terminos' => 'Válido por ' . rand(7, 30) . ' días. Precios sujetos a cambios sin previo aviso.',
            ]);

            // Crear entre 2 y 5 items para cada cotización
            $cantidadItems = rand(2, 5);
            for ($j = 0; $j < $cantidadItems; $j++) {
                $producto = $productos->random();
                $cantidad = rand(1, 10);
                $precioUnitario = $producto->price;
                $descuento = rand(0, 50);

                CotizacionItem::create([
                    'cotizacion_id' => $cotizacion->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precioUnitario,
                    'descuento' => $descuento,
                    'descripcion' => 'Producto de alta calidad',
                ]);
            }

            // Calcular totales
            $cotizacion->calcularTotal();

            $this->command->info("✅ Cotización {$cotizacion->numero_cotizacion} creada - Estado: {$estado}");
        }

        $this->command->info('✨ Seeders de cotizaciones completados correctamente');
    }

    /**
     * Obtener una nota aleatoria para la cotización
     */
    private function obtenerNotaAleatoria(): string
    {
        $notas = [
            'Cliente preferente con descuento especial',
            'Envío incluido en el precio',
            'Promoción de temporada aplicada',
            'Cotización urgente - prioridad alta',
            'Cliente corporativo - términos especiales',
            'Incluye garantía extendida',
            'Precios promocionales válidos hasta fin de mes',
            'Descuento por volumen aplicado',
        ];

        return $notas[array_rand($notas)];
    }
}
