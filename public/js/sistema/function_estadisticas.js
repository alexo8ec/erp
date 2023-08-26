$(document).ready(function () {
    var safeColors = [
        '00',
        '33',
        '66',
        '99',
        'cc',
        'ff'
    ];
    var rand = function () {
        return Math.floor(Math.random() * 6);
    };
    var randomColor = function () {
        var r = safeColors[rand()];
        var g = safeColors[rand()];
        var b = safeColors[rand()];
        return "#" + r + g + b;
    };
    (async () => {
        for (var i = 0; i < $('#numBar').val(); i++) {
            var article = document.getElementById("barChart" + i);
            const respuestaRaw = await fetch($('#controlador').val() + "/" + article.dataset.titulo + "Mes?anio=" + article.dataset.columns);
            const respuesta = await respuestaRaw.json();
            var barData = {
                labels: [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre"
                ],
                datasets: [
                    {
                        label: article.dataset.titulo,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(145, 158, 86, 0.2)',
                            'rgba(95, 48, 86, 0.2)',
                            'rgba(146, 136, 86, 0.2)',
                            'rgba(250, 200, 86, 0.2)',
                            'rgba(178, 97, 86, 0.2)',
                            'rgba(93, 178, 86, 0.2)',
                        ],
                        borderColor: "rgba(26,179,148,0.7)",
                        pointBackgroundColor: "rgba(26,179,148,1)",
                        pointBorderColor: randomColor(),
                        data: respuesta,
                        borderWidth: 1
                    }
                ]
            };
            var barOptions = {
                responsive: true
            };
            try {
                var ctx2 = article.getContext("2d");
            } catch (error) {
                console.log(error);
            }
            new Chart(ctx2, {
                type: 'bar',
                data: barData,
                options: barOptions
            });
        }
    })();
});
