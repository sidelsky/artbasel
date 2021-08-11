<?php

function dzsap_admin_echo_registeredUsersCapabilityMainOption($dzsap) {

$lab = 'allow_download_only_for_registered_users_capability';
$dependency = array(
  array(
    'element' => 'allow_download_only_for_registered_users',
    'value' => array('on'),
  ),
);
?>
<div class="setting" data-dependency='<?php echo json_encode($dependency); ?>'>
  <h4 class="label"><?php echo esc_html__('Required user capability for download', DZSAP_ID); ?></h4>
  <?php


  $seekval = 'read';

  global $current_user;

  if($dzsap->mainoptions[$lab]){
    $seekval = $dzsap->mainoptions[$lab];
  }



  $opts = array(
    array(
      'label' => esc_html__("Subscriber ( lowest )"),
      'value' => 'read'
    ),
    array(
      'label' => esc_html__("Contributor"),
      'value' => 'edit_posts'
    ),
    array(
      'label' => esc_html__("Author"),
      'value' => 'edit_published_posts',
    ),

  );
  $opts_default = $opts;

  $optsAllCaps = array();
  $all_caps = get_role( 'administrator' )->capabilities;
  foreach ($all_caps as $keyCap => $valCap){
    if($keyCap!='read' && $keyCap!='edit_posts' && $keyCap!='edit_published_posts'){
      array_push($optsAllCaps, array(
        'label' => $keyCap,
        'value' => $keyCap,
      ));
    }
  }


  global $wp_roles;
  $roles = $wp_roles->roles;
  ?>
  <select name="<?php echo $lab ?>" class=" styleme "><optgroup label="<?php echo esc_html__("Default roles", DZSAP_ID) ?>"><?php
      $fout = '';
      foreach ($opts_default as $key => $valOpt){

        $fout .= '<option value="' . $valOpt['value'] . '"';
        if ($seekval != '' && $seekval == $valOpt['value']) {
          $fout .= ' selected';
        }

        $fout .= '>' . $valOpt['label'] . '</option>';
      }
      echo $fout;
      ?></optgroup>
    <optgroup label="<?php echo esc_html__("Site Roles", DZSAP_ID) ?>"><?php
      $fout = '';
      foreach ($roles as $key => $valOpt){

        if($key==='subscriber' || $key==='author'){
          continue;
        }
        $fout .= '<option value="' . $key . '"';
        if ($seekval != '' && $seekval == $key) {
          $fout .= ' selected';
        }

        $fout .= '>' . $valOpt['name'] . '</option>';
      }
      echo $fout;
      ?></optgroup>

    print_rr($roles);

    ?><optgroup label="<?php echo esc_html__("Custom permissions", DZSAP_ID) ?>"><?php
      $fout = '';
      foreach ($optsAllCaps as $key => $valOpt){

        $fout .= '<option value="' . $valOpt['value'] . '"';
        if ($seekval != '' && $seekval == $valOpt['value']) {
          $fout .= ' selected';
        }

        $fout .= '>' . $valOpt['label'] . '</option>';
      }
      echo $fout;
      ?></optgroup></select>


  <div class="sidenote"><?php echo esc_html__("select a class to restrict downloads to", DZSAP_ID) . ''; ?></div>
</div><?php
}