@extends('layouts.main')

@section('title', $event->title)

@section('content')

    <div class="col-md-10 offset-md-1">
        <div class="row">
            <div id="image-container" class="col-md-6">
                <img src="/image/events/{{ $event->image }}" alt="{{ $event->title }}" class="img-fluid">
            </div>
            <div id="info-container" class="col-md-6">
                <h1>{{ $event->title }}</h1>
                <p class="event-date"><ion-icon name="calendar-outline"></ion-icon> {{ date('d/m/Y', strtotime($event->date)) }}</p>
                <p class="event-city"><ion-icon name="location-outline"></ion-icon> {{ $event->city }}</p>
                <p class="events-participants"><ion-icon name="people-outline"></ion-icon> {{ count($event->users) }} Participantes</p>
                <p class="events-participants"><ion-icon name="cash-outline"></ion-icon> Valor do Ingresso: {{ $event->valor_ingresso ? "R$ " . number_format($event->valor_ingresso, 2, ",", ".") : 'Gratuito' }}</p>
                <p class="events-participants"><ion-icon name="ticket-outline"></ion-icon> Quantidade de ingressos disponíveis: {{ $event->qtd_ingresso_disponivel > 0 ? $event->qtd_ingresso_disponivel - count($event->users) : 'Não definido' }} </p>
                <p class="event-owner"><ion-icon name="star-outline"></ion-icon> {{ $eventOwner['name'] }}</p>
                @if (!$isParticipant)
                    <form action="/events/join/{{ $event->id }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary" id="event-submit">Confirmar Presença</button>
                    </form>
                @else
                    <p class="msg p-3 mt-2 mb-2 justify-content-center rounded-3">Você já está associado a esse evento</p>
                @endif
                <h3>O evento conta com:</h3>
                <ul id="items-list">
                    @foreach ($event->items as $item)
                        <li><ion-icon name="play-outline"></ion-icon> <span>{{ $item }}</span></li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-12" id="description-container">
                <h3>Sobre o evento:</h3>
                <p class="event-description">{{ $event->description }}</p>
            </div>
        </div>
    </div>

@endsection
