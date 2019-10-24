<?php

namespace UI\Control\Draw;

use UI\Control;
use FFI\CData;

/**
 * @method void newFigure(float x, float y)
 * @method void newFigureWithArc(float xCenter, float yCenter, float radius, float startAngle, float sweep, int negative)
 * @method void lineTo(float $x, float $y)
 * @method void arcTo(float $xCenter, float $yCenter, float $radius, float $startAngle, float $sweep, int $negative)
 * @method void bezierTo(float $c1x, float $c1y, float $c2x, float $c2y, float $endX, float $endY)
 * @method void closeFigure()
 * @method void addRectangle(float $x, float $y, float $width, float $height)
 * @method void end()
 *
 */
class Path extends Control
{
    public function newControl(): CData
    {
        $this->instance = self::$ui->drawNewPath($this->attr['fillMode']);
        return $this->instance;
    }

    public function free()
    {
        $this->drawFreePath($this->instance);
    }

    public function __call($func, $args)
    {
        $func = 'drawPath' . ucfirst($func);
        return parent::__call($func, $args);
    }

    public function fill(CData $context, Brush $brush)
    {
        self::$ui->drawFill($context, $this->instance, self::$ui->addr($brush->getBrush()));
    }

    /**
     * stroke
     *
     * @param CData $context    The param from callable function that is Area::draw($handler, $area, $params) passed $params['context']
     * @param Brush $brush      is new UI\Control\Draw\Brush
     * @param StrokeParams $params  is new UI\Control\Draw\StrokeParams
     * @return void
     */
    public function stroke(CData $context, Brush $brush, StrokeParams $params)
    {
        $paramType = $params->getStrokeParams();
        self::$ui->drawStroke($context, $this->instance, self::$ui->addr($brush->getBrush()), self::$ui->addr($paramType));
    }

    public function newBrush() {
        return new Brush($this->build);
    }

    public function newStrokeParams() {
        return new StrokeParams($this->build);
    }
}
