@extends('layouts.main')

@section('title', 'Criar Evento')

@section('content')

    @if ($errors->has('image'))
        <p class="msg-erro">{{ $errors->first('image') }}</p>
    @endif

    @if ($errors->has('title'))
        <p class="msg-erro">{{ $errors->first('title') }}</p>
    @endif

    @if ($errors->has('date'))
        <p class="msg-erro">{{ $errors->first('date') }}</p>
    @endif

    @if ($errors->has('city'))
        <p class="msg-erro">{{ $errors->first('city') }}</p>
    @endif

    @if ($errors->has('description'))
        <p class="msg-erro">{{ $errors->first('description') }}</p>
    @endif

    @if ($errors->has('valor_ingresso'))
        <p class="msg-erro">{{ $errors->first('valor_ingresso') }}</p>
    @endif

    @if ($errors->has('qtd_ingresso_disponivel'))
        <p class="msg-erro">{{ $errors->first('qtd_ingresso_disponivel') }}</p>
    @endif

    <div id="event-create-container" class="col-md-6 offset-md-3">
        <h1>Crie o seu evento</h1>
        <form action="/events" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-3">
                <label for="image">Imagem do evento:</label>
                <input type="file" class="form-control" id="image" name="image" placeholder="Nome do evento">
            </div>
            <div class="form-group mb-3">
                <label for="title">Evento:</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Nome do evento" value="{{ old('title') }}">
            </div>
            <div class="form-group mb-3">
                <label for="date">Data do evento:</label>
                <input type="date" class="form-control" id="date" name="date" value="{{ old('date') }}">
            </div>
            <div class="form-group mb-3">
                <label for="city">Cidade:</label>
                <input type="text" class="form-control" id="city" name="city" placeholder="Local do evento" value="{{ old('city') }}">
            </div>
            <div class="row align-items-end">
                <div class="col-md-12 col-lg-4 p-0 pe-lg-5">
                    <div class="form-group mb-3">
                        <label for="private">O evento é privado?</label>
                        <select class="form-select" id="private" name="private" value="{{ old('private') }}">
                            <option value="0">Não</option>
                            <option value="1">Sim</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12 col-lg-5 p-0 pe-lg-5">
                    <div class="form-group md-3">
                        <label for="qtd_ingresso_disponivel">Quantidade de ingressos disponíveis:</label>
                        <div class="input-group mb-3">
                            <input type="number" class="form-control" id="qtd_ingresso_disponivel" min="0" step="1" placeholder="0" name="qtd_ingresso_disponivel" value="{{ old('qtd_ingresso_disponivel') }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-3 p-0">
                    <div class="form-group mb-3">
                        <label for="valor_ingresso">Valor dos ingressos:</label>
                        <div class="input-group mb-3">
                            <input type="number" class="form-control" id="valor_ingresso" min="0" step="0.01" placeholder="00,00" name="valor_ingresso" value="{{ old('valor_ingresso') }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group mb-3">
                <label for="description">Descrição:</label>
                <textarea class="form-control" id="description" name="description" placeholder="O que vai acontecer no evento?">{{ old('description') }}</textarea>
            </div>
            <div class="form-group mb-3">
                <label for="title">Adicione itens de infraestrutura:</label>
                <div class="form-group mb-1">
                    <input type="checkbox" name="items[]" value="Cadeiras"
                    {{ in_array('Cadeiras', old('items', [])) ? 'checked' : '' }}> Cadeiras
                </div>
                <div class="form-group mb-1">
                    <input type="checkbox" name="items[]" value="Palco"
                    {{ in_array('Palco', old('items', [])) ? 'checked' : '' }}> Palco
                </div>
                <div class="form-group mb-1">
                    <input type="checkbox" name="items[]" value="Cerveja grátis"
                    {{ in_array('Cerveja grátis', old('items', [])) ? 'checked' : '' }}> Cerveja grátis
                </div>
                <div class="form-group mb-1">
                    <input type="checkbox" name="items[]" value="Open food"
                    {{ in_array('Open food', old('items', [])) ? 'checked' : '' }}> Open food
                </div>
                <div class="form-group mb-1">
                    <input type="checkbox" name="items[]" value="Brindes"
                    {{ in_array('Brindes', old('items', [])) ? 'checked' : '' }}> Brindes
                </div>
            </div>
            <button type="submit" class="btn btn-primary" value="Criar evento">Criar evento</button>
        </form>
    </div>

@endsection
