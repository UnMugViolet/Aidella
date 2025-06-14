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
	
## —— Production ——————————————————————————————————————————————————————————————

prod: ## Install production dependencies and build assets
	@echo "$(CLR_YELLOW) Running production environment...$(CLR_RESET)"
	@mkdir -p bootstrap/cache
	@mkdir -p storage/app/public/uploads/
	@mkdir -p storage/app/public/uploads/dog-races/
	@chmod -R 755 storage bootstrap/cache
	@cp ~/env_aidella/.env .env
	@$(COMPOSER_PROD) install --no-dev --optimize-autoloader
	@$(ARTISAN) migrate --force

deploy: ## Deploy the Laravel app to a the production server
	@echo "Deploying Laravel app to the production server..."
	@$(MAKE) prod
	@rm -rf public_html
	@ln -s public public_html
	@$(ARTISAN) config:cache
	@$(ARTISAN) route:cache
	@$(ARTISAN) view:cache
	@${ARTISAN} storage:link
	@$(ARTISAN) optimize:clear
	@$(ARTISAN) optimize

.PHONY: dev install migration prod deploy help clean fclean user

