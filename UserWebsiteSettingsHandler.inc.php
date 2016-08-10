<?php

/**
 * @file UserWebsiteSettingsHandler.inc.php
 *
 * Copyright (c) 2016 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class UserWebsiteSettingsHandler
 * Find page content and display it.
 */

import('classes.handler.Handler');
import('plugins.generic.userWebsiteSettings.UserWebsiteSettingsDAO');

class UserWebsiteSettingsHandler extends Handler {

	/** @var StaticPagesPlugin The static pages plugin */
	static $plugin;

	/**
	 * Constructor
	 */
	function UserWebsiteSettingsHandler() {
		parent::Handler();
	}

	function viewUserWebsiteSettings($args, $request) {

		// get user role 
		$authorizedUserGroups = array(ROLE_ID_SITE_ADMIN,ROLE_ID_MANAGER);
		$userRoles = $this->getAuthorizedContextObject(ASSOC_TYPE_USER_ROLES);

		// redirect to index page if no user is logged in
		$user = $request->getUser();
		if (!$user) {
			$request->redirect('index');
		}

		// if there is no table "langsci_website_settings" redirect to index page
		$userWebsiteSettingsDAO = new UserWebsiteSettingsDAO;
		if(!$userWebsiteSettingsDAO->existsTable('langsci_website_settings')) {
			$request->redirect('index');
		}

		// get username and user_id
		$username = $user-> getUsername();
		$userId = $user->getId();

		// get the settings for this plugin 
		$plugin = PluginRegistry::getPlugin('generic', USERWEBSITESETTINGS_PLUGIN_NAME);
		$context = $request->getContext();
		$contextId = $context->getId();

		$settings = array(
			"PublicProfile" => $plugin->getSetting($contextId,'langsci_userWebsiteSettings_publicProfile'),
			"Email"			=> $plugin->getSetting($contextId,'langsci_userWebsiteSettings_email'),
			"HallOfFame"	=> $plugin->getSetting($contextId,'langsci_userWebsiteSettings_hallOfFame')
		);

		// set up template manager and assign setting variables
		$templateMgr = TemplateManager::getManager($request);
		$templateMgr->assign('pageTitle','plugins.generic.userWebsiteSettings.title');
		$templateMgr->assign('pressPath',$this->getPressPath($request));
		$templateMgr->assign('userId',$userId);
		$templateMgr->assign('userRoles', $userRoles); // necessary for the backend sidenavi to appear

		//  assign path to hall of fame if exists
		$hallOfFamePlugin = PluginRegistry::getPlugin('generic','halloffameplugin');
		if ($hallOfFamePlugin) {
			$pathHallOfFame = $hallOfFamePlugin->getSetting($contextId,'langsci_hallOfFame_path');
			if ($pathHallOfFame) {
				$templateMgr->assign('pathHallOfFame',$pathHallOfFame);
			}
		}
		//  assign path to public profile if exists
		$publicProfilesPlugin = PluginRegistry::getPlugin('generic','publicprofilesplugin');
		if ($publicProfilesPlugin) {
			$pathPublicProfiles = $publicProfilesPlugin->getSetting($contextId,'langsci_publicProfiles_path');
			if ($pathPublicProfiles) {
				$templateMgr->assign('pathPublicProfiles',$pathPublicProfiles);
			}
		}

		//$templateMgr->assign('pathPublicProfiles',$press->getSetting('langsci_publicProfiles_path'));

		foreach($settings as $key => $value) {
			$templateMgr->assign(''.$key, $value);
		}

		// if save button is set: get data from website and save it to database, else: get data from the database
		foreach($settings as $key => $value) {
			if ($value && isset($_POST['buttonSaveWebsiteSettings'])) {
				$userWebsiteSettingsDAO->setWebsiteSetting($userId,$key,isset($_POST['checkbox'.$key]));
				$templateMgr->assign('issetCheckbox'.$key, $userWebsiteSettingsDAO->getWebsiteSetting($userId,$key)=='true');
			} else if ($value) {
				$templateMgr->assign('issetCheckbox'.$key, $userWebsiteSettingsDAO->getWebsiteSetting($userId,$key)=='true');
			}
		}	

		$userWebsiteSettingsPlugin = PluginRegistry::getPlugin('generic', USERWEBSITESETTINGS_PLUGIN_NAME);
		$templateMgr->display($userWebsiteSettingsPlugin->getTemplatePath()."userWebsiteSettings.tpl");
	}

	function getPressPath(&$request) {
		$press = $request -> getPress();
		$pressPath = $press -> getPath();
 		$completeUrl = $request->getCompleteUrl();
		return substr($completeUrl,0,strpos($completeUrl,$pressPath)) . $pressPath ;
	}

}

?>

