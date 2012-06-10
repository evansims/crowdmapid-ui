
						<!--
						<div id="avatar" class="filedrop filedrop-images" style="width: 128px; height: auto">
							<div class="loader">&nbsp;</div>
							<img class="preview" src="images/demo.png" />
							<div class="overlay">Drop to Replace</div>
						</div>
						-->

						<ul class="preferences-table">
							<li class="editable" onclick="window.location = '<?php echo($site['url']); ?>/accounts'; return false;">
								<span class="title">Email Addresses</span>
								<span class="status">
									<?php
										$emails = '';
										foreach($user->emails as $email) {
											if($email->master == 1) $emails .= '<strong>';
											$emails .= $email->email;
											if($email->master == 1) $emails .= ' (Primary Contact)</strong>';
											$emails .= '<br />';
										}
										echo rtrim($emails, '<br />');
									?>
								</span>
								<span class="links"><a href="<?php echo($site['url']); ?>/accounts">Edit</a></span>
							</li>
							<li class="editable" onclick="window.location = '<?php echo($site['url']); ?>/security'; return false;">
								<span class="title">Password</span>
								<span class="status">Last changed <strong><?php echo($passwordChanged); ?>.</strong></span>
								<span class="links"><a href="<?php echo($site['url']); ?>/security">Edit</a></span>
							</li>
							<li>
								<span class="title">Account Age</span>
								<span class="status">Your account was created <strong><?php echo($accountRegistered); ?>.</strong></span>
								<span class="links"></span>
							</li>
							<li>
								<span class="title">Badges</span>
								<span class="status"><?php echo(Localize::contextPlural('You have <strong>:count :context.</strong>', count($user->badges), 'badge', 'badges', 'You don\'t have any badges yet.')); ?></span>
								<span class="links"></span>
							</li>
						</ul>
