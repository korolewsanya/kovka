const tables = window.tablesData; //объявлена в izdelie.php

// Используется для построения пути к картинке: ../img/<imageName>.
const tableFolders = {
    'mangal': 'Мангалы',
    'lavo4ki': 'Лавочки',
    'kozirek': 'Навесы',
    'zabor': 'Заборы',
    'vorota': 'Ворота',
    'ogradki': 'Оградки',
    'reshetki': 'Решетки',
    'mebel': 'Мебель',
    'melo4i': 'Мелочи'
};
// Текущая выбранная категория. 'all' — показать все изделия.
let currentTable = 'all';

// ЗАГРУЗКА СПИСКА ИЗДЕЛИЙ С СЕРВЕРА (AJAX через fetch)
function loadItems(table) {
    fetch(`izdelie_ajax.php?table=${table}`)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('itemsContainer');
            // Проверка: если сервер вернул пустой список — показываем сообщение.
            if (!data.items || data.items.length === 0) {
                container.innerHTML = '<p>Нет изделий в этой категории.</p>';
                return;
            }
            let html = '';
            for (let item of data.items) {

                // Если цена указана и не равна 0 — показываем "N ₽", иначе — "цена не указана".
                let priceDisplay = (item.Prise && item.Prise != 0) ? item.Prise + ' ₽' : 'цена не указана';

                // Ищем папку по таблице (если нет — пустая строка).
                let folder = tableFolders[item.table] || '';

                // rawImage может содержать путь с / или \ — берём только имя файла.
                // .split('/').pop() — отрезает всё до последнего '/'.
                // .split('\\').pop() — то же для обратного слэша (Windows-пути).
                let rawImage = item.image || '';
                let imageName = rawImage.split('/').pop().split('\\').pop();

                // Формируем путь к картинке. Если имя файла есть — ../img/<name>,
                // иначе — заглушка placeholder.png.
                let imagePath = '';
                if (folder && imageName) {
                    imagePath = `../img/${imageName}`;
                } else {
                    imagePath = '../img/placeholder.png';
                }

                // Сборка HTML-карточки через шаблонные строки.
                // escapeHtml() защищает от XSS при выводе названия изделия.
                // data-table и data-id — кастомные атрибуты, читаются через dataset.
                html += `
                    <div class="card" data-table="${item.table}" data-id="${item.id}">
                        <div class="card-content">
                            <div class="card-info">
                                <div class="card-title">${escapeHtml(item.izdelie)}</div>
                                <div class="card-type">${tables[item.table] || item.table}</div>
                                <div class="card-price">${priceDisplay}</div>
                            </div>
                            <div class="card-image">
                                <img src="${imagePath}" alt="${escapeHtml(item.izdelie)}" onerror="this.src='/МоиПроекты/Ковка_сайт/img/placeholder.png'">
                            </div>
                        </div>
                    </div>
                `;
            }

            // Одной операцией вставляем весь HTML в контейнер.
            container.innerHTML = html;

            document.querySelectorAll('.card').forEach(card => {
                card.addEventListener('click', (e) => {
                    const table = card.dataset.table;
                    const id = card.dataset.id;
                    editItem(table, id);
                });
            });
        })
        .catch(err => console.error(err));
}

// ЗАГРУЗКА ОДНОГО ИЗДЕЛИЯ И ОТКРЫТИЕ ФОРМЫ РЕДАКТИРОВАНИЯ
function editItem(table, id) {
    // Запрос на сервер с параметром get_one=1 — получить одну запись.
    fetch(`izdelie_ajax.php?get_one=1&table=${table}&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Заполняем скрытые поля формы, чтобы на сервере понять, что это редактирование, а не добавление.
                document.getElementById('formAction').value = 'edit';
                document.getElementById('editId').value = id;
                document.getElementById('tableSelect').value = table;

                // .value = ... — прямая установка значения input
                // || '' защищает от null/undefined (если поле пустое).
                document.getElementById('izdelie').value = data.row.izdelie || '';
                document.getElementById('image').value = data.row.image || '';
                document.getElementById('dlina').value = data.row.Dlina || '';
                document.getElementById('shirina').value = data.row.Shirina || '';
                document.getElementById('visota').value = data.row.Visota || '';
                document.getElementById('prise').value = data.row.Prise || 0;

                // Меняем заголовок формы и показываем панель редактирования.
                document.getElementById('formTitle').innerText = 'Редактирование изделия';
                document.getElementById('deleteBtn').style.display = 'inline-block';
                document.getElementById('formPanel').style.display = 'block';

                // Навешиваем обработчик удаления.
                // .onclick = ... перезаписывает предыдущий обработчик (в отличие от addEventListener, который добавляет новый).
                const delBtn = document.getElementById('deleteBtn');
                delBtn.onclick = () => {
                    // confirm() — нативный диалог подтверждения.
                    if (confirm('Удалить изделие?')) {
                        const form = document.getElementById('itemForm');

                        // Удаляем старый скрытый input[name="action"], если он есть, чтобы не отправить два разных значения.
                        let oldAction = form.querySelector('input[name="action"]');
                        if (oldAction) oldAction.remove();

                        // Создаём новый скрытый input action=delete и добавляем его в форму программно.
                        const actionInput = document.createElement('input');
                        actionInput.type = 'hidden';
                        actionInput.name = 'action';
                        actionInput.value = 'delete';
                        form.appendChild(actionInput);

                        // Отправляем форму обычным способом (не через fetch).
                        form.submit();
                    }
                };
            } else {
                alert('Ошибка загрузки данных');
            }
        });
}

// ЭКРАНИРОВАНИЕ HTML — защита от XSS
// Заменяет &, <, > на HTML-сущности, чтобы пользовательский текст (название изделия) не мог вставить тег <script> и т.п.
function escapeHtml(str) {
    if (!str) return '';
    // .replace с регуляркой и функцией-обработчиком.
    // /[&<>]/g — найти все вхождения символов &, <, >.
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

// ОБРАБОТКА КЛИКОВ ПО ВКЛАДКАМ КАТЕГОРИЙ
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        // this — кликнутая кнопка
        this.classList.add('active');
        currentTable = this.dataset.table;
        loadItems(currentTable);
    });
});

// КНОПКА «ДОБАВИТЬ ИЗДЕЛИЕ»
document.getElementById('showAddFormBtn').addEventListener('click', () => {
    document.getElementById('formAction').value = 'add';
    document.getElementById('editId').value = 0;
    // .reset() — нативный метод формы, сбрасывает все поля к исходным значениям.
    document.getElementById('itemForm').reset();
    document.getElementById('tableSelect').value = '';
    document.getElementById('formTitle').innerText = 'Добавление нового изделия';
    document.getElementById('deleteBtn').style.display = 'none';
    document.getElementById('formPanel').style.display = 'block';
});

// КНОПКА «ОТМЕНА» — скрыть форму
document.getElementById('cancelBtn').addEventListener('click', () => {
    document.getElementById('formPanel').style.display = 'none';
});

// СТАРТОВАЯ ЗАГРУЗКА — показать все изделия
loadItems('all');