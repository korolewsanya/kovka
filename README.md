# 🚀 ПРОЕКТ-ПОРТФОЛИО: "КОВКА" — Интернет-магазин кованых изделий. Выполнен на PHP без фреймворков

![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?logo=javascript&logoColor=black)
![jQuery](https://img.shields.io/badge/jQuery-0769AD?logo=jquery&logoColor=white)
![AJAX](https://img.shields.io/badge/AJAX-005571?logo=javascript&logoColor=white)
![REST API](https://img.shields.io/badge/REST_API-25A162?logo=fastapi&logoColor=white)
![Java](https://img.shields.io/badge/Java-Android-ED8B00?logo=openjdk&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green)

---

### 📊 Статус проекта:
✅ **Демонстрационный сервер** — проект развернут и доступен онлайн  
✅ **Google Play** — мобильное приложение опубликовано для портфолио  
✅ **Полный функционал** — все модули работают (CRM, API, сайт)  
✅ **Готов к внедрению** — может быть адаптирован для реального бизнеса

---

### 🎯 Что сделано:
- Полноценный интернет-магазин на **чистом PHP** (без фреймворков)
- Админ-панель + CRM для управления бизнесом
- **REST API, написанный с нуля** (без фреймворков) для синхронизации с мобильным приложением
- Полная CRM система (сотрудники, финансы, материалы, заказы)
- Мобильное приложение-админка на Java

---

### 🏗 Архитектура проекта

Проект разделён на **4 независимых модуля**, каждый со своей зоной ответственности:

#### 🌐 Kovka_Website — Витрина интернет-магазина
Отвечает за клиентскую часть: каталог, карточки товаров, оформление заказа.

#### ⚙️ Kovka_CRM — Админ-панель и CRM
Управление бизнесом: заказы, товары, сотрудники, финансы, материалы.

#### 🔌 Kovka_CRM_Api — REST API
Обмен данными между CRM и мобильным приложением. Структурирован по CRUD-операциям.


#### 🖼 img — Медиа-хранилище
Все изображения товаров: ворота, заборы, мангалы, лавочки, мебель и т.д.

---

### 🛠️ Технологии:

| Компонент | Технологии |
|-----------|------------|
| **Backend** | PHP 8.4 (чистый, без фреймворков), REST API  |
| **Frontend** | HTML5, CSS3, JavaScript (ES6+), jQuery, AJAX |
| **База данных** | MySQL 8.0 |
| **Mobile** | Java (Android), Retrofit|

---

### 📸 Галерея скриншотов

#### 🛍 Витрина интернет-магазина
<details>
<summary>👉 Нажмите, чтобы развернуть скриншоты сайта</summary>
<br>

| Главная страница | Каталог товаров |
| :---: | :---: |
| <img src="screenshots/Сайт/главная.png" width="400"> | <img src="screenshots/Сайт/товары.png" width="400"> |
| **Поиск по сайту** | **Оформление заказа** |
| <img src="screenshots/Сайт/поиск.png" width="400"> | <img src="screenshots/Сайт/заказ.png" width="400"> |
| **Успешный заказ** | |
| <img src="screenshots/Сайт/финиш.png" width="400"> | |

</details>

#### ⚙️ Админ-панель и CRM
<details>
<summary>👉 Нажмите, чтобы развернуть скриншоты админки</summary>
<br>

| Инфопанель | Управление заказами |
| :---: | :---: |
| <img src="screenshots/Админка/Инфопанель.png" width="400"> | <img src="screenshots/Админка/Заказы.png" width="400"> |
| **Редактирование заказа** | **Управление товарами** |
| <img src="screenshots/Админка/ЗаказыРедактирование.png" width="400"> | <img src="screenshots/Админка/Товары.png" width="400"> |
| **Редактирование товара** | **Материалы** |
| <img src="screenshots/Админка/ТоварыРедактирование.png" width="400"> | <img src="screenshots/Админка/Материалы.png" width="400"> |
| **Отчеты** | |
| <img src="screenshots/Админка/Отчеты.png" width="400"> | |

</details>

#### 📱 Мобильное приложение (Android/Java)
<details>
<summary>👉 Нажмите, чтобы развернуть скриншоты приложения</summary>
<br>

| Главная | Заказы | Материалы |
| :---: | :---: | :---: |
| <img src="screenshots/Приложение/Главная.png" width="200"> | <img src="screenshots/Приложение/Заказы.png" width="200"> | <img src="screenshots/Приложение/Материалы.png" width="200"> |
| **Редактирование заказа** | **Создание товара** | **Редактирование материала** |
| <img src="screenshots/Приложение/ЗаказыРедактирование.png" width="200"> | <img src="screenshots/Приложение/ТоварыСоздание.png" width="200"> | <img src="screenshots/Приложение/МатериалыРедактирование.png" width="200"> |
| **Отчеты** | **Редактирование отчета** | **Финансы** |
| <img src="screenshots/Приложение/Отчеты.png" width="200"> | <img src="screenshots/Приложение/ОтчетыРедактирование.png" width="200"> | <img src="screenshots/Приложение/Финансы.png" width="200"> |

</details>

---

### 🔗 Демо и ссылки

- 🌐 **Сайт (витрина):** [ваш-домен.ru](https://ваш-домен.ru)
- 🌐 **Админка/CRM:** [ваш-домен.ru/admin](https://ваш-домен.ru/admin)
- 📱 **Google Play:** [ссылка на приложение](https://play.google.com/store/apps/details?id=ваш.package)
- 📂 **GitHub:** [korolewsanya/kovka-php](https://github.com/korolewsanya/kovka-php)

#### 🔑 Единый доступ для тестирования

> Используется для входа в **Админку/CRM** и **мобильное приложение**

| Роль | Логин | Пароль |
| :--- | :--- | :--- |
| Администратор | `admin@kovka.com` | `12345678` |
| Рабочий | `employee@kovka.com` | `12345678` |

---

### 📌 О проекте

> Проект разработан как портфолио-решение для демонстрации навыков разработки на **PHP без фреймворков**. Показывает понимание архитектуры, разделения ответственности и работы с REST API. Может быть адаптирован под реальные задачи бизнеса.

---


