/*(function($) {

})(jQuery);*/

(function() {
   tinymce.create('tinymce.plugins.wpgeocode', {
      init : function(ed, url) {
         ed.addButton('wpgeocode', {
            title : 'geocity',
            image : url + '/../../images/icons/icon.png',
            onclick : function() {
               var id = prompt("Введите города через запятую", "");

               ed.execCommand('mceInsertContent', false, '[GeoCity in="' + id + '"]Текст[/GeoCity]');
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
   tinymce.PluginManager.add('wpgeocode', tinymce.plugins.wpgeocode);
})();