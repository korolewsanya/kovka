const tables = window.tablesData;
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
let currentTable = 'all';

function loadItems(table) {
    fetch(`izdelie_ajax.php?table=${table}`)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('itemsContainer');
            if (!data.items || data.items.length === 0) {
                container.innerHTML = '<p>Нет изделий в этой категории.</p>';
                return;
            }
            let html = '';
            for (let item of data.items) {
                let priceDisplay = (item.Prise && item.Prise != 0) ? item.Prise + ' ₽' : 'цена не указана';
                let folder = tableFolders[item.table] || '';
                let rawImage = item.image || '';
                let imageName = rawImage.split('/').pop().split('\\').pop();
                let imagePath = '';
                if (folder && imageName) {
                    imagePath = `../img/${imageName}`;
                } else {
                    imagePath = '../img/placeholder.png';
                }
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

function editItem(table, id) {
    fetch(`izdelie_ajax.php?get_one=1&table=${table}&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('formAction').value = 'edit';
                document.getElementById('editId').value = id;
                document.getElementById('tableSelect').value = table;
                document.getElementById('izdelie').value = data.row.izdelie || '';
                document.getElementById('image').value = data.row.image || '';
                document.getElementById('dlina').value = data.row.Dlina || '';
                document.getElementById('shirina').value = data.row.Shirina || '';
                document.getElementById('visota').value = data.row.Visota || '';
                document.getElementById('prise').value = data.row.Prise || 0;
                document.getElementById('formTitle').innerText = 'Редактирование изделия';
                document.getElementById('deleteBtn').style.display = 'inline-block';
                document.getElementById('formPanel').style.display = 'block';

                const delBtn = document.getElementById('deleteBtn');
                delBtn.onclick = () => {
                    if (confirm('Удалить изделие?')) {
                        const form = document.getElementById('itemForm');
                        let oldAction = form.querySelector('input[name="action"]');
                        if (oldAction) oldAction.remove();
                        const actionInput = document.createElement('input');
                        actionInput.type = 'hidden';
                        actionInput.name = 'action';
                        actionInput.value = 'delete';
                        form.appendChild(actionInput);
                        form.submit();
                    }
                };
            } else {
                alert('Ошибка загрузки данных');
            }
        });
}

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        currentTable = this.dataset.table;
        loadItems(currentTable);
    });
});

document.getElementById('showAddFormBtn').addEventListener('click', () => {
    document.getElementById('formAction').value = 'add';
    document.getElementById('editId').value = 0;
    document.getElementById('itemForm').reset();
    document.getElementById('tableSelect').value = '';
    document.getElementById('formTitle').innerText = 'Добавление нового изделия';
    document.getElementById('deleteBtn').style.display = 'none';
    document.getElementById('formPanel').style.display = 'block';
});

document.getElementById('cancelBtn').addEventListener('click', () => {
    document.getElementById('formPanel').style.display = 'none';
});

loadItems('all');