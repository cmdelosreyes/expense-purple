Expense Manager

Default Passwords of Users that are added by the Administrator is default to '1234' without the single quotes.
- Based on the instruction given, there's no updating of password in the Update Function of the User Module.
- Addition to that, on the Add Function of the User Module. Same goes with the password. There are no mentioned instruction about it.

Default Username and Password for Administrator:
Username: admin@test.com
Password: admin

Modules named Roles and Expense Categories has a relationship with the User and Expenses respectively, there is no instruction about restricting to delete the models under it. Therefore, I assumed to create a foreign key constraint for cascading the parent model so no problems will be encountered for it.

Front End CSS: AdminLTE 3
Database: mySQL Community Edition v8.0.19
PHP version: v7.4.2
Laravel Version: v7.10.3
Module Bundler: Laravel Mix
