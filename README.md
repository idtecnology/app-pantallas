# AdsUpp

Requerimientos: 


## Instalacion Laravel: 
1. Instalamos laravel y sus dependencia
```
sudo php composer update
```
2. Instalamos las dependencias de vite
```
sudo npm i
```
3. Compilamos los recursos de vite
```
sudo npm run build
```
4. Copiamos el archivo de `.env`
```
sudo cp .env.example .env
```
5. Editamos los parametros necesarios

```env
APP_DEBUG=false
APP_URL=<host_server>

DB_CONNECTION=mysql
DB_HOST=<host_database>
DB_PORT=3306
DB_DATABASE=<database_name>
DB_USERNAME=<database_user>
DB_PASSWORD=<database_password>
```
6. Creamos la key
```
sudo php artisan key:generate
```
7. Migramos la base de datos
```
sudo php artisan migrate --seed
```
8. Crear el enlace simbolico para la subida de imagenes
```
php artisan storage:link
```

        
## CAMBIOS EN EL `php.ini`
post_max_size = 512M
upload_max_filesize = 500M
max_input_time = -1
memory_limit = 128M
max_execution_time = 3600



sudo chown -R www-data.www-data /var/www/laravel
sudo chmod -R 755 /var/www/laravel
sudo chmod -R 777 /var/www/laravel/storage


```
sudo nano /etc/apache2/sites-available/laravel.conf
```

```
<VirtualHost *:80>
        ServerAdmin host
        ServerName admin@host
        DocumentRoot /var/www/html/app-pantallas/public
        <Directory /var/www/html/app-pantallas/public>
                Options +FollowSymLinks
                AllowOverride All
                Require all granted
        </Directory>

        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

```
sudo a2ensite laravel.conf
sudo a2dissite 000-default.conf
sudo a2enmod rewrite
systemctl reload apache2
```




