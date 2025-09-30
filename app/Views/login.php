<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kantor Komunikasi Kepresidenan</title>
    <link href="<?=base_url('assets/css/bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/vendors/fontawesome/css/all.min.css')?>" rel="stylesheet">
    <script src="<?=base_url('assets/js/jquery-3.7.1.min.js')?>"></script>
    <script src="<?=base_url('assets/js/bootstrap.bundle.min.js')?>"></script>
    <?php if($status=="true"){?>
        <script src="<?=base_url()?>/assets/js/app.min.js"></script>
        <script src='https://www.google.com/recaptcha/api.js'></script>
    <?php } ?>
    <style>
        :root {
            --biru-prabowo: #1E3A8A;
            --biru-gelap: #1E40AF;
            --biru-terang: #3B82F6;
            --putih-indonesia: #FFFFFF;
            --abu-gelap: #2C3E50;
            --abu-terang: #ECF0F1;
            --primary-color: #297db5;
            --secondary-color: #afddfc;
        }

        body {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--biru-prabowo) 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
        }

        .header-section {
            background: linear-gradient(135deg, var(--putih-indonesia) 0%, var(--abu-terang) 100%);
            padding: 40px 30px 30px;
            text-align: center;
            border-bottom: 3px solid var(--biru-prabowo);
        }

        .logo-container {
            margin-bottom: 20px;
        }

        .garuda-icon {
            width: 80px;
            height: 80px;
            background: var(--biru-prabowo);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            box-shadow: 0 8px 20px rgba(30, 58, 138, 0.3);
        }

        .garuda-icon i {
            font-size: 36px;
            color: white;
        }

        .title-text {
            color: var(--abu-gelap);
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
            line-height: 1.2;
        }

        .subtitle-text {
            color: #7F8C8D;
            font-size: 14px;
            font-weight: 500;
        }

        .form-section {
            padding: 40px 30px;
        }

        .form-label {
            color: var(--abu-gelap);
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-control {
            border: 2px solid #E8E8E8;
            border-radius: 12px;
            padding: 15px 20px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #FAFAFA;
        }

        .form-control:focus {
            border-color: var(--biru-prabowo);
            box-shadow: 0 0 0 0.2rem rgba(30, 58, 138, 0.15);
            background: white;
        }

        .input-group {
            position: relative;
            margin-bottom: 25px;
        }

        .input-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #95A5A6;
            z-index: 10;
        }

        .form-control.with-icon {
            padding-left: 55px;
        }

        .btn-login {
            background: linear-gradient(135deg, var(--biru-prabowo) 0%, var(--biru-gelap) 100%);
            border: none;
            border-radius: 12px;
            padding: 15px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 58, 138, 0.3);
            background: linear-gradient(135deg, var(--biru-gelap) 0%, #1D4ED8 100%);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .forgot-password {
            text-align: center;
            margin-top: 20px;
        }

        .forgot-password a {
            color: var(--biru-prabowo);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .footer-section {
            background: var(--abu-terang);
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #E8E8E8;
        }

        .footer-text {
            color: #7F8C8D;
            font-size: 12px;
            margin: 0;
        }

        .security-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(46, 204, 113, 0.1);
            color: #27AE60;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            margin-top: 15px;
        }

        @media (max-width: 576px) {
            .login-container {
                padding: 10px;
            }
            
            .form-section,
            .header-section {
                padding: 30px 20px;
            }
            
            .title-text {
                font-size: 20px;
            }
        }

        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }
        .shape:nth-child(1) {
            width: 100px;
            height: 100px;
            top: 20%;
            left: 10%;
            animation: float 6s ease-in-out infinite;
        }
        .shape:nth-child(2) {
            width: 150px;
            height: 150px;
            top: 60%;
            right: 15%;
            animation: float 8s ease-in-out infinite reverse;
        }
        .shape:nth-child(3) {
            width: 80px;
            height: 80px;
            bottom: 20%;
            left: 20%;
            animation: float 7s ease-in-out infinite;
        }
        .shape:nth-child(4) {
            width: 200px;
            height: 200px;
            top: 30%;
            right: 25%;
            animation: float 8s ease-in-out infinite reverse;
        }
        .shape:nth-child(5) {
            width: 80px;
            height: 80px;
            top: 20%;
            right: 20%;
            animation: float 7s ease-in-out infinite;
        }
        .shape:nth-child(6) {
            width: 170px;
            height: 170px;
            top: 40%;
            left: 31%;
            animation: float 8s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        #canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }
    </style>
</head>
<body>
    <canvas id="canvas"></canvas>
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="header-section p-1">
                <div class="logo-container">
                    <!-- <div class="garuda-icon"> -->
                        <!-- <i class="fas fa-crown"></i> -->
                    <!-- </div> -->
                    <!-- <div class="title-text">KANTOR KOMUNIKASI<br>KEPRESIDENAN</div> -->
                    <!-- <div class="subtitle-text">Republik Indonesia</div> -->
                    <img src="<?=base_url('assets/img/logo.png')?>" height="250">
                </div>
            </div>

            <div class="form-section">
                <?php if(session()->get('message')){?>
                    <!-- ALERT FLASHDATA -->
                    <div class="alert alert-danger" id="flashdata_message">
                        <i class="fa fa-exclamation-circle"></i> <?=session()->get('message')?>
                    </div>
                <?php }?>
				<?=form_open('', ['id'=>'form_login', 'class'=>'m-2'])?>
                    <div class="input-group">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" class="form-control with-icon" name="username" id="username" placeholder="Username atau Email" required>
                    </div>

                    <div class="input-group">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" class="form-control with-icon" name="password" id="password" placeholder="Password" required>
						<span class="input-group-text" onclick="show_hide_password()">
							<i class="fa fa-eye" id="addon-eye"></i>
						</span>
						<script type="text/javascript">
							function show_hide_password()
							{
								var addon_eye = $('#addon-eye').attr('class')
								if(addon_eye=='fa fa-eye')
								{
									$('#addon-eye').prop('class', 'fa fa-eye-slash')
									$('#password').prop('type', 'text')
								}else{
									$('#addon-eye').prop('class', 'fa fa-eye')
									$('#password').prop('type', 'password')
								}
							}
						</script>
                    </div>

                    <?php if($status=="true"){?>
                    <div class="row mt-3 mb-3">
                        <div class="col-sm-12 col-md-12">
                            <!-- for google captcha set sitekey -->
                            <div class="g-recaptcha" data-sitekey="<?=$sitekey?>"></div>
                        </div>
                    </div>
                    <?php } ?>

                    <!-- <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="rememberMe">
                        <label class="form-check-label" for="rememberMe" style="color: #7F8C8D; font-size: 14px;">
                            Ingat saya
                        </label>
                    </div> -->

                    <button type="submit" class="btn btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Masuk
                    </button>

                    <?php if(strtolower($oauth_start)=='true'){?>
                        <a href="<?=site_url('auth/google?key='.$api_key) ?>" class="btn btn-lg btn-warning d-block mt-2"><i class="fab fa-google"></i> Login dengan Google</a>
                    <?php }?>

                    <!-- <div class="forgot-password">
                        <a href="#" onclick="showForgotPassword()">Lupa password?</a>
                    </div> -->
                <?=form_close()?>

                <!-- <div class="text-center">
                    <div class="security-badge">
                        <i class="fas fa-shield-alt"></i>
                        Sistem Aman & Terenkripsi
                    </div>
                </div> -->
            </div>

            <div class="footer-section">
                <p class="footer-text">
                    <i class="fas fa-copyright me-1"></i>
                    2025 Kantor Komunikasi Kepresidenan RI
                </p>
                <p class="footer-text">
                    Hanya untuk pengguna yang berwenang
                </p>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(function(){
            $("#flashdata_message").fadeTo(5000, 500).slideUp(500, function(){
                $("#flashdata_message").slideUp(500);
            });
        })
		$('#form_login').on('submit', function(e)
		{
			$('.btn-login').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Memproses...');
			let data = $(this);
			e.preventDefault();
			$.ajax({
				crossDomain: true,
				crossOrigin: true,
				dataType: 'json',
				type: "POST",
				data: data.serialize(),
				// url: '<?=site_url('auth/login')?>',//data.attr('action'),
				headers: {
					"Key": "<?=$api_key?>",
				},
				success: function(responseData, textStatus, jqXHR) {
					var dt = responseData
					if(dt.status==1)
					{
						if(dt.data.options && dt.data.options.toptp==1)
						{
							window.location.assign('<?=site_url('auth/otp')?>');
						}else{
							window.location.assign('<?=base_url()?>');
						}
					}else{
						alert(dt.message.replace(/<p>|<\/p>/g, ""));
					}
					$('.btn-login').prop('disabled', false).html('<i class="fas fa-sign-in-alt me-2"></i>Masuk');
					$('input[name=<?=csrf_token()?>]').val(dt.csrf);
				}
			});
		});

		<?php /*
        // Fungsi untuk lupa password
        function showForgotPassword() {
            alert('Silakan hubungi administrator sistem untuk reset password.\n\nKontak: admin@kominfo.go.id\nTelepon: (021) 3456789');
        }
        */ ?>

        // Efek focus pada input
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.style.transform = 'scale(1)';
            });
        });

        // Animasi saat halaman dimuat
        window.addEventListener('load', function() {
            const card = document.querySelector('.login-card');
            card.style.opacity = '0';
            card.style.transform = 'translateY(50px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.8s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        });

        // ANIMASI BACKGROUND
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        
        let mouse = {
            x: undefined,
            y: undefined,
            radius: 100 // Reduced radius of influence
        };

        function handleMouseMove(event) {
            mouse.x = event.clientX;
            mouse.y = event.clientY;
        }

        function handleTouchMove(event) {
            event.preventDefault();
            mouse.x = event.touches[0].clientX;
            mouse.y = event.touches[0].clientY;
        }

        canvas.addEventListener('mousemove', handleMouseMove);
        canvas.addEventListener('touchmove', handleTouchMove, { passive: false });
        canvas.addEventListener('mouseleave', () => {
            mouse.x = undefined;
            mouse.y = undefined;
        });

        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }
        
        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();

        class Particle {
            constructor() {
                this.init();
                // Initialize at random position
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
            }

            init() {
                // Base movement properties
                this.baseX = Math.random() * canvas.width;
                this.baseY = Math.random() * canvas.height;
                this.angle = Math.random() * Math.PI * 2;
                this.speed = 0.3 + Math.random() * 0.3; // Consistent floating speed
                this.orbitRadius = 30 + Math.random() * 30; // Random orbit size
                
                // Size variation
                const sizeRand = Math.random();
                if (sizeRand < 0.6) {
                    this.radius = Math.random() * 1 + 0.5;
                } else if (sizeRand < 0.9) {
                    this.radius = Math.random() * 1.5 + 1.5;
                } else {
                    this.radius = Math.random() * 2 + 3;
                }
                
                // Visual properties
                this.baseAlpha = Math.max(0.1, Math.min(0.5, 1 / (this.radius * 0.8)));
                this.alpha = this.baseAlpha;
                this.phaseOffset = Math.random() * Math.PI * 2;
                
                // Mouse interaction properties
                this.friction = 0.95;
                this.pushFactor = 1;
                this.returnSpeed = 0.1;
                
                // Current velocity (for mouse interaction)
                this.vx = 0;
                this.vy = 0;
            }

            update(time) {
                if (mouse.x !== undefined && mouse.y !== undefined) {
                    // Calculate distance to mouse
                    const dx = mouse.x - this.x;
                    const dy = mouse.y - this.y;
                    const distance = Math.sqrt(dx * dx + dy * dy);
                    
                    if (distance < mouse.radius) {
                        // Push away from mouse
                        const force = (mouse.radius - distance) / mouse.radius;
                        const angle = Math.atan2(dy, dx);
                        this.vx -= Math.cos(angle) * force * this.pushFactor;
                        this.vy -= Math.sin(angle) * force * this.pushFactor;
                    }
                }
                
                // Apply velocity from mouse interaction
                this.x += this.vx;
                this.y += this.vy;
                this.vx *= this.friction;
                this.vy *= this.friction;
                
                // Regular floating movement
                if (Math.abs(this.vx) < 0.1 && Math.abs(this.vy) < 0.1) {
                    this.angle += this.speed * 0.01;
                    const targetX = this.baseX + Math.cos(this.angle) * this.orbitRadius;
                    const targetY = this.baseY + Math.sin(this.angle) * this.orbitRadius;
                    
                    this.x += (targetX - this.x) * this.returnSpeed;
                    this.y += (targetY - this.y) * this.returnSpeed;
                }

                // Keep within bounds
                if (this.x < 0 || this.x > canvas.width) {
                    this.vx *= -0.5;
                    this.x = Math.max(0, Math.min(canvas.width, this.x));
                }
                if (this.y < 0 || this.y > canvas.height) {
                    this.vy *= -0.5;
                    this.y = Math.max(0, Math.min(canvas.height, this.y));
                }

                // Update alpha for twinkling
                this.alpha = this.baseAlpha + Math.sin(time * 0.001 + this.phaseOffset) * 0.2;
            }

            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(255, 255, 255, ${this.alpha})`;
                ctx.fill();
            }
        }

        const particles = [];
        const numParticles = 150;
        const connectionDistance = 200;

        for (let i = 0; i < numParticles; i++) {
            particles.push(new Particle());
        }

        function animate(timestamp) {
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            for (let i = 0; i < particles.length; i++) {
                const p1 = particles[i];
                p1.update(timestamp);

                // Draw connections
                for (let j = i + 1; j < particles.length; j++) {
                    const p2 = particles[j];
                    const dx = p1.x - p2.x;
                    const dy = p1.y - p2.y;
                    const distance = Math.sqrt(dx * dx + dy * dy);

                    if (distance < connectionDistance) {
                        const alpha = (1 - distance / connectionDistance) * 0.2 * Math.min(p1.alpha, p2.alpha);
                        ctx.beginPath();
                        ctx.moveTo(p1.x, p1.y);
                        ctx.lineTo(p2.x, p2.y);
                        ctx.strokeStyle = `rgba(255, 255, 255, ${alpha})`;
                        ctx.lineWidth = 0.9;
                        ctx.stroke();
                    }
                }

                p1.draw();
            }

            requestAnimationFrame(animate);
        }

        animate(0);
    </script>
</body>
</html>