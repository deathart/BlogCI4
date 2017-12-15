# BlogCI4
I created this blog system with the help of codeigniter v4, thank you for being lenient I'm not a professional developer, this system just helps me to learn more deeply codeigniter but also are style, feel free to help me in this project and tell me what goes and what doesn't go well.

# Installation
Configure the `.env` file and `application/config/*.php`
```
composer install
php spark migrate:latest
php spark db:seed
```

# Admin access
Then go to the link of your blog and added "/admin/" the identifiers are:
```
contact@blog.dev
password
```

# Server Requirements

[php](http://php.net) version 7.1 or newer is required, with the *intl* extension installed.

A database is required for most web application programming.
Currently supported databases are:

  - MySQL (5.1+) via the *MySQLi* driver
  - PostgreSQL via the *Postgre* driver

Not all of the drivers have been converted/rewritten for CodeIgniter4.
The list below shows the outstanding ones.

  - MySQL (5.1+) via the *pdo* driver
  - Oracle via the *oci8* and *pdo* drivers
  - PostgreSQL via the *pdo* driver
  - MS SQL via the *mssql*, *sqlsrv* (version 2005 and above only) and *pdo* drivers
  - SQLite via the *sqlite* (version 2), *sqlite3* (version 3) and *pdo* drivers
  - CUBRID via the *cubrid* and *pdo* drivers
  - Interbase/Firebird via the *ibase* and *pdo* drivers
  - ODBC via the *odbc* and *pdo* drivers (you should know that ODBC is actually an abstraction layer)
  
# TODO
- [ ] Create new theme
- [ ] Add Users management
- [ ] Add Medias management
- [ ] Add translation in admin
- [ ] Update configuration admin
- [ ] Add feed system
- [ ] Add new BBCODE