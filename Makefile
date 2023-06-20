boot: ## Install and start the project
	cp .env.example .env
	docker-compose up -d --build 
	docker-compose exec app composer install
	docker-compose exec app php artisan migrate

up: ## Start the project
	docker-compose up -d

down: ## Stop the project
	docker-compose down