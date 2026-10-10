<div class="page-shader-layer fixed inset-0 w-full h-full" aria-hidden="true">
    <canvas id="site-shader-canvas" style="display:block;width:100%;height:100%"></canvas>
    <script>
    (function () {
        const canvas = document.getElementById('site-shader-canvas');
        if (!canvas || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        function syncSize() {
            const width = canvas.clientWidth || window.innerWidth;
            const height = canvas.clientHeight || window.innerHeight;
            if (canvas.width !== width || canvas.height !== height) {
                canvas.width = width;
                canvas.height = height;
            }
        }

        if (typeof ResizeObserver !== 'undefined') new ResizeObserver(syncSize).observe(canvas);
        syncSize();

        const gl = canvas.getContext('webgl') || canvas.getContext('experimental-webgl');
        if (!gl) return;

        const vertexShader = `attribute vec2 a_position;
            varying vec2 v_texCoord;
            void main() {
                v_texCoord = a_position * 0.5 + 0.5;
                gl_Position = vec4(a_position, 0.0, 1.0);
            }`;
        const fragmentShader = `precision highp float;
            uniform float u_time;
            uniform vec2 u_resolution;
            uniform vec2 u_mouse;
            varying vec2 v_texCoord;
            vec3 mod289(vec3 x) { return x - floor(x * (1.0 / 289.0)) * 289.0; }
            vec2 mod289(vec2 x) { return x - floor(x * (1.0 / 289.0)) * 289.0; }
            vec3 permute(vec3 x) { return mod289(((x * 34.0) + 1.0) * x); }
            float snoise(vec2 v) {
                const vec4 C = vec4(0.211324865405187, 0.366025403784439, -0.577350269189626, 0.024390243902439);
                vec2 i = floor(v + dot(v, C.yy));
                vec2 x0 = v - i + dot(i, C.xx);
                vec2 i1 = (x0.x > x0.y) ? vec2(1.0, 0.0) : vec2(0.0, 1.0);
                vec4 x12 = x0.xyxy + C.xxzz;
                x12.xy -= i1;
                i = mod289(i);
                vec3 p = permute(permute(i.y + vec3(0.0, i1.y, 1.0)) + i.x + vec3(0.0, i1.x, 1.0));
                vec3 m = max(0.5 - vec3(dot(x0, x0), dot(x12.xy, x12.xy), dot(x12.zw, x12.zw)), 0.0);
                m = m * m;
                m = m * m;
                vec3 x = 2.0 * fract(p * C.www) - 1.0;
                vec3 h = abs(x) - 0.5;
                vec3 ox = floor(x + 0.5);
                vec3 a0 = x - ox;
                m *= 1.79284291400159 - 0.85373472095314 * (a0 * a0 + h * h);
                vec3 g;
                g.x = a0.x * x0.x + h.x * x0.y;
                g.yz = a0.yz * x12.xz + h.yz * x12.yw;
                return 130.0 * dot(m, g);
            }
            void main() {
                vec2 p = v_texCoord * 2.0 - 1.0;
                p.x *= u_resolution.x / u_resolution.y;
                vec2 mouse = u_mouse / u_resolution;
                vec2 mouseP = mouse * 2.0 - 1.0;
                mouseP.x *= u_resolution.x / u_resolution.y;
                float dist = length(p - mouseP);
                float time = u_time * 0.15;
                float n1 = snoise(p * 0.8 + time);
                float n2 = snoise(p * 1.2 - time * 0.7);
                float n3 = snoise(p * 0.5 + time * 0.3 + dist * 0.2);
                vec3 background = vec3(0.98, 0.98, 0.977);
                vec3 pink = vec3(0.957, 0.78, 0.843);
                vec3 blue = vec3(0.78, 0.867, 0.957);
                vec3 color = background;
                color = mix(color, pink, smoothstep(-0.5, 1.2, n1) * 0.25);
                color = mix(color, blue, smoothstep(-0.5, 1.2, n2) * 0.25);
                color = mix(color, pink, smoothstep(0.2, 1.5, n3) * 0.15);
                color = mix(color, background, (1.0 - smoothstep(0.0, 0.8, dist)) * 0.1);
                gl_FragColor = vec4(color, 1.0);
            }`;

        function compile(type, source) {
            const shader = gl.createShader(type);
            gl.shaderSource(shader, source);
            gl.compileShader(shader);
            return shader;
        }

        const program = gl.createProgram();
        gl.attachShader(program, compile(gl.VERTEX_SHADER, vertexShader));
        gl.attachShader(program, compile(gl.FRAGMENT_SHADER, fragmentShader));
        gl.linkProgram(program);
        gl.useProgram(program);

        const buffer = gl.createBuffer();
        gl.bindBuffer(gl.ARRAY_BUFFER, buffer);
        gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 1, -1, -1, 1, 1, 1]), gl.STATIC_DRAW);
        const position = gl.getAttribLocation(program, 'a_position');
        gl.enableVertexAttribArray(position);
        gl.vertexAttribPointer(position, 2, gl.FLOAT, false, 0, 0);

        const timeUniform = gl.getUniformLocation(program, 'u_time');
        const resolutionUniform = gl.getUniformLocation(program, 'u_resolution');
        const mouseUniform = gl.getUniformLocation(program, 'u_mouse');
        const mouse = { x: canvas.width / 2, y: canvas.height / 2 };

        window.addEventListener('mousemove', (event) => {
            const rect = canvas.getBoundingClientRect();
            if (!rect.width || !rect.height) return;
            mouse.x = ((event.clientX - rect.left) / rect.width) * canvas.width;
            mouse.y = (1.0 - (event.clientY - rect.top) / rect.height) * canvas.height;
        });

        function render(timestamp) {
            if (typeof ResizeObserver === 'undefined') syncSize();
            gl.viewport(0, 0, canvas.width, canvas.height);
            if (timeUniform) gl.uniform1f(timeUniform, timestamp * 0.0005);
            if (resolutionUniform) gl.uniform2f(resolutionUniform, canvas.width, canvas.height);
            if (mouseUniform) gl.uniform2f(mouseUniform, mouse.x, mouse.y);
            gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
            requestAnimationFrame(render);
        }

        requestAnimationFrame(render);
    })();
    </script>
</div>
