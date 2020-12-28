## symfony-crud

Requirements:
- Apache server with php, php xml, mysql and mod_rewrite support enabled
- Composer and npm

Install:
- `git clone https://github.com/wgomg/symfony-crud.git`
- Move into project directory.
- `cd api && composer install -W`
- `cd ../client && npm install`
- `npm run build && cp -r build/* ../api/public/`
- Edit `client/package.json` and replace `http://example.com/<example>` with the full base route for your site

Database:
- `sudo mysql -u root -p`
- Default user, password and database are all `colegio`
- Run this commands with defaults or replace with your credentials
- `CREATE USER 'colegio'@'localhost' IDENTIFIED BY 'colegio';`
- `GRANT ALL PRIVILEGES ON colegio.* TO 'colegio'@'localhost';`
- `FLUSH PRIVILEGES;`
- `exit`
- Edit `config/packages/doctrine.yaml` if you are not using defaults

Run migrations:
- `cd api`
- `php bin/console make:migration`
- `php bin/console doctrine:migrations:migrate`

Dump data:
- `sudo mysql -p -u colegio colegio < dump-colegio-202012272325.sql`

