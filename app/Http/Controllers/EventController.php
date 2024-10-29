<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Feedback;
use App\Models\User;

class EventController extends Controller
{
    public function index() {

        $search = request('search');

        if ($search) {

            $events = Event::where([
                ['title', 'like', '%'. $search .'%']
            ])->get();

        } else {
            $events = Event::where('private', 0)
                ->orderBy('date', 'desc')
                ->get();
        }


        return view('welcome',['events' => $events, 'search' => $search]);
    }

    public function create() {
        return view('events.create');
    }

    public function store(Request $request) {

        $request->validate([
            'title' => 'required|string',
            'date' => 'required|date|after_or_equal:today',
            'city' => 'required|string',
            'private' => 'required|boolean',
            'description' => 'required|string',
            'items' => 'required|array',
            'valor_ingresso' => 'nullable|numeric|min:0',
            'qtd_ingresso_disponivel' => 'nullable|numeric|min:0',
            'image' => 'nullable|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg+xml|max:51200'
        ]);

        $event = new Event;
        $event->title = $request->title;
        $event->date = $request->date;
        $event->city = $request->city;
        $event->private = $request->private;
        $event->description = $request->description;
        $event->items = $request->items;
        $event->valor_ingresso = $request->valor_ingresso;
        $event->qtd_ingresso_disponivel = $request->qtd_ingresso_disponivel;

        // Imagem upload
        if($request->hasFile('image') && $request->file('image')->isValid()) {
            $requestImage = $request->image;

            $extension = $requestImage->extension();

            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            $requestImage->move(public_path('image/events'), $imageName);

            $event->image = $imageName;
        } else {
            $event->image = 'default.jpg';
        }

        $user = auth()->user();
        $event->user_id = $user->id;

        $event->save();

        return redirect('/')->with('msg', 'Evento criado com sucesso');
    }

    public function show($id) {

        $event = Event::findOrFail($id);
        $eventOwner = User::where('id', $event->user_id)->first()->toArray();

        // Verifica se o usuário está autenticado
        if (auth()->check()) {
            $userId = auth()->user()->id;
            $isParticipant = $event->users->contains($userId);
        } else {
            $isParticipant = false;
        }

        return view('events.show', ['event' => $event, 'eventOwner' => $eventOwner, 'isParticipant' => $isParticipant]);
    }

    public function getEventData($id) {

        $event = Event::findOrFail($id);
        $eventsAsParticipant = $event->users;
        $feedbacks = $event->feedbacks;
        $ticketSold = $event->ticketsSoldLast12Months();

        return response()->json([
            'event' => $event,
            'eventsAsParticipant' => $eventsAsParticipant,
            'feedbacks' => $feedbacks,
            'ticketSold' => $ticketSold
        ]);
    }

    public function dashboard() {
        $user = auth()->user();

        $events = $user->events()->orderBy('date', 'desc')->get();

        $eventsAsParticipant = $user->eventsAsParticipant()
                                ->orderBy('created_at', 'desc')
                                ->get();

        return view('events.dashboard', ['events' => $events, 'eventsAsParticipant' => $eventsAsParticipant]);
    }

    public function destroy($id) {

        $event = Event::findOrFail($id);

        if ($event) {
            $event->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Evento deletado com sucesso!'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Evento não encontrado.'
            ], 404);
        }
    }

    public function update(Request $request) {

        $request->validate([
            'title' => 'required|string',
            'date' => 'required|date|after_or_equal:today',
            'city' => 'required|string',
            'private' => 'required|boolean',
            'description' => 'required|string',
            'items' => 'required|array',
            'valor_ingresso' => 'nullable|numeric|min:0',
            'qtd_ingresso_disponivel' => 'nullable|numeric|min:0',
            'image' => 'nullable|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg+xml|max:51200'
        ]);

        $data = $request->all();

        // Imagem upload
        if($request->hasFile('image') && $request->file('image')->isValid()) {
            $requestImage = $request->image;

            $extension = $requestImage->extension();

            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            $requestImage->move(public_path('image/events'), $imageName);

            $data['image'] = $imageName;
        }


        $event = Event::findOrFail($request->id)->update($data);

        if ($event) {
            return redirect('/dashboard')->with('msg', 'Evento atualizado com sucesso');
        } else {
            return redirect('/dashboard')->with('msg-erro', 'Evento não encontrado ou não atualizado');
        }
    }

    public function joinEvent($id){
        $user = auth()->user();

        $event = Event::findOrFail($id);

        // Anexa o evento ao usuário e insere o valor pago na tabela pivot
        $user->eventsAsParticipant()->attach($id, [
            'valor_pago' => $event->valor_ingresso,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect('/dashboard')->with('msg', 'Sua presença está confirmada no evento "' . $event->title . '"');
    }

    public function leaveEvent($id) {
        $user = auth()->user();

        $user->eventsAsParticipant()->detach($id);

        $event = Event::findOrFail($id);

        if ($event) {
            return response()->json([
                'status' => 'success',
                'message' => 'Sua presença foi removida do evento "' . $event->title . '"'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Evento não encontrado.'
            ], 404);
        }
    }

    public function createFeedback($id) {
        $event = Event::findOrFail($id);

        // Verifica se o evento já ocorreu
        if (now()->lt($event->date)) {
            return redirect('/dashboard')->with('msg-erro', 'Você só pode dar feedback após o evento.');
        }

        $eventOwner = User::where('id', $event->user_id)->first()->toArray();

        return view('events.feedback', ['event' => $event, 'eventOwner' => $eventOwner]);
    }

    public function storeFeedback(Request $request) {
        $user = auth()->user();

        // Verificar se o usuário já enviou feedback para este evento
        $existingFeedback = Feedback::where('event_id', $request->id)
        ->where('user_id', $user->id)
        ->first();

        if ($existingFeedback) {
            // Se o feedback já existe, redirecionar com uma mensagem de erro
            return redirect('/dashboard')->with('msg-erro', 'Você já enviou um feedback para este evento.');
        }

        $validatedData = $request->validate([
            'avaliacao' => 'required|integer|min:1|max:4',
            'comentario' => 'nullable|string|max:1000',
        ]);

        $feedback = new Feedback;
        $feedback->event_id = $request->id;
        $feedback->user_id = $user->id;
        $feedback->avaliacao = $validatedData['avaliacao'];
        $feedback->comentario = $validatedData['comentario'] ?? null;

        $feedback->save();

        return redirect('/dashboard')->with('msg', 'Feedback enviado com sucesso!');
    }
}
