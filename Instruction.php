<?php
/**
 * Instruction.php
 * @author Revin Roman http://phptime.ru
 */

namespace rmrevin\yii\db\migration;

/**
 * Class Instruction
 * @package rmrevin\yii\db\migration
 */
class Instruction extends \yii\base\Object
{

    public $key;

    private $up;
    private $down;

    public function init()
    {
        parent::init();

        $this->key = uniqid();
    }

    /**
     * @param callable $handler
     * @return self
     */
    public function up($handler)
    {
        $this->up = $handler;

        return $this;
    }

    /**
     * @param callable $handler
     * @return self
     */
    public function down($handler)
    {
        $this->down = $handler;

        return $this;
    }

    /**
     * @param PowerMigration $Migration
     * @return boolean
     */
    public function upgrade(PowerMigration $Migration)
    {
        return call_user_func($this->up, $Migration, $this);
    }

    /**
     * @param PowerMigration $Migration
     * @return boolean
     */
    public function downgrade(PowerMigration $Migration)
    {
        return call_user_func($this->down, $Migration, $this);
    }
}