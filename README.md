# CRAFTD

Сайт студии ручной кастомизации одежды (PHP + MySQL).

Работает локально в **XAMPP** и готов к выгрузке на хостинг / в **GitHub**.

## Быстрый старт (XAMPP)

1. Положите проект в `C:\xampp\htdocs\castomsite` (или клонируйте туда из GitHub).
2. Запустите Apache и MySQL в XAMPP.
3. Скопируйте настройки БД:
   ```
   config.local.php.example  →  config.local.php
   ```
   Для типичного XAMPP уже подходит:
   - `DB_HOST` = `127.0.0.1`
   - `DB_NAME` = `castomize`
   - `DB_USER` = `root`
   - `DB_PASS` = `` (пусто)
4. В phpMyAdmin импортируйте `database/schema.sql`.
5. Откройте: http://localhost/castomsite/
6. Админка: http://localhost/castomsite/admin/  
   - логин: `admin`  
   - пароль: `admin123`  
   **Смените пароль после первого входа на боевом сервере.**

Файл `config.local.php` не попадает в Git — локальный сайт продолжит работать с вашими настройками.

## Выгрузка на GitHub

```bash
git init
git add .
git commit -m "Initial commit: CRAFTD site"
gh repo create castomsite --private --source=. --remote=origin --push
```

Или создайте пустой репозиторий на GitHub и:

```bash
git remote add origin https://github.com/USERNAME/REPO.git
git branch -M main
git push -u origin main
```

**Не коммитьте** `config.local.php` и загрузки из `uploads/requests/` — они уже в `.gitignore`.

## Хостинг

1. Залейте файлы (без `config.local.php` из своего ПК, если там только XAMPP-настройки).
2. На сервере создайте `config.local.php` с данными MySQL из панели хостинга.
3. Импортируйте `database/schema.sql` в базу хостинга (при необходимости поправьте имя БД в SQL или создайте таблицы в уже существующей БД).
4. Убедитесь, что каталоги `uploads/requests` и `storage/rate_limit` доступны для записи PHP.
5. Смените пароль админа.

## Структура

| Путь | Назначение |
|------|------------|
| `index.php` | Главная |
| `order.php` | Форма заказа |
| `works.php`, `work.php` | Портфолио |
| `admin/` | Панель заявок |
| `database/schema.sql` | Схема БД |
| `config.php` | Общий код (без паролей) |
| `config.local.php` | Секреты БД (локально / на сервере) |

## Безопасность

- Пароли БД только в `config.local.php`
- `.htaccess` закрывает служебные файлы и каталоги
- После публикации **смените** пароль админа и пароль MySQL, если они когда-либо светились в старых файлах
