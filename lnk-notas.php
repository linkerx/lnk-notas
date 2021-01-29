<?php
/**
 * Plugin Name: LNK  Encuentro / EnTV
 * Plugin URI: https://github.com/linkerx
 * Description: Configuracion de articulos.
 * Version: 0.1
 * Author: Diego Martinez Diaz
 * Author URI: https://github.com/linkerx
 * License: GPLv3
 */

/**
 * Input de volanta
 */
function lnk_notas_volanta(){
	global $post;
        if($post->post_type == 'post'){

            $id = $post->ID;

            $volanta = get_post_meta($id,'lnk_notas_volanta',true);

            print "<div id='lnk_notas_volanta_container' class='postbox-container'>";
            print "<label class='inline-label' for='lnk_notas_volanta_input'>Volanta:</label>";
            print "<input class='title-like' type='text' name='lnk_notas_volanta_input' id='lnk_notas_volanta_input' size='80' ";
            print "value='".$volanta."' />";
            print "</div>";
            print "<div style='clear:both;'></div>";

        }
}
add_action('edit_form_after_title','lnk_notas_volanta');

function lnk_notas_titulo_contenido() {
	global $post;
    if($post->post_type == 'post'){
	print "<h2 style='margin-bottom:0;'>Contenido Articulo:</h2>";
    }
}
add_action('edit_form_after_title','lnk_notas_titulo_contenido');

/**
 * Declaracion de meta boxes
 */
function lnk_notas_meta_boxes() {
	global $post;
	if($post->post_type == 'post'){
		// add_meta_box('lnk_notas_sitio_vista',"Sitios Vista", 'lnk_notas_sitio_vista_meta_box', null, 'side', 'core');
		add_meta_box('lnk_notas_audio',"Audio", 'lnk_notas_audio_meta_box', null, 'normal', 'core');
		add_meta_box('lnk_notas_video',"Video Youtube", 'lnk_notas_video_meta_box', null, 'normal', 'core');
		add_meta_box('lnk_notas_related',"Relacionados", 'lnk_notas_related_meta_box', null, 'normal', 'core');
	}
}
add_action ('add_meta_boxes','lnk_notas_meta_boxes');

/**
 * Meta box sitio vista
 */
function lnk_notas_sitio_vista_meta_box(){
	global $post;
	$id = $post->ID;
  $entv = get_post_meta($id,'lnk_notas_sitio_vista_entv',true);
  $encuentro = get_post_meta($id,'lnk_notas_sitio_vista_encuentro',true);

	print "<div id='lnk_notas_audio_container'>";

  print "<input type='checkbox' name='lnk_notas_sitio_vista_entv_check' ";
  if($entv == 'on'){
    print "checked";
  }
  print " /> EnTV";

  print "<br />";

  print "<input type='checkbox' name='lnk_notas_sitio_vista_encuentro_check' ";
  if($encuentro == 'on'){
    print "checked";
  }
  print " /> Encuentro";

	print "</div>";
	print "<div style='clear:both;'></div>";
}

/**
 * Meta box audio
 */
function lnk_notas_audio_meta_box(){
	global $post;
	$id = $post->ID;
	$url = get_post_meta($id,'lnk_notas_audio',true);
	$texto1 = get_post_meta($id,'lnk_notas_audio_texto1',true);
	$texto2 = get_post_meta($id,'lnk_notas_audio_texto2',true);

	print "<div id='lnk_notas_audio_container'>";
	print "<label>Audio:</label>&nbsp;&nbsp;";
	print "<input type='text' name='lnk_notas_audio_input' id='lnk_notas_audio_input' value='".$url."'/>&nbsp;&nbsp;";	
	print "<label>Texto1:</label>&nbsp;&nbsp;";
	print "<input type='text' name='lnk_notas_audio_texto1_input' id='lnk_notas_audio_texto1_input' value='".$texto1."'/>&nbsp;&nbsp;";	
	print "<label>Texto2:</label>&nbsp;&nbsp;";
	print "<input type='text' name='lnk_notas_audio_texto2_input' id='lnk_notas_audio_texto2_input' value='".$texto2."'/>&nbsp;&nbsp;";	
	print "</div>";
	print "<div style='clear:both;'></div>";
}

/**
 * Meta box video youtube
 */
function lnk_notas_video_meta_box(){
	global $post;
	$id = $post->ID;
	$url = get_post_meta($id,'lnk_notas_video',true);

	print "<div id='lnk_notas_video_container'>";
	print "<input type='text' name='lnk_notas_video_input' id='lnk_notas_video_input' value='".$url."'/>";
	print "</div>";
	print "<div style='clear:both;'></div>";
}


