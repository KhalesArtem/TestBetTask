@extends('layouts.app')

@section('title', 'Страница A')

@section('content')
    <h1>Страница A</h1>
    
    <div class="button-group">
        <button id="renewLink" class="secondary">Сгенерировать новый линк</button>
        <button id="deactivateLink" class="danger">Деактивировать данный линк</button>
        <button id="playGame">I'm feeling lucky</button>
        <button id="showHistory" class="secondary">History</button>
    </div>
    
    <div id="gameResult" class="result-box" style="display: none;">
        <h3>Результат игры</h3>
        <div id="gameResultContent"></div>
    </div>
    
    <div id="historyResult" class="result-box" style="display: none;">
        <h3>История игр</h3>
        <div id="historyResultContent"></div>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const linkToken = '{{ $link->token }}';
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Helper функция для API запросов
    function apiRequest(url, options = {}) {
        const defaultOptions = {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        };
        
        // Добавляем CSRF токен для POST запросов
        if (options.method && options.method !== 'GET') {
            defaultOptions.headers['X-CSRF-TOKEN'] = csrfToken;
        }
        
        // Объединяем опции
        const finalOptions = {
            ...defaultOptions,
            ...options,
            headers: {
                ...defaultOptions.headers,
                ...(options.headers || {})
            }
        };
        
        return fetch(url, finalOptions)
            .then(response => response.json())
            .catch(error => {
                console.error('Error:', error);
                alert('Произошла ошибка');
                throw error;
            });
    }
    
    // Renew link
    document.getElementById('renewLink').addEventListener('click', function() {
        apiRequest(`/api/links/${linkToken}/renew`, { method: 'POST' })
            .then(data => {
                if (data.new_url) {
                    alert(`Новая ссылка создана: ${data.new_url}`);
                    window.location.href = data.new_url;
                } else {
                    alert('Ошибка при создании новой ссылки');
                }
            });
    });
    
    // Deactivate link
    document.getElementById('deactivateLink').addEventListener('click', function() {
        if (confirm('Вы уверены, что хотите деактивировать эту ссылку?')) {
            apiRequest(`/api/links/${linkToken}/deactivate`, { method: 'POST' })
                .then(data => {
                    alert(data.message || 'Ссылка деактивирована');
                    window.location.href = '/';
                });
        }
    });
    
    // Play game
    document.getElementById('playGame').addEventListener('click', function() {
        apiRequest(`/api/game/${linkToken}/play`, { method: 'POST' })
            .then(data => {
                const resultDiv = document.getElementById('gameResult');
                const contentDiv = document.getElementById('gameResultContent');
                
                if (data.error) {
                    contentDiv.innerHTML = `<p style="color: red;">${data.error}</p>`;
                } else {
                    const color = data.result === 'Win' ? 'green' : 'red';
                    contentDiv.innerHTML = `
                        <p>Случайное число: <strong>${data.random_number}</strong></p>
                        <p>Результат: <strong style="color: ${color};">${data.result}</strong></p>
                        <p>Сумма выигрыша: <strong>${data.win_amount}</strong></p>
                    `;
                }
                
                resultDiv.style.display = 'block';
                document.getElementById('historyResult').style.display = 'none';
            });
    });
    
    // Show history
    document.getElementById('showHistory').addEventListener('click', function() {
        apiRequest(`/api/game/${linkToken}/history`)
            .then(data => {
                const historyDiv = document.getElementById('historyResult');
                const contentDiv = document.getElementById('historyResultContent');
                
                if (data.length === 0) {
                    contentDiv.innerHTML = '<p>История пуста</p>';
                } else {
                    let html = '';
                    data.forEach(item => {
                        const color = item.result === 'Win' ? 'green' : 'red';
                        const date = new Date(item.created_at).toLocaleString('ru-RU');
                        html += `
                            <div class="history-item">
                                <p>Дата: ${date}</p>
                                <p>Число: ${item.random_number} | 
                                   Результат: <span style="color: ${color};">${item.result}</span> | 
                                   Выигрыш: ${item.win_amount}</p>
                            </div>
                        `;
                    });
                    contentDiv.innerHTML = html;
                }
                
                historyDiv.style.display = 'block';
                document.getElementById('gameResult').style.display = 'none';
            });
    });
});
</script>
@endsection