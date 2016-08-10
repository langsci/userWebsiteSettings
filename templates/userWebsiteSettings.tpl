{**
 * plugins/generic/userWebsiteSettings/templates/userWebsiteSettings.tpl
 *
 * Copyright (c) 2016 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * main template for the User Website Settings plugin. 
 *}

{strip}
	{if !$contentOnly}
		{include file="common/header.tpl"}
	{/if}
{/strip}

<link rel="stylesheet" href="{$baseUrl}/plugins/generic/userWebsiteSettings/css/userWebsiteSettings.css" type="text/css" />

<div id="langsciUserWebsiteSettings">

	<p class="intro">{translate key="plugins.generic.userWebsiteSettings.intro"}</p>

	<form class="pkp_form" method="post" action="viewUserWebsiteSettings">

	<div class="section">

		<ul class="checkbox_and_radiobutton">
			{if $PublicProfile}
				<li>
					<input id="checkboxPublicProfile" name="checkboxPublicProfile" type="checkbox"></input>
					<label>
						{translate key="plugins.generic.userWebsiteSettings.displayPublicProfile1"}
						{if $pathPublicProfiles}<a href="{$pressPath}/{$pathPublicProfiles}/{$userId}">{/if}
						{translate key="plugins.generic.userWebsiteSettings.displayPublicProfile2"}
						{if $pathPublicProfiles}</a>{/if}
						{translate key="plugins.generic.userWebsiteSettings.displayPublicProfile3"}
				</label>
				</li>
			{/if}
			{if $Email}
				<li>
					<input id="checkboxEmail" name="checkboxEmail" type="checkbox"></input>
					<label>
						{translate key="plugins.generic.userWebsiteSettings.displayEmailAddress"}
					</label>
				</li>
			{/if}
			{if $HallOfFame}
				<li>
					<input id="checkboxHallOfFame" name="checkboxHallOfFame" type="checkbox"></input>
					<label>
						{translate key="plugins.generic.userWebsiteSettings.includeInHallOfFame1"}
						{if $pathHallOfFame}<a href="{$pressPath}/{$pathHallOfFame}">{/if}
						{translate key="plugins.generic.userWebsiteSettings.includeInHallOfFame2"}
						{if $pathHallOfFame}</a>{/if}
					</label>
				</li>
			{/if}     
    	</ul>
	</div>

	<div class="section formButtons ">
		<div>
			<a id="cancelFormButton" class="cancelFormButton" href="{$pressPath}">Cancel</a>
		</div>

		<div>
			<button id="buttonSaveWebsiteSettings" name="buttonSaveWebsiteSettings" type="submit"
					class="submitFormButton button ui-button ui-widget
					ui-state-default ui-corner-all ui-button-text-only" role="button" >
					<span class="ui-button-text">Save changes</span>
			</button>
		</div>
	</div>

	</form> 

</div>

<script language="JavaScript" type="text/javascript">
 
	var issetCheckboxPublicProfile = "{$issetCheckboxPublicProfile}";
	var issetCheckboxEmail = "{$issetCheckboxEmail}";
	var issetCheckboxHallOfFame = "{$issetCheckboxHallOfFame}";

	{literal}

		document.getElementById("checkboxPublicProfile").checked = issetCheckboxPublicProfile;
		document.getElementById("checkboxEmail").checked = issetCheckboxEmail;
		document.getElementById("checkboxHallOfFame").checked = issetCheckboxHallOfFame;

	{/literal}

</script>


{strip}
		{include file="common/footer.tpl"}
{/strip}

