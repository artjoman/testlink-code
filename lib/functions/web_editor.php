<?php
/**
 * TestLink Open Source Project - http://testlink.sourceforge.net/ 
 * This script is distributed under the GNU General Public License 2 or later.
 *  
 * Support for web editor switching
 * 
 * @filesource	web_editor.php
 * @package     TestLink
 * @copyright 	2005-2019, TestLink community 
 * @link 		    http://www.teamst.org/index.php
 * @uses 		    config.inc.php
 * @uses 		    common.php
 *
 **/

require_once(dirname(__FILE__)."/../../config.inc.php");
require_once("common.php");


/**
 * require_web_editor
 *
 */
function require_web_editor($editor_type=null) {
  $webEditorType = $editor_type;
  if(is_null($editor_type)) {
    $cfg = getWebEditorCfg();
    $webEditorType = $cfg['type'];
	}
	  
	switch($webEditorType) {
		case 'ckeditor':
    	return "ckeditor.class.php";
    break;
   
    case 'tinymce':
    	return "tinymce.class.php";
    break;

    case 'none':
    default:
    	return "no_editor.class.php";
    break;
  }
}

/**
 *
 */
function web_editor($html_input_id,$base_path,$editor_cfg=null) {
  $webEditorCfg = is_null($editor_cfg) ? getWebEditorCfg() : $editor_cfg;

	switch($webEditorCfg['type']) {
		case 'ckeditor':
			// CKEditor Language according to chosen Testlink language
			$locale = (isset($_SESSION['locale'])) ? $_SESSION['locale'] : 'en_GB';
			
			switch($locale) {
				case 'cs_CZ': $ckeditorLang = 'cs'; break;
				case 'de_DE': $ckeditorLang = 'de'; break;
				case 'en_GB': $ckeditorLang = 'en-gb'; break;
				case 'en_US': $ckeditorLang = 'en'; break;
				case 'es_AR': $ckeditorLang = 'es'; break;
				case 'es_ES': $ckeditorLang = 'es'; break;
				case 'fi_FI': $ckeditorLang = 'fi'; break;
				case 'fr_FR': $ckeditorLang = 'fr'; break;
				case 'id_ID': $ckeditorLang = 'en-gb'; break;
				case 'it_IT': $ckeditorLang = 'it'; break;
				case 'ja_JP': $ckeditorLang = 'ja'; break;
				case 'ko_KR': $ckeditorLang = 'ko'; break;
				case 'nl_NL': $ckeditorLang = 'nl'; break;
				case 'pl_PL': $ckeditorLang = 'pl'; break;
				case 'pt_BR': $ckeditorLang = 'pt-br'; break;
				case 'ru_RU': $ckeditorLang = 'ru'; break;
				case 'zh_CN': $ckeditorLang = 'zh-cn'; break;
				default: $ckeditorLang = 'en-gb'; break;
			}
			
			$of = new ckeditorInterface($html_input_id) ;
			$of->Editor->basePath = $base_path . 'third_party/ckeditor/';
			$of->Editor->config['customConfig'] = $base_path . $webEditorCfg['configFile'];
			$of->Editor->config['toolbar'] = $webEditorCfg['toolbar'];
			$of->Editor->config['language'] = $ckeditorLang;
			if (isset($webEditorCfg['height'])) {
				$of->Editor->config['height'] = $webEditorCfg['height'];
			}
			
			if (isset($webEditorCfg['width'])) {
				$of->Editor->config['width'] = $webEditorCfg['width'];
			}	
		break;
		    
		case 'tinymce':
			$of = new tinymce($html_input_id) ;
			if (isset($webEditorCfg['rows'])) {
				$of->rows = $webEditorCfg['rows'];
			}

			if (isset($webEditorCfg['cols'])) {
				$of->cols = $webEditorCfg['cols'];
			}
		break;
		    
		case 'none':
		default:
			$of = new no_editor($html_input_id) ;
			if (isset($webEditorCfg['rows'])) {
				$of->rows = $webEditorCfg['rows'];
			}
			
			if (isset($webEditorCfg['cols'])) {
				$of->cols = $webEditorCfg['cols'];
			}	
		break;
	}
    
  return $of;
}