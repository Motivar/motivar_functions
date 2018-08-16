<?php
if ( ! defined( 'ABSPATH' ) ) exit;

 function motivar_functions_slugify($text)
{
  // replace non letter or digits by -
  $text = preg_replace('~[^\pL\d]+~u', '-', $text);

  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  // trim
  $text = trim($text, '-');

  // remove duplicate -
  $text = preg_replace('~-+~', '-', $text);

  // lowercase
  $text = strtolower($text);

  if (empty($text)) {
    return 'n-a';
  }

  return $text;
}



/* change slug*/
function motivar_functions_update_post($id,$changes,$types, $flag)
{
  /*id, array('post_title'=>$title) */
  global $wpdb;
  switch ($flag) {
    case '1':
      $wpdb->update($wpdb->posts,$changes , array(
      'ID' => $id) ,$types , array( '%d'));
      break;
    case '2':
      $wpdb->update($wpdb->terms,$changes , array(
      'term_id' => $id) ,$types , array( '%d'));
      break;
    default:
      # code...
      break;
  }


}


function motivar_functions_greeklish($Name)
{
$greek   = array('α','ά','Ά','Α','β','Β','γ', 'Γ', 'δ','Δ','ε','έ','Ε','Έ','ζ','Ζ','η','ή','Η','θ','Θ','ι','ί','ϊ','ΐ','Ι','Ί', 'κ','Κ','λ','Λ','μ','Μ','ν','Ν','ξ','Ξ','ο','ό','Ο','Ό','π','Π','ρ','Ρ','σ','ς', 'Σ','τ','Τ','υ','ύ','Υ','Ύ','φ','Φ','χ','Χ','ψ','Ψ','ω','ώ','Ω','Ώ',' ',"'","'",',');
$english = array('a', 'a','A','A','b','B','g','G','d','D','e','e','E','E','z','Z','i','i','I','th','Th', 'i','i','i','i','I','I','k','K','l','L','m','M','n','N','x','X','o','o','O','O','p','P' ,'r','R','s','s','S','t','T','u','u','Y','Y','f','F','ch','Ch','ps','Ps','o','o','O','O','-','-','-','-');
$string  = str_replace($greek, $english, $Name);
return $string;
}



