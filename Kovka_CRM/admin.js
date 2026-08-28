// Авто-прокрутка таблиц вниз
$(function() {
    // Прокрутка таблицы заказов
    $('.tableFixHead').each(function() {
        var $table = $(this).find('table');
        if ($table.length) {
            // Прокручиваем контейнер таблицы вниз
            $(this).animate({ scrollTop: $(this)[0].scrollHeight }, 1000);
        }
    });
});

// Вставка в поля ввода из таблицы (клик по строке) – работает для всех таблиц
$(function() {
    $('tr').click(function() {
        var report_id = $(this).find("td:eq(0)").text(); // № отчёта
        var prof = $(this).find('td:eq(1)').text();
        var name = $(this).find('td:eq(2)').text();
        var tz = $(this).find('td:eq(3)').text();
        var cod = $(this).find("td:eq(5)").text();
        var class_work = $(this).find("td:eq(6)").text();

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
    $('#specialist_select').change(function() {
        var $option = $(this).find('option:selected');
        if ($option.val() !== "") {
            $('#cod').val($option.data('cod'));
            $('#class_work').val($option.data('class_work'));
            $('#prof').val($option.data('prof'));
            $('#name').val($option.data('name'));
            $('#report_id').val('0'); // сброс ID, чтобы не путать с отчётом
        } else {
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
        if ($(event.target).is('#imageModal')) {
            $('#imageModal').hide();
        }
    });
});