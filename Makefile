.PHONY: setup

setup:
	@echo "🚀  Начинаю установку проекта..."
	@echo "🧩  Копирую файл окружения..."
	cp .env.example .env
	@echo "📦  Устанавливаю зависимости Composer..."
	composer install
	@echo "⚙️  Оптимизирую автозагрузку..."
	composer dump-autoload -o
	@echo "🔐  Генерирую ключ приложения ..."
	php artisan key:generate
	@echo "🧱  Запускаю миграции..."
	php artisan migrate:fresh
	@echo "🌱  Запускаю seeders..."
	php artisan db:seed
	@echo "🚀  Запускаю локальный сервер..."
	php artisan serve
