import Chart from "chart.js/auto";

document.addEventListener("DOMContentLoaded", function () {
    const canvas = document.getElementById("hexStatsChart");
    if (!canvas) return;

    // Mengambil data dari atribut HTML
    const labels = JSON.parse(canvas.getAttribute("data-labels"));
    const values = JSON.parse(canvas.getAttribute("data-values"));

    const ctx = canvas.getContext("2d");

    new Chart(ctx, {
        type: "radar",
        data: {
            labels: labels,
            datasets: [
                {
                    data: values,
                    fill: true,
                    backgroundColor: "rgba(99, 102, 241, 0.15)",
                    borderColor: "#6366f1",
                    pointBackgroundColor: "#6366f1",
                    pointBorderColor: "#fff",
                    pointHoverBackgroundColor: "#fff",
                    pointHoverBorderColor: "#6366f1",
                    borderWidth: 3,
                    pointRadius: 4,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { display: false } },
            scales: {
                r: {
                    angleLines: { color: "rgba(229, 231, 235, 1)" },
                    grid: { color: "rgba(229, 231, 235, 1)" },
                    pointLabels: {
                        font: { size: 11, weight: "900", family: "sans-serif" },
                        color: "#9ca3af",
                    },
                    ticks: { display: false },

                    min: 0,
                    suggestedMax: 10,
                },
            },
        },
    });
});
