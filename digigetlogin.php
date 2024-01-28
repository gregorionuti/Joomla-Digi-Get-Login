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

// namespaces
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

// include jquery library only in admin
if (Factory::getApplication()->isClient('administrator')) {
	$document = Factory::getDocument();
	$document->addScript(DS.'media'.DS.'vendor'.DS.'jquery'.DS.'js'.DS.'jquery.min.js');
}

class plgSystemDigigetlogin extends JPlugin	{
		
	protected $_username;
    protected $_password;
	
    public function __construct(&$subject, $config) {
        parent::__construct($subject, $config);
        $this->loadLanguage();
    }

    public function onAfterInitialise()	{
		$usernameVar = $this->params->get('username-var', 'username');
		$passwordVar = $this->params->get('password-var', 'password');
		$base64_username = $this->params->get('base64-username', 0);
    	$base64_password = $this->params->get('base64-password', 0);
        
		$input = Factory::getApplication()->input;
		//die(var_dump($input));
		$this->_[urlencode($usernameVar)] = $input->get($usernameVar, NULL, 'string');
		$this->_[urlencode($passwordVar)] = $input->get($passwordVar, NULL, 'string');
		
		// if username and password parameters are in the url and if base64 options are not enabled, alert the user that errors may occur due of the special characters
		if (isset($_GET[$usernameVar]) || isset($_GET[$passwordVar])) {
			if ($base64_username == 0 || $base64_password == 0) {
			
				// check if username contains speicial characters and enqueue message if base64 options are not enabled
				if (preg_match('/[\'^£$%*()}{@#~><>,|+¬]/', Uri::getInstance()->toString())) {
					
					Factory::getApplication()->enqueueMessage(Text::_('PLG_SYSTEM_DIGIGETLOGIN_MESSAGE_SPECIAL_CHARACTERS_IN_URL'), 'warning');
					
					return true;
				}
			}
		}
		
		// if username parameter is passed but password parameter not and if base64 username option is not enabled, alert the user that maybe a hash is included in username parameter
		// in this case password parameters is not passed because the uri is sliced by the hash contained in the username
		if (isset($_GET[$usernameVar]) && !isset($_GET[$passwordVar])) {
			if ($base64_username == 0) {
			
				Factory::getApplication()->enqueueMessage(Text::_('PLG_SYSTEM_DIGIGETLOGIN_MESSAGE_CHECK_FOR_HASH_IN_USERNAME'), 'warning');
				
				return true;
			}
		}
		
		// avoid the execution of plugin if username and password parameters are not in the url (means that the user login by normal joomla functions)
		if (isset($_GET[$usernameVar]) && isset($_GET[$passwordVar])) {

			// check if the username and password parameters are not empty
			if (!empty($this->_[$usernameVar]) && !empty($this->_[$passwordVar])) {
				$result = $this->login($this->_[$usernameVar],$this->_[$passwordVar]);
			}
		}
		
		return true;
	}
	
    function login($username,$password) {
    	$base64_username = $this->params->get('base64-username', 0);
    	$base64_password = $this->params->get('base64-password', 0);
    	
		$app = Factory::getApplication();
		$loginCredentials = array();
		if ($base64_username == 0) {
			$loginCredentials['username'] = urldecode($username);
		} else if ($base64_username == 1) {
			$loginCredentials['username'] = base64_decode(urldecode($username));
		}
		if ($base64_password == 0) {
			$loginCredentials['password'] = urldecode($password);
		} else if ($base64_password == 1) {
			$loginCredentials['password'] = base64_decode(urldecode($password));
		}
		$options = array('remember' => false);
		$result = $app->login($loginCredentials, $options);
		$redirectType = $this->params->get('redirect-type');
		$urlRedirectLogin = $this->params->get('url-redirect-login');
		$urlRedirectError = $this->params->get('url-redirect-error');
		$menuItemRedirectLogin = $this->params->get('menu-item-redirect-login');
		$menuItemRedirectError = $this->params->get('menu-item-redirect-error');
        
		$menu = $app->getMenu();
		$menuLinkRedirectLogin = $menu->getItem($menuItemRedirectLogin);
		$menuLinkRedirectError = $menu->getItem($menuItemRedirectError);
		$linkLogin = $menuLinkRedirectLogin->link.'&Itemid='.$menuLinkRedirectLogin->id;
		$linkError = new URI($menuLinkRedirectError->link.'&Itemid='.$menuLinkRedirectError->id);
        
		$user = Factory::getUser();
		$user_id = $user->id;
        if ($user_id) {
			if ($result) {
				$app = Factory::getApplication();
				if ($redirectType == 0) {
					$app->redirect($linkLogin);
				} else {
					$app->redirect($urlRedirectLogin);
				}
			}
        } else {
			$app = Factory::getApplication();
			if ($redirectType == 0) {
				$app->redirect($linkError);
			} else {
				$app->redirect($urlRedirectError);
			}
		}
	}
}