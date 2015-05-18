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

        $executed = $this->executed;
        if (!empty($executed)) {
            echo Console::ansiFormat("  > rollback executed instructions\n", [Console::FG_RED, Console::BOLD]);

            foreach ($executed as $instruction) {
                switch ($executed_in) {
                    case 'up':
                        call_user_func([$this, $instruction . '_down']);
                        break;
                    case 'down':
                        call_user_func([$this, $instruction . '_up']);
                        break;
                }
            }
        } else {
            echo Console::ansiFormat("  > no executed instructions, skip rollback\n");
        }
    }

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->executed = [];

        $instructions = $this->instructions();
        if (!empty($instructions)) {
            foreach ($instructions as $instruction) {
                try {
                    $method = $instruction . '_up';

                    if (!method_exists($this, $method)) {
                        throw new \yii\base\UnknownMethodException(sprintf('Method `%s` not exists', get_called_class() . '::' . $method));
                    }

                    if (call_user_func([$this, $method]) === false) {
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
        $this->executed = [];

        $instructions = array_reverse($this->instructions());
        if (!empty($instructions)) {
            foreach ($instructions as $key => $instruction) {
                try {
                    $method = $instruction . '_down';

                    if (!method_exists($this, $method)) {
                        throw new \yii\base\UnknownMethodException(sprintf('Method `%s` not exists', get_called_class() . '::' . $method));
                    }

                    if (call_user_func([$this, $method]) === false) {
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
        echo "\n" . Console::ansiFormat('Exception: ' . $e->getMessage() . ' (' . $e->getFile() . ':' . $e->getLine() . ')', [Console::BG_RED, Console::FG_BLACK]) . "\n";
    }
}