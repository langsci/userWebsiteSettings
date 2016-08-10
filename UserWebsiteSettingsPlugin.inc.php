<?php

/**
 * @file public/generic/userWebsiteSettings/UserWebsiteSettings.inc.php
 *
 * Copyright (c) 2016 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class UserWebsiteSettingsPlugin
 * User website settings plugin main class
 */

import('lib.pkp.classes.plugins.GenericPlugin');

class UserWebsiteSettingsPlugin extends GenericPlugin {

	/**
	 * Register the plugin, attaching to hooks as necessary.
	 * @param $category string
	 * @param $path string
	 * @return boolean
	 */
	function register($category, $path) {

		if (parent::register($category, $path)) {
			if ($this->getEnabled()) {
				// Register the DAO.
				import('plugins.generic.userWebsiteSettings.UserWebsiteSettingsDAO');
				$userWebsiteSettingsDao = new UserWebsiteSettingsDAO();
				DAORegistry::registerDAO('UserWebsiteSettingsDAO', $userWebsiteSettingsDao);
				HookRegistry::register('LoadHandler', array($this, 'callbackHandleContent'));
				HookRegistry::register('TemplateManager::display', array(&$this, 'handleDisplayTemplate'));
			}
			return true;
		}
		return false;
	}

	function handleDisplayTemplate($hookName, $args) {

		$request = $this->getRequest();
		$press = $request->getPress();

		$templateMgr =& $args[0];
		$template =& $args[1];

		switch ($template) {

			case 'user/profile.tpl':	
				// add links to the user profile page (for website and community settings)
				$templateMgr->assign('linkToWebsiteSettings','viewUserWebsiteSettings');
				$templateMgr->assign('linkToCommunitySettings','subscriptions');
				$templateMgr->display($this->getTemplatePath() . 
				'profileModified.tpl', 'text/html', 'TemplateManager::display');
				return true;
			case 'authorDashboard/authorDashboard.tpl':	
		}
		return false;
	}
	/**
	 * Declare the handler function to process the actual page PATH
	 * @param $hookName string The name of the invoked hook
	 * @param $args array Hook parameters
	 * @return boolean Hook handling status
	 */
	function callbackHandleContent($hookName, $args) {

		$request = $this->getRequest();
		$press   = $request->getPress();		

		// get url path components to overwrite them 
		$pageUrl =& $args[0];
		$opUrl =& $args[1];

		$goToWebsiteSettings = $this->checkUrl($pageUrl,$opUrl);

		$completeUrl = $args[0].$args[1].implode("",$request->getRequestedArgs());
		$view = str_replace("index","",$completeUrl);

		$viewUserWebsiteSettingsRequested = substr($view,-23)=="viewUserWebsiteSettings";

		if ($goToWebsiteSettings||$viewUserWebsiteSettingsRequested) {

			$pageUrl = '';
			$opUrl = 'viewUserWebsiteSettings';

			define('HANDLER_CLASS', 'UserWebsiteSettingsHandler');
			define('USERWEBSITESETTINGS_PLUGIN_NAME', $this->getName());

			$this->import('UserWebsiteSettingsHandler');

			return true;

		}
		return false;

	}

	// PKPPlugin::getManagementVerbs()
	function getManagementVerbs() {
		$verbs = parent::getManagementVerbs();
		if ($this->getEnabled()) {
			$verbs[] = array('settings', __('plugins.generic.hallOfFame.settings'));
		}
		return $verbs;
	}

	/**
	 * @see Plugin::getActions()
	 */
	function getActions($request, $verb) {
		$router = $request->getRouter();
		import('lib.pkp.classes.linkAction.request.AjaxModal');
		return array_merge(
			$this->getEnabled()?array(
				new LinkAction(
					'settings',
					new AjaxModal(
						$router->url($request, null, null, 'manage', null, array('verb' => 'settings', 'plugin' => $this->getName(), 'category' => 'generic')),
						$this->getDisplayName()
					),
					__('manager.plugins.settings'),
					null
				),
			):array(),
			parent::getActions($request, $verb)
		);
	}

 	/**
	 * @see Plugin::manage()
	 */
	function manage($args, $request) {
		switch ($request->getUserVar('verb')) {
			case 'settings':
				$context = $request->getContext();
				$this->import('UserWebsiteSettingsSettingsForm');
				$form = new UserWebsiteSettingsSettingsForm($this, $context->getId());
				if ($request->getUserVar('save')) {
					$form->readInputData();
					if ($form->validate()) {
						$form->execute();
						return new JSONMessage(true);
					}
				} else {
					$form->initData();
				}
				return new JSONMessage(true, $form->fetch($request));
		}
		return parent::manage($args, $request);
	}

	private function checkUrl($pageUrl,$opUrl) {

		$request = $this->getRequest();
		$context = $request->getContext();
	
		// get path components
		$urlArray = array();
		$urlArray[] = $pageUrl;
		$urlArray[] = $opUrl;
		$urlArray = array_merge($urlArray,$request->getRequestedArgs());
		$urlArrayLength = sizeof($urlArray);

		// get path components specified in the plugin settings
		$settingPath = $this->getSetting($context->getId(),'langsci_userWebsiteSettings_path');

		if (!ctype_alpha(substr($settingPath,0,1))&&!ctype_digit(substr($settingPath,0,1))) {
			return false;
		}
		$settingPathArray = explode("/",$settingPath);
		$settingPathArrayLength = sizeof($settingPathArray);
		if ($settingPathArrayLength==1) {
			$settingPathArray[] = 'index';
		}

		// compare path and path settings
		$goToWebsiteSettings = false;
		if ($settingPathArray==$urlArray){
			$goToWebsiteSettings = true;
		}
		return $goToWebsiteSettings;
	}

	/**
	 * Get the filename of the ADODB schema for this plugin.
	 * @return string Full path and filename to schema descriptor.
	 */
	function getInstallSchemaFile() {
		return $this->getPluginPath() . '/schema.xml';
	}

	/**
	 * Get the plugin's display (human-readable) name.
	 * @return string
	 */
	function getDisplayName() {
		return __('plugins.generic.userWebsiteSettings.displayName');
	}

	/**
	 * Get the plugin's display (human-readable) description.
	 * @return string
	 */
	function getDescription() {
		return __('plugins.generic.userWebsiteSettings.description');
	}

	/**
	 * @copydoc PKPPlugin::getTemplatePath
	 */
	function getTemplatePath() {
		return parent::getTemplatePath() . 'templates/';
	}

}

?>
