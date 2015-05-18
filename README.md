Migration Extension for Yii2
============================

Features
--------
* Full rolled back if migration is unsuccessful (for example, in mysql with the rollback is not rolled back the new or dropped tables https://dev.mysql.com/doc/refman/5.0/en/cannot-roll-back.html)

Installation
------------
In `composer.json`:
```
{
    "require": {
        "rmrevin/yii2-power-migration": "~1.1"
    }
}
```

Configuration
-------------
`/config/console.php`
```php
<?
return [
	// ...
	'controllerMap' => [
		// ...
		'migrate' => [
            'class' => yii\console\controllers\MigrateController::class,
            'templateFile' => '@vendor/rmrevin/yii2-power-migration/template.php',
        ],
	],
	// ...
];
```

Usage
-----
```php
<?

use yii\db\Schema;
use rmrevin\yii\db\migration;

class m140317_055355_file extends migration\PowerMigration
{

    public function instructions()
    {
        return [
            'createTableFile',
            'createTableFileLink',
        ];
    }

    public function createTableFile_up()
    {
        $this->createTable('{{%file}}', [
            'id' => Schema::TYPE_PK,
            'mime' => Schema::TYPE_STRING . ' NOT NULL',
            'size' => Schema::TYPE_BIGINT . ' NOT NULL DEFAULT 0',
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'origin_name' => Schema::TYPE_STRING . ' NOT NULL',
            'sha1' => Schema::TYPE_STRING . '(40) NOT NULL',
            'image_bad' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 0',
        ]);
    }

    public function createTableFile_down()
    {
        $this->dropTable('{{%file}}');
    }
    
    public function createTableFileLink_up()
    {
        $this->createTable('{{%file_link}}', [
            'file_id' => Schema::TYPE_PK,
            'url' => Schema::TYPE_STRING . ' NOT NULL',
        ]);
    }

    public function createTableFileLink_down()
    {
        $this->dropTable('{{%file_link}}');
    }
}
```