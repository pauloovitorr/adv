// Inicializar Lucide Icons
lucide.createIcons();

// Efeito de Confetti
function launchConfetti() {
  var end = Date.now() + (2 * 1000); // 3 segundos

  var colors = ['#061124', '#ffffff'];

  (function frame() {
    confetti({
      particleCount: 2,
      angle: 60,
      spread: 55,
      origin: { x: 0 },
      colors: colors
    });

    confetti({
      particleCount: 2,
      angle: 120,
      spread: 55,
      origin: { x: 1 },
      colors: colors
    });

    if (Date.now() < end) {
      requestAnimationFrame(frame);
    }
  })();
}


// Lançar confetti ao carregar a página
window.addEventListener('load', function() {
    setTimeout(launchConfetti, 1000);
});

// Configuração global dos gráficos
Chart.defaults.font.family = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
Chart.defaults.font.size = 12;
Chart.defaults.color = '#6b7280';


// Gráfico 1: Novos Processos (Line)
const ctx1 = document.getElementById('chartNovosProcessos').getContext('2d');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
        datasets: [{
            label: 'Processos',
            data: [12, 19, 15, 25, 22, 30, 28, 35, 32, 38, 42, 45],
            borderColor: '#4A9EFF',
            backgroundColor: 'rgba(74, 158, 255, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: '#f3f4f6'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});


// Gráfico 2: Atividades da Semana (Bar Horizontal)
const ctx2 = document.getElementById('chartAtividades').getContext('2d');
new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta'],
        datasets: [{
            label: 'Atividades',
            data: [12, 18, 15, 22, 16],
            backgroundColor: '#8B5CF6',
            borderRadius: 6
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            x: {
                beginAtZero: true,
                grid: {
                    color: '#f3f4f6'
                }
            },
            y: {
                grid: {
                    display: false
                }
            }
        }
    }
});


// Gráfico 3: Honorários Mensais (Line com área)
const ctx3 = document.getElementById('chartHonorarios').getContext('2d');
new Chart(ctx3, {
    type: 'line',
    data: {
        labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
        datasets: [{
            label: 'R$ Honorários',
            data: [25000, 32000, 28000, 45000, 38000, 52000, 48000, 58000, 62000, 55000, 68000, 72000],
            borderColor: '#10B981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'R$ ' + value.toLocaleString('pt-BR');
                    }
                },
                grid: {
                    color: '#f3f4f6'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});


// Gráfico 4: Áreas de Atuação (Bar)
const ctx4 = document.getElementById('chartAreasAtuacao').getContext('2d');
new Chart(ctx4, {
    type: 'polarArea',
    data: {
        labels: ['Civil', 'Trabalhista', 'Criminal', 'Família', 'Tributário'],
        datasets: [{
            label: 'Processos',
            data: [45, 38, 28, 35, 22],
            backgroundColor: '#4A9EFF',
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: '#f3f4f6'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});


// Gráfico 5: Processos por Status (Doughnut)
const ctx5 = document.getElementById('chartProcessosStatus').getContext('2d');
new Chart(ctx5, {
    type: 'doughnut',
    data: {
        labels: ['Em Andamento', 'Arquivados', 'Aguardando', 'Concluídos'],
        datasets: [{
            data: [45, 28, 15, 32],
            backgroundColor: ['#4A9EFF', '#10B981', '#F59E0B', '#8B5CF6'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    usePointStyle: true
                }
            }
        }
    }
});



// Gráfico 6: Taxa de Sucesso (Pie)
const ctx6 = document.getElementById('chartTaxaSucesso').getContext('2d');
new Chart(ctx6, {
    type: 'pie',
    data: {
        labels: ['Ganhos', 'Perdidos', 'Acordos'],
        datasets: [{
            data: [65, 15, 20],
            backgroundColor: ['#10B981', '#EF4444', '#F59E0B'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    usePointStyle: true
                }
            }
        }
    }
});




