(() => {
  const canvas = document.querySelector("[data-ripple-canvas]");
  if (!canvas) return;

  const ctx = canvas.getContext("2d", { alpha: true });
  const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  const ripples = [];
  let width = 0;
  let height = 0;
  let dpr = 1;
  let lastAutoRipple = 0;
  let pointerX = 0;
  let pointerY = 0;

  const colors = [
    "rgba(139, 92, 246, 0.42)",
    "rgba(56, 189, 248, 0.38)",
    "rgba(99, 102, 241, 0.34)",
    "rgba(45, 212, 191, 0.24)",
  ];

  function resize() {
    dpr = Math.min(window.devicePixelRatio || 1, 2);
    width = window.innerWidth;
    height = window.innerHeight;
    canvas.width = Math.floor(width * dpr);
    canvas.height = Math.floor(height * dpr);
    canvas.style.width = `${width}px`;
    canvas.style.height = `${height}px`;
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
  }

  function addRipple(x, y, strength = 1) {
    ripples.push({
      x,
      y,
      radius: 8,
      opacity: 0.44 * strength,
      velocity: 1.55 + Math.random() * 0.7,
      color: colors[Math.floor(Math.random() * colors.length)],
    });

    if (ripples.length > 28) {
      ripples.shift();
    }
  }

  function drawBackground(time) {
    const wave = Math.sin(time * 0.00035);
    const glow = ctx.createRadialGradient(
      width * (0.48 + wave * 0.08),
      height * 0.44,
      0,
      width * 0.5,
      height * 0.5,
      Math.max(width, height) * 0.78
    );

    glow.addColorStop(0, "rgba(118, 95, 255, 0.2)");
    glow.addColorStop(0.32, "rgba(28, 145, 255, 0.13)");
    glow.addColorStop(0.68, "rgba(10, 16, 48, 0.02)");
    glow.addColorStop(1, "rgba(10, 16, 48, 0)");

    ctx.fillStyle = glow;
    ctx.fillRect(0, 0, width, height);

    ctx.save();
    ctx.globalAlpha = 0.18;
    ctx.strokeStyle = "rgba(255, 255, 255, 0.24)";
    ctx.lineWidth = 1;
    for (let y = -40; y < height + 40; y += 42) {
      ctx.beginPath();
      for (let x = -20; x <= width + 20; x += 18) {
        const offset = Math.sin(x * 0.012 + time * 0.0012 + y * 0.02) * 7;
        if (x === -20) {
          ctx.moveTo(x, y + offset);
        } else {
          ctx.lineTo(x, y + offset);
        }
      }
      ctx.stroke();
    }
    ctx.restore();
  }

  function drawRipples() {
    for (let i = ripples.length - 1; i >= 0; i -= 1) {
      const ripple = ripples[i];
      ripple.radius += ripple.velocity;
      ripple.opacity *= 0.975;

      ctx.beginPath();
      ctx.arc(ripple.x, ripple.y, ripple.radius, 0, Math.PI * 2);
      ctx.strokeStyle = ripple.color.replace(/[\d.]+\)$/, `${ripple.opacity})`);
      ctx.lineWidth = Math.max(1, 4 - ripple.radius * 0.015);
      ctx.stroke();

      ctx.beginPath();
      ctx.arc(ripple.x, ripple.y, ripple.radius * 0.55, 0, Math.PI * 2);
      ctx.strokeStyle = `rgba(255, 255, 255, ${ripple.opacity * 0.36})`;
      ctx.lineWidth = 1;
      ctx.stroke();

      if (ripple.opacity < 0.012 || ripple.radius > Math.max(width, height) * 0.8) {
        ripples.splice(i, 1);
      }
    }
  }

  function animate(time) {
    ctx.clearRect(0, 0, width, height);
    drawBackground(time);
    drawRipples();

    if (!reducedMotion && time - lastAutoRipple > 1700) {
      lastAutoRipple = time;
      addRipple(
        width * (0.2 + Math.random() * 0.6),
        height * (0.18 + Math.random() * 0.64),
        0.46
      );
    }

    window.requestAnimationFrame(animate);
  }

  window.addEventListener("resize", resize, { passive: true });
  window.addEventListener("pointermove", (event) => {
    if (reducedMotion) return;

    const dx = event.clientX - pointerX;
    const dy = event.clientY - pointerY;
    pointerX = event.clientX;
    pointerY = event.clientY;

    if (Math.hypot(dx, dy) > 22) {
      addRipple(event.clientX, event.clientY, 0.72);
    }
  }, { passive: true });

  window.addEventListener("pointerdown", (event) => {
    addRipple(event.clientX, event.clientY, 1.2);
  }, { passive: true });

  resize();
  addRipple(window.innerWidth * 0.5, window.innerHeight * 0.46, 0.7);
  window.requestAnimationFrame(animate);
})();
