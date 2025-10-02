$(document).ready(function() {
    function renderStepTimes(campeonatoId, minTimes) {

        let options = TIMES.map(time => `<option value="${time.id}">${time.nome}</option>`).join('');

        $("#step-1").html(`
            <h4>Selecione os Times</h4>
            <form id="form-times">
                <input type="hidden" name="campeonato_id" value="${campeonatoId}">
                <select name="times[]" id="select-times" class="form-control" multiple required>
                    ${options}
                </select>

                <div class="mt-3">
                    <button type="button" class="btn btn-success" id="btn-add-time">+ Adicionar Novo Time</button>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Salvar Times</button>
            </form>
        `);

        $("#select-times").select2({
            placeholder: "Selecione os times",
            width: '100%'
        });

        $("#steps .nav-link").on("click", function(e) {
            e.preventDefault();

            let step = $(this).data("step");

            $(".step").addClass("d-none");
            $("#steps .nav-link").removeClass("active");

            $("#step-" + step).removeClass("d-none");
            $(this).addClass("active");
        });

        $("#btn-add-time").on("click", function() {
            let modal = new bootstrap.Modal(document.getElementById('modalNovoTime'));
            modal.show();
        });

        $("#form-times").on("submit", function(e){
            e.preventDefault();
            let selected = $("#select-times option:selected").length;

            if(selected < minTimes) {
                alert(`Selecione pelo menos ${minTimes} times.`);
                return;
            }

            let data = new FormData(this);

            fetch('/api/campeonatos/'+campeonatoId+'/starter/times', {
                method: "POST",
                body: data
            }).then(r => r.json())
                .then(res => {
                    if(res.success) {
                        alert("Times salvos com sucesso!");
                        $("#step-1").addClass("d-none");
                        $("#step-2").removeClass("d-none");
                        $("#steps .nav-link.active").removeClass("active");
                        $("#steps .nav-link[data-step='2']").addClass("active");
                    }
                });
        });

        $("#btn-save-step2").on("click", function(){
            let form = document.getElementById("form-step2");
            let data = new FormData(form);

            data.append("campeonato_id", CAMPEONATO_ID);

            fetch('/api/campeonatos/'+campeonatoId+'/starter/regras', {
                method: "POST",
                body: data
            })
                .then(r => r.json())
                .then(res => {
                    if(res.success) {
                        alert("Regras salvas com sucesso!");
                        $("#step-2").addClass("d-none");
                        $("#step-3").removeClass("d-none");
                        $("#steps .nav-link.active").removeClass("active");
                        $("#steps .nav-link[data-step='3']").addClass("active");
                    }
                });
        });

        $(document).on('click', '#btn-sortear', function() {
            fetch('/api/campeonatos/' + campeonatoId + '/starter/sortear', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
            }).then(r => r.json()).then(res => {
                if (!res.success) { alert(res.message || 'Erro'); return; }
                renderConfrontos(res.confrontos);
            });
        });

        function renderConfrontos(confrontos) {
            let html = `<h5>Confrontos Sorteados (Ida e Volta)</h5>
                <table class="table">
                    <thead><tr><th>#</th><th>Confronto</th><th>Ida</th><th>Volta</th><th>Ações</th></tr></thead>
                    <tbody>`;
            confrontos.forEach((c, i) => {
                const toInputValue = dt => {
                    const d = new Date(dt);
                    const pad = n => (n<10? '0'+n : n);
                    return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
                };
                html += `<tr data-index="${i}">
            <td>${i+1}</td>
            <td>${c.time1} x ${c.time2}</td>
            <td>
                <input type="datetime-local" class="form-control input-data" data-jogo-id="${c.jogo_ida}" value="${toInputValue(c.data_ida)}">
            </td>
            <td>
                <input type="datetime-local" class="form-control input-data" data-jogo-id="${c.jogo_volta}" value="${toInputValue(c.data_volta)}">
            </td>
            <td>
                <button class="btn btn-sm btn-primary btn-save-row" data-ids="${c.jogo_ida},${c.jogo_volta}">Salvar</button>
            </td>
        </tr>`;
            });
            html += `</tbody></table>
             <div class="mb-3">
                <button id="btn-save-all" class="btn btn-success">Iniciar Campeonato</button>
             </div>`;
            $("#resultado-sorteio").html(html);
        }

        $(document).on('click', '.btn-save-row', function() {
            const ids = $(this).data('ids').toString().split(',');
            const id1 = ids[0], id2 = ids[1];
            const input1 = $(`input[data-jogo-id='${id1}']`).val();
            const input2 = $(`input[data-jogo-id='${id2}']`).val();

            Promise.all([
                fetch(`/api/jogos/${id1}/update-date`, {
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    body: new URLSearchParams({ data_partida: input1 })
                }),
                fetch(`/api/jogos/${id2}/update-date`, {
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    body: new URLSearchParams({ data_partida: input2 })
                })
            ]).then(res => Promise.all(res.map(r => r.json())))
                .then(results => {
                    alert('Datas salvas!');
                });
        });

        $(document).on('click', '#btn-save-all', function() {
            const items = [];
            $('.input-data').each(function() {
                items.push({ id: $(this).data('jogo-id'), data_partida: $(this).val() });
            });

            fetch('/api/jogos/bulk-update-dates', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ items })
            }).then(r => r.json())
                .then(res => {
                    if (res.success) {
                        window.location.href = `/campeonatos/${CAMPEONATO_ID}/jogos`;
                    } else {
                        alert('Erro ao salvar datas.');
                    }
                });
        });
    }

    $("#form-novo-time").on("submit", function(e){
        e.preventDefault();
        let data = new FormData(this);

        fetch(`/api/times`, {
            method: "POST",
            body: data
        }).then(r => r.json())
            .then(res => {
                if(res.success) {
                    let option = new Option(res.time.nome, res.time.id, true, true);
                    $("#select-times").append(option).trigger('change');

                    bootstrap.Modal.getInstance(document.getElementById('modalNovoTime')).hide();
                    this.reset();
                }
            });
    });

    renderStepTimes(CAMPEONATO_ID, MIN_TIMES);
    carregarStatusInicial(CAMPEONATO_ID);
    function carregarStatusInicial(campeonatoId) {
        fetch(`/api/campeonatos/${campeonatoId}/starter/status`)
            .then(r => r.json())
            .then(res => {
                if (res.times && res.times.length > 0) {
                    let selected = res.times.map(t => t.id.toString());
                    $("#select-times").val(selected).trigger("change");

                    $("#step-1").addClass("d-none");
                    $("#step-2").removeClass("d-none");
                    $("#steps .nav-link").removeClass("active");
                    $("#steps .nav-link[data-step='2']").addClass("active");
                }

                if (res.regras) {
                    $("select[name=penaltis]").val(res.regras.penaltis);
                    $("select[name=prorrogacao]").val(res.regras.prorrogacao);
                    $("select[name=criterio_desempate]").val(res.regras.criterio_desempate);
                }

                if (res.confrontos && res.confrontos.length > 0) {
                    $("#step-2").addClass("d-none");
                    $("#step-3").removeClass("d-none");
                    $("#steps .nav-link").removeClass("active");
                    $("#steps .nav-link[data-step='3']").addClass("active");

                    renderConfrontos(res.confrontos);
                }
            });
    }

    $(document).on('click', '.btn-save-row', function() {
        const ids = $(this).data('ids').toString().split(',');
        const id1 = ids[0], id2 = ids[1];
        const input1 = $(`input[data-jogo-id='${id1}']`).val();
        const input2 = $(`input[data-jogo-id='${id2}']`).val();

        Promise.all([
            fetch(`/api/jogos/${id1}/update`, {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                body: new URLSearchParams({ gols_casa: $(`input[data-jogo-id='${id1}']`).data('gols-casa'),
                    gols_fora: $(`input[data-jogo-id='${id1}']`).data('gols-fora') })
            }),
            fetch(`/api/jogos/${id2}/update`, {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                body: new URLSearchParams({ gols_casa: $(`input[data-jogo-id='${id2}']`).data('gols-casa'),
                    gols_fora: $(`input[data-jogo-id='${id2}']`).data('gols-fora') })
            })
        ]).then(res => Promise.all(res.map(r => r.json())))
            .then(results => {
                alert('Datas e placares salvos!');

                results.forEach(res => {
                    if(res.confrontos_proxima_fase && res.confrontos_proxima_fase.length > 0) {
                        $("#resultado-sorteio").append("<h5>Semifinal Gerada</h5>");
                        renderConfrontos(res.confrontos_proxima_fase);
                    }
                });
            });
    });

});
