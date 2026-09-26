// Авто-прокрутка таблиц вниз
$(function() {
    // Прокрутка таблицы заказов
    // $('.tableFixHead') — выбираем ВСЕ элементы с классом tableFixHead.
    // .each() — перебираем каждый найденный элемент 
    $('.tableFixHead').each(function() {
        // $(this) — текущий элемент
        // .find('table') — ищем внутри него вложенный <table>.
        var $table = $(this).find('table');
        // Проверяем: если таблица найдена (длина > 0) — прокручиваем контейнер таблицы вниз
        if ($table.length) {
            $(this).animate({ scrollTop: $(this)[0].scrollHeight }, 1000);
        }
    });
});

// Вставка в поля ввода из таблицы (клик по строке) – работает для всех таблиц
$(function() {
    $('tr').click(function() {
        // $(this) — строка, по которой кликнули.
        // .find("td:eq(0)") — найти первый <td> (индекс 0).
        // :eq(n) — псевдокласс jQuery для выбора по индексу.
        // .text() — получить текстовое содержимое (в vanilla JS: element.textContent).
        var report_id = $(this).find("td:eq(0)").text(); // № отчёта
        var prof = $(this).find('td:eq(1)').text();
        var name = $(this).find('td:eq(2)').text();
        var tz = $(this).find('td:eq(3)').text();
        var cod = $(this).find("td:eq(5)").text();
        var class_work = $(this).find("td:eq(6)").text();

        // .val(value) — установка значения input
        $('#report_id').val(report_id);
        $('#cod').val(cod);
        $('#class_work').val(class_work);
        $('#prof').val(prof);
        $('#name').val(name);
        $('#tz').val(tz);
    });
});

// Выпадающий список заполняет поля формы и сбрасывает report_id
$(function() {
    // .change() — обработчик события change
    $('#specialist_select').change(function() {
        // Находим выбранный <option>.
        var $option = $(this).find('option:selected');
        // $option.val() — значение value="" выбранной опции. Сравниваем со строкой "", чтобы отловить «пустой» выбор.
        if ($option.val() !== "") {
            $('#cod').val($option.data('cod')); // .data('cod') — читаем HTML-атрибут data-cod="...".
            $('#class_work').val($option.data('class_work'));
            $('#prof').val($option.data('prof'));
            $('#name').val($option.data('name'));
            $('#report_id').val('0'); // сброс ID, чтобы не путать с отчётом
        } else {
            // Если выбран пустой пункт — очищаем все поля
            $('#cod').val('');
            $('#class_work').val('');
            $('#prof').val('');
            $('#name').val('');
            $('#report_id').val('0');
        }
    });
});

// Скрываем 6 и 7 столбцы (код и классификация)
$(function() {
    $('td:nth-child(6),th:nth-child(6)').hide();
    $('td:nth-child(7),th:nth-child(7)').hide();
});

// просмотр изображения по клику на строку таблицы otchet
$(function() {
    // Клик по строкам таблицы otchet (tbody tr)
    $('table.otchet-table tbody tr').click(function(e) {
        var imageUrl = $(this).data('image-url');
        if (imageUrl) {
            $('#modalImage').attr('src', imageUrl);
            $('#imageModal').show();
        }
    });

    // Закрытие модального окна по крестику
    $('#imageModal .close').click(function() {
        $('#imageModal').hide();
    });

    // Закрытие по клику на затемнённый фон (не на изображение)
    $(window).click(function(event) {
        // event.target — реальный DOM-элемент, по которому кликнули.
        // .is('#imageModal') — проверяет, совпадает ли элемент с селектором.
        // Клик по фону ≠ клик по картинке внутри — поэтому модалка закрывается.
        if ($(event.target).is('#imageModal')) {
            $('#imageModal').hide();
        }
    });
});