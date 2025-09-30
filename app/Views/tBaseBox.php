<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=isset($title)?$title:'Page'?></title>
    <link href="<?=base_url('assets/css/bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/vendors/fontawesome/css/all.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/css/main.css')?>" rel="stylesheet">
    <script src="<?=base_url('assets/js/jquery-3.7.1.min.js')?>"></script>
    <script src="<?=base_url('assets/js/bootstrap.bundle.min.js')?>"></script>
    <script src="<?=base_url('assets/js/custom.js')?>"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers:{
                "Key": "<?=session()->get('key')?>",
                "User": '<?=session()->get('id')?>',
                "Token": '<?=session()->get('token')?>',
            }
        });
    </script>
    <style type="text/css">
        .bggelap{
/*            background: linear-gradient(135deg, var(--biru-prabowo) 0%, var(--biru-gelap) 100%);*/
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--biru-prabowo) 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
<body class="bggelap">
    <canvas id="canvas"></canvas>
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <!-- NAVBAR HEADER -->
    <?=view('tBaseHeader')?>

    <!-- Main Content -->
    <div class="box-content">
        <?php if(session()->get('message')){?>
            <!-- ALERT FLASHDATA -->
            <div class="alert alert-info" id="flashdata_message">
                <i class="fa fa-exclamation-circle"></i> <?=session()->get('message')?>
            </div>
        <?php }?>
        <?php 
            // LOAD VIEW PAGE
            if(isset($page) && file_exists(APPPATH.'Views/'.$page.'.php'))
            {
                echo view($page);
            }else{
                echo '<h1 class="text-warning"><i class="fa fa-exclamation-circle"></i> Page not found</h1>';
            }
        ?>

    </div>


   <!--  <footer class="footer mt-auto py-3 bg-body-tertiary" style="height:50px; padding-bottom: 0px; vertical-align: bottom;"> 
        <div class="container"> 
            <span class="text-body-secondary">Place sticky footer content here.</span> 
        </div> 
    </footer> -->
        
    <!-- SCRIPTS -->
    <script type="text/javascript">
        // Show dropdown on hover
        $('.dropdown').mouseover(function () {
            if($('.navbar-toggler').is(':hidden')) {
                $(this).addClass('show').attr('aria-expanded', 'true');
                $(this).find('.dropdown-menu').addClass('show');
            }
        }).mouseout(function () {
            if($('.navbar-toggler').is(':hidden')) {
                $(this).removeClass('show').attr('aria-expanded', 'false');
                $(this).find('.dropdown-menu').removeClass('show');
            }
        });

        // Go to the parent link on click
        $('.dropdown > a').click(function(){
            location.href = this.href;
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
    <!-- JAVASCRIPT -->
    <?=view('tBaseJavascript')?>
</body>
</html>