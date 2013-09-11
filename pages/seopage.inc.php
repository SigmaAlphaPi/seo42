<?php
$articleID = rex_request('article_id');
$clang = rex_request('clang');
$ctype = rex_request('ctype');

$hideExtendedSection = '';
$hideCanonicalUrl = '';
$enableNoPrefixCheckbox = '';
$enableTitlePreview = '';

// react on editContentOnly[] and rexseo42[seo_extended] but only for non admins
if (is_object($REX['USER']) && !$REX['USER']->isAdmin() && ($REX['USER']->hasPerm('editContentOnly[]') || !$REX['USER']->hasPerm('rexseo42[seo_extended]'))) {
	// hide extended section
	$hideExtendedSection = 'style="display: none;"';
}

// react on userdef_canonical_url option in settings
if (!$REX['ADDON']['rexseo42']['settings']['userdef_canonical_url']) {
	// hide userdef canonical url
	$hideCanonicalUrl = 'style="display: none;"';
}

// react on enable_no_prefix_checkbox option in settings
if (!$REX['ADDON']['rexseo42']['settings']['enable_no_prefix_checkbox']) {
	// hide no-prefix/suffix checkbox
	$enableNoPrefixCheckbox = 'style="display: none;"';
}

// react on enable_title_preview option in settings
if (!$REX['ADDON']['rexseo42']['settings']['enable_title_preview']) {
	$enableTitlePreview = 'style="display: none;"';
}

if (rex_post('saveseo', 'boolean')) {
	$sql = rex_sql::factory();

	$sql->setTable($REX['TABLE_PREFIX'] . "article");
	//$sql->debugsql = 1;
	$sql->setWhere("id=" . $articleID . " AND clang=" . $clang);

	//sanitize
	$title = rexseo42_utils::sanitizeString(rex_post('seo_title'));
	$description = rexseo42_utils::sanitizeString(rex_post('seo_description'));

	$keywords = str_replace(',', ', ', rex_post('seo_keywords')); // always have a whitespace char after comma 
	$keywords = strtolower(rexseo42_utils::sanitizeString($keywords)); // also keywords should be all lowercase
	$canonicalUrl = rexseo42_utils::sanitizeString(rex_post('seo_canonical_url'));

	// seo fields
	$sql->setValue('seo_title', $title);
	$sql->setValue('seo_description', $description);
	$sql->setValue('seo_keywords', $keywords);
	$sql->setValue('seo_canonical_url', $canonicalUrl);

	// ignore prefix
	$ignorePrefix = rex_post('seo_ignore_prefix');

	if (is_array($ignorePrefix)) {
		$sql->setValue('seo_ignore_prefix',  '1');
	} else {
		$sql->setValue('seo_ignore_prefix',  '');
	}

	// no index
	$noIndex = rex_post('seo_noindex');
	
	if (is_array($noIndex)) {
		$sql->setValue('seo_noindex',  '1');
	} else {
		$sql->setValue('seo_noindex',  '');
	}

	// update updatedate of article
	$sql->setValue('updatedate',  time());

	// do db update
	if ($sql->update()) {
		// info msg
		echo rex_info($I18N->msg('rexseo42_seopage_updated'));

		// delete cached article
		rex_generateArticle($articleID);
	} else {
		// err msg
		echo rex_warning($sql->getError());
	}
}

$sql = rex_sql::factory();
//$sql->debugsql = 1;
$seoData = $sql->getArray('SELECT * FROM '. $REX['TABLE_PREFIX'] .'article WHERE id=' . $articleID . ' AND clang=' . $clang);
$seoData = $seoData[0];

