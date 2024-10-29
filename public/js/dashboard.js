$(document).ready(() => {

    // Gráficos
    const ctx = $('#grafico-dashboard');
    const ctx2 = $('#grafico-valores');
    const ctx3 = $('#grafico-feedback');
    let graficoDashboard, graficoValores, graficoFeedback;
    let textoValorTotal = "R$ 00,00";

    // Progresso de Vendas
    graficoDashboard = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            datasets: [{
                label: 'Índice de Vendas',
                data: [12, 19, 3, 5, 2, 3, 12, 19, 15, 3, 4, 8],
                backgroundColor: 'rgba(255, 159, 64, 0.2)',
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 1,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Ingressos
    graficoValores = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            datasets: [
                {
                    data: [25, 75],
                    backgroundColor: ['rgba(255, 159, 64, 1)', 'rgba(255, 159, 64, .2)'],
                    borderWidth: 1,
                    borderColor: 'rgba(255, 159, 64, 1)',
                    cutout: '50%',
                },
            ],
            labels: ['Valor Arrecadado', 'Valor Restante']
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'right',
                    align: 'center'
                },
                tooltip: {
                    enabled: true,
                    callbacks: {
                        label: (context) => {
                            const label = context.dataset.labels || '';
                            const value = context.raw;
                            return `${label}: R$ ${parseFloat(value).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                        }
                    }
                },
            }
        },
        plugins: [{
            // Plugin personalizado para adicionar o texto central
            afterDraw: (chart) => {
                const { width, height, ctx } = chart;
                ctx.restore();
                const fontSize = (height / 6).toFixed(2);
                ctx.font = `${fontSize}px Arial`;
                ctx.textBaseline = 'middle';

                const text = textoValorTotal;
                const textX = Math.round((width - ctx.measureText(text).width));
                const textY = height / height + 15;

                ctx.fillStyle = 'rgba(255, 159, 64, 1)';
                ctx.fillText(text, textX, textY);
                ctx.save();
            }
        }]
    });

    // Feedbacks
    graficoFeedback = new Chart(ctx3, {
        type: 'doughnut',
        data: {
            labels: ['Excelente', 'Bom', 'Regular', 'Ruim'],
            datasets: [{
                data: [200, 300, 60, 50],
                backgroundColor: ['rgba(102, 205, 170, .4)', 'rgba(70, 130, 180, .4)', 'rgba(255, 160, 122, .4)', 'rgba(255, 127, 127, .4)'],
                hoverBackgroundColor: ['rgba(94, 191, 161, 1)', 'rgba(62, 113, 158, 1)', 'rgba(255, 138, 110, 1)', 'rgba(255, 106, 106, 1)'],
                borderColor: ['rgba(82, 185, 150, 1)', 'rgba(50, 110, 170, 1)', 'rgba(235, 140, 102, 1)', 'rgba(235, 107, 107, 1)'],
                borderWidth: 1,
                cutout: '40%',
            }],
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'right',
                    align: 'center'
                },
                tooltip: {
                    enabled: true,
                    callbacks: {
                        label: function(context) {
                            return ` Votos: ${context.raw}`;
                        }
                    }
                },
            }
        },
    });

    // Funções para atualizar os gráficos
    function atualizarGraficoDashboard(labels, novosDados) {
        graficoDashboard.data.labels = labels
        graficoDashboard.data.datasets[0].data = novosDados;
        graficoDashboard.update();
    }

    function atualizarGraficoValores(novosDados, valorTotal) {
        graficoValores.data.datasets[0].data = novosDados;
        textoValorTotal = valorTotal;
        graficoValores.update();
    }

    function atualizarGraficoFeedback(novosDados) {
        graficoFeedback.data.datasets[0].data = novosDados;
        graficoFeedback.update();
    }

    // Função que formata o número do mês para a sigla correspondente
    function formatarMesAno(mes, ano) {
        const meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        return `${meses[mes - 1]}/${ano%100}`; // Lembre-se: o array de meses começa no índice 0
    }

    // Btn do card dos gráficos
    const btnDropGrafico = $('#close-dashboard');
    let rotated = false;

    btnDropGrafico.on('click', (e) => {
        if (!rotated) {
            $(e.target).find('ion-icon').css({
                'transform': 'rotate(-180deg)',
                'transition': 'transform 0.5s ease'
            });
            rotated = true;
        } else {
            $(e.target).find('ion-icon').css({
                'transform': 'rotate(0deg)',
                'transition': 'transform 0.5s ease'
            });
            rotated = false;
        }
    });

    // Modal mensagem e modal de delete e editar, Modal sair do evento
    const mensagemModal = $('#mensagemModal');
    const modalMensagemContent = $('#modalMensagemContent');
    const mensagem = $('#mensagem');
    const exitModal = $('#exitModal');
    const deleteModal = $('#deleteModal');
    const editFormModal = $('#editFormModal');
    const actionEditFormModal = editFormModal.attr('action');


    // Btn de edit, de delete e exit
    const btnEditEvent = $('.edit-btn');
    const btnEditConfirm = $('#editConfirm');
    const btnDeleteEvent = $('.deletar-btn');
    const btnDeleteConfirm = $('#deleteConfirm');
    const btnExitEvent = $('.sairEvent-btn');
    const btnExitConfirm = $('#exitConfirm');

    btnEditEvent.on('click', (e) => {
        let idEvent = $(e.currentTarget).data('id');
        btnEditConfirm.attr('data-id', idEvent);

        $.ajax({
            type: 'GET',
            url: `/events/show/${idEvent}`,
            dataType: 'json',
            success: (response) => {
                const event = response.event;

                let dataEvent = event.date.split(' ')[0];
                let urlAction = actionEditFormModal + idEvent;
                editFormModal.attr('action', urlAction);

                $('.img-fluid').attr('src', `/image/events/${event.image}`);
                $('.img-fluid').attr('alt', event.title);
                $('#editModalLegenda').html(`Editando: ${event.title}`);
                $('#title').val(event.title);
                $('#date').val(dataEvent);
                $('#city').val(event.city);
                $('#private').val(event.private);
                $('#valor_ingresso').val(event.valor_ingresso ? event.valor_ingresso : null);
                $('#qtd_ingresso_disponivel').val(event.qtd_ingresso_disponivel ? event.qtd_ingresso_disponivel : null);
                $('#description').val(event.description);

                // Preencher os checkboxes com base na resposta
                let checkItems = event.items;
                const inputsGroup = $('input[name="items[]"]');
                $.each(inputsGroup, (idx, e) => {
                    let checkboxValue = $(e).val();
                    if (checkItems.includes(checkboxValue)) {
                        $(e).prop('checked', true);
                    } else {
                        $(e).prop('checked', false);
                    }
                });
            },
            error: () => {
                mensagemModal.modal('show');
                modalMensagemContent.addClass('msg-erro');
                mensagem.html('Erro ao buscar evento');

                setTimeout(() => {
                    mensagemModal.modal('hide');
                    modalMensagemContent.removeClass('msg-erro');
                }, 2000);
            },
        });
    });

    btnEditConfirm.on('click', (e) => {
        e.preventDefault();
        editFormModal.submit();
    });

    btnDeleteEvent.on('click', (e) => {
        let idEvent = $(e.currentTarget).data('id');

        $.ajax({
            type: 'GET',
            url: `/events/show/${idEvent}`,
            dataType: 'json',
            success: (response) => {
                const event = response.event;
                btnDeleteConfirm.attr('data-id', idEvent);
                $('#deleteContentTitle, #deleteModalLegenda').html(event.title);
            },
            error: (erro) => {
                mensagemModal.modal('show');
                modalMensagemContent.addClass('msg-erro');
                mensagem.html('Erro ao buscar evento');

                setTimeout(() => {
                    mensagemModal.modal('hide');
                    modalMensagemContent.removeClass('msg-erro');
                }, 2000);
            },
        });
    });

    btnDeleteConfirm.on('click', (e) => {
        let idEvent = $(e.currentTarget).data('id');

        $.ajax({
            type: 'DELETE',
            url: `/events/${idEvent}`,
            dataType: 'json',
            success: (response) => {
                deleteModal.modal('hide');
                mensagemModal.modal('show');
                modalMensagemContent.addClass('msg');
                mensagem.html(response.message);

                setTimeout(() => {
                    mensagemModal.modal('hide');
                    modalMensagemContent.removeClass('msg');
                    window.location.reload(true);
                }, 2000);
            },
            error: (erro) => {
                mensagemModal.modal('show');
                modalMensagemContent.addClass('msg-erro');
                mensagem.html(erro.message);

                setTimeout(() => {
                    mensagemModal.modal('hide');
                    modalMensagemContent.removeClass('msg-erro');
                }, 2000);
            },
        });
    });

    btnExitEvent.on('click', (e) => {
        let idEvent = $(e.currentTarget).data('id');

        $.ajax({
            type: 'GET',
            url: `/events/show/${idEvent}`,
            dataType: 'json',
            success: (response) => {
                const event = response.event;

                btnExitConfirm.attr('data-id', idEvent);
                $('#exitModalLegenda, #exitContentTitle').html(event.title);
            },
            error: (erro) => {
                mensagemModal.modal('show');
                modalMensagemContent.addClass('msg-erro');
                mensagem.html('Erro ao buscar evento');

                setTimeout(() => {
                    mensagemModal.modal('hide');
                    modalMensagemContent.removeClass('msg-erro');
                }, 2000);
            },
        });
    });

    btnExitConfirm.on('click', (e) => {
        let idEvent = $(e.currentTarget).data('id');
        console.log(idEvent);

        $.ajax({
            type: 'DELETE',
            url: `/events/leave/${idEvent}`,
            dataType: 'json',
            success: (response) => {
                exitModal.modal('hide');
                mensagemModal.modal('show');
                modalMensagemContent.addClass('msg');
                mensagem.html(response.message);

                setTimeout(() => {
                    mensagemModal.modal('hide');
                    modalMensagemContent.removeClass('msg');
                    window.location.reload(true);
                }, 2000);
            },
            error: (erro) => {
                mensagemModal.modal('show');
                modalMensagemContent.addClass('msg-erro');
                mensagem.html(erro.message);

                setTimeout(() => {
                    mensagemModal.modal('hide');
                    modalMensagemContent.removeClass('msg-erro');
                }, 2000);
            },
        });
    });

    const btnSeachEvent = $('.search-btn');

    btnSeachEvent.on('click', (e) => {
        e.preventDefault();
        let idEvent = $(e.currentTarget).data('id');

        $.ajax({
            type: 'GET',
            url: `/events/show/${idEvent}`,
            dataType: 'json',
            success: (response) => {
                const event = response.event;
                const participants = response.eventsAsParticipant;
                const feedbacks = response.feedbacks;
                const ticketSold = response.ticketSold;
                let graficoValoresValorArrecadado = 0;
                let textValor = "R$ 00,00";

                $('#estatistica-title').html(event.title);

                // Calcula o valor arrecadado das vendas dos ingressos
                $.each(participants, (idx, e) => {
                    graficoValoresValorArrecadado += parseFloat(e.pivot.valor_pago);
                });

                // Calcula o valor dos ingressos que ainda não foram vendidos
                let graficoValoresValorTotal = (event.qtd_ingresso_disponivel * event.valor_ingresso) - graficoValoresValorArrecadado;

                // Separa os feedbacks e acumula a quantidade de vezes que o indice aparece
                let avaliacoes = feedbacks.reduce((acc, feedback) => {

                    if (acc[feedback.avaliacao]) {
                        acc[feedback.avaliacao]++;
                    } else {
                        acc[feedback.avaliacao] = 1;
                    }

                    return acc;
                }, {});

                // Checa se o calculor não for um NaN então ele formata a label do gráfico de valores
                if(!isNaN(graficoValoresValorArrecadado)){
                    textValor = "R$ " + graficoValoresValorArrecadado.toFixed(2).replace('.', ',').toLocaleString('pt-BR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }

                // Mapeando as labels e os dados de venda dos ingressos
                let labels = ticketSold.map(item => formatarMesAno(item.month, item.year));
                let dadosIngressos = ticketSold.map(item => item.total_tickets);

                // Atualiza os gráficos
                atualizarGraficoValores([
                    graficoValoresValorArrecadado,
                    graficoValoresValorTotal
                ], textValor);

                atualizarGraficoFeedback([
                    avaliacoes[4] ?? 0,
                    avaliacoes[3] ?? 0,
                    avaliacoes[2] ?? 0,
                    avaliacoes[1] ?? 0
                ]);

                atualizarGraficoDashboard(labels, dadosIngressos);
            },
            error: (erro) => {
                mensagemModal.modal('show');
                modalMensagemContent.addClass('msg-erro');
                mensagem.html('Erro ao buscar evento');

                setTimeout(() => {
                    mensagemModal.modal('hide');
                    modalMensagemContent.removeClass('msg-erro');
                }, 2000);
            },
        });
    });

    /**
     * Ao carregar a página dispara o click no primeiro
     * botão para buscar as estatisticas do evento mais recente
     * e alimentar os gráficos
     */
    btnSeachEvent.first().trigger('click');
});
