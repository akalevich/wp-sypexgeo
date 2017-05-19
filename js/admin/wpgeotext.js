/*(function($) {

})(jQuery);*/

(function() {
   tinymce.create('tinymce.plugins.wpgeotext', {
      init : function(ed, url) {
         ed.addButton('wpgeotext', {
            title : 'geotext',
            image : url + '/../../images/icons/icon-text.png',
            onclick : function() {
               var id = prompt("Введите города через запятую", "");

               ed.execCommand('mceInsertContent', false, '[geotext in="' + id + '" text=""]');
            }
         });
      },
      createControl : function(n, cm) {
         return null;
      },
      getInfo : function() {
         return {
            longname    : "WP geo targeting",
            author      : 'twin',
            version     : "1.0.0"
         };
      }
   });
   tinymce.PluginManager.add('wpgeotext', tinymce.plugins.wpgeotext);
})();