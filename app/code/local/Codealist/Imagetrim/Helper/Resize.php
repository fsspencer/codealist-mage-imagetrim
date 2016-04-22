<?php


/**
 * This extension requires
 * PHP ImageMagick installed in you webserver
 * otherwise it won't trim your images
 *
 * Remember to clear the Images cache after installing
 */

/**
 * @author  	Francis S. Spencer <francis.s.spencer@gmail.com>
 * @version 	1.0 Trim method added
 * @link 		http://www.codealist.com/
 * @category    codealist
 * @package     Codealist_Imagetrim
 *
 */
class Codealist_Imagetrim_Helper_Resize extends Mage_Catalog_Helper_Image
{
    public function resizeImg($fileName, $width, $height = '')
    {
        $folderURL = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
        $imageURL = $folderURL . $fileName;

        $basePath = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . $fileName;
        $newPath = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . $width . "x" . $height . DS . $fileName;
        //if width empty then return original size image's URL
        if ($width != '') {
            //if image has already resized then just return URL
            if (file_exists($basePath) && is_file($basePath) && !file_exists($newPath)) {
                $imageObj = new Varien_Image($basePath);
                $imageObj->backgroundColor(array(255,255,255));
                $imageObj->constrainOnly(TRUE);
                $imageObj->keepAspectRatio(true);
                $imageObj->keepFrame(FALSE);
                if ( $height != '')
                    $imageObj->resize($width, $height);
                else
                    $imageObj->resize($width);
                $imageObj->save($newPath);
            }
            $resizedURL = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . $width . "x" . $height . DS . $fileName;
        } else {
            $resizedURL = $imageURL;
        }
        return $resizedURL;
    }

    public function resizeImgSkinUrl($fullUrl, $width, $height = '')
    {
        $fileName = basename($fullUrl);
        $imageURL = $fullUrl;

        $basePath = Mage::getDesign()->getSkinBaseDir() . $fullUrl;
        $newPath = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . $width . "x" . $height . DS . $fileName;

        //if width empty then return original size image's URL
        if ($width != '') {
            //if image has already resized then just return URL
            if (file_exists($basePath) && is_file($basePath) && !file_exists($newPath)) {
                $imageObj = new Varien_Image($basePath);
                $imageObj->backgroundColor(array(255,255,255));
                $imageObj->constrainOnly(FALSE);
                $imageObj->keepAspectRatio(true);
                $imageObj->keepFrame(FALSE);
                if ( $height != '')
                    $imageObj->resize($width, $height);
                else
                    $imageObj->resize($width);
                $imageObj->save($newPath);
            }
            $resizedURL = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . $width . "x" . $height . DS . $fileName;
        } else {
            $resizedURL = $imageURL;
        }
        return $resizedURL;
    }

    public function resizeImgEE($dir, $fullUrl, $width, $height = '')
    {
        $fileName = basename($fullUrl);
        $imageURL = $fullUrl;

        $basePath = $dir . DS . 'images' . DS . 'uploads' . DS . $fileName;
        if(!file_exists($basePath)) return $imageURL;

        $newPath = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . $width . "x" . $height . DS . $fileName;
        //if width empty then return original size image's URL
        if ($width != '') {
            //if image has already resized then just return URL
            if (file_exists($basePath) && is_file($basePath) && !file_exists($newPath)) {
                $imageObj = new Varien_Image($basePath);
                $imageObj->backgroundColor(array(255,255,255));
                $imageObj->constrainOnly(TRUE);
                $imageObj->keepAspectRatio(true);
                $imageObj->keepFrame(FALSE);
                if ( $height != '')
                    $imageObj->resize($width, $height);
                else
                    $imageObj->resize($width);
                $imageObj->save($newPath);
            }
            $resizedURL = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . $width . "x" . $height . DS . $fileName;
        } else {
            $resizedURL = $imageURL;
        }
        return $resizedURL;
    }

    public function getImageHeight($fileName, $width, $height = '')
    {
        $newPath = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . $width . "x" . $height . DS . $fileName;
        $imageHeight = '';

        // getting image width and height
        if (file_exists($newPath)) {
            $imageObj = new Varien_Image($newPath);
            $imageHeight = $imageObj->getOriginalHeight();
        }

        return $imageHeight;
    }
}