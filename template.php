<?php
/**
 * migration.php
 * @author Revin Roman http://phptime.ru
 *
 * This view is used by yii\console\controllers\MigrateController
 * The following variables are available in this view:
 */
/* @var $className string the new migration class name */

echo "<?php\n";
?>

use yii\db\Schema;
use rmrevin\yii\db\migration;

class  extends migration\PowerMigration
{

    public function instructions()
    {
        return [
            'firstStep',
            'secondStep',
            'lastStep',
        ];
    }

    public function firstStep_up()
    {
        // one instruction is up
        // $this->createTable('table_name', ['id' => Schema::TYPE_PK]);
    }

    public function firstStep_down()
    {
        // one instruction is down
        // $Migration->dropTable('table_name');
    }

    public function secondStep_up()
    {
        // one instruction is up
    }

    public function secondStep_down()
    {
        // one instruction is down
    }

    public function lastStep_up()
    {
        // one instruction is up
    }

    public function lastStep_down()
    {
        // one instruction is down
    }
}