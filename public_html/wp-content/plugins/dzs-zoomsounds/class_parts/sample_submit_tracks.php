<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16/08/2019
 * Time: 22:11
 */



$args = array(
  'post_title' => 'Track 1 from stephaniequinn.com',
  'post_content' => 'stephaniequinn.com',
  'post_status' => 'inherit',
  'post_author' => 1,
  'post_type' => 'attachment',
  'post_mime_type' => 'audio/mpeg',
  'post_name' => 'steph1',
  'guid' => 'https://digitalzoomstudio.net/links/sample-mp3.php',
);

$sample_post_2_id = wp_insert_post($args);

update_post_meta($sample_post_2_id,'_waveformbg','https://lh3.googleusercontent.com/OCkCqtmYpqevOPlhNY4R8oy37CmypYXtsM6CdwstJp-2X8y4O_MdmnOOyTZ2dODVq7sfxLqoRG2H-fGJ8GAwYDp7jtiyyesUiMjIZA4czV7dDqnaw0qhpkRBpfSmqW_uOkQtGvhJUn9nYAK2MQwQ_PtCfl4uHgb1cae5n7qNC8DjRgVorBBr_gZVLg0IZFXbLW0UTp-8KsqrZSyGHAgxbh7Q40-CKFvBKxZ7KblCTfwsEun4LElkYFe5ZPZOsn1EBrxsbXrSyAZVmm0VX7UXRnEQR-5YTIzZ6ttugwYonTFNwmiGxOCsg5RyYpwTNWMLE1v2fBUsBgSStiLrnwQqrK4VAfV-irLXdfXsy6ZG174u0uPdjGJq3qw3PcJUHatmxZDC5PbSrxTHR-K6OqTOV7bM641t40ZVNZfZmjOTzzL-eDWkKCUu5q5VBm254sJ4FK63bP5QbxOQem6nPadxEayRSKfyF4z4HUnoqsR1giPk8eWI63LcgGOZeSWGVw0T27N_Ugwz37Twr5Ilyk7q66elCiyOxK7IUuiur6-QYi0=w1170-h140-no');
update_post_meta($sample_post_2_id,'_waveformprog','https://lh3.googleusercontent.com/3ZCeepH9HAhs1ojwrMVKRW4poGaqPSbeczAAs8XjBl8E4zh0vSzXY4ou7KtRXUoMDff70qz8vEa5YLwq_4kp4ufRHcTK8_7lbs5Ux4jTETAkhluI75nUweiBYztNkwtxRggzTLnu2kdyVn3lubZGDbe4-pxyvBtz2tWauKs9fw7wiMCcrkFz5BFi_X1q7ViGA205qTfuTLjltWzom09Xm8vgt5EsTHyInFoMAeSobImMrG5j67VTgrX_9vYDNu3RE_TbISRY9c7wdEXOplQZXJDHH3c86rdVaoclhGAbli3mHJ92iZmGrZM1JH0glyj-ymSSq8RU1Tw2Slb1QFYEwzJpr_wOR9BqqccLAf-yLawNG5TqTQLhrYekNfPaWEtUrcYvHMDeg2R_x7zZg0Q_FI4qvUjBrTu8ClZIf_fml4mer7KEl3uhNEDNr7pe9suucRGO_f_whT8bqjFsRCvh9obFhvj0Suvc-SNFTeLavV6EwIqFVYdHCwyedHxdmOGTsruvXw3CRqon0UFb2jqR2GO6ZUSQ9k9emXdGCZAVzqY=w1170-h140-no');
update_post_meta($sample_post_2_id,'_dzsap-thumb','https://lh3.googleusercontent.com/dF5JBlMfXMsYxXl3pvzmAtkWOhC-aP1rPOpoDHlOSXU1s0tG9XcgfXonQ6Z27jqId77KI3yv9nkbDWVKD3DsHTjoeHfw2PgpH9aoiykmbPXmQ64OKEVn1uJ5gGeiKD1zyPRlHd-yg7wy59wLoUxYpbbJpdf4uiB8Bf7NNo_1VXpyaMGjHRI7BMl5jFyXkJA7H2J5xT3kemlEo7HMUAg7vRDhCBLdvGoyNzZCuzFJ8meA3TLxi8SoQdCn371iv7joSWSfdQH6MCbE9VmCvLnYJIpkPs1PEtYOlbPUnb2UdFEA6kNiJmWnNqjOYxdb2v9mfsggNv8rk5IEazadXCwBqhREiCYvFd0fB3zsx9-zUHASEjWCF-LNFAYHvv8N4ZM7wzeWbRSsSKbxqk2ma7aym_QVc5GqDMQkp1LlEQxMI2zCIACiukehV6DvVOvw5Z1JLLPKL6Gq4kN8oNuS8glcgHzhwIlPBXy1wQ3hz_PU_H2Iu_wZt0eag77YArwha1Av5sINngPyJHu0UI2OrqgQd-7HqiGGuzWUkumAR8UAYQ=s80-no');





