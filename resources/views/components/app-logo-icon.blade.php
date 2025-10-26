<!-- URL Shortener Logo - Icon + Wordmark -->
<svg xmlns="http://www.w3.org/2000/svg" width="720" height="200" viewBox="0 0 720 200" role="img" aria-labelledby="title desc">
    <title id="title">shortnr — URL shortener logo</title>
    <desc id="desc">Interlocking chain link icon with inward arrows representing shortening, plus the wordmark.</desc>

    <!-- ====== THEME ====== -->
    <!-- Tweak these to recolor the logo -->
    <style>
        :root {
            --primary: #6C5CE7;  /* violet */
            --accent:  #00D1B2;  /* teal   */
            --ink:     #0f172a;  /* slate-900 for wordmark */
        }
        /* Optional: inherit text color if you prefer */
        /* .wordmark { fill: currentColor } */
    </style>

    <defs>
        <!-- Gradient stroke for the link -->
        <linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%"  stop-color="var(--primary)"/>
            <stop offset="100%" stop-color="var(--accent)"/>
        </linearGradient>

        <!-- Reusable icon group so you can extract the icon-only version easily -->
        <g id="shortnr-icon" transform="translate(0,0)">
            <!-- Two rounded rectangles rotated to form an interlocking link -->
            <rect x="20" y="20" width="120" height="56" rx="28" ry="28"
                  fill="none" stroke="url(#g)" stroke-width="14"
                  transform="rotate(-35 80 48)"/>
            <rect x="76" y="64" width="120" height="56" rx="28" ry="28"
                  fill="none" stroke="url(#g)" stroke-width="14"
                  transform="rotate(-35 136 92)"/>

            <!-- Inward "shorten" arrows (chevrons) -->
            <g stroke="var(--accent)" stroke-width="12" stroke-linecap="round" stroke-linejoin="round" fill="none">
                <!-- Left chevron -->
                <path d="M54 100 L34 84 L54 68"/>
                <!-- Right chevron -->
                <path d="M182 44 L202 60 L182 76"/>
            </g>

            <!-- Soft glow -->
            <g opacity="0.25" filter="url(#f)">
                <rect x="20" y="20" width="120" height="56" rx="28" ry="28"
                      fill="none" stroke="url(#g)" stroke-width="14"
                      transform="rotate(-35 80 48)"/>
                <rect x="76" y="64" width="120" height="56" rx="28" ry="28"
                      fill="none" stroke="url(#g)" stroke-width="14"
                      transform="rotate(-35 136 92)"/>
            </g>
        </g>

        <!-- Subtle shadow filter -->
        <filter id="f" x="-50%" y="-50%" width="200%" height="200%">
            <feGaussianBlur in="SourceGraphic" stdDeviation="4" result="b"/>
        </filter>
    </defs>

    <!-- ====== LAYOUT ====== -->
    <!-- Icon (left) -->
    <g transform="translate(20,20) scale(1.3)">
        <use href="#shortnr-icon"/>
    </g>

    <!-- Wordmark (right). Edit the text to rename your app. -->
    <g class="wordmark" transform="translate(300,110)">
        <!-- Using system fonts to keep the SVG lightweight -->
        <text x="0" y="0"
              font-family="Inter, Segoe UI, Roboto, Helvetica, Arial, sans-serif"
              font-size="88" font-weight="700" letter-spacing="-1"
              fill="var(--ink)">shortnr</text>

        <!-- Small tagline (optional). Delete if you don’t need it. -->
        <text x="4" y="34"
              font-family="Inter, Segoe UI, Roboto, Helvetica, Arial, sans-serif"
              font-size="20" font-weight="500" letter-spacing="2"
              fill="var(--primary)" opacity="0.9">link in, shorter out</text>
    </g>
</svg>
