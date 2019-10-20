<?php

namespace UI\Control;

use UI\Control\Menu;
use FFI\CData;

class MenuItem extends Menu
{
    public function newControl(): CData
    {
        $parent  = $this->attr['parent'];
        $this->attr['id'] = $this->attr['id'] ?? $this->attr['parent_id'] . '_item_' . ($this->attr['idx'] - 1);
        $this->attr['type'] = $this->attr['type'] ?? 'text';
        switch ($this->attr['type']) {
            case 'checkbox':
                $this->instance = self::$ui->menuAppendCheckItem($parent->getUIInstance(), $this->attr['title']);
                break;
            case 'quit':
                $this->instance  = $this->menuAppendQuitItem();
                break;
            case 'about':
                $this->instance =  $this->menuAppendAboutItem();
                break;
            case 'preferences':
                $this->instance = $this->menuAppendPreferencesItem();
                break;
            default:
                $this->instance = self::$ui->menuAppendItem($parent->getUIInstance(), $this->attr['title']);
        }
        if (isset($this->attr['click'])) {
            $this->onClick($this->attr['click']);
        }
        return $this->instance;
    }

    public function onClick(array $callable)
    {
        $this->bindEvent('menuItemOnClicked', $callable);
    }

    public function isCheck() {
        return $this->menuItemChecked();
    }

    public function setCheck(int $check = 1) {
        $this->menuItemSetChecked($check);
    }
}