echo '
	<div class="rex-content-body" id="seo-page">
		<div class="rex-content-body-2">
			<div class="rex-form" id="rex-form-content-metamode">
				<form action="index.php" method="post" id="seo-form" name="seo-form">
					<input type="hidden" name="page" value="content" />
					<input type="hidden" name="article_id" value="' . $articleID . '" />
					<input type="hidden" name="mode" value="seo" />
					<input type="hidden" name="save" value="1" />
					<input type="hidden" name="clang" value="' . $clang . '" />
					<input type="hidden" name="ctype" value="' . $ctype . '" />

					<fieldset class="rex-form-col-1">
						<legend id="seo-default">' . $I18N->msg('rexseo42_seopage_main_section') . '</legend>
						<div class="rex-form-wrapper">
							<div class="rex-form-row prefix">
								<p class="rex-form-text">
									<label for="seo_title">' . $I18N->msg('rexseo42_seopage_title') . '</label>
									<input type="text" value="' . $seoData['seo_title'] . '" name="seo_title" id="seo_title" class="rex-form-text seo-title" />
									<span class="rex-form-notice" ' . $enableTitlePreview . '>
										<span id="title-preview">&nbsp;</span>
									</span>
								</p>
								<p id="show-prefix" ' . $enableNoPrefixCheckbox . '>
									<label for="prefix-check"><input id="prefix-check" type="checkbox" value="';
									if ($seoData['seo_ignore_prefix'] == '1') { echo "1"; $check = 'checked = "checked"'; } else { echo ""; $check = ""; }
									if (rexseo42::isStartArticle()) { $checkboxTitle = $I18N->msg('rexseo42_seopage_title_noprefix'); } else { $checkboxTitle = $I18N->msg('rexseo42_seopage_title_nosufix'); } 
									echo '" name="seo_ignore_prefix[]" class="rex-form-checkbox" ' . $check . ' /> <span>' . $checkboxTitle . '</span></label>
								</p>
							</div>
							<div class="rex-form-row">
								<p class="rex-form-textarea">
									<label for="seo_description">' . $I18N->msg('rexseo42_seopage_description') . '</label>
									<textarea name="seo_description" id="seo_description" class="rex-form-textarea">' . $seoData['seo_description'] . '</textarea>
									<span class="rex-form-notice right">
										<span id="description-charcount">0</span>/156 ' . $I18N->msg('rexseo42_seopage_chars') . '
									</span>
								</p>
							</div>
							<div class="rex-form-row">
								<p class="rex-form-textarea">
									<label for="seo_keywords">' . $I18N->msg('rexseo42_seopage_keywords') . '</label>
									<textarea name="seo_keywords" id="seo_keywords" rows="2" cols="50" class="rex-form-textarea">' . $seoData['seo_keywords'] . '</textarea>
									<span class="rex-form-notice right">
										<span id="keywords-wordcount">0</span>/5 ' . $I18N->msg('rexseo42_seopage_words') . '
									</span>
								</p>
							</div>
						</div>
					</fieldset>
					<fieldset ' . $hideExtendedSection . ' class="rex-form-col-1">
						<legend>' . $I18N->msg('rexseo42_seopage_extended_section') . '</legend>
						<div class="rex-form-wrapper">
							<div class="rex-form-row" ' . $hideCanonicalUrl . '>
								<p class="rex-form-text">
									<label for="canonical-url">' . $I18N->msg('rexseo42_seopage_canonical_url') . '</label>
									<input type="text" value="' . $seoData['seo_canonical_url'] . '" name="seo_canonical_url" id="canonical-url" class="rex-form-text" />
								</p>
							</div>

							<div class="rex-form-row">
								<p class="rex-form-col-a rex-form-checkbox more-padding">
									<input type="checkbox" id="seo_noindex" value="';
									if ($seoData['seo_noindex'] == '1') { echo "1"; $check = 'checked = "checked"'; } else { echo ""; $check = ""; }
									echo '" name="seo_noindex[]" class="rex-form-checkbox" ' . $check . ' />
									<label for="seo_noindex">' . $I18N->msg('rexseo42_seopage_noindex') . '</label>
								</p>
							</div>
						</div>
					</fieldset>
					<fieldset class="rex-form-col-1">
						<div class="rex-form-wrapper">
							<div class="rex-form-row">
								<p class="rex-form-col-a rex-form-submit">
									<input type="submit" value="' . $I18N->msg('rexseo42_seopage_button_text') . '" name="saveseo" class="rex-form-submit" />
									<br/><br/>
								</p>
							</div>
							<div class="rex-clearer"></div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>';
?>

<style type="text/css">
#seo-page #show-prefix label {
	display: block;
	float: left;
	padding-right: 10px;
	white-space: nowrap;
	/*padding-left: 22px;
	text-indent: -22px;*/
}

#seo-page #show-prefix label span {
	vertical-align: middle;
	padding-left: 2px;
}

#seo-page #show-prefix label input[type="checkbox"] {
	vertical-align: middle;
}

#seo-page #seo_description,
#seo-page #seo_keywords {
	height: 50px;
}

#seo-page .more-padding {
	padding-top: 3px;
}

