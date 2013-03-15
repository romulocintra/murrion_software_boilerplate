function create_menu(basepath)
{
	var base = (basepath == 'null') ? '' : basepath;

	document.write(
		'<table cellpadding="0" cellspaceing="0" border="0" style="width:98%"><tr>' +
		'<td class="td" valign="top">' +

		'<ul>' +
		'<li><a href="'+base+'index.html">User Guide Home</a></li>' +
		'<li><a href="'+base+'toc.html">Table of Contents Page</a></li>' +
		'</ul>' +
		
		'</td><td class="td_sep" valign="top">' +

		'</td><td class="td_sep" valign="top">' +

		'<h3>Class Reference</h3>' +
		'<ul>' +
		'<li><a href="'+base+'libraries/email.html">Email Class</a></li>' +
		'<li><a href="'+base+'libraries/form_validation.html">Form Validation Class</a></li>' +
		'</ul>' +

		'</td><td class="td_sep" valign="top">' +

		'<h3>Helper Reference</h3>' +
		'<ul>' +
		
		'<li><a href="'+base+'helpers/array_helper.html">Array Helper</a></li>' +
		'<li><a href="'+base+'helpers/database_helper.html">Database Helper</a></li>' +
		'<li><a href="'+base+'helpers/date_helper.html">Date Helper</a></li>' +
		'<li><a href="'+base+'helpers/export_helper.html">Export Helper</a></li>' +
		'<li><a href="'+base+'helpers/form_helper.html">Form Helper</a></li>' +
		'<li><a href="'+base+'helpers/map_helper.html">Map Helper</a></li>' +
		'<li><a href="'+base+'helpers/number_helper.html">Number Helper</a></li>' +
		'<li><a href="'+base+'helpers/path_helper.html">Path Helper</a></li>' +
		'<li><a href="'+base+'helpers/string_helper.html">String Helper</a></li>' +

		'</ul>' +

		'</td></tr></table>');
}