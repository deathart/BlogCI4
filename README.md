# BlogCI4
I created this blog system with the help of codeigniter v4, thank you for being lenient I'm not a professional developer, this system just helps me to learn more deeply codeigniter but also are style, feel free to help me in this project and tell me what goes and what doesn't go well.

# Installation (An automatic installation will come later on)
First of all, configure the file `.env` to the root of the folder.

## Then execute the following commands : 

- PHP
    - Normal
        - ```shell
            composer install
            php spark migrate:latest
            php spark db:seed DatabaseSeeder
            php spark serve -host 127.0.0.1
            ```
    - Docker
        - ```shell
            docker-compose build && docker-compose up -d
            ./docker/composer install
            ./docker/console spark migrate:latest
            ./docker/console spark db:seed DatabaseSeeder
            ```
- Theme
    - ```shell
        npm install
        npm run theme:build
        ```

Go to [http://127.0.0.1:8080](http://127.0.0.1:8080)

# Admin access
Then go to the link of your blog and added "/admin/" the identifiers are:
```
contact@blog.dev
password
```

# Themes and languages
- Themes : To modify or add a new theme, this can be found in the folder: `public/themes/` (Provisional).
- Language : To modify or add a new language, this can be found in the folder: `application/Languages/` (Provisional).

# Server Requirements
[PHP](http://php.net) version 7.1 or newer is required, with the *intl* extension installed. [Why 7.1](https://gophp71.org/)?

A database is required for most web application programming.
Currently supported databases are:

 - MySQL (5.1+) via the *MySQLi* driver
 - PostgreSQL via the *Postgre* driver
 
More information : [Codeigniter4 User Guide](https://bcit-ci.github.io/CodeIgniter4/)

# Issues
For any problems or suggestions created a new issue (By checking that this issue has not already been created)

# Helps
Don't hesitate to help this project, to improve it to make it grow, even constructive criticism helps.

# TODO (Open to all suggestions)
- [ ] Add theme management
- [ ] Add Users management
- [ ] Add translation in admin
- [ ] Add Tags manager (WIP)
- [ ] Add installer (and automatic update system)
- [ ] Add feed system
- [ ] Update configuration admin
- [ ] Optimise SEO
- [ ] Optimise AJAX
- [ ] Clean rewriting of the css/js
- [ ] Create Docs
- [ ] Create demo
- [ ] Move translate folder template (Use gettext for translation ?)