function motivar_all_countries()
{
 /*function which shows all countries and their prefix*/
return array(
array("gb","United Kingdom"),
array("nl","Netherlands"),
array("it","Italy"),
array("de","Germany"),
array("us","United States"),
array("fr","France"),
array("gr","Greece"),
array("dk","Denmark"),
array("af","Afghanistan"),
array("al","Albania"),
array("dz","Algeria"),
array("as","American Samoa"),
array("ad","Andorra"),
array("ao","Angola"),
array("ai","Anguilla"),
array("ag","Antigua and Barbuda"),
array("ar","Argentina"),
array("am","Armenia"),
array("aw","Aruba"),
array("au","Australia"),
array("at","Austria"),
array("az","Azerbaijan"),
array("bs","Bahamas"),
array("bh","Bahrain"),
array("bd","Bangladesh"),
array("bb","Barbados"),
array("by","Belarus"),
array("be","Belgium"),
array("bz","Belize"),
array("bj","Benin"),
array("bm","Bermuda"),
array("bt","Bhutan"),
array("bo","Bolivia"),
array("ba","Bosnia and Herzegovina"),
array("bw","Botswana"),
array("br","Brazil"),
array("bn","Brunei Darussalam"),
array("bg","Bulgaria"),
array("bf","Burkina Faso"),
array("bi","Burundi"),
array("kh","Cambodia"),
array("cm","Cameroon"),
array("ca","Canada"),
array("cv","Cape Verde"),
array("ky","Cayman Islands"),
array("cf","Central African Republic"),
array("td","Chad"),
array("cl","Chile"),
array("cn","China"),
array("co","Colombia"),
array("km","Comoros"),
array("cd","Congo (DRC)"),
array("cg","Congo (Republic)"),
array("ck","Cook Islands"),
array("cr","Costa Rica"),
array("ci","Côte d'Ivoire"),
array("hr","Croatia"),
array("cu","Cuba"),
array("cy","Cyprus"),
array("cz","Czech Republic"),
array("dj","Djibouti"),
array("dm","Dominica"),
array("do","Dominican Republic"),
array("ec","Ecuador"),
array("eg","Egypt"),
array("sv","El Salvador"),
array("gq","Equatorial Guinea"),
array("er","Eritrea"),
array("ee","Estonia"),
array("et","Ethiopia"),
array("fo","Faroe Islands"),
array("fj","Fiji"),
array("fi","Finland"),
array("pf","French Polynesia"),
array("ga","Gabon"),
array("gm","Gambia"),
array("ge","Georgia"),
array("gh","Ghana"),
array("gi","Gibraltar"),
array("gl","Greenland"),
array("gd","Grenada"),
array("gp","Guadeloupe"),
array("gu","Guam"),
array("gt","Guatemala"),
array("gg","Guernsey"),
array("gn","Guinea"),
array("gw","Guinea","Bissau"),
array("gy","Guyana"),
array("ht","Haiti"),
array("hn","Honduras"),
array("hk","Hong Kong"),
array("hu","Hungary"),
array("is","Iceland"),
array("in","India"),
array("id","Indonesia"),
array("ir","Iran"),
array("iq","Iraq"),
array("ie","Ireland"),
array("im","Isle of Man"),
array("il","Israel"),
array("jm","Jamaica"),
array("jp","Japan"),
array("je","Jersey"),
array("jo","Jordan"),
array("kz","Kazakhstan"),
array("ke","Kenya"),
array("ki","Kiribati"),
array("kw","Kuwait"),
array("kg","Kyrgyzstan"),
array("la","Laos"),
array("lv","Latvia"),
array("lb","Lebanon"),
array("ls","Lesotho"),
array("lr","Liberia"),
array("ly","Libya"),
array("li","Liechtenstein"),
array("lt","Lithuania"),
array("lu","Luxembourg"),
array("mo","Macao"),
array("mk","Macedonia"),
array("mg","Madagascar"),
array("mw","Malawi"),
array("my","Malaysia"),
array("mv","Maldives"),
array("ml","Mali"),
array("mt","Malta"),
array("mh","Marshall Islands"),
array("mq","Martinique"),
array("mr","Mauritania"),
array("mu","Mauritius"),
array("mx","Mexico"),
array("fm","Micronesia"),
array("md","Moldova"),
array("mc","Monaco"),
array("mn","Mongolia"),
array("me","Montenegro"),
array("ms","Montserrat"),
array("ma","Morocco"),
array("mz","Mozambique"),
array("mm","Myanmar (Burma)"),
array("na","Namibia"),
array("nr","Nauru"),
array("np","Nepal"),
array("nc","New Caledonia"),
array("nz","New Zealand"),
array("ni","Nicaragua"),
array("ne","Niger"),
array("ng","Nigeria"),
array("kp","North Korea"),
array("no","Norway"),
array("om","Oman"),
array("pk","Pakistan"),
array("pw","Palau"),
array("ps","Palestinian Territory"),
array("pa","Panama"),
array("pg","Papua New Guinea"),
array("py","Paraguay"),
array("pe","Peru"),
array("ph","Philippines"),
array("pl","Poland"),
array("pt","Portugal"),
array("pr","Puerto Rico"),
array("qa","Qatar"),
array("re","Réunion"),
array("ro","Romania"),
array("ru","Russian Federation"),
array("rw","Rwanda"),
array("kn","Saint Kitts and Nevis"),
array("lc","Saint Lucia"),
array("vc","Saint Vincent and the Grenadines"),
array("ws","Samoa"),
array("sm","San Marino"),
array("st","São Tomé and Príncipe"),
array("sa","Saudi Arabia"),
array("sn","Senegal"),
array("rs","Serbia"),
array("sc","Seychelles"),
array("sl","Sierra Leone"),
array("sg","Singapore"),
array("sk","Slovakia"),
array("si","Slovenia"),
array("sb","Solomon Islands"),
array("so","Somalia"),
array("za","South Africa"),
array("kr","South Korea"),
array("es","Spain"),
array("lk","Sri Lanka"),
array("sd","Sudan"),
array("sr","Suriname"),
array("sz","Swaziland"),
array("se","Sweden"),
array("ch","Switzerland"),
array("sy","Syrian Arab Republic"),
array("tw","Taiwan, Province of China"),
array("tj","Tajikistan"),
array("tz","Tanzania"),
array("th","Thailand"),
array("tl","Timor","Leste"),
array("tg","Togo"),
array("to","Tonga"),
array("tt","Trinidad and Tobago"),
array("tn","Tunisia"),
array("tr","Turkey"),
array("tm","Turkmenistan"),
array("tc","Turks and Caicos Islands"),
array("tv","Tuvalu"),
array("ug","Uganda"),
array("ua","Ukraine"),
array("ae","United Arab Emirates"),
array("uy","Uruguay"),
array("uz","Uzbekistan"),
array("vu","Vanuatu"),
array("va","Vatican City"),
array("ve","Venezuela"),
array("vn","Viet Nam"),
array("vg","Virgin Islands (British)"),
array("vi","Virgin Islands (U.S.)"),
array("eh","Western Sahara"),
array("ye","Yemen"),
array("zm","Zambia"));
}



