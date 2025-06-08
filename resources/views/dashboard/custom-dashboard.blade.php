<style>
	.dashboard-cards {
		display: flex;
		gap: 1.5rem;
		justify-content: center;
		align-items: center;
		flex-wrap: wrap;
		margin-top: 2rem;
	}

	.dashboard-card {
		background: #f5f6fa;
		border-radius: 12px;
		box-shadow: 0 2px 8px rgba(60, 60, 60, 0.06);
		padding: 1.5rem 1.2rem;
		flex: 1 1 200px;
		min-width: 220px;
		max-width: 260px;
		display: flex;
		flex-direction: column;
		align-items: center;
		text-align: center;
		transition: box-shadow 0.2s;
	}

	.dashboard-card:hover {
		box-shadow: 0 4px 16px rgba(60, 60, 60, 0.12);
	}

	.dashboard-card svg {
		width: 40px;
		height: 40px;
		margin-bottom: 1rem;
		color: #6c757d;
	}

	.dashboard-card-title {
		font-weight: 600;
		font-size: 1.1rem;
		margin-bottom: 0.5rem;
		color: #333;
	}

	.dashboard-card-desc {
		font-size: 0.97rem;
		color: #666;
		margin-bottom: 1.2rem;
	}

	.dashboard-card a {
		text-decoration: none;
		color: #4a6fa5;
		font-weight: 500;
	}
</style>