function lnk_notas_update_post_meta($id) {
    global $wpdb,$post_type;
    if($post_type == 'post'){
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return $id;
	if (defined('DOING_AJAX') && DOING_AJAX)
            return $id;

  update_post_meta($id,'lnk_notas_volanta',$_POST['lnk_notas_volanta_input']);
  update_post_meta($id,'lnk_notas_sitio_vista_entv',$_POST['lnk_notas_sitio_vista_entv_check']);
  update_post_meta($id,'lnk_notas_sitio_vista_encuentro',$_POST['lnk_notas_sitio_vista_encuentro_check']);
  
  update_post_meta($id,'lnk_notas_audio',$_POST['lnk_notas_audio_input']);
  update_post_meta($id,'lnk_notas_audio_texto1',$_POST['lnk_notas_audio_texto1_input']);
  update_post_meta($id,'lnk_notas_audio_texto2',$_POST['lnk_notas_audio_texto2_input']);

  update_post_meta($id,'lnk_notas_video',$_POST['lnk_notas_video_input']);

  $aPost = get_post($id);
	$idParent = $aPost->post_parent;
  if($idParent == 0)
    $idParent = $id;

	$relatedJson = get_post_meta($idParent,'lnk_notas_related_temp',true);
	$relatedOldJson = get_post_meta($idParent,'lnk_notas_related',true);

  $arrRelated = json_decode($relatedJson,true);
  if(!is_array($arrRelated))
      $arrRelated = array();
  $arrRelatedOld = json_decode($relatedOldJson,true);
  if(!is_array($arrRelatedOld))
      $arrRelatedOld = array();

	//recorro de los anteriores los que ahora ya no estan para eliminar las relaciones bi-direccionales
	foreach($arrRelatedOld as $keyRelatedOld => $relatedOld)
        {
            if(!in_array($relatedOld,$arrRelated))
            {
                $arrRelatedOtro = json_decode(get_post_meta($relatedOld,'lnk_notas_related',true),true);
                if(!is_array($arrRelatedOtro))
                        $arrRelatedOtro = array();

                foreach($arrRelatedOtro as $keyRelatedOtro => $idRelatedOtro)
                {
                        if($idRelatedOtro == $idParent)
                                unset($arrRelatedOtro[$keyRelatedOtro]);
                }

                $arrRelatedOtro = array_values(array_filter($arrRelatedOtro));
                update_post_meta($relatedOld,'lnk_notas_related',json_encode($arrRelatedOtro));
            }
        }

        //recorro de los nuevos los agregados para hacer la relacion bi-direccional
        foreach($arrRelated as $keyRelated => $related)
        {
                if(!in_array($related,$arrRelatedOld))
                {
                        //print "hay uno nuevo ".$related." <br>";
                        //print "agrego este a sus relacionados<br>";

                        $arrRelatedOtro = json_decode(get_post_meta($related,'lnk_notas_related',true),true);
                        if(!is_array($arrRelatedOtro))
                                $arrRelatedOtro = array();

                        if(!in_array($idParent,$arrRelatedOtro))
                                $arrRelatedOtro[] = $idParent;
                        update_post_meta($related,'lnk_notas_related',json_encode($arrRelatedOtro));
                }
        }

        update_post_meta($idParent,'lnk_notas_related',$relatedJson);
        delete_post_meta($idParent,'lnk_notas_related_temp');

    }
}

add_action('save_post','lnk_notas_update_post_meta');

add_action('wp_ajax_lnk_notas_search_related', 'lnk_notas_search_related_callback');
function lnk_notas_search_related_callback() {
	echo lnk_notas_search_related();
	die(); // this is required to return a proper result
}

add_action('wp_ajax_lnk_notas_related_add', 'lnk_notas_related_add_callback');
function lnk_notas_related_add_callback() {
	echo lnk_notas_related_add();
	die(); // this is required to return a proper result
}

add_action('wp_ajax_lnk_notas_related_remove', 'lnk_notas_related_remove_callback');
function lnk_notas_related_remove_callback() {
	echo lnk_notas_related_remove();
	die(); // this is required to return a proper result
}

/**
 * Busca los resultados del autocomplete
 */
function lnk_notas_search_related(){
	$autocompleteQuery = new WP_Query("s={$_POST['term']}");
	$posts = $autocompleteQuery->get_posts();
	$postRelated = json_decode(get_post_meta($_POST['idPost'],'lnk_notas_related_temp',true),true);
	if(!is_array($postRelated))
		$postRelated = array();

	$jsonPosts = array();
	if($autocompleteQuery)
	foreach($posts as $keyPost => $aPost)
	{
		if(!in_array($aPost->ID,$postRelated))
		{
			$postArray['item'] = $aPost->ID;
			$postArray['label'] = $aPost->post_title;
			$postArray['title'] = $aPost->post_title;
			$postArray['date'] = date('d/m/Y',strtotime($aPost->post_date));
			$postArray['img_del'] = get_template_directory_uri()."/img/iconos/delete.png";

			$primero = true;
			$cats = '';
			foreach((get_the_category($aPost->ID)) as $category)
			{
	    		if(!$primero)
	    			$cats.= ", ";
				$cats.= $category->cat_name;
			}

			$postArray['category'] = $cats;

			$infoThumb = wp_get_attachment_image_src(get_post_thumbnail_id($aPost->ID));
			$postArray['img'] = $infoThumb[0];
			$jsonPosts[] = $postArray;
		}
	}
	$jsonPosts = json_encode($jsonPosts);
	print $jsonPosts;
}

