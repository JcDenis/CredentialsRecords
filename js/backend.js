/*global $, dotclear */
'use strict';

Object.assign(dotclear.msg, dotclear.getData('CredentialsRecords_msg'));

$(function(){
	$('.checkboxes-helpers').each(function(){
		dotclear.checkboxesHelpers(this)
	});
	$('input[name="selected_credentials"]').click(function(){
		return window.confirm(dotclear.msg.confirm_delete_selected_credential)
	});
  	dotclear.condSubmit('#CredentialsRecords_form  td input[type=checkbox]', '#CredentialsRecords_form #selected_credentials');
})