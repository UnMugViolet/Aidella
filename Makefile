COMPOSER = composer
COMPOSER_PROD = composer2
ARTISAN = php artisan
NPM = npm

# Colors for output
BOLD = \033[1m
UNDERLINE = \033[4m
CLR_RESET = \033[0m
CLR_GREEN = \033[32m
CLR_YELLOW = \033[33m
CLR_RED = \033[31m

help:
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s$(CLR_RESET) %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Installation ——————————————————————————————————————————————————————————————

install: ## Install all dependencies (need php composer npm installed)
	@echo "$(CLR_YELLOW) Installing dependencies...$(CLR_RESET)"
	@sudo -v && sudo apt install php8.3-curl php8.3-mysql php8.3-xml php8.3-fpm php8.3-mbstring mysql-server -y
	@$(COMPOSER) update --with-all-dependencies
	@$(COMPOSER) install
	@$(NPM) install
	@$(ARTISAN) orchid:install
	@$(ARTISAN) key:generate
	@$(ARTISAN) migrate:fresh --seed
	@$(ARTISAN) storage:link
	@$(ARTISAN) config:cache
	@$(ARTISAN) route:cache
	@$(ARTISAN) view:cache

## —— Developpement ——————————————————————————————————————————————————————————————

dev: ## Run the development environment
	@echo "$(CLR_YELLOW) Launching development environment...$(CLR_RESET)"
	@$(ARTISAN) serve --host=0.0.0.0 --port=8000 & $(NPM) run dev

clean: ## Clean the cache and compiled files
	@echo "$(CLR_YELLOW) Cleaning cache and compiled files...$(CLR_RESET)"
	@$(ARTISAN) optimize:clear
	@$(ARTISAN) config:clear
	@$(ARTISAN) route:clear
	@$(ARTISAN) view:clear
	@$(ARTISAN) cache:clear
	@rm -rf ./public/storage/uploads/dog-races/*
	@rm -rf ./public/storage/uploads/posts/*

fclean: ## Run database migrations
	@echo "$(CLR_YELLOW) Running database migrations...$(CLR_RESET)"
	@$(ARTISAN) migrate:fresh --seed
	@$(MAKE) clean

user: ## Create a new admin user
	@echo "$(CLR_YELLOW) Creating a new user...$(CLR_RESET)"
	@$(ARTISAN) orchid:admin

## —— Docker Utils ———————————————————————————————————————————————————————————————————

up: ## Start the production environment
	@echo "$(CLR_YELLOW) Starting production environment...$(CLR_RESET)"
	@docker compose up -d

down: ## Stop the production environment
	@echo "$(CLR_YELLOW) Stopping production environment...$(CLR_RESET)"
	@docker compose down

## —— Docker Production Deployment ————————————————————————————————————————————————————

build-frontend: ## Build frontend assets
	@echo "$(CLR_YELLOW)📦 Installing frontend dependencies...$(CLR_RESET)"
	@$(NPM) install 
	@echo "$(CLR_YELLOW)🏗️  Building frontend assets with environment variables...$(CLR_RESET)"
	@$(NPM) run build

build: build-frontend ## Build Docker container after frontend is ready
	@echo "$(CLR_YELLOW)🐳 Building Docker container...$(CLR_RESET)"
	@docker compose build --no-cache
	@docker compose push

deploy: ## Complete secure Docker deployment
	@echo "🔄 Preparing for prod environment"
	@$(ARTISAN) config:clear
	@$(ARTISAN) cache:clear
	@$(ARTISAN) route:clear
	@$(ARTISAN) view:clear
	@$(ARTISAN) optimize:clear
	@echo "🗄️  Running database migrations..."
	@$(ARTISAN) migrate --force
	@echo "🔗 Creating storage link..."
	@$(ARTISAN) storage:link
	@echo "⚡ Rebuilding optimized caches..."
	@$(ARTISAN) config:cache
	@$(ARTISAN) route:cache
	@$(ARTISAN) view:cache
	@echo "✅ Deployment completed successfully!"

.PHONY: dev install migration prod deploy help clean fclean user build-frontend build deploy