array_push($this->sample_data['media'], $sample_post_2_id);
$this->sample_data['first_sg_id'] = $sample_post_2_id;






$args = array(
  'post_title' => 'Track 2 from stephaniequinn.com',
  'post_content' => 'stephaniequinn.com',
  'post_status' => 'inherit',
  'post_author' => 1,
  'post_type' => 'attachment',
  'post_mime_type' => 'audio/mpeg',
  'post_name' => 'steph1',
  'guid' => 'https://www.stephaniequinn.com/Music/Commercial%20DEMO%20-%2001.mp3',
);

$sample_post_2_id = wp_insert_post($args);

update_post_meta($sample_post_2_id,'_waveformbg','https://lh3.googleusercontent.com/s_WsedJQkZIRGfooorFv1oZRApVy4FIpYvjP76Kpbo-5leiu1avPr65ElLuMb0bzRQuLeuk8OQnU4pywclzzjIDlZbQaWnCjnOIaQzkk37zyPKSJb-nnY2aov-SavJgFmAN2P6CeBdHI74tJaAOYycRxP7KrCdMdx0vwAixVcYkeJ7zR7Iad5ifaJ-jlBh_7mf97Xro6aVawW9BdxCs006vxrIY0l4QuNvOmBJ3jFcv38qkEeemaMDKxeaYYVPCzr5_ZnfumgK6WFvIrAEjiexlcFK2m5sFXz1c1b0IWyYYAITtYcasVqgAGuCsWTM9ujqR_T0dzWeg_uWOpZNJp2Y04LIsxmqMyCo6bL9mkWly0wLGkwVSpZFSZUKGJ5Vmti94Z6NXeVC4wpb-GOaYk5U3CDbxFDTBqXA3Gi5RT7mocTG3N4ZOR2gaIb530e0to6K2rMUixSqSvfOvfqV-vfsU4AZGs_NGF5-z5bFHioCTSXtmcNfl1CQn7HZnUqbdjE90R-vvvcI0SlYp6x9VCOhWof958SJzAGQSXmubbA-Q=w1170-h140-no');
update_post_meta($sample_post_2_id,'_waveformprog','https://lh3.googleusercontent.com/Xl5bEyPhd4Rin99rRZg8vwj7XRuee4ED9d_FGas4ayh8G_VlZFtRUlfPYozrHduEKdhiW2AgEELjpbCubLhZbUZaFUaBNgwVbkVYtlDBvs1EI78hnDsgUozzltwIAypfe6OlgZn7nyUiYtDTG4iMBgBLLFX1CeN9LDmmB3EQO4d820eyIn0xz9ba9UEERq9ILzC2QkkWeCZQXS5zElaTXOLAVlZh2qgRbNkFNMjiQfCXuLbPizNKagbixAMXqiqOD-Z_vS7JklaeW2LuYHyrtp5MVW92NgHERk_P01N04CS2-dxc0ufYpo-vAenz6s2EVxHi292aRvC95alzGIT0_B30p5Cs_9yw_06fsypf3XTPd6ZqVgW2pdGxYOMk8Kwg_2IMEjULUkf9WSoVBarxAetG0hsfIVT9KVwsZBuER9dcXmLZpndLyH6wHejzIXb6FueuTZdWpw5_opTqqxQpLEM27V9J1hLJFyCcAcysVEVZkB-m5viDePPL1WqwFebBoOETjc4OIhh8Zs-dVeZNQSMI8nzH2d9kP3w6ocm-8HQ=w1170-h140-no');
update_post_meta($sample_post_2_id,'_dzsap-thumb','https://lh3.googleusercontent.com/dF5JBlMfXMsYxXl3pvzmAtkWOhC-aP1rPOpoDHlOSXU1s0tG9XcgfXonQ6Z27jqId77KI3yv9nkbDWVKD3DsHTjoeHfw2PgpH9aoiykmbPXmQ64OKEVn1uJ5gGeiKD1zyPRlHd-yg7wy59wLoUxYpbbJpdf4uiB8Bf7NNo_1VXpyaMGjHRI7BMl5jFyXkJA7H2J5xT3kemlEo7HMUAg7vRDhCBLdvGoyNzZCuzFJ8meA3TLxi8SoQdCn371iv7joSWSfdQH6MCbE9VmCvLnYJIpkPs1PEtYOlbPUnb2UdFEA6kNiJmWnNqjOYxdb2v9mfsggNv8rk5IEazadXCwBqhREiCYvFd0fB3zsx9-zUHASEjWCF-LNFAYHvv8N4ZM7wzeWbRSsSKbxqk2ma7aym_QVc5GqDMQkp1LlEQxMI2zCIACiukehV6DvVOvw5Z1JLLPKL6Gq4kN8oNuS8glcgHzhwIlPBXy1wQ3hz_PU_H2Iu_wZt0eag77YArwha1Av5sINngPyJHu0UI2OrqgQd-7HqiGGuzWUkumAR8UAYQ=s80-no');


