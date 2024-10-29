@extends('layouts.main')

@section('title', 'Dashboard')

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

    <div class="col-md-10 offset-md-1 dashboard-title-container text-center">
        <h1>Meus Eventos</h1>
    </div>
    <div class="col-md-10 offset-md-1 dashboard-events-container">
        @if (count($events) > 0)
            <div class="card mb-5">
                <div class="card-body">
                    <h4 class="mt-0 mb-3">Estatísticas: <strong id="estatistica-title"></strong></h4>
                    <div class="row collapse" id="drop-graficos">
                        <hr class="hr-title">
                        <div class="col-sm-4">
                            <div class="row mb-4">
                                <div class="col-sm-12">
                                    <div class="card">
                                        <h5 class="card-header">Ingressos:</h5>
                                        <div class="card-body">
                                            <canvas id="grafico-valores"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card ">
                                        <h5 class="card-header">Feedbacks:</h5>
                                        <div class="card-body">
                                            <canvas id="grafico-feedback"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8 d-none d-sm-block">
                            <div class="card h-100">
                                <h5 class="card-header">Progresso de Vendas:</h5>
                                <div class="card-body">
                                    <canvas id="grafico-dashboard"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center" id="close-dashboard" data-bs-toggle="collapse" data-bs-target="#drop-graficos" aria-expanded="false" aria-controls="drop-graficos">
                    <ion-icon name="chevron-down-outline"></ion-icon>
                </div>
            </div>
            <div class="card mb-5">
                <div class="card-body">
                    <h4 class="mt-0 mb-3">Eventos</h4>
                    <hr>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-primary">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Participantes</th>
                                    <th scope="col">Preço do Ingresso</th>
                                    <th scope="col">Quantidade de Ingressos disponíveis</th>
                                    <th scope="col">Quantidade de Feedbacks</th>
                                    <th scope="col">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($events as $event)
                                    <tr>
                                        <td scope="row">{{ $loop->index + 1}}</td>
                                        <td>
                                            <a href="/events/{{ $event->id }}">{{ $event->title }}</a>
                                        </td>
                                        <td>{{ count($event->users) }}</td>
                                        <td> {{ $event->valor_ingresso ? "R$ " . number_format($event->valor_ingresso, 2, ',', '.') : 'Gratuito' }}</td>
                                        <td>{{ $event->qtd_ingresso_disponivel > 0 ? $event->qtd_ingresso_disponivel - count($event->users) : 'Não definida' }}</td>
                                        <td>{{ count($event->feedbacks) > 0 ? count($event->feedbacks) : 'Sem feedbacks' }}</td>
                                        <td class="coluna-acoes-dashboard">
                                            {{-- Button trigger modal --}}
                                            <button type="button" title="Editar evento" class="text-warning edit-btn" data-id="{{ $event->id }}" data-bs-toggle="modal" data-bs-target="#editModal">
                                                <ion-icon name="create"></ion-icon>
                                            </button>
                                            <button type="button" title="Deletar evento" class="text-danger deletar-btn" data-id="{{ $event->id }}" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                <ion-icon name="trash"></ion-icon>
                                            </button>
                                            {{-- Buscar informações para os gráficos --}}
                                            <button type="button" title="Buscar estatísticas do evento" class="text-primary search-btn" data-id="{{ $event->id }}">
                                                <ion-icon name="search-circle"></ion-icon>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <p>Você ainda não tem eventos, <a href="/events/create">criar evento!</a></p>
        @endif
    </div>

    <div class="col-md-10 offset-md-1 dashboard-events-container">
        @if (count($eventsAsParticipant) > 0)
            <div class="card mb-5">
                <div class="card-body">
                    <h4 class="mt-0 mb-3">Eventos que estou participando</h4>
                    <hr>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-primary">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Participantes</th>
                                    <th scope="col">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($eventsAsParticipant as $event)
                                    <tr>
                                        <td scope="row">{{ $loop->index + 1}}</td>
                                        <td>
                                            <a href="/events/{{ $event->id }}">{{ $event->title }}</a>
                                        </td>
                                        <td>{{ count($event->users) }}</td>
                                        <td class="coluna-acoes-dashboard">
                                            {{-- Sair do evento --}}
                                            <button type="submit" class="text-danger sairEvent-btn" data-id="{{ $event->id }}" data-bs-toggle="modal" data-bs-target="#exitModal">
                                                <ion-icon name="exit"></ion-icon>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <p>Você ainda não está participando de nenhum evento, <a href="/">veja todos os eventos!</a></p>
        @endif
    </div>

    {{-- Modal Edit --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLegenda" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModalLegenda">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/events/update/" method="POST" enctype="multipart/form-data" id="editFormModal">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="col-sm-12 d-flex justify-content-center">
                                <img src="" alt="" class="img-fluid">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="image">Imagem do evento:</label>
                            <input type="file" class="form-control" id="image" name="image" placeholder="Nome do evento">
                        </div>
                        <div class="form-group mb-3">
                            <label for="title">Evento:</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Nome do evento">
                        </div>
                        <div class="form-group mb-3">
                            <label for="date">Data do evento:</label>
                            <input type="date" class="form-control" id="date" name="date">
                        </div>
                        <div class="form-group mb-3">
                            <label for="city">Cidade:</label>
                            <input type="text" class="form-control" id="city" name="city" placeholder="Local do evento">
                        </div>
                        <div class="row align-items-end">
                            <div class="col-md-12 col-lg-4 p-0 pe-lg-5">
                                <div class="form-group mb-3">
                                    <label for="private">O evento é privado?</label>
                                    <select class="form-select" id="private" name="private">
                                        <option value="0">Não</option>
                                        <option value="1">Sim</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-5 p-0 pe-lg-5">
                                <div class="form-group md-3">
                                    <label for="qtd_ingresso_disponivel">Quantidade de ingressos disponíveis:</label>
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control" id="qtd_ingresso_disponivel" min="0" step="1" placeholder="0" name="qtd_ingresso_disponivel">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-3 p-0">
                                <div class="form-group mb-3">
                                    <label for="valor_ingresso">Valor dos ingressos:</label>
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control" id="valor_ingresso" min="0" step="0.01" placeholder="00,00" name="valor_ingresso">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="description">Descrição:</label>
                            <textarea class="form-control" id="description" name="description" placeholder="O que vai acontecer no evento?"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="title">Adicione itens de infraestrutura:</label>
                            <div class="form-group mb-1">
                                <input type="checkbox" name="items[]" value="Cadeiras"> Cadeiras
                            </div>
                            <div class="form-group mb-1">
                                <input type="checkbox" name="items[]" value="Palco"> Palco
                            </div>
                            <div class="form-group mb-1">
                                <input type="checkbox" name="items[]" value="Cerveja grátis"> Cerveja grátis
                            </div>
                            <div class="form-group mb-1">
                                <input type="checkbox" name="items[]" value="Open food"> Open food
                            </div>
                            <div class="form-group mb-1">
                                <input type="checkbox" name="items[]" value="Brindes"> Brindes
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-outline-warning" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary" id="editConfirm">Salvar Alterações</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Delete --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLegenda" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deleteModalLegenda">Modal Title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Deseja deletar o "<span id="deleteContentTitle"></span>" e todos as suas informações?</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Não</button>
                    <button type="button" class="btn btn-success" id="deleteConfirm">Sim</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Exit --}}
    <div class="modal fade" id="exitModal" tabindex="-1" aria-labelledby="exitModalLegenda" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exitModalLegenda">Modal Title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Deseja remover sua presença do evento "<span id="exitContentTitle"></span>"?</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Não</button>
                    <button type="button" class="btn btn-success" id="exitConfirm">Sim</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de Mensagem --}}
    <div class="modal fade" id="mensagemModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" id="modalMensagemContent">
                <div class="modal-body">
                    <p id="mensagem" class="m-0">Exemplo</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var eventData = @json($events);
    </script>

    <script src="{{ asset('js/dashboard.js') }}"></script>
@endsection
