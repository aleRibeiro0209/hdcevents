@extends('layouts.main')

@section('title', 'Feedback: '. $event->title)

@section('content')
    <div class="col-md-10 offset-md-1">
        <h1 class="text-center mt-4">Sua opinião nos ajuda a melhorar!</h1>
        <div class="row mb-5">
            <div class="col-md-6">
                <div id="image-container">
                    <img src="/image/events/{{ $event->image }}" alt="{{ $event->title }}" class="img-fluid">
                </div>
            </div>
            <div class="col-md-6 mt-4">
                <h3 class="text-center text-sm-start">Obrigado por participar do evento: "{{ $event->title }}"</h3>
                <p class="agradecimento">
                    Sua opinião é muito importante para nós! Para melhorarmos nossos próximos eventos, gostaríamos de saber como foi sua experiência no(a) <strong>"{{ $event->title }}"</strong> que aconteceu no dia <strong>{{ date('d/m/Y', strtotime($event->date)) }}</strong>. Por favor, responda às perguntas a seguir. Sua contribuição nos ajudará a criar eventos ainda mais incríveis! Obrigado por dedicar seu tempo.
                </p>
                <form action="/events/{{ $event->id }}/feedback/create" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="avaliacao">Avaliação:</label>
                        <select class="form-select" id="avaliacao" name="avaliacao" value="{{ old('avaliacao') }}">
                            <option value="">Selecione...</option>
                            <option value="1">Ruim</option>
                            <option value="2">Regular</option>
                            <option value="3">Bom</option>
                            <option value="4">Excelente</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="comentario">Conte-nos sobre a sua experiência:</label>
                        <textarea class="form-control" id="comentario" name="comentario" placeholder="O que achou do evento?">{{ old('comentario') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Enviar Feedback</button>
                </form>
            </div>
        </div>
    </div>
@endsection