array_push($this->sample_data['media'], $sample_post_2_id);



$time = current_time('mysql');

$playerid = $sample_post_2_id;



$data = array(
  'comment_post_ID' => $playerid,
  'comment_author' => 'admin',
  'comment_author_email' => 'admin@admin.com',
  'comment_author_url' => 'https://',
  'comment_content' => '<span class="dzstooltip-con" style="left:37.66387884267631%"><span class="dzstooltip arrow-from-start transition-slidein arrow-bottom skin-black talign-start style-rounded color-dark-light" style="width: 250px;"><span class="dzstooltip--inner"><span class="the-comment-author">@admin</span> says:<br>test</span><span class="the-avatar" style="background-image: url(https://1.gravatar.com/avatar/12d1738b0f28c211e5fd5ae066e631a1?s=20&#038;d=mm&#038;r=g)"></span></span></span>',
  'comment_type' => '',
  'comment_parent' => 0,
  'user_id' => 1,
  'comment_author_IP' => '127.0.0.1',
  'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
  'comment_date' => $time,
  'comment_approved' => 1,
);

wp_insert_comment($data);


$data = array(
  'comment_post_ID' => $playerid,
  'comment_author' => 'admin',
  'comment_author_email' => 'admin@admin.com',
  'comment_author_url' => 'https://',
  'comment_content' => '<span class="dzstooltip-con" style="left:37.66387884267631%"><span class="dzstooltip arrow-from-start transition-slidein arrow-bottom skin-black talign-start style-rounded color-dark-light" style="width: 250px;"><span class="dzstooltip--inner"><span class="the-comment-author">@admin</span> says:<br>test</span><span class="the-avatar" style="background-image: url(https://1.gravatar.com/avatar/12d1738b0f28c211e5fd5ae066e631a1?s=20&#038;d=mm&#038;r=g)"></span></span></span>',
  'comment_type' => '',
  'comment_parent' => 0,
  'user_id' => 1,
  'comment_author_IP' => '127.0.0.1',
  'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
  'comment_date' => $time,
  'comment_approved' => 1,
);

wp_insert_comment($data);


update_option(DZSAP_DBNAME_SAMPLEDATA, $this->sample_data);
