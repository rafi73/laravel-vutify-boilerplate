# Setup information

Backend
1. composer install
2. cp .env.example .env
3. php artisan key:generate
4. Create database and change the credentials to .env file of the application
5. php artisan migrate
6. php artisan db:seed

Frontend
1. npm install
2. npm run watch / npm run dev

Host for development [WINDOWS]
1. create new virtual domain - vi /c/Windows/System32/drivers/etc/hosts  	
2. create new configuration - [YOUR_APACHE_PATH]\apache2\conf\extra\httpd-vhosts.conf
3. Update webpack.mix.js file and replace "spa.work" with your virtual domain for auto page refresh on page save

API development
1. php artisan make:model XxxYyyZzz -mcf
2. Change migration file  and add the columns.
3. Open controller file and prepare for CRUD.
2. php artisan make:resource XxxYxxZzz (Same as Model name)
3. Change resource accouding to the table structure
4. Add new routes for the CRUD in api.php
