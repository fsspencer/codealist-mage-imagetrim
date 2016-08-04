<?php

require_once 'Mage/Catalog/Helper/Image.php';

/**
 * This extension requires 
 * PHP ImageMagick installed in you webserver
 * otherwise it won't trim your images
 *
 * Remember to clear the Images cache after installing
 */

/**
 * @author      Francis S. Spencer <francis.s.spencer@gmail.com>
 * @version     1.0 Trim method added
 * @link        http://www.codealist.com/
 * @category    codealist
 * @package     Codealist_Imagetrim
 * 
 */
class Codealist_Imagetrim_Helper_Trim extends Mage_Catalog_Helper_Image
{

    public function getAuxFile($newExt = null)
    {
        $model = $this->_getModel();

        if ($this->getImageFile()) {
            $path = $this->getImageFile();
        } else {
            $path = $this->getProduct()->getData($model->getDestinationSubdir());
        }
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $path);
        $ext = $newExt ? $newExt : $ext;
        return $withoutExt . '_aux.' . $ext;
    }

    public function getAuxPath($newExt = null)
    {
        $path = $this->_getModel()->getBaseFile();
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $path);
        $ext = $newExt ? $newExt : $ext;
        return $withoutExt . '_aux.' . $ext;   
    }

	/**
     * Schedule trim of the image
     *
     * @see Mage_Catalog_Model_Product_Image
     * @return Mage_Catalog_Helper_Image
     */
    public function trim()
    {
    	if(extension_loaded('imagick')) {
            try {
                if(!$this->_getModel()->isCached()) {
                    $image = new Imagick($this->_getModel()->getBaseFile());
                    $image->setImageBackgroundColor('white');
                    $image->setImageAlphaChannel(11);
                    $image = $image->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
                    $image->borderImage("#FFFFFF", 1, 1);
                    $image->trimImage(0);
                    $image->writeImage($this->getAuxPath('jpg'));
                }
            } catch(Exception $e) {
                Mage::logException($e);
            }
        }
        
        return $this;
    }

    /**
     * Return Image URL
     *
     * @return string
     */
    public function __toString()
    {
        try {
            $model = $this->_getModel();

            $this->trim();

            if(file_exists(Mage::getBaseDir('media') . DS.'catalog'.DS.'product'.DS.$this->getAuxFile('jpg'))) {
                $model->setBaseFile($this->getAuxFile('jpg'));
            } else {
                if ($this->getImageFile()) {
                    $model->setBaseFile($this->getImageFile());
                } else {
                    $model->setBaseFile($this->getProduct()->getData($model->getDestinationSubdir()));
                }
            }

            if ($model->isCached()) {
                return $model->getUrl();
            } else {

                if ($this->_scheduleRotate) {
                    $model->rotate($this->getAngle());
                }

                if ($this->_scheduleResize) {
                    $model->resize();
                }

                if ($this->getWatermark()) {
                    $model->setWatermark($this->getWatermark());
                }

                $url = $model->saveFile()->getUrl();

            }
        } catch (Exception $e) {
            $url = Mage::getDesign()->getSkinUrl($this->getPlaceholder());
        }
        return $url;
    }
	
}