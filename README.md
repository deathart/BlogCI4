# BlogCI4
I created this blog system with the help of codeigniter v4, thank you for being lenient I'm not a professional developer, this system just helps me to learn more deeply codeigniter but also are style, feel free to help me in this project and tell me what goes and what doesn't go well.

# Installation (An automatic installation will come later on)
First of all, configure the file `.env` to the root of the folder.

Then execute the following commands : 

```shell
composer install
php spark migrate:latest
php spark db:seed DatabaseSeeder
```

# Admin access
Then go to the link of your blog and added "/admin/" the identifiers are:
```
contact@blog.dev
password
```

# Themes and languages
- Themes : To modify or add a new theme, this can be found in the folder: `public/themes/` (Provisional).
- Languuage : To modify or add a new language, this can be found in the folder: `application/Languages/` (Provisional).

# Server Requirements

[PHP](http://php.net) version 7.1 or newer is required, with the *intl* extension installed. [Why 7.1](https://gophp71.org/)?

A database is required for most web application programming.
Currently supported databases are:

 - MySQL (5.1+) via the *MySQLi* driver
 - PostgreSQL via the *Postgre* driver

# TODO (Open to all suggestions)
- [ ] Add theme management
- [ ] Add Users management
- [ ] Add translation in admin
- [ ] Add Tags manager
- [ ] Add installer (and automatic update system)
- [ ] Add feed system
- [ ] Update configuration admin
- [ ] Optimise SEO
- [ ] Optimise AJAX
- [ ] Create Docs
- [ ] Create demo

# Issues
For any problems or suggestions created a new issue (By checking that this issue has not already been created)