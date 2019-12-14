<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://waplugin.com/
 * @since      1.0.0
 *
 * @package    Waplugin
 * @subpackage Waplugin/admin/partials
 */
?>

<div class="wrap">
	<div class="container">
		<div class="columns is-mobile is-centered mt-3">
		    <div class="column is-half">
				<nav class="navbar is-black mynav" role="navigation" aria-label="main navigation">
				    <div class="navbar-brand">
				        <a class="navbar-item" href="<?php echo admin_url('/admin.php?page=wapluginoverview');?>">
				            <img src="<?php echo plugins_url( '../images/wapluginlogo.png', __FILE__ );?>" width="150">
				        </a>
				        <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
				            <span aria-hidden="true"></span>
				            <span aria-hidden="true"></span>
				            <span aria-hidden="true"></span>
				        </a>
				    </div>
				    <div id="navbarBasicExample" class="navbar-menu">
				        <div class="navbar-end">
				            <div class="navbar-item">
				                <div class="buttons">
				                    <a href="https://waplugin.com/home/developer" class="button is-primary" target="_Blank">
				                        <strong><?php echo esc_html('GET API KEY', 'waplugin');?></strong>
				                    </a>
				                </div>
				            </div>
				        </div>
				    </div>
				</nav>
				<div class="card is-paddingless is-marginless">
				    <div class="card-content">
						<div class="tabs is-centered waplugin-tabs">
						    <ul>
						        <li class="is-active" data-tab="1"><a><?php echo esc_html('API', 'waplugin');?></a></li>
						        <li data-tab="2"><a><?php echo esc_html('Account', 'waplugin');?></a></li>
						        <li data-tab="3"><a><?php echo esc_html('Admin', 'waplugin');?></a></li>
						    </ul>
						</div>
						<div class="waplugin-tabs-content">
							<!-- API -->
						    <div class="waplugin-tab-content content is-active" data-content="1">
								<div class="notification is-success waplugin-alert" id="waplugin-api-valid">
								    <?php echo esc_html('Success! Your API Key is Valid.', 'waplugin');?>
								</div>
								<div class="notification is-danger waplugin-alert" id="waplugin-api-invalid">
								    <?php echo esc_html('Failed! Your API Key is Invalid.', 'waplugin');?>
								</div>
								<div class="field">
								    <label class="label"><?php echo esc_html('API KEY', 'waplugin');?></label>
								    <div class="control">
								    	<?php if (false === $waplugin_api): ?>
								    		<input name="<?php echo esc_attr('waplugin_api', 'waplugin');?>" class="input" type="text" placeholder="<?php echo esc_attr('xxxxxxxxxxxxxxxxxx', 'waplugin');?>" required>
								    	<?php else: ?>
								    		<input name="<?php echo esc_attr('waplugin_api', 'waplugin');?>" value="<?php echo esc_attr($waplugin_api, 'waplugin');?>" class="input" type="text" placeholder="<?php echo esc_attr('xxxxxxxxxxxxxxxxxx', 'waplugin');?>" required>
								    	<?php endif ?>
								    </div>
								</div>
								<button class="button is-success is-outlined is-fullwidth" id="<?php echo esc_attr('submit-waplugin-api', 'waplugin');?>"><?php echo esc_html('SAVE CHANGES', 'waplugin');?></button>
						    </div>
						    <!-- Account -->
						    <div class="waplugin-tab-content content">
								<div class="notification is-success waplugin-alert" id="waplugin-account-valid">
								    <?php echo esc_html('Success! Account Added.', 'waplugin');?>
								</div>
								<div class="notification is-danger waplugin-alert" id="waplugin-account-invalid">
								    <?php echo esc_html('Failed!', 'waplugin');?>
								</div>
								<?php if (!empty($accounts)): ?>
									<div class="field">
									    <label class="label"><?php echo esc_html('Account', 'waplugin');?></label>
									    <div class="control">
									    	<div class="select is-fullwidth">
												<select name="waplugin_account_id" required>
													<option value="" selected disabled>-- <?php echo esc_html('Select Your Account', 'waplugin');?> --</option>
													<?php foreach ($accounts as $account): ?>
														<?php if ($account['active'] && $account['connected']): ?>
															<option value="<?php echo esc_attr($account['id'], 'waplugin');?>" <?php selected( $waplugin_account_id, $account['id'] ); ?>><?php echo $account['name'];?></option>
														<?php endif ?>
													<?php endforeach ?>
												</select>
									    	</div>
									    </div>
									</div>
									<button class="button is-success is-outlined is-fullwidth" id="<?php echo esc_attr('submit-waplugin-account', 'waplugin');?>"><?php echo esc_html('SAVE CHANGES', 'waplugin');?></button>
								<?php else: ?>
									<div class="notification is-warning ">
									    <?php echo esc_html('Whoops! Please add your valid API Key first and make sure you hava active & connected account.', 'waplugin');?>
									</div>
								<?php endif ?>
						    </div>
						    <!-- Admin -->
						    <div class="waplugin-tab-content content">
								<div class="notification is-success waplugin-alert" id="waplugin-admin-valid">
								    <?php echo esc_html('Success! Admin Data Saved.', 'waplugin');?>
								</div>
								<div class="notification is-danger waplugin-alert" id="waplugin-admin-invalid">
								    <?php echo esc_html('Failed!', 'waplugin');?>
								</div>
								<?php if (!empty($waplugin_api) && !empty($accounts)): ?>
									<div class="field">
									    <label class="label"><?php echo esc_html('Country', 'waplugin');?></label>
									    <div class="control">
									    	<div class="select is-fullwidth">
												<select name="waplugin_admin_country" required>
													<option value="" selected disabled>-- <?php echo esc_html('Select Country', 'waplugin');?> --</option>
													<?php foreach ($countries as $country): ?>
														<option value="<?php echo esc_attr($country['cca2'], 'waplugin');?>" <?php selected( $waplugin_admin_country, $country['cca2'] ); ?>><?php echo $country['name']['common'];?> <?php echo $country['flag'];?></option>
													<?php endforeach ?>
												</select>
									    	</div>
									    </div>
									</div>
									<div class="field">
									    <label class="label"><?php echo esc_html('Phone Number', 'waplugin');?></label>
									    <div class="control">
									    	<?php if (false === $waplugin_admin_phone): ?>
									    		<input name="<?php echo esc_attr('waplugin_admin_phone', 'waplugin');?>" class="input" type="text" required>
									    	<?php else: ?>
									    		<input name="<?php echo esc_attr('waplugin_admin_phone', 'waplugin');?>" value="<?php echo esc_attr($waplugin_admin_phone, 'waplugin');?>" class="input" type="text" required>
									    	<?php endif ?>
									    </div>
									</div>
									<button class="button is-success is-outlined is-fullwidth" id="<?php echo esc_attr('submit-waplugin-admin', 'waplugin');?>"><?php echo esc_html('SAVE CHANGES', 'waplugin');?></button>
								<?php else: ?>
									<div class="notification is-warning ">
									    <?php echo esc_html('Whoops! Please add your valid API Key first and make sure you hava active & connected account.', 'waplugin');?>
									</div>
								<?php endif ?>
						    </div>
						</div>
				    </div>
				</div>
		    </div>
		</div>
	</div>
</div>