//function to get names from acf-fields
function motivar_get_acf_field_info_custom($name)
{
global $wpdb;
$results = $wpdb->get_results($wpdb->prepare("SELECT DISTINCT post_name FROM $wpdb->posts WHERE post_excerpt=%s AND post_status=%s",$name, 'publish' ));
if (!empty($results))
    {
       $key=$results[0]->post_name;
       return get_field_object($key);
    }
}





//admin php file
if(class_exists('acf')){
if (is_admin())
{
    add_filter('acf/settings/save_json', 'motivar_acf_json_save_point');
    add_filter('acf/settings/load_json', 'motivar_acf_json_load_point');
}
function motivar_acf_json_save_point( $path ) {
  $pathh = plugin_dir_path(__FILE__).'../motivar_functions_child/fields/';
    if (file_exists($pathh))
    {
      $path=$pathh;
    }

    return $path;
}
function motivar_acf_json_load_point( $paths ) {
  $pathh=plugin_dir_path(__FILE__).'../motivar_functions_child/fields/';
  if (file_exists($pathh))
  {
        unset($paths[0]);
    $paths[] = $pathh;
  }

    return $paths;
}

}

if (!function_exists('motivar_footer_function'))
{


function motivar_footer_function($atts)
{

  extract(shortcode_atts(array(
    'company' => '',
    'year' => '',
    'pre_html' => '',
    'after_html' => '',
), $atts));

    $msg='<div id="motivar-footer-shortcode">';
    if (!empty($pre_html))
    {
      $msg.='<div class="motivar-footer-pre">'.$pre_html.'</div>';
    }
    if (!empty($company)) {
      if (empty($year))
      {
        $year=date('Y',strtotime('now'));
      }
    $msg .= '<div class="motivar-footer-company">' . __('All rights reserved','motivar') . ' '.__($company,'motivar').' <span class="year">'.$year.'</span></div>';
    }
    $msg .= '<div class="motivar-footer-motivar"><a href="https://motivar.io" target="_blank" title="'.__('Web design and development Corfu', 'motivar').'">' . __('Web design and development', 'motivar') . '</a> by <a href="https://motivar.io" target="_blank" title="'.__('Web design and development Corfu', 'motivar').'">Motivar.io</a></div>';
    if (!empty($after_html))
    {
      $msg.='<div class="motivar-footer-after">'.$after_html.'</div>';
    }      
    
    $msg.='</div>';

    return $msg;
}
}
add_shortcode('motivar_footer', 'motivar_footer_function');