#seo-page  #title-preview {
    display: block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    width: 399px; /* 512px; */
	font-family: arial,sans-serif;
	font-size: normal; /* medium */
	line-height: 16px;
	font-weight: normal;
}

#seo-page #title-preview span {
	font-weight: bold;
}

#seo-page div.rex-form div.rex-form-row label, div.rex-form div.rex-form-row p.rex-form-label {
    width: 155px;
}

#seo-page div.rex-form div.rex-form-row p span.rex-form-notice {
	margin-left: 165px;
	margin-top: 4px;
	font-size: 100%;
}

#seo-page div.rex-form div.rex-form-row p {
    margin-bottom: 2px;
}

div.rex-form div.rex-form-row.prefix p {
	width: auto;
}

#seo-page #show-prefix {
	float: right;
	width: auto;
}

#seo-page div#rex-form-content-metamode fieldset.rex-form-col-1 div.rex-form-row div.rex-form-checkboxes-wrapper, div#rex-form-content-metamode fieldset.rex-form-col-1 div.rex-form-row div.rex-form-radios-wrapper, div#rex-form-content-metamode fieldset.rex-form-col-1 div.rex-form-row p.rex-form-label-right label, div#rex-form-content-metamode fieldset.rex-form-col-1 div.rex-form-row p.rex-form-read span, div#rex-form-content-metamode fieldset.rex-form-col-1 div.rex-form-row p.rex-form-text input, div#rex-form-content-metamode fieldset.rex-form-col-1 div.rex-form-row p.rex-form-select select, div#rex-form-content-metamode fieldset.rex-form-col-1 div.rex-form-row p textarea {
    width: 390px;
}

#seo-page div.rex-form div.rex-form-row p span.rex-form-notice.right {
    float: right;
    margin-left: 0;
    margin-right: 184px;
}

#seo-page div.rex-form div.rex-form-row p input.rex-form-submit {
	margin-top: 8px;
    margin-left: 165px;
}
</style>

<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#seo_title').keyup(function() {
		updateTitlePreview();
	});

	jQuery('#prefix-check').change(function() {
		updateTitlePreview();
	});

	jQuery('#seo_description').keyup(function() {
		updateDescriptionCount();
	});

	jQuery('#seo_keywords').keypress(function() {
		updateKeywordsCount();
	});

	updateTitlePreview();
	updateDescriptionCount();
	updateKeywordsCount();

	jQuery('#seo-form').submit(function() {
		var pat = /^https?:\/\//i;
		var canonicalUrl = jQuery('#canonical-url').val();

		if (canonicalUrl === '' || pat.test(canonicalUrl)) {
			return true;
		}

		alert('<?php echo $I18N->msg('rexseo42_seopage_canonical_alert'); ?>');

		return false;
	});
});

function updateTitlePreview() {
	var titlePrefix = '<?php echo rexseo42::getWebsiteName(); ?>';
	var articleName = '<?php echo rexseo42::getArticleName(); ?>';
	var customTitle = jQuery('#seo_title').val();
	var titleDelimiter = '<?php echo rexseo42::getTitleDelimiter(); ?>';
	var hasPrefix = !jQuery('#prefix-check').is(':checked');
	var isStartPage = <?php if (rexseo42::isStartArticle()) { echo 'true'; } else { echo 'false'; } ?>;
	var curTitle = '';
	var curTitlePart = '';

	if (customTitle !== '') {
		curTitlePart = customTitle;
	} else {
		curTitlePart = articleName;
	}

	if (!hasPrefix) {
		curTitle = curTitlePart;
	} else {
		if (isStartPage) {
			curTitle = titlePrefix + titleDelimiter + curTitlePart;
		} else {
			curTitle = curTitlePart + titleDelimiter + titlePrefix;
		}
	}

	jQuery('#title-preview').html(curTitle);
}

function updateDescriptionCount() {
	jQuery('#description-charcount').html(jQuery('#seo_description').val().length);
}
function ltrim(str, chr) {
	var rgxtrim = (!chr) ? new RegExp('^\\s+') : new RegExp('^' + chr + '+');
	return str.replace(rgxtrim, '');
}

function updateKeywordsCount() {
	var curKeywords = jQuery('#seo_keywords').val().replace(' ', '');
	var keywordCount = 0; 

	if (curKeywords !== '') {
		keywordCount = curKeywords.split(',').length;
	}

	jQuery('#keywords-wordcount').html(keywordCount);
}
</script>


