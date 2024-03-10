## About RAMPASS

RAMPASS is a simple web application to share secret content to others via a url link.

## Install for development

1. Clone the repo
```sh
git clone https://github.com/RamJett/rampass.git
```

2. Change into the repo directory
```sh
cd rampass
```

3. Copy and edit .env
```sh
cp .env.example .env
vi .env
```

4. Install composer packages
```sh
composer install
```

5. Install npm packages
```sh
npm install
```

6. Generate project key
```sh
php artisan key:generate
```

7. Generate salt
```sh
php artisan salt:generate
```

8. Create storage link
```sh
php artisan storage:link
```

9. [in a new window] compile resource assets
```sh
npm run dev
```

10. create the database
```sh
php artisan migrate
```
11. Launch development webserver 
```sh
php artisan serve
``` 

12. Visit project in your browser
```
http://127.0.0.1:8000
```

## License

RAMPASS is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
