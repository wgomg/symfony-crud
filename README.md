## symfony-crud

### Live demo can be seen here: [https://wg-anra.bid/alumnoscrud/](https://wg-anra.bid/alumnoscrud/)

Requirements:
- Apache2 server with php7+, php xml, mysql and rewrite support enabled
- Composer (v2.0+) and npm (v6.14+)

Install:
- `git clone https://github.com/wgomg/symfony-crud.git`
- Move into project directory.
- `cd api && composer update -W`
- `cd ../client && npm install` (this may take a while)
- Edit `client/package.json` and replace `http://example.com/<example>` with the full base route for your site
- `npm run build && cp -r build/* ../api/public/` (this may also take a while)

Database:
- `sudo mysql -u root -p`
- Default user, password and database are all `colegio`
- Run this commands with defaults or replace with your credentials
- `CREATE DATABASE colegio;`
- `CREATE USER 'colegio'@'localhost' IDENTIFIED BY 'colegio';`
- `GRANT ALL PRIVILEGES ON colegio.* TO 'colegio'@'localhost';`
- `FLUSH PRIVILEGES;`
- `exit`
- Edit `api/config/packages/doctrine.yaml` database parameters if you are not using defaults

Run migrations:
- `cd api`
- `php bin/console make:migration`
- `php bin/console doctrine:migrations:migrate`

Dump data:
- `sudo mysql -p -u colegio colegio < dump-colegio-202012280155.sql`