<div class="dashboard-cards">
	<!-- Website -->
	<div class="dashboard-card">
		<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
			<path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"></path>
		</svg>
		<div class="dashboard-card-title">Site Web</div>
		<div class="dashboard-card-desc">Accédez à la page d’accueil de votre site.</div>
		<a href="{{ env('APP_URL', 'https://elevage-canin-vosges.fr') }}" target="_blank">Visiter le site</a>
	</div>
	<!-- Email -->
	<div class="dashboard-card">
		<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
			<path fill="currentColor" d="M4 20q-.825 0-1.412-.587T2 18V6q0-.825.588-1.412T4 4h10.1q-.1.5-.1 1t.1 1H4l8 5l3.65-2.275q.35.325.763.563t.862.412L12 13L4 8v10h16V9.9q.575-.125 1.075-.35T22 9v9q0 .825-.587 1.413T20 20zM4 6v12zm15 2q-1.25 0-2.125-.875T16 5t.875-2.125T19 2t2.125.875T22 5t-.875 2.125T19 8" />
		</svg>
		<div class="dashboard-card-title">Webmail</div>
		<div class="dashboard-card-desc">Accédez à votre boite mail personnelle Aidella.</div>
		<a href="{{ env('WEBMAIL_URL', 'https://webmail.elevage-canin-vosges.fr') }}" target="_blank">Accéder au Webmail</a>
	</div>
	<!-- Google search console -->
	<div class="dashboard-card">
		<svg xmlns="http://www.w3.org/2000/svg" style="padding: 4px 4px 4px 4px;" width="32" height="32" viewBox="0 0 128 128"><!-- Icon from Devicon by konpa - https://github.com/devicons/devicon/blob/master/LICENSE -->
			<path fill="#fff" d="M44.59 4.21a63.28 63.28 0 0 0 4.33 120.9a67.6 67.6 0 0 0 32.36.35a57.13 57.13 0 0 0 25.9-13.46a57.44 57.44 0 0 0 16-26.26a74.3 74.3 0 0 0 1.61-33.58H65.27v24.69h34.47a29.72 29.72 0 0 1-12.66 19.52a36.2 36.2 0 0 1-13.93 5.5a41.3 41.3 0 0 1-15.1 0A37.2 37.2 0 0 1 44 95.74a39.3 39.3 0 0 1-14.5-19.42a38.3 38.3 0 0 1 0-24.63a39.25 39.25 0 0 1 9.18-14.91A37.17 37.17 0 0 1 76.13 27a34.3 34.3 0 0 1 13.64 8q5.83-5.8 11.64-11.63c2-2.09 4.18-4.08 6.15-6.22A61.2 61.2 0 0 0 87.2 4.59a64 64 0 0 0-42.61-.38" />
			<path fill="#e33629" d="M44.59 4.21a64 64 0 0 1 42.61.37a61.2 61.2 0 0 1 20.35 12.62c-2 2.14-4.11 4.14-6.15 6.22Q95.58 29.23 89.77 35a34.3 34.3 0 0 0-13.64-8a37.17 37.17 0 0 0-37.46 9.74a39.25 39.25 0 0 0-9.18 14.91L8.76 35.6A63.53 63.53 0 0 1 44.59 4.21" />
			<path fill="#f8bd00" d="M3.26 51.5a63 63 0 0 1 5.5-15.9l20.73 16.09a38.3 38.3 0 0 0 0 24.63q-10.36 8-20.73 16.08a63.33 63.33 0 0 1-5.5-40.9" />
			<path fill="#587dbd" d="M65.27 52.15h59.52a74.3 74.3 0 0 1-1.61 33.58a57.44 57.44 0 0 1-16 26.26c-6.69-5.22-13.41-10.4-20.1-15.62a29.72 29.72 0 0 0 12.66-19.54H65.27c-.01-8.22 0-16.45 0-24.68" />
			<path fill="#319f43" d="M8.75 92.4q10.37-8 20.73-16.08A39.3 39.3 0 0 0 44 95.74a37.2 37.2 0 0 0 14.08 6.08a41.3 41.3 0 0 0 15.1 0a36.2 36.2 0 0 0 13.93-5.5c6.69 5.22 13.41 10.4 20.1 15.62a57.13 57.13 0 0 1-25.9 13.47a67.6 67.6 0 0 1-32.36-.35a63 63 0 0 1-23-11.59A63.7 63.7 0 0 1 8.75 92.4" />
		</svg>
		<div class="dashboard-card-title">Google Search Console</div>
		<div class="dashboard-card-desc">Accédez a la Google Search Console.</div>
		<a href="https://search.google.com/search-console" target="_blank">Voir la Search Console</a>
	</div>
	<!-- Matomo -->
	<div class="dashboard-card">
		<svg xmlns="http://www.w3.org/2000/svg" width="56.5" height="32" viewBox="0 0 256 145">
			<defs>
				<path id="logosMatomoIcon0" d="m105.426 70.887l.035-.021l-.663-1.01c-.1-.153-.2-.313-.303-.46L58.935 0L0 43.91l43.078 66.305c.185.281.36.566.55.847l.229.35l.025-.016c6.676 9.471 17.678 15.673 30.144 15.673c20.373 0 36.889-16.513 36.889-36.89c0-7.083-2.029-13.675-5.489-19.292" />
				<path id="logosMatomoIcon1" fill="#000" d="M64.549 19.33c0-20.374-16.517-36.89-36.89-36.89S-9.23-1.044-9.23 19.33a36.7 36.7 0 0 0 6.08 20.263q-.004 0-.003-.003l-.019.003L-31.179 0h-.04c-6.499-10.524-18.101-17.56-31.376-17.56S-87.472-10.524-93.971 0h-.037l-44.61 69.525c6.633-9.8 17.848-16.235 30.57-16.235c13.39 0 25.077 7.158 31.54 17.832h.047l29.15 40.921h.047c6.718 9.1 17.486 15.026 29.663 15.026c12.181 0 22.95-5.927 29.666-15.026h.05l.297-.46a37 37 0 0 0 2.116-3.312l43.675-68.256v.003A36.75 36.75 0 0 0 64.55 19.33M2.372 46.141c.213.204.435.397.654.594c-.22-.197-.438-.39-.654-.594m3.28 2.745c.243.181.48.369.728.544c-.247-.175-.485-.363-.729-.544m8.096 4.598c.306.128.628.228.94.347c-.312-.12-.634-.22-.94-.347m8.28 2.263c.428.065.853.143 1.287.197c-.434-.054-.856-.132-1.287-.197m9.93.203c.438-.05.869-.135 1.303-.197c-.434.062-.862.147-1.303.197m8.368-2.01c.393-.144.797-.275 1.184-.434c-.387.159-.788.29-1.185.434m8.368-4.326c.313-.216.61-.456.916-.684c-.307.228-.603.465-.916.684m6.258-5.526c.259-.285.528-.563.778-.857c-.25.294-.519.572-.778.857" />
				<path id="logosMatomoIcon2" fill="#95C748" d="m250.511 88.448l.035-.022l-.663-1.01c-.1-.153-.2-.312-.303-.46L204.02 17.56l-58.935 43.91l43.078 66.305c.185.281.36.566.55.847l.229.35l.025-.016c6.676 9.471 17.678 15.673 30.144 15.673c20.373 0 36.889-16.513 36.889-36.89c0-7.083-2.029-13.675-5.489-19.291" />
				<filter id="logosMatomoIcon3" width="106.9%" height="109.7%" x="-3.4%" y="-3.5%" filterUnits="objectBoundingBox">
					<feOffset dy="2" in="SourceAlpha" result="shadowOffsetOuter1" />
					<feGaussianBlur in="shadowOffsetOuter1" result="shadowBlurOuter1" stdDeviation="2" />
					<feColorMatrix in="shadowBlurOuter1" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.5 0" />
				</filter>
			</defs>
			<use href="#logosMatomoIcon2" />
			<path fill="#35BFC0" d="M73.779 107.74c0-20.374-16.516-36.89-36.89-36.89C16.516 70.85 0 87.366 0 107.74c0 20.376 16.516 36.892 36.89 36.892c20.373 0 36.889-16.52 36.889-36.893" />
			<path fill="#3253A0" d="M172.744 0c20.373 0 36.89 16.516 36.89 36.89a36.75 36.75 0 0 1-6.346 20.688v-.003l-43.675 68.256a37 37 0 0 1-2.116 3.313l-.297.46h-.05c-6.717 9.098-17.485 15.025-29.666 15.025c-12.177 0-22.945-5.927-29.663-15.026h-.046l-29.15-40.921h-.047C62.114 78.008 50.427 70.85 37.036 70.85c-12.721 0-23.936 6.436-30.569 16.235l44.61-69.525h.037C57.613 7.036 69.215 0 82.49 0s24.877 7.036 31.376 17.56h.04l28.006 39.593l.02-.003q0 .004.002.003a36.7 36.7 0 0 1-6.08-20.264C135.855 16.516 152.372 0 172.745 0" />
			<use href="#logosMatomoIcon2" />
			<g transform="translate(145.085 17.56)">
				<mask id="logosMatomoIcon4" fill="#fff">
					<use href="#logosMatomoIcon0" />
				</mask>
				<g mask="url(#logosMatomoIcon4)">
					<use filter="url(#logosMatomoIcon3)" href="#logosMatomoIcon1" />
				</g>
			</g>
			<path fill="#F38334" d="M209.487 36.89c0-20.374-16.516-36.89-36.89-36.89c-20.373 0-36.89 16.516-36.89 36.89c0 20.373 16.517 36.889 36.89 36.889s36.89-16.516 36.89-36.89" />
			<path fill="#3152A0" d="M172.597 73.782c-12.887 0-24.214-6.617-30.81-16.629h-.021L113.759 17.56h-.04C107.22 7.04 95.618.003 82.343.003S57.466 7.04 50.967 17.56h-.037L6.323 87.085c6.63-9.796 17.848-16.232 30.566-16.232c13.39 0 25.08 7.155 31.545 17.829h.047l29.15 40.921h.044c6.72 9.096 17.488 15.029 29.665 15.029c12.178 0 22.946-5.93 29.663-15.029h.05l.297-.462a38 38 0 0 0 2.12-3.307l43.672-68.256c-6.636 9.774-17.839 16.204-30.545 16.204" />
		</svg>
		<div class="dashboard-card-title">Matomo Analytics</div>
		<div class="dashboard-card-desc">Consultez les statistiques de fréquentation.</div>
		<a href="https://matomo.rocketegg.systems" target="_blank">Voir Matomo</a>
	</div>
</div>
