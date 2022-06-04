<div class="container mt-3">
	<div class="row justify-content-md-center">
		<div class="col-md-4"> 

			<!-- Status message -->
			<?php  
				if(!empty($success_msg)){ 
					echo '<p class="status-msg success ">'.$success_msg.'</p>'; 
				}elseif(!empty($error_msg)){ 
					echo '<p class="status-msg error text-center">'.$error_msg.'</p>'; 
				} 
			?>

			<div class="card">
				<div class="card-header">
					Login to Your Account
				</div>
				<div class="card-body">
					<!-- Login form -->
					<form action="" method="post">
							<div class="form-group">
								<label for="exampleInputEmail1">Email address</label>
								<input class="form-control form-control-sm" type="email" name="email" placeholder="email@domain.com" required="">
								<?php echo form_error('email','<p class="help-block alerts">','</p>'); ?>
							</div>
							<div class="form-group mt-3">
								<label for="exampleInputEmail1">Email address</label>
								<input class="form-control form-control-sm" type="password" name="password" placeholder="PASSWORD" required="">
								<?php echo form_error('password','<p class="help-block alerts">','</p>'); ?>
							</div>
							<input class="btn btn-success btn-sm mt-3" type="submit" name="loginSubmit" value="LOGIN">
							
						
							<p>Don't have an account? <a href="<?php echo base_url('users/registration'); ?>">Register</a></p>
						
					</form>
				</div>
			</div>
		
		</div>
	</div>
</div>
<style>
	.success{background:rgba(0,255,0,0.3);border:1px solid rgba(0,255,0,0.5);border-radius:2px;color:#fff;padding:3px;}
	.error{background:rgba(255,0,0,0.3);border:1px solid rgba(255,0,0,0.5);border-radius:2px;color:#fff;padding:3px;}
	.help-block{background:rgba(255,0,0,0.3);border:1px solid rgba(255,0,0,0.5);border-radius:2px;color:#fff;padding:3px;}
</style>
