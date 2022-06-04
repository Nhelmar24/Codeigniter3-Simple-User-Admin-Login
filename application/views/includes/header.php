<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS --><link href="<?= base_url();?>assets/css/core.css">
    <link href="<?= base_url();?>assets/css/bootstrap.min.css" rel="stylesheet">

    <title>Login</title>
  </head>
  <body>
    <div class="container-fluid clock">
      <div class="container">
				<div class="row ">
					<div class="col-md-12">
						<div class="float-end text-white">
							<h3>Simple Login System<br>
									<pre class="sysclock">Today is:<?php

echo(date("Y-m-d"));
?></pre>
							</h3>
						</div>
					</div>
				</div>
      </div>
    </div>
    <style>
      *{font-family: 'Tajawal', sans-serif;}
      a{text-decoration:none;}
      body{
      background: url('<?= base_url();?>assets/images/back.jpg') no-repeat center center fixed; 
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;}
  .clock{background:rgba(0,0,0,0.2);box-shadow: 1px 1px 3px rgba(255,255,255,0.3);}
	.sysclock{margin:0;padding:0;font-size: 10px;}

  ul{list-style-type:none;padding:0;margin:0;}
  a{text-decoration:none;}
    </style>


