<?php

/*
*   JoomGallery Category With Description Content Plugin 'JGCat' 1.0
*   Copyright (C) 2019  Sergey Tolkachyov
*   Released under GNU GPL Public License
*   License: http://www.gnu.org/copyleft/gpl.html
*	Shows category with description or part of description filtered by specified tag from JoomGallery in content articles
*	catid - integer, category id 
*	itemid - integer, menu item id. If you have more then 1 JoomGallery item in menu that you need specify an itemid for true sef links
*	imgid - image id. You can show any image not only from this category
*	tmpl - output template for plugin
*	"jgcat" is used to avoid conflict with JoomGallery Joomplu plugin, that uses "joomplucat" for displaying category layout.
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgContentjgcat extends JPlugin
{

  public function __construct(&$subject, $params)
  {
    parent::__construct($subject, $params);
    $this->loadLanguage('', JPATH_ADMINISTRATOR);
  }

  public function onContentPrepare($context, $article, $params, $limitstart = 0)
	{
		
	  //Проверка есть ли строка замены в контенте
		if(strpos($article->text, 'jgcat') === false)
		{
		  return;
		}
		require_once JPATH_ROOT.'/components/com_joomgallery/interface.php';
		   // Create interface object
		$this->_interface = new JoomInterface();
		$this->_interface->getPageHeader();
		$defaultJoomGalleryItemId = $this->_interface->getJoomId();
	// Регулярка для поиска строчки замены
		$regex = '/{jgcat\s(.*?)}/i';
		preg_match_all($regex, $article->text, $settings_string); //Нашли строку
	for($i = 0; $i <= count($settings_string); $i++){
		$params = array('catid' => '',
						'imgid' => '',
						'itemid' => '',
						'tmpl' => 'default',
					);

		$output_replace_pattern = $settings_string["0"][$i];
		$settings = $settings_string["1"][$i];//Получили catid=68 tmpl=bootstrap4 ItemId=...
		$settings1 = explode(" ",$settings);//1-й уровень разбора, разобрали строку по пробелу. [0]=>string(8) "catid=68"  [1]=> string(15) "tmpl=bootstrap4"
			foreach($settings1 as $param){
				$param = explode("=", $param);//2-й уровень разбора, разобрали строку по знаку "равно"
				if (isset($params[$param[0]])) {
					$params[$param[0]] = $param[1];// Значения приравняли параметрам по умолчанию
				} else {
					$params[$param[0]] = "";
				}
			}
			$catid = $params["catid"];
			$imgid = $params["imgid"];
			$tmpl = $params["tmpl"];
			

		$category = $this->getCategoryInfo($catid);//Получили описание категории (description), название (name) и номер тумбочки (thumbnail) из настроек категории
		$catdesc = $category["description"];
		
		

		$catname = $category["name"];
		$catthumb = $category["thumbnail"];
		$tmpl = (!empty($params['tmpl'])) ? $params['tmpl'] : 'default';

		if ($imgid != ""){ //Если в строке вызова плагина указан imgid, то отображается фото по id
			$imglink = $this->_interface->getImageLink($imgid,"thumb");//Получаем url картинки
		} elseif ($catthumb != 0){ //Если в строке вызова плагина не указан imgid, то получаем id изображения, указанного в качестве миниатюры категории в настрйоках категории.
			$imglink = $this->_interface->getImageLink($catthumb,"thumb");//Получаем url картинки
		} else {
			$imgid = $this->getImageIDFromCategory($catid);//Иначе берем id первой картинки из категории
			$imgid = $imgid["id"];
			$imglink = $this->_interface->getImageLink($imgid,"thumb");//И по id получаем url картинки
		}
		
		// TODO = получить alt картинки. Сейчас в качестве alt в шаблоне название категории.
		// TODO = Сделать возможность смены тегов и отключения этой функции.
		
		if ($params['itemid'] != ""){ 
			$ItemId = "&Itemid=".$params["itemid"];			
			} else {
				$ItemId = $defaultJoomGalleryItemId;	//$this->_interface->getJoomId();
			}
		$catlink = "index.php?&option=com_joomgallery&Itemid=".$ItemId."&view=category&catid=".$catid;//ссылка на категорию
		$catlink = $this->_interface->route($catlink);//ссылка на категорию  JRoute::link('site', $catlink);
		unset($ItemId);
		if($category){
			$cleanCatDescOption = $this->params->get('cleanCatDesc', 0);
				if ($cleanCatDescOption == 1){
					$tag = $this->params->get('tag', 'ol');
					$catdesc = $this->cleanCatDesc($catdesc, $tag);
				}
		}
		//лезем в tmpl
		ob_start();
		require JPATH_SITE . DS . 'plugins' . DS . 'content' . DS . 'jgcat' . DS . 'tmpl' . DS . $tmpl . '.php';
		$html = ob_get_clean();
		$article->text = str_replace($output_replace_pattern, $html, $article->text);
		unset($params);
	}
	
} //onContentPrepare END
	

	function getCategoryInfo($id){// Берем название, описание и тумбочку категории JoomGallery
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('name', 'description', 'thumbnail')))
			->from($db->quoteName('#__joomgallery_catg'))
			->where($db->quoteName('cid') . ' = '. $db->quote($id));
		
			
        $db->setQuery($query);
		$category = $db->loadAssoc();
		return $category;
	}
	
	function getImageIDFromCategory($catid){//Берем id первого изображения из категории JoomGallery
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select($db->quoteName('id'))
			->from($db->quoteName('#__joomgallery'))
			->where($db->quoteName('catid') . ' = '. $db->quote($catid))
			->setLimit('1');
		
			
        $db->setQuery($query);
		$imgid = $db->loadAssoc();
		return $imgid;
	}

	function cleanCatDesc($text, $tag){
			// Берем часть описания категории JoomGallery между указанными открывающим и закрывающим тегом. 
			// В нашем случае отрезается все, что не относится к ol li
			// В дальнейшем открывающий и закрывающий тег вынесем в настройка плагина, а сюда впендюрим переменные
		$pattern = "/<".$tag."[^>]*>(.*)<\/".$tag.">/s";
			if ($tag == 'ol'){
				$tag = "<ol><li>";
			} elseif ($tag == 'ul'){
				$tag = "<ul><li>";
			} elseif ($tag == 'div'){
				$tag = "<div>";
			} elseif ($tag == 'span'){
				$tag = "<span>";
			}
		$text2 = strip_tags($text, $tag);
		
			if (preg_match($pattern, $text2, $match)) {
				$text = $match[1];
				
			if ($tag == 'ol'){
				$text = "<ol>".$text."</ol>";
			} elseif ($tag == 'ul'){
				$text = "<ul>".$text."</ul>";
			} elseif ($tag == 'div'){
				$text = "<div>".$text."</div>";
			} elseif ($tag == 'span'){
				$text = "<span>".$text."</span>";
			}	
				return $text;
			}
		}
}