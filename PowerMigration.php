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
class PowerMigration extends \yii\db\Migration
{

    /** @var Instruction[] */
    private $instructions = [];

    /** @var array */
    private $executed = [];

    /**
     * @param \Exception $e
     */
    public function errorTrace(\Exception $e)
    {
        echo Console::ansiFormat("Exception: " . $e->getMessage() . ' (' . $e->getFile() . ':' . $e->getLine() . ")\n", [Console::FG_RED]);
        Console::clearLine();
    }

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
            foreach ($executed as $key) {
                $Instruction = $this->getInstruction($key);
                switch ($executed_in) {
                    case 'up':
                        $Instruction->downgrade($this);
                        break;
                    case 'down':
                        $Instruction->upgrade($this);
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
        $instructions = $this->getInstructions();
        if (!empty($instructions)) {
            foreach ($instructions as $key => $Instruction) {
                try {
                    if ($Instruction->upgrade($this) === false) {
                        $this->rollback('up');

                        return false;
                    } else {
                        $this->executed[] = $key;
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
        $instructions = $this->getInstructions();
        if (!empty($instructions)) {
            foreach ($instructions as $key => $Instruction) {
                try {
                    if ($Instruction->downgrade($this) === false) {
                        $this->rollback('down');

                        return false;
                    } else {
                        $this->executed[] = $key;
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
     * @return Instruction
     */
    public function addInstruction()
    {
        $Instruction = new Instruction();
        $key = $Instruction->key;

        $this->instructions[$key] = $Instruction;

        return $this->getInstruction($key);
    }

    /**
     * @param string $key
     * @return Instruction
     */
    public function getInstruction($key)
    {
        return $this->instructions[$key];
    }

    /**
     * @return Instruction[]
     */
    public function getInstructions()
    {
        return $this->instructions;
    }
}