## Backend Laravel News API

The challenge is to build a news aggregator website that pulls articles from various sources and displays them in a clean,
easy-to-read format

<img src="https://res.cloudinary.com/diaylgu7a/image/upload/f_auto,q_auto/rzpzb5fyot8xfxdovv4u"/>

### Installation

#### Install manually

1. Clone this repository : git clone https://github.com/hktom/backend-laravel-news-api.git
2. Execute : cd backend-laravel-news-api
3. Execute : copy .env.example .env **(if you have windows)** or cp .env.example .env **(if you have linux/macos)**
4. Execute: docker-compose up -d --build **(if you have docker installed)**
5. Execute: docker-compose exec app composer install
6. Execute migrations: docker-compose exec app php artisan migrate
7. Access the application via http://localhost:8000

#### Install with Makefile

1. Clone this repository : git clone https://github.com/hktom/backend-laravel-news-api.git
2. Execute : cd backend-laravel-news-api
3. make boot **(install dependencies and run migration)**

#### Other commands
1. make up **(start docker)**
2. make down **(stop docker)**
