Key data
============

- name of the plugin: User Website Settings Plugin
- author: Carola Fanselow
- current version: 1.0
- tested on OMP version: 1.2.0
- github link: https://github.com/langsci/userWebsiteSettings.git
- community plugin: no
- date: 2016/05/25

Description
============

This plugin lets each registered user of the langsci website set:
1. wether to be included in the hall of fame
2. wether to have a public profile
3. wether to display the email address in the public profile
In the settings of this plugin you can specify
1. path to the setting page
2. which of the 3 setting options the user gets
A link to the setting page is display in the OMP-profile.
 
Implementation
================

Hooks
-----
- used hooks: 2

		LoadHandler
		TemplateManager::display

New pages
------
- new pages: 1

		path can be specified in the plugin settings

Templates
---------
- templates that replace other templates: 1

		profileModified.tpl replaces user/profile.tpl

- templates that are modified with template hooks: 0
- new/additional templates: 1

		userWebsiteSettings.tpl
		settings.tpl

Database access, server access
-----------------------------
- reading access to OMP tables: 1

		plugin_settings

- writing access to OMP tables: 1

		plugin_settings

- new tables: 1

		langsci_website_settings

- nonrecurring server access: yes

		creating table langsci_website_settings during installation

- recurring server access: no
 
Classes, plugins, external software
-----------------------
- OMP classes used (php): 9
	
		GenericPlugin
		Handler
		DAO
		AjaxModal
		Form
		LinkAction
		JSONMessage
		TemplateManager
		FormValidatorPost

- OMP classes used (js, jqeury, ajax): 2

		AjaxFormHandler
		TabHandler

- necessary plugins: 0
- optional plugins: 2

	Public Profiles Plugin (uses website settings)
	Hall of Fame Plugin (uses website settings)

- use of external software: no
- file upload: no
 
Metrics
--------
- number of files: 14
- lines of code: 973

Settings
--------
- settings: 4

		path to the user website settings page
		whether to be included in the hall of fame (checkbox)
		whether to have a public profile (checkbox)
		whether to display the email address in the public profile

Plugin category
----------
- plugin category: generic

Other
=============
- does using the plugin require special (background)-knowledge?: yes, the relationshipt between this plugin and the Public Profiles Plugin and the Hall of Fame Plugin
- access restrictions: access for all registered and logged in users
- adds css: yes




