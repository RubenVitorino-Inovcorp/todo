<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@todo.test',
            'password' => bcrypt('password'),
        ]);

        $tasks = [
            [
                'title' => 'Fazer as compras da semana',
                'description' => 'Ir ao supermercado comprar frescos, carne, peixe e produtos de limpeza. Não esquecer a lista.',
                'priority' => 'high',
                'status' => 'pending',
                'due_date' => Carbon::today()->setHour(18)->setMinute(0),
            ],
            [
                'title' => 'Pagar a conta da eletricidade',
                'description' => 'A fatura vence no final do dia. Entrar no homebanking e liquidar a referência multibanco.',
                'priority' => 'high',
                'status' => 'pending',
                'due_date' => Carbon::today()->setHour(23)->setMinute(59),
            ],
            [
                'title' => 'Marcar consulta no dentista',
                'description' => 'Ligar para a clínica para agendar a limpeza e o check-up anual.',
                'priority' => 'medium',
                'status' => 'pending',
                'due_date' => Carbon::tomorrow()->setHour(10)->setMinute(30),
            ],
            [
                'title' => 'Limpar a casa',
                'description' => 'Aspirar o pó, lavar o chão, limpar as casas de banho e tratar da roupa.',
                'priority' => 'medium',
                'status' => 'pending',
                'due_date' => Carbon::now()->addDays(2)->setHour(9)->setMinute(0),
            ],
            [
                'title' => 'Lavar o carro',
                'description' => 'Passar pela estação de serviço para dar uma lavagem exterior e aspirar o interior.',
                'priority' => 'low',
                'status' => 'pending',
                'due_date' => Carbon::now()->addDays(4)->setHour(16)->setMinute(0),
            ],
            [
                'title' => 'Treinar no ginásio',
                'description' => 'Fazer o treino de pernas e 20 minutos de cardio.',
                'priority' => 'medium',
                'status' => 'completed',
                'due_date' => Carbon::yesterday()->setHour(19)->setMinute(0),
            ],
            [
                'title' => 'Ligar aos pais',
                'description' => 'Fazer uma chamada de vídeo para saber como estão as coisas e combinar o almoço de domingo.',
                'priority' => 'high',
                'status' => 'completed',
                'due_date' => Carbon::yesterday()->setHour(21)->setMinute(0),
            ],
            [
                'title' => 'Comprar prenda de aniversário para a Joana',
                'description' => 'Passar no shopping e ver se encontro o livro que ela queria ou um perfume.',
                'priority' => 'medium',
                'status' => 'pending',
                'due_date' => Carbon::now()->addDays(5)->setHour(18)->setMinute(30),
            ],
            [
                'title' => 'Ler 30 páginas do novo livro',
                'description' => 'Tentar manter o hábito de leitura antes de ir dormir.',
                'priority' => 'low',
                'status' => 'pending',
                'due_date' => Carbon::today()->setHour(22)->setMinute(30),
            ],
            [
                'title' => 'Levar o cão a passear',
                'description' => 'Dar uma volta maior pelo parque para ele gastar energia.',
                'priority' => 'medium',
                'status' => 'pending',
                'due_date' => Carbon::today()->setHour(19)->setMinute(30),
            ],
            [
                'title' => 'Preparar as marmitas (Meal Prep)',
                'description' => 'Cozinhar o almoço para levar para o trabalho de segunda a quarta-feira.',
                'priority' => 'high',
                'status' => 'pending',
                'due_date' => Carbon::now()->next(Carbon::SUNDAY)->setHour(16)->setMinute(0),
            ],
            [
                'title' => 'Agendar revisão do carro',
                'description' => 'O carro está quase a atingir os quilómetros para mudar o óleo e filtros.',
                'priority' => 'medium',
                'status' => 'pending',
                'due_date' => Carbon::now()->addDays(10)->setHour(11)->setMinute(0),
            ],
            [
                'title' => 'Renovar o Cartão de Cidadão',
                'description' => 'O cartão caduca no próximo mês. Agendar online a renovação no Registo Civil.',
                'priority' => 'high',
                'status' => 'completed',
                'due_date' => Carbon::now()->subDays(3)->setHour(14)->setMinute(0),
            ],
            [
                'title' => 'Organizar faturas para o IRS',
                'description' => 'Verificar no e-fatura se há faturas pendentes de validação nos diferentes setores.',
                'priority' => 'medium',
                'status' => 'pending',
                'due_date' => Carbon::now()->addDays(15)->setHour(21)->setMinute(0),
            ],
            [
                'title' => 'Regar as plantas',
                'description' => 'Verificar a terra das plantas da sala e da varanda e regar as que precisarem.',
                'priority' => 'low',
                'status' => 'pending',
                'due_date' => Carbon::today()->setHour(17)->setMinute(0),
            ]
        ];

        foreach ($tasks as $task) {
            $user->tasks()->create($task);
        }
    }
}