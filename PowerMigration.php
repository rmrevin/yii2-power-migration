<?php
/**
 * PowerMigration.php
 * @author Revin Roman http://phptime.ru
 */

namespace rmrevin\yii\db\migration;

use yii\helpers\Console;

/**
 * Class PowerMigration
 * @package rmrevin\yii\db\migration
 */
abstract class PowerMigration extends \yii\db\Migration
{

    /** @var array */
    private $executed = [];

    /**
     * @return array
     */
    abstract public function instructions();

    /**
     * @param string $executed_in if method call in up command, must be `up`, if method call in down command, must be `down`
     * @throws \yii\base\Exception
     */
    private function rollback($executed_in)
    {
        if (empty($executed_in)) {
            throw new \yii\base\Exception('You should specified `$execute` param.');
        }

        if (!in_array($executed_in, ['up', 'down'], true)) {
            throw new \yii\base\Exception('Param `$execute` must be `up` or `down`.');
        }

        echo Console::ansiFormat("  > rollback executed instructions\n", [Console::FG_RED, Console::BOLD]);

        $executed = $this->executed;
        if (!empty($executed)) {
            foreach ($executed as $instruction) {
                switch ($executed_in) {
                    case 'up':
                        call_user_func($instruction . '_down');
                        break;
                    case 'down':
                        call_user_func($instruction . '_up');
                        break;
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function up()
    {
        $instructions = $this->instructions();
        if (!empty($instructions)) {
            foreach ($instructions as $instruction) {
                try {
                    if (call_user_func($instruction . '_up') === false) {
                        $this->rollback('up');

                        return false;
                    } else {
                        $this->executed[] = $instruction;
                    }
                } catch (\Exception $e) {
                    $this->errorTrace($e);

                    $this->rollback('up');

                    return false;
                }
            }
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $instructions = array_reverse($this->instructions());
        if (!empty($instructions)) {
            foreach ($instructions as $key => $instruction) {
                try {
                    if (call_user_func($instruction . '_down') === false) {
                        $this->rollback('down');

                        return false;
                    } else {
                        $this->executed[] = $instruction;
                    }
                } catch (\Exception $e) {
                    $this->errorTrace($e);

                    $this->rollback('down');

                    return false;
                }
            }
        }

        return null;
    }

    /**
     * @param \Exception $e
     */
    public function errorTrace(\Exception $e)
    {
        echo Console::ansiFormat("Exception: " . $e->getMessage() . ' (' . $e->getFile() . ':' . $e->getLine() . ")\n", [Console::FG_RED]);
    }
}