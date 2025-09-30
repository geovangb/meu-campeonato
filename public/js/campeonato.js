$(document).ready(function () {
    const selectTipo = $("select[name='tipo_campeonato']");
    const inputQtdTimes = $("input[name='qtd_times']");

    if (!selectTipo.length || !inputQtdTimes.length) return;

    if (!selectTipo.hasClass("select2-hidden-accessible")) {
        selectTipo.select2({
            placeholder: "Selecione o tipo de campeonato",
            width: "100%"
        });
    }

    const mapQtdTimes = {
        "Fase de Grupos (32 times)": 32,
        "Oitavas de Final (16 times)": 16,
        "Copa Quartas de final (8 times)": 8,
        "Semi Final (4 times)": 4
    };

    function atualizarQtdTimes() {
        const tipo = selectTipo.val();
        inputQtdTimes.val(mapQtdTimes[tipo] ?? 0);
    }

    atualizarQtdTimes();
    selectTipo.on("change", atualizarQtdTimes);
});
