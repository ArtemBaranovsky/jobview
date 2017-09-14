1) composer global require "fxp/composer-asset-plugin:~1.1.1"


3) composer install


4) php init


5) Выполнить следующие команды:

    chmod 666 common/modules/rbac/data/assignments.php
    chmod 666 common/modules/rbac/data/items.php
    chmod 666 common/modules/rbac/data/rules.php


6) Создать БД с кодировкой utf8mb4_general_ci


7) Прописать данные от БД в файле /common/config/main-local.php


8) php yii migrate


9) Выполнить запросы к БД:

    create table `app_log`
    (
       `id`          bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
       `level`       integer,
       `category`    varchar(255),
       `log_time`    double,
       `prefix`      text,
       `message`     text,
       key `idx_log_level` (`level`),
       key `idx_log_category` (`category`)
    ) engine InnoDB;

    create table `app_session`
    (
        `id`  varchar(64) not null,
        `user_id` integer,
        `ip`  varchar(50) null,
        `ua`  varchar(150) null,
        `last_action` integer,
        `expire` integer,
        `data`   LONGBLOB,
        primary key (`id`),
        key `user` (`user_id`)
    ) engine InnoDB;
