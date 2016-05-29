<?php

require_once 'oauth2/zwoauth.class.php';
$oauth = new ZWPDOOAuth2();

$auth_params = $oauth->getAuthorizeParams();

$resultClass = $oauth->finishClientAuthorization(true, $_GET);
$code = $resultClass->result['query']['code'];

if ($show_instructions) {
    $columns = 'twocolumns';
} else {
    $columns = 'onecolumn';
}

if (!empty($CFG->loginpasswordautocomplete)) {
    $autocomplete = 'autocomplete="off"';
} else {
    $autocomplete = '';
}
if (empty($CFG->authloginviaemail)) {
    $strusername = get_string('username');
} else {
    $strusername = get_string('usernameemail');
}
?>
<div class="loginbox clearfix <?php echo $columns ?>">
  <div class="loginpanel">
<?php
  if (($CFG->registerauth == 'email') || !empty($CFG->registerauth)) { ?>
      <div class="skiplinks"><a class="skip" href="signup.php"><?php print_string("tocreatenewaccount"); ?></a></div>
<?php
  } ?>
    <h2><?php print_string("login") ?></h2>
      <div class="subcontent loginsub">
        <?php
          if (!empty($errormsg)) {
              echo html_writer::start_tag('div', array('class' => 'loginerrors'));
              echo html_writer::link('#', $errormsg, array('id' => 'loginerrormessage', 'class' => 'accesshide'));
              echo $OUTPUT->error_text($errormsg);
              echo html_writer::end_tag('div');
          }
        ?>
        
        
        <form action="<?php echo $CFG->httpswwwroot; ?>/loginoauth/index.php" method="post" id="login" <?php echo $autocomplete; ?> >
          <div class="loginform nloginform"><!--加了class:nloginform-->
              <?php foreach ($auth_params as $k => $v) { ?>
                  <input type="hidden" name="<?php echo $k ?>" value="<?php echo $v ?>" />
              <?php } ?>

              <input type="hidden" name="code" value="<?php echo $code;?>" id="code">
              <input type="hidden" name="grant_type" value="authorization_code" id="grant_type">

<!--              <input type="hidden" name="response_type" value="code" id="response_type" />-->
            <div class="label">
            	<label for="username" class="p24"><?php echo($strusername) ?></label><!--用户名的label,加了class:p24-->
            </div>
            <div class="form-input" class="fr"><!--加了class:fr-->
            	<input  style="height:30px; width:200px;float:right;" type="text" name="username" id="username" size="15" value="<?php p($frm->username) ?>" /><!--用户名的输入框加了style-->
            </div>
            <div class="clearer"><!-- --></div>
            
            <div class="label">
            	<label for="password"  class="p24"><?php print_string("password") ?></label><!--密码的label,加了class:p24-->
            </div>
            <div class="form-input">
              <input style="height:30px; width:200px;float:right;" type="password" name="password" id="password" size="15" value="" <?php echo $autocomplete; ?> /><!--密码的输入框加了style-->
            </div>
            
          </div>
            <div class="clearer"><!-- --></div>

            <div style="width:300px; height:30px; margin:auto">
          	  <div class="clearer"><!-- --></div>
          	  <input type="submit" name="login"  style=" float:left; margin:15px 0px 0px 0px; width:100%;height:35px;" id="loginbtn" value="<?php print_string("login") ?>" /><!--登录按钮加了style-->
              		
          </div>
         
        </form>
        <div class="desc" style="margin:20px 20px 0px 0px"><!--加了style-->
           
        </div>
      </div>

<?php if ($show_instructions) { ?>
    <div class="signuppanel">
      <h2><?php print_string("firsttime") ?></h2>
      <div class="subcontent">
<?php     if (is_enabled_auth('none')) { // instructions override the rest for security reasons
              print_string("loginstepsnone");
          } else if ($CFG->registerauth == 'email') {
              if (!empty($CFG->auth_instructions)) {
                  echo format_text($CFG->auth_instructions);
              } else {
                  print_string("loginsteps", "", "signup.php");
              } ?>
                 <div class="signupform">
                   <form action="signup.php" method="get" id="signup">
                   <div><input type="submit" value="<?php print_string("startsignup") ?>" /></div>
                   </form>
                 </div>
<?php     } else if (!empty($CFG->registerauth)) {
              echo format_text($CFG->auth_instructions); ?>
              <div class="signupform">
                <form action="signup.php" method="get" id="signup">
                <div><input type="submit" value="<?php print_string("startsignup") ?>" /></div>
                </form>
              </div>
<?php     } else {
              echo format_text($CFG->auth_instructions);
          } ?>
      </div>
    </div>
<?php } ?>
<?php if (!empty($potentialidps)) { ?>
    <div class="subcontent potentialidps">
        <h6><?php print_string('potentialidps', 'auth'); ?></h6>
        <div class="potentialidplist">
<?php foreach ($potentialidps as $idp) {
    echo  '<div class="potentialidp"><a href="' . $idp['url']->out() . '" title="' . $idp['name'] . '">' . $OUTPUT->render($idp['icon'], $idp['name']) . $idp['name'] . '</a></div>';
} ?>
        </div>
    </div>
<?php } ?>
</div>
