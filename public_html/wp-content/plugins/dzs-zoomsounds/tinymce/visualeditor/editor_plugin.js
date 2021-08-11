
(function() {
	tinymce.create('tinymce.plugins.ve_zoomsounds_player', {

		init : function(ed, url) {
			var t = this;

			t.url = url;



			//replace shortcode before editor content set
			ed.onBeforeSetContent.add(function(ed, o) {

				o.content = t.replace_wsi(o.content);
			});

			ed.onExecCommand.add(function(ed, cmd) {

			    if (cmd ==='mceInsertContent'){
					tinyMCE.activeEditor.setContent( t.replace_wsi(tinyMCE.activeEditor.getContent()) );
				}
			});
			ed.onPostProcess.add(function(ed, o) {
				if (o.get){
					o.content = t.replace_sho(o.content);
                }
			});
		},

		replace_wsi : function(co) {

            if(co!=undefined){
                return co.replace(/\[zoomsounds_player([^\]]*)\]/g, function(a,b){


                    var aux = '<p class="dzsap-auxp">&nbsp;</p><div class=\'ve_zoomsounds_player mceItem mceNonEditable\' data-shortcodecontent=\'zoomsounds_player'+tinymce.DOM.encode(b)+'\' ';

                    if(getAttr(b,'thumb') && getAttr(b,'thumb')!=''){

                        aux+='style="background-size: cover; background-image:url('+getAttr(b,'thumb')+');"';
                    }
                    aux+='><span style="position:relative;">[ zoomsounds_player'+jQuery('<div/>').text(b).html()+' ]</span>';
                    aux+='</div><p class="dzsap-auxp">&nbsp;</p>';



                    return aux;
                });
            }



            return co;
		},



		replace_sho : function(co) {

            co = co.replace(/<p class="dzsap-auxp">.*?<\/p>/g, '');

			co = co.replace(/<div.*?class="ve_zoomsounds_player.*?<\/div>/g, function(a,b){


                var aux = (getAttr(a, 'data-shortcodecontent'));

                aux = aux.replace(/&amp;/g, '');
                aux = aux.replace(/&quot;/g, '"');

                return '['+aux+']';
            });

            return co;
		}

	});

    //--better idea to have image and :before and / :after tags - with editor buttons
	tinymce.PluginManager.add('ve_zoomsounds_player', tinymce.plugins.ve_zoomsounds_player);
})();
function getAttr(s, n) {
    n = new RegExp(n + '=[\"|\'](.*?)[\"|\']', 'g').exec(s);
    if(n[1]){
        return n[1];
    }else{
        return null;
    }
};