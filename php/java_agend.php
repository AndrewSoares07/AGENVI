


<!-- carrossel dos funcionarios -->

 <script type="text/javascript">
        $(document).ready(function () {
            $('.func-carousel').slick({
                slidesToShow: 4,     // Exibe 3 funcionários por vez
                slidesToScroll: 3,   // Move um funcionário por vez
                // infinite: true,      // Loop infinito
                dots: true,          // Adiciona indicadores
                // arrows: true,        // Setas de navegação
                draggable: true,     // Permite arrastar com o mouse
                responsive: [
                    {
                        breakpoint: 768,  // Para telas menores (tablets e celulares)
                        settings: {
                            slidesToShow: 1
                        }
                    },
                    {
                        breakpoint: 1024, // Para telas médias (tablets horizontais)
                        settings: {
                            slidesToShow: 2
                        }
                    }
                ]
            });
        });

// codigo dos likes

        function curtir(button) {
            const idAvaliacao = button.getAttribute('data-avaliacao');

            // Enviar requisição AJAX para processar a curtida
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'atualizar_curtida.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);

                    if (response.success) {
                        // Atualizar o número de curtidas na página
                        const numCurtidas = document.getElementById('curtidas-' + idAvaliacao);
                        numCurtidas.textContent = response.total_curtidas;

                        // Atualizar o ícone de curtir
                        const heartIcon = button.querySelector('.heart-icon');
                        if (response.curtido) {
                            heartIcon.src = '../img/heath.png'; // Coração cheio
                        } else {
                            heartIcon.src = '../img/coracaop.png'; // Coração vazio
                        }
                    }
                }
            };
            xhr.send('idavaliacao=' + idAvaliacao);
        }

    </script>

















<script>




function openModal(event) {
    event.preventDefault(); // Prevent the form from submitting
    document.getElementById('myModal').style.display = "block"; // Show the modal
}

function closeModal() {
    document.getElementById('myModal').style.display = "none"; // Hide the modal
}

// Close the modal when clicking outside of it
window.onclick = function (event) {
    if (event.target == document.getElementById('myModal')) {
        closeModal();
    }
}

</script>



<!--  script do agendamento -->


