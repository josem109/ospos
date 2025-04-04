<!doctype html>
<html lang="<?php echo current_language_code(); ?>">

<head>
  <meta charset="utf-8">
	<base href="<?php echo base_url(); ?>">
	<title><?php echo $this->config->item('company') . '&nbsp;|&nbsp;' . $this->lang->line('common_software_short')  . '&nbsp;|&nbsp;' .  $this->lang->line('login_login'); ?></title>
	<meta content="width=device-width, initial-scale=1" name="viewport">
  <meta content="noindex, nofollow" name="robots">
	<link href="images/favicon.ico" rel="shortcut icon" type="image/x-icon">
  <link href="<?php echo 'dist/bootswatch-5/' . (empty($this->config->item('theme')) || 'paper' == $this->config->item('theme') || 'readable' == $this->config->item('theme') ? 'flatly' : $this->config->item('theme')) . '/bootstrap.min.css'; ?>" rel="stylesheet" type="text/css">
  <!-- start css template tags -->
  <link rel="stylesheet" type="text/css" href="css/login.min.css"/>
  <!-- end css template tags -->
	<meta content="#2c3e50" name="theme-color">
</head>

<body class="bg-light d-flex flex-column">
  <main class="d-flex justify-content-around align-items-center flex-grow-1">
    <div class="container-login container-fluid d-flex flex-column flex-md-row bg-body shadow rounded m-3 p-4 p-md-0">
      <div class="box-logo d-flex flex-column justify-content-center align-items-center border-end px-4 pb-3 p-md-4">
      <?php if ($this->Appconfig->get('company_logo')): ?>
        <img class="logo w-100" src="<?php echo base_url('uploads/' . $this->Appconfig->get('company_logo')); ?>" alt="<?php echo $this->lang->line('common_logo') . '&nbsp;' . $this->config->item('company'); ?>">
      <?php else: ?>
        <svg class="logo text-primary" role="img" viewBox="0 0 792 612" xmlns="http://www.w3.org/2000/svg">
          <title><?php echo $this->lang->line('common_software_title') . '&nbsp;' . $this->lang->line('common_logo'); ?></title>
          <g>
            <g>
              <g>
                <rect x="351.97" y="239.88" transform="matrix(0.7071 -0.7071 0.7071 0.7071 -66.2587 363.5643)" width="107.53" height="43.76" fill="currentColor"/>
                <path d="M459.93,239.22l-76.74,76.74l-31.65-31.65l76.74-76.74L459.93,239.22z M383.19,314.55l75.33-75.33l-30.24-30.24l-75.33,75.33L383.19,314.55z" fill="currentColor"/>
              </g>
              <g>
                <rect x="311.37" y="203.52" transform="matrix(0.7071 -0.7071 0.7071 0.7071 -49.4432 322.9681)" width="107.53" height="35.29" fill="currentColor"/>
                <path d="M416.34,195.63l-76.74,76.74l-25.66-25.66l76.74-76.74L416.34,195.63z M339.6,270.96l75.33-75.33l-24.25-24.25l-75.33,75.33L339.6,270.96z" fill="currentColor"/>
              </g>
              <g>
                <path d="M410.27,332.86c-11.16,11.16-29.27,11.16-40.43,0l-64.77-64.77c-11.16-11.16-11.16-29.27,0-40.43l50.99-50.99l-27.2-27.2l-50.7,50.7c-25.98,25.98-25.98,68.11,0,94.09l66.87,66.87c25.98,25.98,68.11,25.98,94.09,0l2.62-2.62l-28.56-28.56L410.27,332.86z" fill="currentColor"/>
                <path d="M442.45,358.5l-2.97,2.97c-26.13,26.13-68.66,26.13-94.79,0l-66.87-66.87c-26.13-26.13-26.13-68.66,0-94.79l51.06-51.06l27.91,27.91l-51.35,51.35c-10.95,10.95-10.95,28.77,0,39.72l64.77,64.77c10.95,10.95,28.77,10.95,39.72,0l3.27-3.27L442.45,358.5z M278.51,200.52c-25.74,25.74-25.75,67.64,0,93.38l66.87,66.87c25.75,25.75,67.64,25.74,93.38,0l2.27-2.27l-27.85-27.85l-2.56,2.56c-11.34,11.34-29.8,11.34-41.14,0l-64.77-64.77c-11.34-11.34-11.34-29.8,0-41.14l50.64-50.64l-26.5-26.5L278.51,200.52z" fill="currentColor"/>
              </g>
              <g>
                <path d="M344.8,133.53l-5.78,5.78l27.2,27.2l4.79-4.79c11.16-11.16,29.27-11.16,40.43,0l64.77,64.77c11.16,11.16,11.16,29.27,0,40.43l-52.71,52.71l28.56,28.56l52.7-52.7c25.98-25.98,25.98-68.11,0-94.09l-67.87-67.87C411.46,108.1,370.23,108.1,344.8,133.53z" fill="currentColor"/>
                <path d="M505.11,295.84l-53.05,53.05l-29.26-29.26l53.06-53.06c10.95-10.95,10.95-28.77,0-39.72l-64.77-64.77c-10.95-10.95-28.77-10.95-39.72,0l-5.15,5.15l-27.91-27.91l6.14-6.14c25.58-25.58,67.21-25.58,92.8,0l67.87,67.87C531.25,227.18,531.25,269.71,505.11,295.84z M452.06,347.48l52.35-52.35c25.74-25.74,25.74-67.63,0-93.38l-67.87-67.87c-25.19-25.19-66.19-25.19-91.38,0l-5.43,5.43l26.5,26.5l4.44-4.44c11.34-11.34,29.8-11.34,41.14,0l64.77,64.77c11.34,11.34,11.34,29.8,0,41.14l-52.35,52.35L452.06,347.48z" fill="currentColor"/>
              </g>
            </g>
            <g>
              <g>
                <path d="M112.9,431.83l27.71,40.19v-40.19h15.14v65.68h-14.69l-27.71-40.19v40.19H98.2v-65.68H112.9z" fill="currentColor"/>
                <path d="M156.24,498.01h-15.46l-26.94-39.08v39.08H97.7v-66.68h15.46l26.94,39.08v-39.08h16.14V498.01z M141.31,497.01h13.93v-64.68H141.1v41.29l-28.47-41.29H98.7v64.68h14.14v-41.29L141.31,497.01z" fill="currentColor"/>
              </g>
              <g>
                <path d="M218.13,431.83v13.81h-28.7v12.22h25.41v13.63h-25.41v12.22h29.5v13.81h-43.37v-65.68H218.13z" fill="currentColor"/>
                <path d="M219.43,498.01h-44.37v-66.68h43.57v14.81h-28.7v11.22h25.41v14.63h-25.41v11.22h29.5V498.01z M176.06,497.01h42.37v-12.81h-29.5v-13.22h25.41v-12.63h-25.41v-13.22h28.7v-12.81h-41.57V497.01z" fill="currentColor"/>
              </g>
              <g>
                <path d="M231.46,431.83h18.23l12.83,20l12.66-20h17.88l-21.86,32.75l22.13,32.93h-18.23l-13.1-20.45l-12.92,20.45h-17.88l22.04-33.11L231.46,431.83z" fill="currentColor"/>
                <path d="M294.27,498.01h-19.45L262,478l-12.65,20.02h-19.09l22.37-33.6l-22.11-33.08h19.44l12.56,19.57l12.39-19.57H294l-22.2,33.25L294.27,498.01z M275.37,497.01h17.02l-21.79-32.43l21.53-32.25h-16.67l-12.93,20.44l-13.11-20.44h-17.03l21.44,32.07l-21.71,32.61h16.67l13.2-20.88L275.37,497.01z" fill="currentColor"/>
              </g>
              <g>
                <path d="M303.83,445.73v-13.9h55.23v13.9h-19.92v51.78h-15.4v-51.78H303.83z" fill="currentColor"/>
                <path d="M339.65,498.01h-16.4v-51.78h-19.92v-14.9h56.23v14.9h-19.92V498.01z M324.25,497.01h14.4v-51.78h19.92v-12.9h-54.23v12.9h19.92V497.01z" fill="currentColor"/>
              </g>
              <g>
                <path d="M394.1,431.83v65.68h-15.4v-65.68H394.1z" fill="currentColor"/>
                <path d="M394.6,498.01h-16.4v-66.68h16.4V498.01z M379.2,497.01h14.4v-64.68h-14.4V497.01z" fill="currentColor"/>
              </g>
              <g>
                <path d="M460.93,431.83v13.81h-31.69v12.22h28.06v13.63h-28.06v12.22h32.57v13.81h-47.89v-65.68H460.93z" fill="currentColor"/>
                <path d="M462.32,498.01h-48.89v-66.68h48v14.81h-31.69v11.22h28.06v14.63h-28.06v11.22h32.57V498.01z M414.43,497.01h46.89v-12.81h-32.57v-13.22h28.06v-12.63h-28.06v-13.22h31.69v-12.81h-46V497.01z" fill="currentColor"/>
              </g>
              <g>
                <path d="M497.39,431.83l27.71,40.19v-40.19h15.14v65.68h-14.69l-27.71-40.19v40.19h-15.14v-65.68H497.39z" fill="currentColor"/>
                <path d="M540.73,498.01h-15.46l-0.15-0.22l-26.79-38.86v39.08h-16.14v-66.68h15.46l0.15,0.22l26.79,38.86v-39.08h16.14V498.01z M525.8,497.01h13.93v-64.68h-14.14v41.29l-28.47-41.29h-13.93v64.68h14.14v-41.29L525.8,497.01z" fill="currentColor"/>
              </g>
              <g>
                <path d="M584.66,431.83c21.42,0,34.79,13.46,34.79,32.84s-13.37,32.84-34.79,32.84h-24.61v-65.68H584.66z M584.66,445.73h-9.21v37.88h9.21c12.48,0,19.38-6.9,19.38-18.94S597.14,445.73,584.66,445.73z" fill="currentColor"/>
                <path d="M584.66,498.01h-25.11v-66.68h25.11c21.11,0,35.29,13.4,35.29,33.34S605.76,498.01,584.66,498.01z M560.55,497.01h24.11c20.83,0,34.29-12.69,34.29-32.34s-13.46-32.34-34.29-32.34h-24.11V497.01z M584.66,484.12h-9.71v-38.88h9.71c12.64,0,19.88,7.09,19.88,19.44S597.29,484.12,584.66,484.12z M575.95,483.12h8.71c12.18,0,18.88-6.55,18.88-18.44s-6.71-18.44-18.88-18.44h-8.71V483.12z" fill="currentColor"/>
              </g>
              <g>
                <path d="M672.85,485.48h-25.85l-4.43,12.04h-15.93l25.58-65.68h15.76l25.58,65.68h-16.29L672.85,485.48z M668.25,472.73l-8.32-22.75l-8.32,22.75H668.25z" fill="currentColor"/>
                <path d="M694.3,498.01h-17.37l-4.43-12.04h-25.15l-4.43,12.04h-17.01l25.97-66.68h16.44L694.3,498.01z M677.63,497.01h15.21l-25.19-64.68h-15.07l-25.19,64.68h14.85l4.43-12.04h26.54L677.63,497.01z M668.96,473.23h-18.07l9.04-24.7L668.96,473.23z M652.32,472.23h15.21l-7.61-20.79L652.32,472.23z" fill="currentColor"/>
              </g>
            </g>
          </g>
        </svg>
      <?php endif; ?>
      </div>
      <section class="box-login d-flex flex-column justify-content-center align-items-center p-md-4">
				<?php echo form_open('login'); ?>
        <h3 class="text-center m-0"><?php echo $this->lang->line('login_welcome', $this->lang->line('common_software_short')); ?></h3>
        <?php if (validation_errors()): ?>
        <div class="alert alert-danger mt-3">
          <?php echo validation_errors(); ?>
        </div>
        <?php endif; ?>
				<?php if (!$this->migration->is_latest()): ?>
        <div class="alert alert-info mt-3">
					<?php echo $this->lang->line('login_migration_needed', $this->config->item('application_version')); ?>
				</div>
				<?php endif; ?>
        <?php if (empty($this->config->item('login_form')) || 'floating_labels'==($this->config->item('login_form'))): ?>
        <div class="form-floating mt-3">
          <input class="form-control" id="input-username" name="username" type="text" placeholder="<?php echo $this->lang->line('login_username'); ?>">
          <label for="input-username"><?php echo $this->lang->line('login_username'); ?></label>
        </div>
        <div class="form-floating mb-3">
          <input class="form-control" id="input-password" name="password" type="password" placeholder="<?php echo $this->lang->line('login_password'); ?>">
          <label for="input-password"><?php echo $this->lang->line('login_password'); ?></label>
        </div>
      <?php elseif ('input_groups'==($this->config->item('login_form'))): ?>
        <div class="input-group mt-3">
          <span class="input-group-text" id="input-username">
            <svg class="bi" fill="currentColor" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
              <title><?php echo $this->lang->line('common_icon') . '&nbsp;' . $this->lang->line('login_username'); ?></title>
              <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
            </svg>
          </span>
          <input class="form-control" name="username" type="text" placeholder="<?php echo $this->lang->line('login_username'); ?>" aria-label="<?php echo $this->lang->line('login_username'); ?>" aria-describedby="input-username" <?php if (ENVIRONMENT == "testing") echo "value='admin'"; ?>>
        </div>
        <div class="input-group mb-3">
          <span class="input-group-text" id="input-password">
            <svg class="bi" fill="currentColor" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
              <title><?php echo $this->lang->line('common_icon') . '&nbsp;' . $this->lang->line('login_password'); ?></title>
              <path d="M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2zM2.5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
            </svg>
          </span>
          <input class="form-control" name="password" type="password" placeholder="<?php echo $this->lang->line('login_password'); ?>" aria-label="<?php echo $this->lang->line('login_password'); ?>" <?php if (ENVIRONMENT == "testing") echo "value='pointofsale'"; ?>" aria-describedby="input-password">
        </div>
        <?php endif; ?>
				<?php if($this->config->item('gcaptcha_enable')) {
					echo '<script src="https://www.google.com/recaptcha/api.js"></script>';
					echo '<div class="g-recaptcha mb-3" align="center" data-sitekey="' . $this->config->item('gcaptcha_site_key') . '"></div>'; }
        ?>
        <div class="d-grid">
          <button class="btn btn-lg btn-primary" name="login-button" type="submit" ><?php echo $this->lang->line('login_go'); ?></button>
        </div>
				<?php echo form_close(); ?>
      </section>
    </div>
  </main>
  <footer class="d-flex justify-content-center flex-shrink-0 text-center">
    <div class="footer container-fluid bg-body rounded shadow p-3 mb-md-4 mx-md-3">
      <span class="text-muted">
        <svg height="1em" role="img" viewBox="0 0 229.85 143.05001" xmlns="http://www.w3.org/2000/svg">
          <title><?php echo $this->lang->line('common_software_short') . '&nbsp;' . $this->lang->line('common_logo_mark'); ?></title>
          <path fill="currentColor" d="M115.51 50.18c-.03-1.26-.03-3.29.19-4.29 4.6-11.1 15.57-18.82 28.3-18.82h.41v58.3c0 .12-.03.78-.04.9-.54 16.46-14.01 29.7-30.59 29.7v27.08c21 0 39.17-11.27 49.29-28.07l.07-.11c2.9.45 5.86.75 8.9.75 31.95 0 57.81-26 57.81-57.81 0-30.87-24.37-56.46-55.1-57.81h-30.74c-17.18 0-32.61 7.64-43.22 19.63-10.59-11.92-25.86-19.59-43.02-19.59-31.86 0-57.77 25.91-57.77 57.77 0 31.86 25.91 57.77 57.77 57.77 31.86 0 57.77-25.91 57.77-57.77v-3.68c-.01.01-.02-3.31-.03-3.95zm-57.75 38.33c-16.92 0-30.69-13.77-30.69-30.69s13.77-30.69 30.69-30.69 30.69 13.77 30.69 30.69-13.77 30.69-30.69 30.69zm142.96-19.87c-4.33 11.64-15.57 19.9-28.7 19.9h-.54v-61.47h.54c13.13 0 24.37 8.26 28.7 19.9 1.35 3.25 2.03 6.91 2.03 10.83s-.67 7.59-2.03 10.84z"/>
        </svg>
      </span>
      <span><?php echo $this->lang->line('common_software_title'); ?></span>
    </div>
  </footer>
</body>

</html>
