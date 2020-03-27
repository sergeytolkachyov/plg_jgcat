<?php
/*
*	This template for manual design. Write your own css rules.
* @version		1.0
* @package		JoomGallery Category With Description Content Plugin 'JoomPluCat'
* @copyright	Copyright (C) 2019 Sergey Tolkachyov
* @license		GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*
*
*/

?>

<div class="joomcat">
	<div class="joomcat_img_wrapper">
		<img class="jg_photo" src="<?php echo $imglink;?>" alt="<?php echo $catname;?>">
	</div>
	<div class="joomcat_cat_desc">
		<h5 class="joomcat_desc_header"><?php echo $catname;?></h5>
		<?php echo $catdesc;?>
		<div class="joomcat_btn_view_more_wrapper">
			<a class="joomcat_btn_view_more" href="<?php echo $catlink;?>"><?php echo JText::_("PLG_JGCAT_TMPL_VIEW_MORE_TEXT");?></a>
		</div>
	</div>
</div>