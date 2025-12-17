<?php
$user = $_GET['user'] ?? 'Unknown';
$subject = $_GET['subject'] ?? 'Unknown';
$score = $_GET['score'] ?? 0;
$total = $_GET['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Certificate - QuizzyMind</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Great+Vibes&family=Poppins:wght@400;600;700&display=swap');

body {
  margin: 0;
  font-family: 'Poppins', sans-serif;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  padding-top: 50px;
  background: linear-gradient(120deg, #00090a, #2575fc);
}

.certificate {
  width: 850px;
  padding: 50px;
  text-align: center;
  border-radius: 25px;
  background: rgba(255,255,255,0.05);
  backdrop-filter: blur(20px);
  border: 3px solid rgba(255,255,255,0.25);
  box-shadow: 0 0 50px rgba(0,255,255,0.2);
  position: relative;
}

h1 {
  font-family: 'Great Vibes', cursive;
  font-size: 70px;
  color: #00f9ff;
  text-shadow: 0 0 15px #00f9ff, 0 0 25px #00d4ff, 0 0 35px #0072ff;
}

.highlight {
  font-weight: 700;
  color: #00ffff;
  text-shadow: 0 0 10px #00f9ff, 0 0 20px #0072ff;
}

.score {
  font-size: 30px;
  font-weight: 700;
  margin-top: 25px;
  background: linear-gradient(90deg, #00f9ff, #0072ff);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.btn-group {
  margin-top: 35px;
  display: flex;
  justify-content: center;
  gap: 20px;
}

.btn {
  display: inline-block;
  padding: 14px 36px;
  font-size: 18px;
  font-weight: 600;
  border-radius: 50px;
  text-decoration: none;
  color: #fff;
  background: linear-gradient(45deg, #00f9ff, #0072ff);
}

/* Sparkle animation only for website */
.sparkle {
  position: absolute;
  width: 6px;
  height: 6px;
  background: radial-gradient(circle, #ffffff 0%, #00f9ff 70%);
  border-radius: 50%;
  opacity: 0.8;
  pointer-events: none;
  animation: sparkleAnim 1.5s linear infinite;
}

@keyframes sparkleAnim {
  0% { transform: translateY(0px) scale(1); opacity: 0.8; }
  50% { transform: translateY(-10px) scale(1.2); opacity: 1; }
  100% { transform: translateY(0px) scale(1); opacity: 0.8; }
}
</style>
</head>
<body>

<div class="certificate" id="certificate">
  <h1>Certificate of Completion</h1>
  <p>This certifies that <span class="highlight"><?= htmlspecialchars($user) ?></span></p>
  <p>has successfully completed the <span class="highlight"><?= htmlspecialchars($subject) ?></span> quiz</p>
  <p class="score">Score: <?= htmlspecialchars($score) ?> / <?= htmlspecialchars($total) ?></p>
  <p>🎉 Congratulations!</p>

  <div class="btn-group">
    <a href="user_dashboard.php" class="btn">⬅ Back to Dashboard</a>
    <a href="#" class="btn" id="downloadBtn">⬇ Download</a>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
// Sparkles on website only
function addSparkles(parent, count = 20) {
    for(let i=0;i<count;i++){
        const s = document.createElement('div');
        s.className='sparkle';
        s.style.left = Math.random()*parent.offsetWidth+'px';
        s.style.top = Math.random()*parent.offsetHeight+'px';
        s.style.width = s.style.height = (2+Math.random()*6)+'px';
        parent.appendChild(s);
        setTimeout(()=>s.remove(), 2000);
    }
}
setInterval(()=>{ addSparkles(document.getElementById('certificate'), 15); }, 500);

// Download certificate as clean image (no sparkles)
document.getElementById('downloadBtn').addEventListener('click', function(){
    const certificate = document.getElementById('certificate');
    const clone = certificate.cloneNode(true);
    clone.style.background = '#ffffff';
    clone.style.color = '#000000';
    clone.querySelectorAll('.highlight').forEach(el => { el.style.color = '#0072ff'; el.style.textShadow = 'none'; });
    clone.querySelectorAll('.score').forEach(el => {
        el.style.background = 'none';
        el.style.webkitBackgroundClip = 'unset';
        el.style.webkitTextFillColor = '#0072ff';
    });

    clone.style.position = 'absolute';
    clone.style.left = '-9999px';
    document.body.appendChild(clone);

    html2canvas(clone,{ scale:2, backgroundColor:'#ffffff'}).then(canvas=>{
        const link=document.createElement('a');
        link.download='Certificate_<?= htmlspecialchars($user) ?>.png';
        link.href=canvas.toDataURL('image/png');
        link.click();
        document.body.removeChild(clone);
    });
});
</script>

</body>
</html>
