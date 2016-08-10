<?php

/**
 * @file plugins/generic/userWebsiteSettings/UserWebsiteSettingsSettingsForm.inc.php
 *
 * Copyright (c) 2016 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class UserWebsiteSettingsSettingsForm
 *
 */

import('lib.pkp.classes.form.Form');

class UserWebsiteSettingsSettingsForm extends Form {

	/** @var AddThisBlockPlugin The plugin being edited */
	var $_plugin;

	/** @var int Associated context ID */
	private $_contextId;

	/**
	 * Constructor.
	 * @param $plugin Plugin
	 * @param $press Press
	 */
	function UserWebsiteSettingsSettingsForm($plugin, $contextId) {

		$this->_contextId = $contextId;
		$this->_plugin = $plugin;

		parent::Form($plugin->getTemplatePath() . 'settings.tpl');
		$this->addCheck(new FormValidatorPost($this));

	}

	//
	// Overridden template methods
	//
	/**
	 * Initialize form data from the plugin.
	 */
	function initData() {

		$contextId = $this->_contextId;
		$plugin = $this->_plugin;

		$this->setData('langsci_userWebsiteSettings_path', $plugin->getSetting($contextId, 'langsci_userWebsiteSettings_path'));
		$this->setData('langsci_userWebsiteSettings_publicProfile', $plugin->getSetting($contextId, 'langsci_userWebsiteSettings_publicProfile'));
		$this->setData('langsci_userWebsiteSettings_email', $plugin->getSetting($contextId, 'langsci_userWebsiteSettings_email'));
		$this->setData('langsci_userWebsiteSettings_hallOfFame', $plugin->getSetting($contextId, 'langsci_userWebsiteSettings_hallOfFame'));

	}

	/**
	 * Fetch the form.
	 * @see Form::fetch()
	 * @param $request PKPRequest
	 */
	function fetch($request) {

		$templateMgr = TemplateManager::getManager($request);
		$templateMgr->assign('pluginName', $this->_plugin->getName());
		$templateMgr->assign('pluginBaseUrl', $request->getBaseUrl() . '/' . $this->_plugin->getPluginPath());

		return parent::fetch($request);
	}

	/**
	 * Assign form data to user-submitted data.
	 * @see Form::readInputData()
	 */
	function readInputData() {

		$this->readUserVars(array(
			'langsci_userWebsiteSettings_path',
			'langsci_userWebsiteSettings_publicProfile',
			'langsci_userWebsiteSettings_email',
			'langsci_userWebsiteSettings_hallOfFame'
		));
	}

	/**
	 * Save the plugin's data.
	 * @see Form::execute()
	 */
	function execute() {

		$plugin = $this->_plugin;
		$contextId = $this->_contextId;

		$plugin->updateSetting($contextId, 'langsci_userWebsiteSettings_path', trim($this->getData('langsci_userWebsiteSettings_path')));
		$plugin->updateSetting($contextId, 'langsci_userWebsiteSettings_publicProfile', trim($this->getData('langsci_userWebsiteSettings_publicProfile')));
		$plugin->updateSetting($contextId, 'langsci_userWebsiteSettings_email', trim($this->getData('langsci_userWebsiteSettings_email')));
		$plugin->updateSetting($contextId, 'langsci_userWebsiteSettings_hallOfFame', trim($this->getData('langsci_userWebsiteSettings_hallOfFame')));
		$plugin->updateSetting($contextId, 'xxxx', trim($this->getData('xxxx')));
	}
}
?>
