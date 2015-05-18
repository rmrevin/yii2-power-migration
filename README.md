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
        "rmrevin/yii2-power-migration": "~1.0"
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

    public function init()
    {
        parent::init();

        $this->addInstruction()
            ->up(function (migration\PowerMigration $Migration, migration\Instruction $Instruction) {
                $Migration->createTable('{{%file}}', [
                    'id' => Schema::TYPE_PK,
                    'mime' => Schema::TYPE_STRING,
                    'size' => Schema::TYPE_BIGINT . ' DEFAULT 0',
                    'name' => Schema::TYPE_STRING,
                    'origin_name' => Schema::TYPE_STRING,
                    'sha1' => Schema::TYPE_STRING . '(40) NOT NULL',
                ]);
            })
            ->down(function (PowerMigration $Migration, Instruction $Instruction) {
                $Migration->dropTable('{{%file}}');
            });
            
        $this->addInstruction()
            ->up(function (migration\PowerMigration $Migration, migration\Instruction $Instruction) {
                $Migration->createTable('{{%file_link}}', [
                    'file_id' => Schema::INTEGER,
                    'url' => Schema::STRING,
                    'FOREIGN KEY (file_id) REFERENCES {{%file}} (id) ON DELETE CASCADE ON UPDATE CASCADE',
                ]);
            })
            ->down(function (PowerMigration $Migration, Instruction $Instruction) {
                $Migration->dropTable('{{%file}}');
            });
    }
}
```