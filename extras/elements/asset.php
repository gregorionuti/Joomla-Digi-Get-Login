<?php
/**
 * 
 * @version             See field version manifest file
 * @package             See field name manifest file
 * @author				Gregorio Nuti
 * @copyright			See field copyright manifest file
 * @license             GNU General Public License version 2 or later
 * 
 */

// no direct access
defined('_JEXEC') or die;

// define ds variable for joomla 3 compatibility
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Uri\Uri;

class JFormFieldAsset extends FormField {
    protected $type = 'Asset';

    protected function getLabel() {
        return;
    }

    protected function getInput() {

        // human defined variables
        $extension_family = 'plugins';
        $plugin_type = 'system';
        $extension_name = 'digigetlogin';

        // general variables
        $uri = Uri::getInstance();
        $document = Factory::getDocument();

    	// load jquery in joomla 4
    	$joomlaVersion = JVERSION;
    	if ($joomlaVersion >= '4') {
			$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
			$wa->useScript('jquery');
    	}

        $document->addScript(Uri::root().$extension_family.DS.$plugin_type.DS.$extension_name.DS.'extras'.DS.'asset.js');
        $document->addStyleSheet(Uri::root().$extension_family.DS.$plugin_type.DS.$extension_name.DS.'extras'.DS.'asset.css');
        
        return;
    }
}

?>