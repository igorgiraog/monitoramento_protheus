<!DOCTYPE html>
<html lang='pt-br'>
    <head>
        <title>Configuração de Serviços</title>
        <meta charset='utf-8' />
        <meta name='viewport' content='width=device-width, initial-scale=1.0' />
        <link rel='shortcut icon' href='../../assets/img/login/favicon.ico' type='image/x-icon'>
        <script src='https://cdn.tailwindcss.com'></script>
        <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css' rel='stylesheet' />
        <link href='https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap' rel='stylesheet' />
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            .tbl {
                width: 100%;
                border-collapse: collapse;
            }
            .tbl th {
                text-transform: uppercase;
                letter-spacing: .08em;
                font-size: 11px;
                color: rgb(51 65 85);
                background: rgb(248 250 252);
                border: 1px solid rgb(226 232 240);
                padding: 10px 12px;
                white-space: nowrap;
            }
            .tbl td {
                border: 1px solid rgb(226 232 240);
                padding: 10px 12px;
                font-size: 13px;
                color: rgb(51 65 85);
                background: white;
                vertical-align: middle;
            }
            html.dark .tbl th {
                background: rgba(15, 23, 42, 0.7);
                color: rgb(226 232 240);
                border-color: rgba(148, 163, 184, 0.25);
            }
            html.dark .tbl td {
                background: rgba(2, 6, 23, 0.15);
                color: rgb(226 232 240);
                border-color: rgba(148, 163, 184, 0.25);
            }
            body.swal2-toast-shown .swal2-container {
                z-index: 10000;
            }
        </style>
    </head>

    <body class='bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 min-h-screen p-4 sm:p-6'>
        <div class='mx-auto max-w-5xl'>
            <div class='glass rounded-3xl shadow-2xl overflow-hidden'>

                <div class='bg-white/50 dark:bg-slate-900/40 p-4 sm:p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between gap-4'>
                    <a href="index.php" title="Voltar ao Dashboard">
                        <img src='../../assets/img/logo/logo.png' class='h-10 sm:h-12' alt='Logo' />
                    </a>
                    <div class='text-center'>
                        <h1 class='text-lg sm:text-xl font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wider'>Gerenciador de Serviços</h1>
                        <p class='text-slate-500 dark:text-slate-300 text-sm hidden sm:block'>Adicione, edite ou remova serviços.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button id="add-service-btn" class="inline-flex items-center gap-2 rounded-lg px-3 py-2 sm:px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold shadow-sm transition">
                            <i class="fa-solid fa-plus"></i>
                            <span class="hidden sm:inline">Adicionar</span>
                        </button>
                        <button id='themeToggle' type='button' class='h-10 w-10 flex-shrink-0 rounded-xl bg-white/70 hover:bg-white text-slate-700 dark:bg-slate-800/70 dark:hover:bg-slate-800 dark:text-slate-100 border border-slate-200 dark:border-slate-700 shadow-sm transition' title='Alternar Tema'>
                            <i id='themeIcon' class='fa-solid fa-moon'></i>
                        </button>
                        <a href="./" id='openConfig' class='h-11 w-11 flex items-center justify-center rounded-xl bg-white/70 hover:bg-white text-slate-700 dark:bg-slate-800/70 dark:hover:bg-slate-800 dark:text-slate-100 border border-slate-200 dark:border-slate-700 shadow-sm transition' title='Voltar Página'><i class='fa-solid fa-angles-left'></i></a>
                    </div>
                </div>

                <div class='p-4 sm:p-6 md:p-8'>
                    <div class='bg-white dark:bg-slate-900/60 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden'>
                        <div class='p-5 overflow-x-auto'>
                            <table class="tbl">
                                <thead>
                                    <tr>
                                        <th class="text-left">Nome do Serviço</th>
                                        <th class="text-left">IP AppServer</th>
                                        <th class="text-center">Porta</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="services-table-body">
                                    <!-- As linhas serão inseridas aqui pelo JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class='bg-gray-50 dark:bg-slate-900/50 p-4 text-center border-t border-slate-200 dark:border-slate-700'>
                    <p class='text-[10px] text-gray-400 dark:text-slate-400 uppercase tracking-widest'>© Sua Empresa <?= date("Y") ?></p>
                </div>
            </div>
        </div>

        <!-- Modal para Adicionar/Editar Serviço -->
        <div id="service-modal" class="hidden fixed inset-0 bg-slate-900 bg-opacity-70 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            <div class="glass w-full max-w-md rounded-2xl shadow-2xl">
                <form id="service-form">
                    <div class="p-5 border-b border-slate-200 dark:border-slate-700">
                        <h3 id="modal-title" class="text-lg font-bold text-slate-800 dark:text-slate-100">Adicionar Serviço</h3>
                    </div>
                    <div class="p-5 space-y-4">
                        <input type="hidden" id="original_nome" name="original_nome">
                        <div>
                            <label for="nome" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nome do Serviço</label>
                            <input type="text" id="nome" name="nome" value="SVC_RESTS_0" required class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-900/60 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="ip" class="block text-sm font-medium text-slate-700 dark:text-slate-300">IP do AppServer</label>
                            <input type="text" id="ip" name="ip" value="192.168.13.92" required class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-900/60 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="porta" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Porta</label>
                            <input type="number" id="porta" name="porta" value="200" required class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-900/60 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                    <div class="p-4 bg-slate-50 dark:bg-slate-900/50 flex justify-end gap-3 rounded-b-2xl">
                        <button type="button" id="cancel-btn" class="px-4 py-2 text-sm font-medium bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 rounded-lg transition">Cancelar</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">Salvar</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const tableBody = document.getElementById('services-table-body');
                const serviceModal = document.getElementById('service-modal');
                const serviceForm = document.getElementById('service-form');
                const modalTitle = document.getElementById('modal-title');

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });

                async function loadServices() {
                    const response = await fetch('api_config.php?action=read');
                    const result = await response.json();
                    tableBody.innerHTML = '';
                    if (result.success && Object.keys(result.data).length > 0) {
                        for (const name in result.data) {
                            const service = result.data[name];
                            const row = `
                                    <tr data-name="${name}" data-ip="${service.ip}" data-port="${service.porta}">
                                        <td class="font-semibold text-slate-800 dark:text-slate-100">${name}</td>
                                        <td class="text-left">${service.ip}</td>
                                        <td class="text-center">${service.porta}</td>
                                        <td class="text-center space-x-2">
                                            <button class="edit-btn h-9 w-9 rounded-lg text-blue-600 hover:bg-blue-100 dark:hover:bg-slate-700 transition" title="Editar"><i class="fa-solid fa-pencil"></i></button>
                                            <button class="delete-btn h-9 w-9 rounded-lg text-red-600 hover:bg-red-100 dark:hover:bg-slate-700 transition" title="Excluir"><i class="fa-solid fa-trash-can"></i></button>
                                        </td>
                                    </tr>
                                `;
                            tableBody.innerHTML += row;
                        }
                    } else {
                        tableBody.innerHTML = '<tr><td colspan="4" class="text-center py-8 text-slate-500">Nenhum serviço cadastrado.</td></tr>';
                    }
                }

                function showModal(modal) {
                    modal.classList.remove('hidden');
                }

                function hideModal(modal) {
                    modal.classList.add('hidden');
                }

                document.getElementById('add-service-btn').addEventListener('click', () => {
                    serviceForm.reset();
                    modalTitle.textContent = 'Adicionar Serviço';
                    document.getElementById('original_nome').value = '';
                    showModal(serviceModal);
                });

                document.getElementById('cancel-btn').addEventListener('click', () => hideModal(serviceModal));

                serviceForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const data = Object.fromEntries(formData.entries());

                    const response = await fetch('api_config.php?action=save', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();
                    if (result.success) {
                        hideModal(serviceModal);
                        loadServices();
                        Toast.fire({
                            icon: 'success',
                            title: result.message
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro ao Salvar',
                            text: result.message,
                            confirmButtonColor: '#3b82f6'
                        });
                    }
                });

                tableBody.addEventListener('click', function(e) {
                    const row = e.target.closest('tr');
                    if (!row) return;

                    const serviceName = row.dataset.name;
                    const serviceIp = row.dataset.ip;
                    const servicePort = row.dataset.port;

                    if (e.target.closest('.edit-btn')) {
                        modalTitle.textContent = 'Editar Serviço';
                        document.getElementById('original_nome').value = serviceName;
                        document.getElementById('nome').value = serviceName;
                        document.getElementById('ip').value = serviceIp;
                        document.getElementById('porta').value = servicePort;
                        showModal(serviceModal);
                    }

                    if (e.target.closest('.delete-btn')) {
                        Swal.fire({
                            title: 'Confirmar Exclusão',
                            html: `Tem certeza que deseja excluir o serviço <strong>"${serviceName}"</strong>? <br>Esta ação não pode ser desfeita.`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#dc2626',
                            cancelButtonColor: '#64748b',
                            confirmButtonText: 'Sim, excluir!',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                deleteService(serviceName);
                            }
                        });
                    }
                });

                async function deleteService(serviceName) {
                    const response = await fetch('api_config.php?action=delete', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            nome: serviceName
                        })
                    });

                    const result = await response.json();
                    if (result.success) {
                        loadServices();
                        Toast.fire({
                            icon: 'success',
                            title: result.message
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro ao Excluir',
                            text: result.message,
                            confirmButtonColor: '#3b82f6'
                        });
                    }
                }

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

                loadServices();
            });
        </script>
    </body>
</html>