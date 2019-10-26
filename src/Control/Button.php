<?php

namespace UI\Control;

use UI\Control;
use FFI\CData;
use UI\Event;
use UI\Struct\FontDescriptor;

/**
 * Create button,file,save-file,font,color by type in config
 * 
 * @property-read string $type
 * @property \UI\Event $click
 * @property \UI\Event $change
 * @property-read string $title
 */
class Button extends Control
{
    const CTL_NAME = 'button';

    public function newControl(): CData
    {
        $type = $this->attr['type'] ?? null;
        $this->attr['click'] = $this->attr['click'] ?? null;
        switch ($type) {
            case 'file':
                $this->instance = self::$ui->newButton($this->attr['title']);
                if ($this->attr['click']) {
                    $this->onClick($this->attr['click']);
                }
                break;
            case 'save':
                $this->instance = self::$ui->newButton($this->attr['title']);
                if ($this->attr['click']) {
                    $this->onClick($this->attr['click']);
                }
                break;
            case 'font':
                $this->instance = self::$ui->newFontButton();
                if ($this->attr['change']) {
                    $this->onChage($this->attr['change']);
                }
                break;
            case 'color':
                $this->instance = self::$ui->newColorButton();
                if ($this->attr['click']) {
                    $this->onChange($this->attr['click']);
                }
            case 'button':
            default:
                $this->instance = self::$ui->newButton($this->attr['title']);
                if ($this->attr['click']) {
                    $this->onClick($this->attr['click']);
                }
        }
        return $this->instance;
    }

    public function __set($name, $value)
    {
        switch ($name) {
            case 'click':
                $this->onClick($value);
                break;
            case 'change':
                $this->onChange($value);
                break;
        }
    }

    public function onClick(Event $callable)
    {
        $this->attr['click'] = $callable;
        if ($this->attr['type'] === 'file') {
            $callable->onBefore(function () {
                return $this->build->openFile();
            });
        }
        if ($this->attr['type'] === 'save') {
            $callable->onBefore(function () {
                return $this->build->saveFile();
            });
        }
        if ($this->attr['type'] !== 'font' && $this->attr['type'] !== 'color') {
            $this->bindEvent('buttonOnClicked', $callable);
        }
    }

    public function onChange(Event $callable)
    {
        $this->attr['change'] = $callable;
        if ($this->attr['type'] == 'font') {
            $this->bindEvent('fontButtonOnChanged', $callable);
        } elseif ($this->attr['type'] === 'color') {
            $this->bindEvent('colorButtonOnChanged', $callable);
        }
    }

    public function getValue()
    {
        if ($this->attr['type'] === 'font') {
            $fontDes = new FontDescriptor($this->build);
            $this->fontButtonFont($fontDes->getFontDescriptor());
            $fontDes->fill();
            return $fontDes;
        } elseif ($this->attr['type'] === 'color') {
            $r = self::$ui->new('double*');
            $g = self::$ui->new('double*');
            $bl = self::$ui->new('double*');
            $a = self::$ui->new('double*');
            $this->colorButtonColor($r, $g, $bl, $a);
            return [
                'red' => $r[0], 'green' => $g[0], 'blue' => $bl[0], 'alpha' => $a[0]
            ];
        } else {
            return $this->buttonText();
        }
    }

    public function setValue($text)
    {
        if ($this->attr['type'] === 'font') {
            
        } elseif ($this->attr['type'] === 'color') {
            $this->colorButtonSetColor($text['red'], $text['green'], $text['blue'], $text['alpha']);
        } else {
            $this->buttonSetText($text);
        }
    }

}
