<?php
class Tiny_CompressImages_Model_System_Config_Backend_Comment_LoggingMode
{
    /**
     * Make the /var/log/image-optimization.log file clickable.
     *
     * @return string
     */
    public function getCommentText()
    {
        $text = Mage::helper('tiny_compressimages')->__('Any error messages will be saved to %s');
        $downloadUrl = Mage::helper("adminhtml")->getUrl('adminhtml/CompressImagesAdminhtml_config/downloadLogs');
        $logPath = Mage::helper('tiny_compressimages')->getLogFilePath();
        $basePath = Mage::getBaseDir();

        $nicePath = str_replace($basePath, '', $logPath);

        if (Mage::helper('tiny_compressimages')->getLogFileExists()) {
            $text = sprintf($text, '<a href="' . $downloadUrl . '">' . $nicePath . '</a>');
        } else {
            $text = sprintf($text, $nicePath);
        }

        return $text;
    }
}