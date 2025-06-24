// Registro do Service Worker
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/sw.js')
      .then((registration) => {
        console.log('Service Worker registrado com sucesso:', registration.scope);
      })
      .catch((error) => {
        console.log('Falha no registro do Service Worker:', error);
      });
  });
}

// Verificação de conectividade
window.addEventListener('online', () => {
  console.log('Conexão restaurada');
  showNotification('Conexão restaurada', 'Você está online novamente!', 'success');
});

window.addEventListener('offline', () => {
  console.log('Conexão perdida');
  showNotification('Sem conexão', 'Você está offline. Algumas funcionalidades podem não estar disponíveis.', 'warning');
});

// Função para mostrar notificações
function showNotification(title, message, type = 'info') {
  if ('Notification' in window && Notification.permission === 'granted') {
    new Notification(title, {
      body: message,
      icon: '/icons/icon-192x192.png',
      badge: '/icons/icon-72x72.png'
    });
  }
}

// Solicitar permissão para notificações
function requestNotificationPermission() {
  if ('Notification' in window) {
    Notification.requestPermission().then((permission) => {
      if (permission === 'granted') {
        console.log('Permissão para notificações concedida');
      }
    });
  }
}

// Instalação da PWA
let deferredPrompt;

window.addEventListener('beforeinstallprompt', (e) => {
  console.log('Evento beforeinstallprompt capturado');
  e.preventDefault();
  deferredPrompt = e;

  // Mostrar botão de instalação
  showInstallButton();
});

function showInstallButton() {
  console.log('Tentando mostrar botão de instalação');

  // Remover botão existente se houver
  const existingButton = document.getElementById('install-button');
  if (existingButton) {
    existingButton.remove();
  }

  // Criar novo botão
  const installButton = document.createElement('button');
  installButton.id = 'install-button';
  installButton.innerHTML = '<i class="fas fa-download"></i> Instalar App';
  installButton.className = 'fixed bottom-4 right-4 bg-indigo-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-indigo-700 transition-colors duration-200 z-50';
  installButton.style.display = 'block';

  // Adicionar evento de clique
  installButton.addEventListener('click', installPWA);

  // Adicionar ao body
  document.body.appendChild(installButton);

  console.log('Botão de instalação criado e adicionado');
}

function installPWA() {
  console.log('Tentando instalar PWA');
  if (deferredPrompt) {
    deferredPrompt.prompt();
    deferredPrompt.userChoice.then((choiceResult) => {
      if (choiceResult.outcome === 'accepted') {
        console.log('PWA instalada com sucesso');
        showNotification('App Instalado', 'SisMoney foi instalado com sucesso!', 'success');
      } else {
        console.log('Instalação cancelada pelo usuário');
      }
      deferredPrompt = null;

      // Remover botão após instalação
      const installButton = document.getElementById('install-button');
      if (installButton) {
        installButton.remove();
      }
    });
  } else {
    console.log('DeferredPrompt não disponível');
  }
}

// Sincronização em background
function registerBackgroundSync() {
  if ('serviceWorker' in navigator && 'SyncManager' in window) {
    navigator.serviceWorker.ready.then((registration) => {
      registration.sync.register('background-sync');
    });
  }
}

// Cache de dados offline
function cacheData(key, data) {
  if ('caches' in window) {
    caches.open('sismoney-data').then((cache) => {
      cache.put(key, new Response(JSON.stringify(data)));
    });
  }
}

// Recuperar dados do cache
async function getCachedData(key) {
  if ('caches' in window) {
    const cache = await caches.open('sismoney-data');
    const response = await cache.match(key);
    if (response) {
      return response.json();
    }
  }
  return null;
}

// Inicialização da PWA
document.addEventListener('DOMContentLoaded', () => {
  console.log('PWA inicializando...');

  // Solicitar permissão para notificações
  requestNotificationPermission();

  // Registrar sincronização em background
  registerBackgroundSync();

  // Verificar se já pode instalar
  if (deferredPrompt) {
    showInstallButton();
  }

  // Adicionar botão manual se necessário
  setTimeout(() => {
    const installButton = document.getElementById('install-button');
    if (!installButton && window.matchMedia('(display-mode: browser)').matches) {
      console.log('Criando botão manual de instalação');
      const manualButton = document.createElement('button');
      manualButton.id = 'install-button';
      manualButton.innerHTML = '<i class="fas fa-download"></i> Instalar App';
      manualButton.className = 'fixed bottom-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-green-700 transition-colors duration-200 z-50';
      manualButton.style.display = 'block';
      manualButton.onclick = () => {
        alert('Para instalar o app:\n\n1. Clique no ícone de instalação na barra de endereços\n2. Ou use o menu do navegador > "Adicionar à tela inicial"');
      };
      document.body.appendChild(manualButton);
    }
  }, 2000);
});

// Exportar funções para uso global
window.PWA = {
  showNotification,
  requestNotificationPermission,
  installPWA,
  cacheData,
  getCachedData,
  registerBackgroundSync
};
