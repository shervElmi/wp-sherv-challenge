module.exports = {
	content: ['./*.php', './includes/**/*.php', './assets/**/*.css'],
	prefix: 's11-',
	corePlugins: {
		preflight: false,
	},
	theme: {
		fontFamily: {
			sans: ['Roboto', 'sans-serif'],
		},
		extend: {
			colors: {
				primary: '#2146EC',
				body: '#051235',
				dark: '#061235',
				accent: '#4FDCCE',
			},
			fontSize: {
				base: [
					'1.125rem',
					{
						lineHeight: '1.38',
						fontWeight: '400',
					},
				],
				lg: [
					'1.5625rem',
					{
						lineHeight: '1.36',
						fontWeight: '400',
					},
				],
				xl: [
					'1.875rem',
					{
						lineHeight: '1.266',
						fontWeight: '400',
					},
				],
			},
		},
	},
	plugins: [],
};