function lnk_notas_related_add(){
	$postRelated = json_decode(get_post_meta($_POST['idPost'],'lnk_notas_related_temp',true),true);
	if(!is_array($postRelated))
		$postRelated = array();
	if(!in_array($_POST['idRelated'],$postRelated))
		$postRelated[] = $_POST['idRelated'];
	update_post_meta($_POST['idPost'],'lnk_notas_related_temp',json_encode($postRelated));
}

function lnk_notas_related_remove(){

	$postRelated = json_decode(get_post_meta($_POST['idPost'],'lnk_notas_related_temp',true),true);
	foreach($postRelated as $keyRelated => $idRelated)
	{
		if($idRelated == $_POST['idRelated'])
			unset($postRelated[$keyRelated]);
	}

	$postRelated = array_values(array_filter($postRelated));
	update_post_meta($_POST['idPost'],'lnk_notas_related_temp',json_encode($postRelated));
}

/**
 * Dibuja la caja para articulos relacionados
 */

function lnk_notas_related_meta_box(){
	global $post;
	$id = $post->ID;
	$relatedJson = get_post_meta($id,'lnk_notas_related',true);
	update_post_meta($id,'lnk_notas_related_temp',$relatedJson);
	$postRelated = json_decode($relatedJson,true);

	print "<div id='lnk_notas_related_input' style='float:left;'>";
		print "<input name='lnk_notas_related_input' size='50'/>";
		print "<span><a onclick='closeAutocomplete()'>Cerrar</a></span>";
		print "<input type='hidden' name='lnk_notas_post_id' value='".$id."'/>";
	print "</div>";
	print "<div id='lnk_notas_related_container' style='float:right;width:300px;'>";
	actualizarPostRelated($postRelated);
	print "</div>";
	print "<div style='clear:both;'></div>";
}

function actualizarPostRelated($postRelated){
	global $post;
	$id = $post->ID;

	if(is_array($postRelated))
	{
		foreach($postRelated as $idRelated)
		{
			$p = get_post($idRelated);
			$infoThumb = wp_get_attachment_image_src(get_post_thumbnail_id($p->ID));

			$primero = true;
			$cats = '';
			foreach((get_the_category($p)) as $category)
			{
				if(!$primero)
					$cats.= ", ";
				$cats.= $category->cat_name;
			}

			print "<div class='lnk_notas_related_item' idPost='".$p->ID."' style='padding:3px;margin:3px;background-color:#ffffff;border:1px solid #999999;'>";
			if($infoThumb[0] != null)
			{
				print "<img src='".$infoThumb[0]."' style='width:40px;height:30px;float:left;margin-right:5px;margin-top:4px;'/>";
			}
			print "<span style='font-size:9px;color:#095E83;'>".$cats."</span>";
			print "<span style='font-size:9px;color:#00B1DA;margin-left:5px;'>(".date('d/m/Y',strtotime($p->post_date)).")</span>";
			print "<a onclick='removeRelated({$idRelated},{$id})' style='float:right;'><img src='".get_template_directory_uri()."/img/iconos/delete.png' style='width:20px;cursor:pointer;'></a>";
			print "<div style='font-size:12px;color:#666666;'>".$p->post_title."</div>";
			print "</div>";
		}
	}
}

/**
 * AGREGO METADATA A REST API
 */

function register_api_lnk_post_audio() {
	register_rest_field('post', 'audio', array (
		'get_callback' => 'lnk_notas_get_audio',
		'update_callback' => null,
		'schema' => null,
	));
	register_rest_field('post', 'audio_texto1', array (
		'get_callback' => 'lnk_notas_get_audio_texto1',
		'update_callback' => null,
		'schema' => null,
	));
	register_rest_field('post', 'audio_texto2', array (
		'get_callback' => 'lnk_notas_get_audio_texto2',
		'update_callback' => null,
		'schema' => null,
	));
}
function lnk_notas_get_audio($post) {
	return get_post_meta($post['id'],'lnk_notas_audio' ,true );
}
add_action( 'rest_api_init', 'register_api_lnk_post_audio' );

function lnk_notas_get_audio_texto1($post) {
	return get_post_meta($post['id'],'lnk_notas_audio_texto1' ,true );
}
add_action( 'rest_api_init', 'register_api_lnk_post_audio_texto1' );

function lnk_notas_get_audio_texto2($post) {
	return get_post_meta($post['id'],'lnk_notas_audio_texto2' ,true );
}
add_action( 'rest_api_init', 'register_api_lnk_post_audio_texto2' );

function register_api_lnk_post_video() {
	register_rest_field('post', 'video', array (
		'get_callback' => 'lnk_notas_get_video',
		'update_callback' => null,
		'schema' => null,
	));
}
function lnk_notas_get_video($post) {
	return get_post_meta($post['id'],'lnk_notas_video' ,true );
}
add_action( 'rest_api_init', 'register_api_lnk_post_video' );