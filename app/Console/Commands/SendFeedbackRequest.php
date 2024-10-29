<?php

namespace App\Console\Commands;

use Illuminate\Bus\Queueable;
use Illuminate\Console\Command;
use App\Models\Event;
use App\Notifications\FeedbackRequestNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendFeedbackRequest extends Command implements ShouldQueue
{
    use Queueable;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:feedback-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar solicitações de feedback para usuários de eventos que já terminaram';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {

        // Buscar eventos que já terminaram e carregar os usuários relacionados
        $pastEvents = Event::where('date', '<', now())
            ->with('users') // Carregar os usuários junto com os eventos
            ->get();

        foreach ($pastEvents as $event) {
            // Iterar sobre os usuários participantes do evento
            foreach ($event->users as $user) {

                // Verificar se o usuário já recebeu a notificação de feedback para o evento específico
                if (!$user->hasReceivedFeedbackNotification($event)) {
                    // Enviar notificação
                    $user->notify(new FeedbackRequestNotification($event));

                    // Registrar que a notificação foi enviada para evitar reenvio
                    $user->feedback_notifications()->create(['event_id' => $event->id]);
                }
            }
        }
    }
}
