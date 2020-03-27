# plg_jgcat
Joomla content plugin that allows you to insert categories of JoomGallery images with a description, image and backlink wherever needed.
- Inserts the category heading, description, sef-link, one image from the category into the article.
- Ability to specify itemid menu item for correct sef links
- Ability to use a fragment of the JoomGallery category description.
- Ability to create your own plugin output templates.
![Screenshot JGCat plugin](https://web-tolk.ru/images/swjprojects/projects/3/ru-RU/gallery/gVr0yb9DCGD.jpg "Screenshot JGCat plugin")
Insert a line into the material
**{jgcat catid=... imgid=... itemid=... tmpl=...}**

- **catid**
    -id of the JoomGallery category. Required.
- **itemid**
    - Id of the JoomGallery component menu item. Optional parameter. If you have only one menu item for JoomGallery, then itemid is not necessary. If there are several menu items, then you need to specify itemid in the shortcode of the plugin in order to get the correct SEF link.
-**imgid**
    -id of JoomGallery image. Optional parameter. This can be the id of any image, not only from the displayed category.
    -If `imgid` is not specified, then a thumbnail from the category settings is displayed.
    - If the thumbnail is not specified in the category settings, then the thumbnail of the first image of the category is displayed.
-**tmpl**
    - Optional parameter. Output template.
    - The templates are located in the plugins folder plugins/content/jgcat/tmpl/
    _Available Templates_
        - default - for self-decoration through CSS
        - Bootstrap 4
        - You can create your own template and place it in the folder with the rest of the templates.
- **Link text Details**
    -It changes either in the administrator/languages/en-GB/en-GB.plg_content_jgcat.ini language files, or through the redefinition of Joomla language constants. Language constant `PLG_JGCAT_TMPL_VIEW_MORE_TEXT`
- **Pay attantion**
    -A plugin can take from the category description not all the text, but enclosed in specific tags 
       ````html
       <ol> </ol>, <ul> </ul>, <span> </span>, <div> </div>.
        ````