<script>

    const meses = [
        "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho",
        "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"
    ];

    const dataAtual = new Date();
    let mesIndex = dataAtual.getMonth(); // Mês atual
    let anoAtual = dataAtual.getFullYear(); // Ano atual
    const mesMaximoPermitido = mesIndex + 2; // Permite no máximo dois meses à frente a partir do mês atual

    let semanaAtual = 0;

    function exibirMesAtual() {
        document.getElementById('mesAtual').textContent = `${meses[mesIndex]} de ${anoAtual}`;
        atualizarDias();
    }

    function mudarMes(direcao) {
        // Verifica se é para avançar ou retroceder
        if (direcao > 0) {
            // Avança para o próximo mês
            if (mesIndex + direcao > mesMaximoPermitido || (anoAtual === dataAtual.getFullYear() && mesIndex + direcao > 11)) {
                return; // Não permite avançar além do limite
            }
        } else if (direcao < 0) {
            // Retorna ao mês anterior
            if (anoAtual === dataAtual.getFullYear() && mesIndex + direcao < dataAtual.getMonth()) {
                return; // Não permite voltar para meses anteriores ao mês atual
            }
        }

        mesIndex += direcao;

        // Ajusta o ano e o índice do mês conforme necessário
        if (mesIndex > 11) {
            mesIndex = 0;
            anoAtual++;
            // Atualiza o limite para o novo ano
            if (anoAtual === dataAtual.getFullYear()) {
                mesMaximoPermitido = mesIndex + 2;
            } else {
                mesMaximoPermitido = 11; // Sem restrição de limite de meses para anos futuros
            }
        } else if (mesIndex < 0) {
            mesIndex = 11;
            anoAtual--;
            // Atualiza o limite para o novo ano
            if (anoAtual === dataAtual.getFullYear()) {
                mesMaximoPermitido = mesIndex + 2;
            } else {
                mesMaximoPermitido = 11; // Sem restrição de limite de meses para anos futuros
            }
        }

        semanaAtual = 0;
        exibirMesAtual();
    }

    function avancaSemana(semana) {
        const diasNoMes = new Date(anoAtual, mesIndex + 1, 0).getDate();
        const semanasNoMes = Math.floor((diasNoMes - 1) / 7);

        if (semanaAtual + semana > semanasNoMes) {
            if (mesIndex < mesMaximoPermitido) {
                mudarMes(1);
            }
        } else if (semanaAtual + semana < 0) {
            if (mesIndex > dataAtual.getMonth()) {
                mudarMes(-1);
            }
        } else {
            semanaAtual += semana;
            atualizarDias();
        }
    }

    // Exibindo o mês atual ao carregar a página
    exibirMesAtual();


    function atualizarDias() {
        const diasContainer = document.getElementById('daysContainer');
        diasContainer.innerHTML = ''; // Limpa o contêiner

        const primeiroDiaDoMes = new Date(anoAtual, mesIndex, 1);
        const ultimoDiaDoMes = new Date(anoAtual, mesIndex + 1, 0).getDate();

        const inicioSemana = semanaAtual * 7;
        const fimSemana = inicioSemana + 6;

        // Lista dos dias da semana trabalhados recebida do PHP
        const diasTrabalhados = <?php echo json_encode($diasSemanaArray); ?>;

        // Mapeia os dias da semana como strings
        const diasDaSemana = ["dom", "seg", "ter", "qua", "qui", "sex", "sab"];

        console.log("Dias trabalhados:", diasTrabalhados); // Debug

        for (let dia = inicioSemana + 1; dia <= fimSemana + 1; dia++) {
            if (dia <= ultimoDiaDoMes) {
                const dataDia = new Date(anoAtual, mesIndex, dia);
                const diaSemanaIndex = dataDia.getDay(); // Obtém o índice do dia da semana
                const diaSemanaString = diasDaSemana[diaSemanaIndex]; // Obtém o nome do dia da semana

                // Cria o botão para o dia
                const button = document.createElement('div');
                button.classList.add('text-center');
                button.innerHTML = `<button class="day-button" onclick="selectDay(${dia})" data-day="${dia}">${dia} <hr> <div>${diaSemanaString}</div></button>`;

                const buttonElement = button.querySelector('button'); // Pega o botão para aplicar estilos

                // Verifica se o dia deve ser desabilitado
                if (dataDia < new Date()) {
                    // Desabilita dias passados
                    buttonElement.disabled = true;

                    buttonElement.style.color = '#b0b0b0'; // Cor do texto cinza claro


                } else if (!diasTrabalhados.includes(diaSemanaString)) {
                    // Desabilita dias não trabalhados
                    buttonElement.disabled = true;

                    buttonElement.style.color = '#b0b0b0'; // Cor do texto cinza claro

                }

                // Adiciona o botão ao contêiner de dias
                diasContainer.appendChild(button);
            } else {
                // Adiciona um div vazio para dias fora do mês
                const button = document.createElement('div');
                button.classList.add('text-center', 'empty-day');
                button.innerHTML = '';

                diasContainer.appendChild(button);
            }
        }
    }






    function openModal(event) {
        event.preventDefault();
        const modal = document.getElementById('myModal');
        modal.style.display = "block";

        const hoje = new Date();
        const primeiroDiaSemana = new Date(hoje.setDate(hoje.getDate() - hoje.getDay()));
        const proximaSemana = new Date(primeiroDiaSemana.setDate(primeiroDiaSemana.getDate() + 7));

        mesIndex = proximaSemana.getMonth();
        anoAtual = proximaSemana.getFullYear();
        semanaAtual = Math.floor((proximaSemana.getDate() - 1) / 7);

        exibirMesAtual();
        atualizarDias();

        if (diaSelecionado !== null) {
            selectDay(diaSelecionado);
        }
    }


    let diaSelecionado = null;
    let horario = '';
    let servicoSelecionado = null;
    const empresaInfo = document.getElementById('empresa-info');
    const idEmpresa = empresaInfo.getAttribute('data-idempresa');
    let periodoSelecionado = '';
    let dataFormatada = '';
    let nomeDiaDaSemana = '';

    const nomesDiasDaSemana = ['dom', 'seg', 'ter', 'qua', 'qui', 'sex', 'sab'];

    console.log('ID da Empresa:', idEmpresa);

    function setPeriodo(periodo) {
        periodoSelecionado = periodo;
        console.log('Período Selecionado:', periodoSelecionado);

        // Remove a classe 'turno-selecionado' de todos os botões de turno
        document.querySelectorAll('.turno').forEach(button => {
            button.classList.remove('turno-selecionado');
        });

        // Adiciona a classe 'turno-selecionado' ao botão clicado
        const selectedButton = document.querySelector(`.turno[onclick="setPeriodo('${periodo}')"]`);
        if (selectedButton) {
            selectedButton.classList.add('turno-selecionado');
        }

        if (diaSelecionado && servicoSelecionado && dataFormatada && nomeDiaDaSemana) {
            selecionarServico(servicoSelecionado, dataFormatada, nomeDiaDaSemana, periodoSelecionado);
        }
    }


    function resetModal() {
        diaSelecionado = null;
        horario = '';
        servicoSelecionado = null;
        periodoSelecionado = '';
        dataFormatada = '';
        nomeDiaDaSemana = '';

        document.querySelectorAll('.day-button').forEach(diaBtn => {
            diaBtn.classList.remove('selected');
        });

        document.getElementById('horarios-container').innerHTML = '';
        document.getElementById('container-funcionarios').innerHTML = '';

        document.querySelectorAll('.card-bodyy').forEach(servico => {
            servico.classList.remove('selecionado');
        });

        // Remove estilos aplicados aos horários
        document.querySelectorAll('.horario-item').forEach(horario => {
            horario.classList.remove('selected'); // Remova a classe 'selected' ou outra classe CSS aplicada
        });

        // Remove estilos aplicados aos serviços
        document.querySelectorAll('.card-button').forEach(servico => {
            servico.classList.remove('selecionado'); // Remova a classe 'selecionado' ou outra classe CSS aplicada
        });
        
         document.querySelectorAll('.turno').forEach(button => {
            button.classList.remove('turno-selecionado');
        });
    }
    function selectDay(dia) {
        resetModal(); // Reseta o modal ao selecionar um novo dia

        diaSelecionado = dia;
        const dataSelecionada = new Date(anoAtual, mesIndex, dia);
        dataFormatada = dataSelecionada.toISOString().split('T')[0];

        const diaDaSemana = dataSelecionada.getDay();
        nomeDiaDaSemana = nomesDiasDaSemana[diaDaSemana];

        console.log('Dia Selecionado:', diaSelecionado);
        console.log('Data Selecionada:', dataFormatada);
        console.log('Dia da Semana:', nomeDiaDaSemana);

        const selectedButton = Array.from(document.querySelectorAll('.day-button')).find(diaBtn => parseInt(diaBtn.getAttribute('data-day')) === dia);
        if (selectedButton) {
            selectedButton.classList.add('selected');
        }

        // Atualiza os horários com a nova data
        if (periodoSelecionado) {
            atualizarHorarios(null, dataFormatada, nomeDiaDaSemana, periodoSelecionado);
        }
    }


    function selecionarServico(idServico, dataSelecionada, diaDaSemana, periodo) {
        servicoSelecionado = idServico;

        const todosServicos = document.querySelectorAll('.card-button');
        todosServicos.forEach(servico => {
            servico.classList.remove('selecionado');
        });

        const servicoSelecionadoElemento = document.querySelector('.card-button[data-id="' + idServico + '"]');
        if (servicoSelecionadoElemento) {
            servicoSelecionadoElemento.classList.add('selecionado');
        }

        console.log('ID Serviço:', idServico);
        console.log('Data Selecionada:', dataSelecionada);
        console.log('Dia da Semana:', diaDaSemana);

        // Atualiza os horários com o novo serviço, mantendo o período
        atualizarHorarios(idServico, dataSelecionada, diaDaSemana, periodo);
    }

    document.querySelectorAll('.card-button').forEach(button => {
        button.addEventListener('click', function () {
            const idServico = this.getAttribute('data-id');
            selecionarServico(idServico, dataFormatada, nomeDiaDaSemana, periodoSelecionado);
        });
    });




    document.addEventListener('DOMContentLoaded', function () {
        fetch('get_horarios.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('horarios-container').innerHTML = data;

                const observer = new MutationObserver((mutations) => {
                    mutations.forEach((mutation) => {
                        if (mutation.addedNodes.length > 0) {
                            var buttons = document.querySelectorAll('.horario-item');

                            buttons.forEach(function (button) {
                                button.addEventListener('click', function () {
                                    var inicio = this.getAttribute('data-inicio');
                                    var fim = this.getAttribute('data-fim');
                                    console.log('Horário selecionado: Início -', inicio, ', Fim -', fim);

                                    // Remove a classe 'horario-selecionado' de todos os botões
                                    document.querySelectorAll('.horario-item').forEach(btn => {
                                        btn.classList.remove('horario-selecionado');
                                    });

                                    // Adiciona a classe 'horario-selecionado' ao botão clicado
                                    this.classList.add('horario-selecionado');

                                    // Adicione qualquer lógica adicional que deseja realizar ao selecionar um horário aqui
                                });
                            });
                        }
                    });
                });

                observer.observe(document.getElementById('horarios-container'), {
                    childList: true,
                    subtree: true
                });
            })
            .catch(error => console.error('Erro ao carregar horários:', error));
    });


    let idFuncionarioSelecionado = null; // Variável global para armazenar o ID do funcionário selecionado
    let dadosAgendamento = {}; // Objeto para armazenar os dados do agendamento

    document.addEventListener('DOMContentLoaded', () => {
        console.log('Script JavaScript carregado e executado');

        // Adiciona um listener de evento para o corpo do documento
        document.body.addEventListener('change', (event) => {
            if (event.target.name === 'funcionario') {
                idFuncionarioSelecionado = event.target.value;
                console.log('ID do Funcionário Selecionado:', idFuncionarioSelecionado);
            }
        });
    });

    function atualizarHorarios(idServico, dataSelecionada, diaDaSemana, periodo) {
    console.log('Atualizar Horários chamada com:', { idServico, dataSelecionada, diaDaSemana, periodo });

    if (dataSelecionada && diaDaSemana) {
        console.log('Dia da Semana:', diaDaSemana); // Verifique o valor do dia da semana
        document.getElementById('container-funcionarios').innerHTML = '';

        fetch('get_horarios.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                'idservico': idServico,
                'data': dataSelecionada,
                'idempresa': idEmpresa,
                'diaDaSemana': diaDaSemana,
                'periodo': periodo || ''
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error in response from get_horarios.php');
            }
            return response.text();
        })
        .then(data => {
            document.getElementById('horarios-container').innerHTML = data;
            const horarios = document.querySelectorAll('.horario-item');
            horarios.forEach(horario => {
                horario.addEventListener('click', function () {
                    const inicio = this.getAttribute('data-inicio');
                    const fim = this.getAttribute('data-fim');
                    console.log('Horário selecionado: Início -', inicio, ', Fim -', fim);

                    fetch('get_funcionarios.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            'idservico': idServico,
                            'idempresa': idEmpresa,
                            'data': dataSelecionada,
                            'diaDaSemana': diaDaSemana,
                            'periodo': periodo,
                            'inicio': inicio,
                            'fim': fim
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error in response from get_funcionarios.php');
                        }
                        return response.text();
                    })
                    .then(data => {
                        document.getElementById('container-funcionarios').innerHTML = data;

                        // Atualiza os dados de agendamento
                        dadosAgendamento = {
                            idServico,
                            idEmpresa,
                            dataSelecionada,
                            inicio,
                            diaDaSemana,
                            fim
                        };
                    })
                    .catch(error => console.error('Error loading data:', error));
                });
            });
        })
        .catch(error => console.error('Error loading horarios:', error));
    } else {
        console.error('Data ou dia da semana não definidos.');
    }
}

