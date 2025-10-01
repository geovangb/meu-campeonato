document.addEventListener('DOMContentLoaded', function() {
    const casaEscalacao = document.getElementById('casa-escalacao');
    const casaReservas  = document.getElementById('casa-reservas');
    const foraEscalacao = document.getElementById('fora-escalacao');
    const foraReservas  = document.getElementById('fora-reservas');

    function criarJogadorEl(jogador) {
        const div = document.createElement('div');
        div.className = 'jogador p-1 m-1 bg-light border rounded';
        div.draggable = true;
        div.dataset.jogadorId = jogador.id;
        div.textContent = jogador.nome;
        return div;
    }

    function popularTimes() {
        // Casa
        TIME_CASA.forEach(j => {
            const el = criarJogadorEl(j);
            if(ESCALACAO_CASA.includes(j.id)) casaEscalacao.appendChild(el);
            else if(RESERVAS_CASA.includes(j.id)) casaReservas.appendChild(el);
            else casaReservas.appendChild(el); // default
        });

        // Fora
        TIME_FORA.forEach(j => {
            const el = criarJogadorEl(j);
            if(ESCALACAO_FORA.includes(j.id)) foraEscalacao.appendChild(el);
            else if(RESERVAS_FORA.includes(j.id)) foraReservas.appendChild(el);
            else foraReservas.appendChild(el);
        });
    }

    function setupDragDrop() {
        const containers = document.querySelectorAll('.escala, .reservas');
        document.addEventListener('dragstart', function(e){
            if(e.target.classList.contains('jogador')){
                e.dataTransfer.setData('text/plain', e.target.dataset.jogadorId);
                e.target.classList.add('dragging');
            }
        });
        document.addEventListener('dragend', function(e){
            if(e.target.classList.contains('jogador')){
                e.target.classList.remove('dragging');
            }
        });

        containers.forEach(c => {
            c.addEventListener('dragover', e => e.preventDefault());
            c.addEventListener('drop', e => {
                e.preventDefault();
                const jogadorId = e.dataTransfer.getData('text/plain');
                const jogador = document.querySelector(`.jogador[data-jogador-id='${jogadorId}']`);
                e.currentTarget.appendChild(jogador);
            });
        });
    }

    function atualizarPontos() {
        const golsCasa = parseInt(document.getElementById('gols-casa').value) || 0;
        const golsFora = parseInt(document.getElementById('gols-fora').value) || 0;
        document.getElementById('pontos-casa').textContent = golsCasa - golsFora;
        document.getElementById('pontos-fora').textContent = golsFora - golsCasa;
    }

    popularTimes();
    setupDragDrop();
    atualizarPontos();

    document.getElementById('gols-casa').addEventListener('input', atualizarPontos);
    document.getElementById('gols-fora').addEventListener('input', atualizarPontos);

    document.getElementById('btn-salvar-jogo').addEventListener('click', function() {
        const CAMPEONATO_ID = document.getElementById('jogo-data').dataset.campeonatoId;
        const JOGO_ID = document.getElementById('jogo-data').dataset.jogoId;

        const casaTitulares = Array.from(casaEscalacao.children).map(el => el.dataset.jogadorId);
        const casaReserv = Array.from(casaReservas.children).map(el => el.dataset.jogadorId);
        const foraTitulares = Array.from(foraEscalacao.children).map(el => el.dataset.jogadorId);
        const foraReserv = Array.from(foraReservas.children).map(el => el.dataset.jogadorId);

        const payload = {
            data_partida: document.getElementById('data-jogo').value,
            gols_casa: parseInt(document.getElementById('gols-casa').value) || 0,
            gols_fora: parseInt(document.getElementById('gols-fora').value) || 0,
            juiz: document.getElementById('juiz').value,
            auxiliar_1: document.getElementById('auxiliar-1').value,
            auxiliar_2: document.getElementById('auxiliar-2').value,
            escalacao_time_1: casaTitulares,
            reservas_time_1: casaReserv,
            escalacao_time_2: foraTitulares,
            reservas_time_2: foraReserv
        };

        fetch(`/campeonatos/${CAMPEONATO_ID}/jogos/${JOGO_ID}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })
            .then(r => r.json())
            .then(res => {
                if(res.success) {
                    alert('Jogo salvo com sucesso!');
                    location.reload();
                } else {
                    alert('Erro ao salvar!');
                }
            });
    });
});
