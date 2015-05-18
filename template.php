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

class <?= $className ?> extends \rmrevin\yii\db\migration\PowerMigration
{

    public function init()
    {
        parent::init();

        $this->addInstruction()
            ->up(function (migration\PowerMigration $Migration, migration\Instruction $Instruction) {
                // migration UP
                // $Migration->createTable('table_name', ['id' => Schema::TYPE_PK]);
            })
            ->down(function (migration\PowerMigration $Migration, migration\Instruction $Instruction) {
                // migration DOWN
                // $Migration->dropTable('table_name');
            });

        $this->addInstruction()
            ->up(function (migration\PowerMigration $Migration, migration\Instruction $Instruction) {
                // migration UP
            })
            ->down(function (migration\PowerMigration $Migration, migration\Instruction $Instruction) {
                // migration DOWN
            });

        // $this->addInstruction()
    }
}