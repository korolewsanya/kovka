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

#### 🛍 Витрина интернет-магазина (Kovka_Website)
<details>
<summary>👉 Нажмите, чтобы развернуть скриншоты сайта</summary>
<br>

| Главная страница | Каталог изделий |
| :---: | :---: |
| <img src="screenshots/Kovka_Website/glav.png" width="400"> | <img src="screenshots/Kovka_Website/izdelie.png" width="400"> |
| **Оформление заказа** | **Успешный заказ** |
| <img src="screenshots/Kovka_Website/zakaz.png" width="400"> | <img src="screenshots/Kovka_Website/finish.png" width="400"> |

</details>

#### ⚙️ Админ-панель и CRM (Kovka_CRM)
<details>
<summary>👉 Нажмите, чтобы развернуть скриншоты админки</summary>
<br>

| Вход в систему | Главная |
| :---: | :---: |
| <img src="screenshots/Kovka_CRM/vhod.png" width="400"> | <img src="screenshots/Kovka_CRM/admin.png" width="400"> |
| **Детали заказа** | **Управление изделиями** |
| <img src="screenshots/Kovka_CRM/zakazDetail.png" width="400"> | <img src="screenshots/Kovka_CRM/izdelie.png" width="400"> |
| **Материалы** | **Финансы** |
| <img src="screenshots/Kovka_CRM/mater.png" width="400"> | <img src="screenshots/Kovka_CRM/fin.png" width="400"> |
| **Список изображений** | |
| <img src="screenshots/Kovka_CRM/img_list.png" width="400"> | |

</details>

#### 📱 Мобильное приложение (Android/Java)
<details>
<summary>👉 Нажмите, чтобы развернуть скриншоты приложения</summary>
<br>

| Главная | Заказы | Изделия |
| :---: | :---: | :---: |
| <img src="screenshots/App/Главная.png" width="200"> | <img src="screenshots/App/Заказы.png" width="200"> | <img src="screenshots/App/Изделия.png" width="200"> |
| **Редактирование заказа** | **Отчеты** | **Рабочий процесс** |
| <img src="screenshots/App/ЗаказыРедактирование.png" width="200"> | <img src="screenshots/App/Отчеты.png" width="200"> | <img src="screenshots/App/Рабочий процесс.png" width="200"> |
| **Сотрудники** | **Финансы** | |
| <img src="screenshots/App/Сотрудники.png" width="200"> | <img src="screenshots/App/Финансы.png" width="200"> | |

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


