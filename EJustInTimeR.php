<?php

/**
 * @version 0.2 
 */
class EJustInTimeR extends CWidget
{
    /**
     * Public variables
     */
    public $image = null;
    public $display = 'thumb';
    public $title = null;
    public $alt = null;
    private $src = 'micro';

    /**
     * Private variables
     */
    private $bigPath;
    private $thumbPath;
    private $microPath;
    private $original;
    private $newName = '';
    private $dimensions = array();

    public function init()
    {
        $this->initDimensions();
        $this->checkFolders();
        $this->createUniqueName();
        $this->initPathVars();
        $this->createImagesIfNotExists();
    }

    private function initDimensions()
    {
        $this->dimensions = array(
            'big' => array(
                'width' => 400,
                'height' => 300,
            ),
            'thumb' => array(
                'width' => 160,
                'height' => 120,
            ),
            'micro' => array(
                'width' => 32,
                'height' => 32,
            ),
        );
    }

    public function run()
    {
        echo '<img src="' . Yii::app()->baseUrl . '/images/jitr/' . $this->src . '/' . $this->image . '" title="' . $this->getTitle() . '" alt="' . $this->getAlt() . '" />';
    }

    private function createImagesIfNotExists()
    {
        if (!file_exists($this->src . '/' . $this->image)) {
            Yii::import('application.extensions.image.Image');
            $image = new Image($this->original);
            foreach ($this->dimensions as $path => $dim) {
                $pathName = $path . 'Path';
                if (!file_exists($this->$pathName)) {
                    $image->resize($dim['width'], $dim['height']);
                    $image->save($this->$pathName);
                }
            }
        }
    }

    private function initPathVars()
    {
        $this->original = __DIR__ . '/../../../images/jitr/originals/' . ($this->image);
        foreach ($this->dimensions as $path => $dim) {
            $pathName = $path . 'Path';
            $this->$pathName = __DIR__ . '/../../../images/jitr/' . $path . '/' . $this->newName;
        }
    }

    private function createUniqueName()
    {
        $this->newName = $this->image;
    }

    private function checkFolders()
    {
        foreach (array(
    __DIR__ . '/../../../images/jitr',
    __DIR__ . '/../../../images/jitr/originals',
        ) as $path)
            if (!file_exists($path))
                throw new Exception($path . ' do not exists!');
        foreach ($this->dimensions as $path => $dim)
            if (!file_exists(__DIR__ . '/../../../images/jitr/' . $path))
                throw new Exception(__DIR__ . '/../../../images/jitr/' . $path . ' do not exists!');
        if (!$this->image)
            throw new Exception('Image cannot be null!');
    }

    private function getTitle()
    {
        return $this->title ? $this->title : $this->image;
    }

    private function getAlt()
    {
        return $this->alt ? $this->alt : $this->image;
    }

}
