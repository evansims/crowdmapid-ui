
						<p><?php echo(CFG_SITE_LONG_DESCRIPTION); ?></p>

						<h3>Register an Account</h3>

						<?php if($register_message): ?>
						<p class="info_message"><?php echo($register_message); ?></p>
						<?php endif; ?>

						<?php if($register_error): ?>
						<p class="error_message"><strong>Whoops!</strong> <?php echo($register_error); ?></p>
						<?php endif; ?>

						<form id="account_register" method="post" action="<?php echo($site['url']); ?>/register" autocomplete="off">
							<input type="hidden" id="activity" name="activity" value="register" />

							<div class="form-row">
								<label for="email">Email Address:</label>
								<div class="text"><input type="text" id="email" name="email" /></div>
							</div>

							<div class="form-row">
								<label for="password">Password:</label>
								<div class="text"><input type="password" id="password" name="password" /></div>
							</div>

							<div id="password_strength_container" class="form-row" style="display: none">
								<label>&nbsp;</label>
								<div class="text"><p id="password_strength">&nbsp;</p></div>
							</div>

							<div class="form-row">
								<label class="filler">&nbsp;</label>
								<div class="buttons">
									<input type="submit" class="submit" value="Register" />
								</div>
							</div>

						</form>