function agendar() {
    // Verifica se todos os dados estão presentes
    if (!idFuncionarioSelecionado || !dadosAgendamento.idServico || !dadosAgendamento.idEmpresa || !dadosAgendamento.dataSelecionada || !dadosAgendamento.inicio || !dadosAgendamento.fim || !dadosAgendamento.diaDaSemana) {
        console.error('Dados incompletos para agendar.', dadosAgendamento); // Log para ajudar na depuração
        return;
    }

    console.log('Função agendar chamada com:', {
        ...dadosAgendamento,
        idFuncionario: idFuncionarioSelecionado
    });

    // Aqui você pode enviar esses dados para o servidor ou processar como necessário
    const { idServico, idEmpresa, dataSelecionada, inicio, fim, diaDaSemana } = dadosAgendamento;

    // Exemplo de como enviar os dados para o servidor
    fetch('salvar_agendamento.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            'idservico': idServico,
            'idempresa': idEmpresa,
            'data': dataSelecionada,
            'inicio': inicio,
            'fim': fim,
            'diaDaSemana': diaDaSemana, 
            'idfuncionario': idFuncionarioSelecionado
        })
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error in response from salvar_agendamento.php');
            }
            return response.text();
        })
        .then(data => {
    console.log('Agendamento salvo com sucesso:', data);
    
    // Exibe um modal customizado
    var modal = document.getElementById('customModal');
    var modalContent = modal.querySelector('.modal-content');

    modal.style.display = 'block';  // Exibe o modal

    closeModal();  // Fecha o modal original (se necessário)
})

        .catch(error => console.error('Error saving agendamento:', error));
}
function closeCustomModal() {
    var modal = document.getElementById('customModal');
    modal.style.display = 'none';
}


    function closeModal() {
        var modal = document.getElementById('myModal');
        modal.style.display = 'none';
    }




    exibirMesAtual();
    atualizarDias();
</script>

