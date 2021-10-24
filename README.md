
## About MINI CRM
A MINI CRM is a REST API project where Frontend is built with Vue.js and Backend Laravel

### How to use it?

#### CLIENT / VUE:
```
cd crm-client

npm install
npm run serve

```
Change axois.default.baseURL in main.js file if needed.

#### BACKEND / LARAVEL:

```
cd crm-api

composer install
```
copy '.env.example' content to new file '.env'

```
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link
php artisan serve
php artisan queue:work

```
Don't forget to add your own mailtrap credentials.

## License

The Application is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
