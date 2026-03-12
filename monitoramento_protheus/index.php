<?php

?>
<!DOCTYPE html>
<html lang='pt-br'>
    <head>
        <title>Dashboard Monitoramento Protheus</title>
        <meta charset='utf-8' />
        <meta name='viewport' content='width=device-width, initial-scale=1.0' />
        <script src='https://cdn.tailwindcss.com'></script>
        <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css' rel='stylesheet' />
        <link href='https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap' rel='stylesheet' />
        <script>
            tailwind.config = {
                darkMode: 'class'
            };
            (function() {
                const saved = localStorage.getItem('theme');
                const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (saved === 'dark' || (!saved && prefersDark)) {
                    document.documentElement.classList.add('dark');
                }
            })();
        </script>
        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
            .glass {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }
            html.dark .glass {
                background: rgba(15, 23, 42, 0.92);
                border: 1px solid rgba(148, 163, 184, 0.18);
            }
            .pill-ok {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 2px 10px;
                border-radius: 999px;
                font-weight: 700;
                font-size: 12px;
                background: rgba(16, 185, 129, .10);
                color: rgb(6, 95, 70);
                border: 1px solid rgba(16, 185, 129, .25);
            }
            html.dark .pill-ok {
                color: rgb(167, 243, 208);
            }
            .pill-error {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 2px 10px;
                border-radius: 999px;
                font-weight: 700;
                font-size: 12px;
                background: rgba(185, 16, 16, 0.1);
                color: rgba(95, 6, 6, 1);
                border: 1px solid rgba(185, 16, 16, 0.25);
            }
            html.dark .pill-error {
                color: rgb(243, 167, 167);
            }
            .mb-3 {
                margin-bottom: 0.45rem !important;
            }
            .p-4 {
                padding: 0.5rem !important;
            }
        </style>
    </head>

    <body class='bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 h-screen p-4 sm:p-6 flex flex-col'>
        <div class='mx-auto max-w-8xl w-full flex flex-col flex-grow'>
            <!-- MODIFICAÇÃO: Adicionado flex-grow para que este painel ocupe o espaço vertical -->
            <div class='glass rounded-3xl shadow-2xl overflow-hidden flex flex-col flex-grow'>
                <!-- Header (sem mudanças) -->
                <div class='bg-white/50 dark:bg-slate-900/40 p-4 sm:p-6 border-b border-gray-100 dark:border-slate-700 flex flex-col sm:flex-row items-center justify-between gap-4' style="padding: 1rem !important;">
                    <img src='../../assets/img/logo/logo.png' class='h-12 order-2 sm:order-1' alt='Logo' />
                    <div class='text-center order-1 sm:order-2'>
                        <h1 class='text-lg sm:text-xl md:text-2xl font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wider'>Monitoramento Protheus</h1>
                        <p id="last-update" class='text-slate-500 dark:text-slate-300 text-sm'>Aguardando dados...</p>
                    </div>
                    <div class="flex items-center gap-2 order-3 sm:order-3">
                        <button id='openModalBtn' type='button' class='h-11 w-11 rounded-xl bg-white/70 hover:bg-white text-slate-700 dark:bg-slate-800/70 dark:hover:bg-slate-800 dark:text-slate-100 border border-slate-200 dark:border-slate-700 shadow-sm transition' title='Abrir Formulário'><i class='fa-solid fa-plus'></i></button>
                        <button id='themeToggle' type='button' class='h-11 w-11 rounded-xl bg-white/70 hover:bg-white text-slate-700 dark:bg-slate-800/70 dark:hover:bg-slate-800 dark:text-slate-100 border border-slate-200 dark:border-slate-700 shadow-sm transition' title='Tema escuro'><i id='themeIcon' class='fa-solid fa-moon'></i></button>
                        <a href="config.php" id='openConfig' class='h-11 w-11 flex items-center justify-center rounded-xl bg-white/70 hover:bg-white text-slate-700 dark:bg-slate-800/70 dark:hover:bg-slate-800 dark:text-slate-100 border border-slate-200 dark:border-slate-700 shadow-sm transition' title='Configurar Serviços'><i class='fa-solid fa-gear'></i></a>
                    </div>
                </div>

                <!-- CARDS -->
                <div class='p-4 sm:p-6 md:p-8 space-y-6 flex-grow' style="padding: 1rem !important;">
                
                    <!-- Resumo dos Servidores -->
                    <div>
                        <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wider mb-3">Resumo dos Servidores</h2>
                        <div id="server-summary-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                            <!-- Cards de servidor serão inseridos aqui -->
                        </div>
                    </div>

                    <!-- Linha Divisória -->
                    <hr class="border-slate-200 dark:border-slate-700/80">
                    
                    <!-- ÁDetalhe dos Serviços -->
                    <div>
                        <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wider mb-3">Detalhe dos Serviços</h2>
                        <div id="service-grid-container" class='grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7 gap-4'>
                            <!-- Cards de serviço serão inseridos aqui -->
                        </div>
                    </div>
                </div>

                <!-- CONTROLES DE PAGINAÇÃO -->
                <div id="pagination-controls" class="p-4 bg-white/10 dark:bg-slate-900/20 flex items-center justify-center gap-4" style="padding: 1rem !important;">
                    <button id="prevPageBtn" class="px-4 py-2 rounded-lg bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 disabled:opacity-50 transition"><i class="fa-solid fa-chevron-left"></i></button>
                    <p id="pageIndicator" class="text-sm font-semibold text-slate-700 dark:text-slate-200">Página 1 de 1</p>
                    <button id="nextPageBtn" class="px-4 py-2 rounded-lg bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 disabled:opacity-50 transition"><i class="fa-solid fa-chevron-right"></i></button>
                </div>

                <!-- Footer -->
                <div class='bg-gray-50 dark:bg-slate-900/50 p-4 text-center border-t border-slate-200 dark:border-slate-700'>
                    <p class='text-[10px] text-gray-400 dark:text-slate-400 uppercase tracking-widest'>© Sua Empresa <?= date("Y") ?> - Dashboard Protheus</p>
                </div>
            </div>
        </div>

        <!-- ESTRUTURA DO MODAL -->
        <div id="formModal" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            <div class="glass w-full max-w-md rounded-2xl shadow-2xl overflow-hidden">
                <form id="service-form">
                    <div class="p-5 border-b border-slate-200/80 dark:border-slate-700/80 flex justify-between items-center">
                        <h3 id="modal-title" class="text-lg font-bold text-slate-800 dark:text-slate-100">Configurações Gerais</h3>
                        <button type="button" id="close-modal-btn" class="h-8 w-8 rounded-lg flex items-center justify-center text-xl hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 transition">&times;</button>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label for="itemsPerPage" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Qtd. Cards por Página</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="fa-solid fa-table-cells-large text-slate-400"></i>
                                </div>
                                <input type="number" id="itemsPerPage" name="itemsPerPage" value="14" required class="block w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-800/60 py-2 pl-10 pr-3 text-slate-900 dark:text-slate-100 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            </div>
                        </div>
                        <div>
                            <label for="timeAtuPage" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tempo de Atualização da Página</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="fa-solid fa-table-cells-large text-slate-400"></i>
                                </div>
                                <input type="number" id="timeAtuPage" name="timeAtuPage" value="30" required class="block w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-800/60 py-2 pl-10 pr-3 text-slate-900 dark:text-slate-100 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            </div>
                        </div>
                    </div>
                    <div class="p-4 bg-slate-50 dark:bg-slate-800/30 flex justify-end gap-3 rounded-b-2xl">
                        <button type="button" id="cancel-btn" class="px-4 py-2 text-sm font-medium bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-800 dark:text-slate-100 rounded-lg transition">Cancelar</button>
                        <button type="submit" class="px-5 py-2 text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition shadow-sm">Salvar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- SCRIPT -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // --- CONSTANTES E VARIÁVEIS GLOBAIS ---
                let ITEMS_PER_PAGE       = 14;
                let PAGE_CHANGE_INTERVAL = 30000;
                let allServicesData      = {};
                let currentPage          = 1;
                let totalPages           = 1;
                let pageChangeTimer;

                // --- ELEMENTOS DO DOM ---
                const serverSummaryContainer = document.getElementById('server-summary-container');
                const gridContainer = document.getElementById('service-grid-container');
                const pageIndicator = document.getElementById('pageIndicator');
                const prevPageBtn   = document.getElementById('prevPageBtn');
                const nextPageBtn   = document.getElementById('nextPageBtn');
                const modal         = document.getElementById('formModal');
                const openModalBtn  = document.getElementById('openModalBtn');
                const closeModalBtn = document.getElementById('close-modal-btn');
                const cancelBtn     = document.getElementById('cancel-btn');
                const serviceForm   = document.getElementById('service-form');
                const itemsPerPageInput = document.getElementById('itemsPerPage');
                const timeAtuPageInput  = document.getElementById('timeAtuPage');

                // --- FUNÇÕES DE RENDERIZAÇÃO ---
                // Função para criar os cards de resumo do SERVIDOR
                function createServerCardHTML(ip, data) {
                    // Define a cor da barra de progresso da CPU
                    const cpuColor = data.max_cpu > 85 ? 'bg-red-500' : (data.max_cpu > 60 ? 'bg-amber-500' : 'bg-blue-500');
                    // Define a cor da barra de progresso da Memória
                    const memColor = data.memoria_percent > 85 ? 'bg-red-500' : (data.memoria_percent > 70 ? 'bg-amber-500' : 'bg-green-500');

                    return `
                        <div class="glass p-4 rounded-xl shadow-lg border border-slate-200/80 dark:border-slate-700/80">
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="font-bold text-slate-800 dark:text-slate-100 text-lg"><i class="fa-solid fa-server mr-2"></i>${ip}</h3>
                                <span class="font-semibold text-sm ${data.online_services === data.total_services ? 'text-green-500' : 'text-amber-500'}">
                                    ${data.online_services}/${data.total_services} Online
                                </span>
                            </div>
                            <div class="space-y-3 text-sm">
                                
                                <!-- Bloco da CPU (sem mudanças) -->
                                <div>
                                    <div class="flex justify-between mb-1 text-slate-600 dark:text-slate-300">
                                        <span><i class="fa-solid fa-microchip fa-fw mr-1 text-slate-400"></i>Uso de CPU</span>
                                        <span class="font-bold text-slate-800 dark:text-slate-100">${data.max_cpu}%</span>
                                    </div>
                                    <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2.5">
                                        <div class="${cpuColor} h-2.5 rounded-full" style="width: ${data.max_cpu}%"></div>
                                    </div>
                                </div>

                                <!-- Bloco de Memória com barra de progresso e valor total -->
                                <div>
                                    <div class="flex justify-between mb-1 text-slate-600 dark:text-slate-300">
                                        <span><i class="fa-solid fa-memory fa-fw mr-1 text-slate-400"></i>Consumo de Memória</span>
                                        <span class="font-bold text-slate-800 dark:text-slate-100">${data.memoria_usada_gb} / ${data.memoria_total_gb} GB</span>
                                    </div>
                                    <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2.5">
                                        <div class="${memColor} h-2.5 rounded-full" style="width: ${data.memoria_percent}%"></div>
                                    </div>
                                </div>

                                <!-- Bloco de Usuários -->
                                <div class="flex justify-between text-slate-600 dark:text-slate-300 pt-1">
                                    <span><i class="fa-solid fa-users fa-fw mr-1 text-slate-400"></i>Usuários (Únicos/Total)</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-100">${data.total_usuarios_unique} / ${data.total_usuarios_all}</span>
                                </div>
                            </div>
                        </div>
                    `;
                }

                // Função para criar os cards de detalhe do SERVIÇO
                function createServiceCardHTML(serviceKey, data) {
                    const statusClass = data.status === 'Ativo' ? 'pill-ok' : 'pill-error';
                    return `
                        <div class="bg-white dark:bg-slate-900/60 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden min-h-[170px] flex flex-col">
                            <div class="px-5 py-2 border-b border-slate-100 dark:border-slate-700/60 flex items-center justify-between">
                                <h2 class="font-bold text-sm text-slate-800 dark:text-slate-100 uppercase truncate pr-2" title="${serviceKey}">${serviceKey}</h2>
                                <span class="text-xs ${statusClass} flex-shrink-0">${data.status}</span>
                            </div>
                            <div class="p-5 space-y-3 text-xs text-slate-600 dark:text-slate-300 flex-grow">
                                <p class="flex justify-between">
                                    <span><i class="fa-solid fa-memory fa-fw mr-1 text-slate-400"></i>Memória:</span> 
                                    <strong class="font-semibold text-slate-800 dark:text-slate-100">${data.memoria} MB</strong>
                                </p>
                                <p class="flex justify-between">
                                    <span><i class="fa-solid fa-stopwatch fa-fw mr-1 text-slate-400"></i>Latência:</span> 
                                    <strong class="font-semibold text-slate-800 dark:text-slate-100">${data.latencia} ms</strong>
                                </p>
                                <p class="flex justify-between">
                                    <span><i class="fa-solid fa-microchip fa-fw mr-1 text-slate-400"></i>CPU Serviço:</span> 
                                    <strong class="font-semibold text-slate-800 dark:text-slate-100">${data.cpu_servico}%</strong>
                                </p>
                                <!-- LINHA ADICIONADA DE VOLTA -->
                                <p class="flex justify-between">
                                    <span><i class="fa-solid fa-users fa-fw mr-1 text-slate-400"></i>Usuários:</span> 
                                    <strong class="font-semibold text-slate-800 dark:text-slate-100">${data.usuarios}</strong>
                                </p>
                            </div>
                        </div>
                    `;
                }

                // --- FUNÇÕES DE PAGINAÇÃO (para os serviços) ---
                function renderCurrentPage() {
                    gridContainer.innerHTML = '';
                    const serviceKeys = Object.keys(allServicesData);
                    totalPages = Math.ceil(serviceKeys.length / ITEMS_PER_PAGE) || 1;
                    if (currentPage > totalPages) {
                        currentPage = totalPages;
                    }
                    const startIndex = (currentPage - 1) * ITEMS_PER_PAGE;
                    const endIndex = startIndex + ITEMS_PER_PAGE;
                    const servicesToShow = serviceKeys.slice(startIndex, endIndex);
                    for (const serviceKey of servicesToShow) {
                        gridContainer.innerHTML += createServiceCardHTML(serviceKey, allServicesData[serviceKey]);
                    }
                    updatePaginationControls();
                }

                function updatePaginationControls() {
                    pageIndicator.textContent = `Página ${currentPage} de ${totalPages}`;
                    prevPageBtn.disabled = currentPage === 1;
                    nextPageBtn.disabled = currentPage === totalPages;
                    document.getElementById('pagination-controls').style.display = totalPages > 1 ? 'flex' : 'none';
                }

                function changePage(direction) {
                    currentPage += direction;
                    if (currentPage < 1) currentPage = totalPages;
                    if (currentPage > totalPages) currentPage = 1;
                    renderCurrentPage();
                    resetPageChangeTimer();
                }

                function startPageChangeTimer() {
                    pageChangeTimer = setInterval(() => changePage(1), PAGE_CHANGE_INTERVAL);
                }

                function resetPageChangeTimer() {
                    clearInterval(pageChangeTimer);
                    startPageChangeTimer();
                }

                // --- FUNÇÃO PRINCIPAL DE ATUALIZAÇÃO ---
                async function atualizarDashboard() {
                    try {
                        const response = await fetch('api_dashboard.php');
                        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                        
                        const responseData = await response.json();
                        
                        serverSummaryContainer.innerHTML = '';
                        for (const ip in responseData.servers) {
                            serverSummaryContainer.innerHTML += createServerCardHTML(ip, responseData.servers[ip]);
                        }

                        allServicesData = responseData.services; 
                        
                        document.getElementById('last-update').textContent = `Última atualização: ${new Date().toLocaleTimeString('pt-BR')}`;
                        
                        renderCurrentPage();
                        
                    } catch (error) {
                        console.error('Erro ao buscar dados:', error);
                        document.getElementById('last-update').textContent = 'Erro ao conectar com o servidor.';
                        gridContainer.innerHTML = `<p class="col-span-full text-center text-red-500 dark:text-red-400">Falha ao carregar os serviços.</p>`;
                        serverSummaryContainer.innerHTML = `<p class="col-span-full text-center text-red-500 dark:text-red-400">Falha ao carregar resumo dos servidores.</p>`;
                    }
                }
                
                // --- LÓGICA DO MODAL ---
                itemsPerPageInput.value = ITEMS_PER_PAGE;
                function openModal() { modal.classList.remove('hidden'); }
                function closeModal() { modal.classList.add('hidden'); }

                openModalBtn.addEventListener('click', openModal);
                closeModalBtn.addEventListener('click', closeModal);
                cancelBtn.addEventListener('click', closeModal);
                
                modal.addEventListener('click', (event) => {
                    if (event.target === modal) {
                        closeModal();
                    }
                });
                
                serviceForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const newItemsPerPage = parseInt(itemsPerPageInput.value, 10);
                    const newTimeAtuPage  = parseInt(timeAtuPageInput.value, 10);
                    if (newItemsPerPage > 0) {
                        ITEMS_PER_PAGE = newItemsPerPage;
                        PAGE_CHANGE_INTERVAL = (1000*newTimeAtuPage);
                        renderCurrentPage();
                        closeModal();
                    }
                });

                // --- LÓGICA DO TEMA ---
                (function() {
                    const btn = document.getElementById('themeToggle');
                    const icon = document.getElementById('themeIcon');
                    function applyTheme(mode) {
                        const isDark = mode === 'dark';
                        document.documentElement.classList.toggle('dark', isDark);
                        localStorage.setItem('theme', mode);
                        icon.className = isDark ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
                        btn.title = isDark ? 'Tema claro' : 'Tema escuro';
                    }
                    applyTheme(document.documentElement.classList.contains('dark') ? 'dark' : 'light');
                    btn.addEventListener('click', () => {
                        applyTheme(document.documentElement.classList.contains('dark') ? 'light' : 'dark');
                    });
                })();
                
                // --- INICIALIZAÇÃO ---
                prevPageBtn.addEventListener('click', () => changePage(-1));
                nextPageBtn.addEventListener('click', () => changePage(1));
                atualizarDashboard();
                setInterval(atualizarDashboard, 5000);
                startPageChangeTimer();
            });
        </script>

    </body>
</html>
