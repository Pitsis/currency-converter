# Project Name

Currency Converter

## Installation

1. Clone the repository: `git clone https://github.com/pitsis/currency-converter.git`
2. Navigate to the created directory: `cd ./currency-converter`
3. Run Docker: `docker-compose up --build`
4. Install dependencies: `docker-compose exec app composer install` and `docker-compose exec app npm install`
5. Start the server: `docker-compose exec app symfony server:start`
6. Start encore: `npm run watch`

## Usage

1. Access the application in the browser: `http://localhost:8000`
