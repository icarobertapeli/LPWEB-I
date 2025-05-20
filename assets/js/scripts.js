// scripts.js atualizado com descrição e campos padronizados como 'Informação não disponível'

let generosMap = {};

function carregarGeneros(tipo, callback) {
    const url = `https://api.themoviedb.org/3/genre/${tipo}/list?api_key=2f4b0955b9882844b0530bc1ebf253fd&language=pt-BR`;

    $.get(url, function (data) {
        generosMap = {};
        data.genres.forEach(g => {
            generosMap[g.id] = g.name;
        });

        Object.entries(generosMap).forEach(([id, nome]) => {
            if (nome === "Talk") generosMap[id] = "Talk Show";
        });

        if (typeof callback === "function") callback();
    });
}

function carregarPopulares(tipo) {
    $(".tab-btn").removeClass("active");
    $(`.tab-btn[data-tipo="${tipo}"]`).addClass("active");

    $.get("../api/buscar.php", { tipo }, function (data) {
        try {
            const resultados = JSON.parse(data).results;
            $("#resultado").empty();

            resultados.forEach(item => {
                const titulo = item.title || item.name;
                const poster = item.poster_path
                    ? `https://image.tmdb.org/t/p/w300${item.poster_path}`
                    : 'https://via.placeholder.com/300x450?text=Sem+Imagem';

                const generoId = item.genre_ids?.[0] ?? null;
                const categoria = generoId && generosMap[generoId] ? generosMap[generoId] : "Sem gênero definido";
                const categoriaClasse = categoria === "Sem gênero definido" ? "text-unknown" : "text-info";

                const col = `
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2 d-flex">
                        <div class="card bg-dark text-light w-100 h-100 border-light">
                            <img src="${poster}" class="card-img-top" alt="${titulo}" data-tipo="${tipo}" data-tmdb_id="${item.id}">
                            <div class="card-body p-2 text-center d-flex flex-column justify-content-between">
                                <h6 class="card-title">${titulo}</h6>
                                <small class="${categoriaClasse}">${categoria}</small>
                                ${logado ? `<button class="btn btn-outline-warning btn-sm btn-favoritar mt-2"
                                    data-titulo="${titulo}"
                                    data-tmdb_id="${item.id}"
                                    data-tipo="${tipo}"
                                    data-categoria="${categoria}"
                                    data-poster="${item.poster_path}">
                                    ⭐ Favoritar
                                </button>` : ''}
                            </div>
                        </div>
                    </div>
                `;
                $("#resultado").append(col);
            });
        } catch (e) {
            console.error("Erro ao processar JSON:", e);
            $("#resultado").html("<p>Erro ao carregar dados da API.</p>");
        }
    }).fail(function () {
        $("#resultado").html("<p>Falha na requisição à API.</p>");
    });
}

function abrirModalDetalhes(tipo, tmdb_id) {
    const apiKey = "2f4b0955b9882844b0530bc1ebf253fd";
    const url = `https://api.themoviedb.org/3/${tipo}/${tmdb_id}?api_key=${apiKey}&language=pt-BR`;

    $.get(url, function (data) {
        const titulo = data.title || data.name || "Informação não disponível";
        const descricao = data.overview && data.overview.trim() !== "" ? data.overview : "Informação não disponível.";
        const imagem = data.poster_path
            ? `https://image.tmdb.org/t/p/w500${data.poster_path}`
            : 'https://via.placeholder.com/500x750?text=Sem+Imagem';

        let dataLancamento = data.release_date || data.first_air_date || "Informação não disponível";
        if (dataLancamento !== "Informação não disponível") {
            const partes = dataLancamento.split("-");
            dataLancamento = `${partes[2]}/${partes[1]}/${partes[0]}`;
        }

        const nota = data.vote_average ? data.vote_average.toFixed(1) : "Informação não disponível";
        const generos = data.genres?.length ? data.genres.map(g => g.name).join(", ") : "Informação não disponível";

        const status = {
            "Released": "Lançado",
            "Planned": "Planejado",
            "Canceled": "Cancelado",
            "Post Production": "Pós-produção"
        }[data.status] || "Informação não disponível";

        const idiomaOriginal = {
            "en": "Inglês",
            "pt": "Português",
            "es": "Espanhol",
            "fr": "Francês",
            "ja": "Japonês"
        }[data.original_language] || "Informação não disponível";

        const orcamento = data.budget ? `$${data.budget.toLocaleString()}` : "Informação não disponível";
        const receita = data.revenue ? `$${data.revenue.toLocaleString()}` : "Informação não disponível";

        $("#modalTitulo").text(titulo);
        $("#modalDescricao").text(descricao).removeClass().addClass("text-light small");
        $("#modalImagem").attr("src", imagem);
        $("#modalInfo").html(`
            <p><strong>Data de Lançamento:</strong> ${dataLancamento}</p>
            <p><strong>Nota:</strong> ${nota}</p>
            <p><strong>Gêneros:</strong> ${generos}</p>
            <p><strong>Status:</strong> ${status}</p>
            <p><strong>Idioma original:</strong> ${idiomaOriginal}</p>
            <p><strong>Orçamento:</strong> ${orcamento}</p>
            <p><strong>Receita:</strong> ${receita}</p>
        `);
    });

    $.get(`https://api.themoviedb.org/3/${tipo}/${tmdb_id}/credits?api_key=${apiKey}&language=pt-BR`, function(creditos) {
        const atores = creditos.cast.slice(0, 6).map(ator => {
            const foto = ator.profile_path ? `https://image.tmdb.org/t/p/w185${ator.profile_path}` : 'https://via.placeholder.com/100x150?text=Sem+Foto';
            return `
                <div class="text-center me-3">
                    <img src="${foto}" class="rounded mb-1" style="height:100px">
                    <div><strong>${ator.name}</strong></div>
                    <div class="text-muted small">${ator.character}</div>
                </div>`;
        }).join("");

        $("#modalElenco").html(`<h6>Elenco Principal</h6><div class="d-flex flex-wrap">${atores}</div>`);

        const modal = new bootstrap.Modal(document.getElementById("detalhesModal"));
        modal.show();
    });
}

$(document).ready(function () {
    carregarGeneros("movie", () => carregarPopulares("movie"));

    $(".tab-btn").on("click", function () {
        const tipo = $(this).data("tipo");
        carregarGeneros(tipo, () => carregarPopulares(tipo));
    });

    $(document).on("click", ".btn-favoritar", function () {
        const dados = $(this).data();
        $.post("../admin/favoritar.php", dados, function (resposta) {
            alert(resposta);
        });
    });

    $(document).on("click", ".card-img-top", function () {
        const tipo = $(this).data("tipo");
        const tmdb_id = $(this).data("tmdb_id");
        abrirModalDetalhes(tipo, tmdb_id);
    });
});
