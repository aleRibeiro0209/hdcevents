<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EventUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Encontrar o evento com ID 2
        $event = Event::find(2);

        // Selecionar o único usuário que você tem (usuário de ID 1, por exemplo)
        $user = User::find(1); // Ajuste o ID do usuário conforme necessário

        // Fazer 40 associações do mesmo usuário ao mesmo evento com datas aleatórias
        for ($i = 0; $i < 40; $i++) {
            // Gerar uma data aleatória nos últimos 12 meses
            $randomDate = Carbon::now()->subMonths(rand(0, 12))->subDays(rand(0, 30));

            // Associar o mesmo usuário ao evento com uma data aleatória
            $event->users()->attach($user->id, [
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
                'valor_pago' => 199.45
            ]);
        }
    }
}
